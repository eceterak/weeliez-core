<?php

	namespace models;

	/**
	 * @file search_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class search extends \model {

		/**
		 * Search for bikes/brands/categories or news containing searched phrase. 
		 * Use GET instead of POST to avoid 'confirm form resubmission' message.
		 * If there is no search prhase provided, simply throw new \exception.
		 * Explode searched phrase and search for every word instead of the whole phrase to widen results.
		 * Use both raw like and soundex to provide more results.
		 * Don't use soundex on numbers.
		 * If any results found, sort them with usort comparing text similarity. 
		 * This way, most similar to searched phrase results will be on the top of the list.
		 * Return found results (if any), searched phrase and $amount of found results (if any).
		 */

		public function results() {
			if(isset($_GET['phrase'])) {
				$phrase = htmlspecialchars($_GET['phrase']);
				$phrase = trim($phrase); // Clear spaces.
				$required = requiredField($phrase);
				if($required === false) {
					$this->viewModel->error('Please provide a search phase.');
				}
				else {
					$amount = 0; // Amount of matches.
					$this->viewModel->add('phrase', $phrase);
					$phrase = strtolower($phrase);
					$keyWords = explode(' ', $phrase);
					# SEARCH FOR BIKES
					foreach($keyWords as $word) {
						if(!is_numeric($word)) {
							$this->db->like('bike_name', soundex($word)); // Use soundex.			
						}
						$this->db->like('bike_name', $word, false); // Number, no soundex.
					}
					$this->db->limit(50);
					$this->db->join('brand br', 'b.brand_id = br.brand_id');
					$this->db->join('category c', 'c.category_id = b.category_id');
					$bikes = $this->db->select('bike b', 'b.bike_id, b.bike_name, b.bike_year_start, b.bike_year_end, br.brand_id, br.brand_name, c.category_id, c.category_name');
					if($bikes) {
						$amount = $bikes->num_rows;
						if($bikes->num_rows > 1) {
							// Use a custom sort function, don't use fetch_data(), operation needs to be done on array.
							usort($bikes->data, function($a, $b) use ($phrase) {
								similar_text($phrase, strtolower($a->bike_name), $first);
								similar_text($phrase, strtolower($b->bike_name), $second);
								return ($first === $second) ? 0 : ($first > $second) ? -1 : 1;
							});
						}
						$bikes->create_objects('bike_');
						$this->viewModel->add('bikes', $bikes);
					}
					# SEARCH FOR BRANDS
					foreach($keyWords as $word) {
						if(!is_numeric($word)) {
							$this->db->like('brand_name', soundex($word));
						}
						$this->db->like('brand_name', $word, false);
					}
					$brands = $this->db->select('brand');
					if($brands) {
						$amount = $amount + $brands->num_rows; // Update the amount,
						$brands->create_objects('brand_');
						$this->viewModel->add('brands', $brands);
					}
					# SEARCH FOR CATEGORIES
					foreach($keyWords as $word) {
						if(!is_numeric($word)) {
							$this->db->like('category_name', soundex($word));
						}
						$this->db->like('category_name', $word, false);
					}
					$categories = $this->db->select('category');
					if($categories) {
						$amount = $amount + $categories->num_rows;
						$categories->create_objects('category_');
						$this->viewModel->add('categories', $categories);
					}
					# SEARCH FOR ARTICLES
					foreach($keyWords as $word) {
						if(!is_numeric($word)) {
							$this->db->like('article_content', soundex($word));
						}
						$this->db->like('article_content', $word, false);
					}
					$articles = $this->db->select('article');
					if($articles) {
						$amount = $amount + $articles->num_rows;
						$articles->create_objects('article_');
						$this->viewModel->add('articles', $articles);
					}
					$this->viewModel->add('amount', $amount);	
				}
			}
			else {
				$this->viewModel->error('Please provide a search phase.');
			}
			return $this->viewModel;
		}

		/**
		 * Get all attributes with attribute_search of value 1 and display them on advanced search form.
		 */

		public function advanced() {
			$this->db->join('attribute_type at', 'at.attribute_type_id = a.attribute_type_id');
			$this->db->where('a.attribute_search', 1);
			$attributes = $this->db->select('attribute a', 'a.attribute_id, a.attribute_name, a.attribute_search_method, at.attribute_type_name');
			if($attributes) {
				foreach($attributes->fetch_data() as $attribute) {
					$arr[$attribute->attribute_type_name][] = new \attribute_($attribute); // Group by attribute_type name.
				}
				$this->viewModel->add('attributes', $arr);
			}
			return $this->viewModel;
		}

		/**
		 * PLEASE READ BEFORE TRYING TO UNDERSTAND THE CODE
		 * If $key is an integer, it means that user is looking for bike with specific attribute of $value and search must be executed on spec table.
		 * There are two different ways how user can filter search result - by slider or select. Select always comes with $values in array where slider values are separated with - within a string.
		 * If $key is not an integer, search will be executed on bike table - user is looking for bikes of make/category or between specific years of production.
		 * @queries:
		 * (1) Query will look like this: WHERE (attribute_id = ? AND spec_value = ? OR spec_value = ? OR spec_value = ?)
		 * (1) Because first spec_value must be connected to attribute_id with AND not OR, for loop starts on second index of the array ($i = 1). Rest of the spec_values are connected to each other with OR. 
		 * (1) array_merge_recursive is used to merge two arrays so values of one are appended to the end of the previous one. In other words, it's adding a current array to the start of another.
		 * (1) This way, array of where sub-queries is created. That array can be then used to create a whole WHERE condition. 
		 * (2) When creating queries from a slider values, use a between condition. It will look like this: WHERE (attribute_id = ? AND spec_value BETWEEN ? AND ?)
		 * (1,2) Use WHERE OR when connecting attribute conditions. Use HAVING spec_value to met all spec_value conditions. First (check $having) attribute conection must be connected with AND not OR.
		 * (3) Just like query (2) but without any pre keys/values like attribute_id = ?.
		 * (4) Query: WHERE (bike_year_start >= ? AND bike_year_end <= ?)
		 * (4) Use >= and <= instead of BETWEEN because it only looks for one key (bike_year_start) and don't take in consideration of bike_year_end.
		 */

		public function advresults() {
			$data = getGetValues();
			var_dump($data);
			if($data) {
				$amount = 0;
				$having = 0;
				foreach($data as $key => $value) {
					 // Do anything only if value is not empty.
					if($value !== '') {
						// Search in spec table (attribute is a key).
						if(gettype($key) == 'integer') {
							// Select
							if(is_array($value)) {
								$arr = array();
								if(count($value) > 1) {
									for($i = 1; $i < count($value); $i++) {
										$this->db->subQuery();
										$arr = array_merge_recursive($this->db->returnObject($this->db->whereOr('spec_value', $value[$i])), $arr); // see @queries (1)
									}
									$this->db->subQuery();
									$arr = array_merge_recursive($this->db->returnObject($this->db->where('spec_value', $value[0])), $arr); // First value must be connected with attribute_id with AND.
								}
								else {
									$this->db->subQuery();
									$arr = $this->db->returnObject($this->db->where('spec_value', $value[0]));
								}					
							}
							// Slider
							elseif(strpos($value, '-')) {
								$value = explode('-', $value);
								$values = array();
								foreach($value as $val) {
									$values[] = (int)trim($val);
								}
								$this->db->subQuery();
								$arr = $this->db->returnObject($this->db->between('spec_value', $values[0], $values[1])); // see @queries (2)
							}					
							if($having == 0) {
								$this->db->where('attribute_id', $key, '=', $arr); // Same query for both slider and select. That must be a WHERE OR condition. // see @queries (1,2)
							}
							else {
								$this->db->whereOr('attribute_id', $key, '=', $arr); // Same query for both slider and select. That must be a WHERE OR condition. // see @queries (1,2)
							}
							$having++; // Increase having. Important.
						}
						// Search in bike table
						else {
							if(is_array($value)) {
								if(count($value) > 1) {
									$arr = array(); // Reset the array.
									for($i = 1; $i < count($value); $i++) {
										$this->db->subQuery();
										$arr = array_merge_recursive($this->db->returnObject($this->db->whereOr('b.'.$key, $value[$i])), $arr); // 'b.' stands for - search in bikes.
									}
									$this->db->where('b.'.$key, $value[0], '=', $arr); // see @queries (3)
								}
								else {
									$this->db->where('b.'.$key, $value[0]);
								}
							}		
							elseif($key == 'bike_year_start') {
								$value = explode('-', $value);
								$values = array();
								foreach($value as $val) {
									$values[] = (int)trim($val);
								}
								$this->db->subQuery();
								$arr = $this->db->returnObject($this->db->where('bike_year_start', $values[0], '>=')); // see @queries (4)
								$this->db->where('bike_year_end', $values[1], '<=', $arr);
							}
						}				
					}
				}
				//$this->db->debugMode();
				if($having > 0) $this->db->having('spec_value', $having);
				$this->db->groupBy('b.bike_id'); // Group results/
				$this->db->join('spec s', 's.bike_id = b.bike_id');
				$this->db->join('brand br', 'br.brand_id = b.brand_id');
				$this->db->join('category c', 'c.category_id = b.category_id');
				$result = $this->db->select('bike b', 'b.bike_id, b.bike_name, b.bike_year_start, b.bike_year_end, br.brand_id, br.brand_name, c.category_id, c.category_name');
				if($result) {
					foreach($result->fetch_data() as $res) {
						$bikes[] = new \bike_($res);
					}
					$amount = count($bikes);
					$this->viewModel->add('bikes', $bikes);
				}
				$this->viewModel->add('amount', $amount);
			}
			return $this->viewModel;
		}
	}

?>