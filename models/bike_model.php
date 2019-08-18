<?php

	namespace models;

	/**
	 * @file bike_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class bike extends \model {

		/**
		 * Default action. Display all bikes.
		 * @viewModel $bikes [array] // Array of all bikes.
		 */

		public function index() {
			$filters = getGetValues();
			if($filters) {
				foreach($filters as $key => $value) {
					if(!empty($value)) {
						switch($key) {
							case 'brand':
								if(count($value) > 1) {
									$arr = array();
									for($i = 1; $i < count($value); $i++) {
										$this->db->subQuery();
										$arr = array_merge_recursive($this->db->returnObject($this->db->whereOr('b.brand_id', $value[$i])), $arr);
									}
									$this->db->where('b.brand_id', $value[0], '=', $arr);					
								}
								else {
									$this->db->where('b.brand_id', $value[0], '=');
								}
							break;
							case 'category':
								if(count($value) > 1) {
									$arr = array();
									for($i = 1; $i < count($value); $i++) {
										$this->db->subQuery();
										$arr = array_merge_recursive($this->db->returnObject($this->db->whereOr('b.category_id', $value[$i])), $arr);
									}
									$this->db->where('b.category_id', $value[0], '=', $arr);
								}
								else {
									$this->db->where('b.category_id', $value[0], '=');
								}
							break;
							case 'bike_year_start':
								$value = explode('-', $value);
								$values = array();
								foreach($value as $val) {
									$values[] = (int)trim($val);
								}
								$this->db->subQuery();
								$arr = $this->db->returnObject($this->db->where('bike_year_start', $values[0], '>='));
								$this->db->where('bike_year_end', $values[1], '<=', $arr);								
							break;
						}
					}
				}
			}
			$this->db->join('brand br', 'b.brand_id = br.brand_id'); // Join brands.
			$this->db->join('category c', 'b.category_id = c.category_id'); // Join types.
			$this->db->sort(['name' => 'b.bike_name', 'year' => 'b.bike_year_start', 'brand' => 'br.brand_name', 'category' => 'c.category_name']);
			$bikes = $this->db->select('bike b', 'b.bike_id, b.bike_name, b.bike_year_start, b.bike_year_end, b.bike_path, br.brand_id, br.brand_name, c.category_id, c.category_name', 25);
			if($bikes) {
				$bikes->create_objects('bike_');
				$this->viewModel->add('bikes', $bikes); // Add array of bikes to viewModel.
				$this->viewModel->add('count', $this->db->count('bike'));
			}
			else {
				$this->viewModel->error('No bikes to display'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * Display single bike.
		 * @viewModel $bike
		 * @param $path [int] // id of item to edit.
		 */

		public function specs($path) {
			// Load bike
			$bike = \bike_::getByPath($path);
			if($bike) {
				$this->viewModel->add('bike', $bike);
				// Load specs
				$this->db->where('s.bike_id', $bike->getId());
				$this->db->join('attribute a', 's.attribute_id = a.attribute_id'); // Join attributes.
				$this->db->join('attribute_type at', 'a.attribute_type_id = at.attribute_type_id'); // Join attribute types.
				$this->db->join('unit u', 'a.unit_id = u.unit_id'); // Join units.
				$this->db->order('a.attribute_priority');
				$specs = $this->db->select('spec s');
				if($specs) {
					$bike->loadSpecs($specs);
					if(isset($bike->specs_['Engine']['Displacement'])) {
						$autoRelated = $this->autoRelated($bike, $bike->specs_['Engine']['Displacement']);
						if($autoRelated) $this->viewModel->add('relatedBikes', $autoRelated);
					}
				}
				// Load images
				$this->db->where('item_id', $bike->getId());
				$this->db->where('image_item', 'bike');
				$images = $this->db->select('image');
				$bike->loadImages($images); // Run this method no matter if any images found.
			}
			else {
				$this->viewModel->error('Sorry, bike not found.'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * This method should select bikes related to the current one.
		 * @todo finish this method
		 * @param $bike [bike object]
		 * @param $spec [string]
		 */

		public function autoRelated(\bike_ $bike, $spec) {
			if($spec) {
				$this->db->whereNot('b.bike_id', $bike->bike_id); // Exclude current bike.
				$this->db->where('s.attribute_id', $spec->attribute_->attribute_id);
				$this->db->where('b.category_id', $bike->category_->category_id); // Only bikes from same category.
				$this->db->between('s.spec_value', $spec->spec_value - ($spec->spec_value * 0.1), $spec->spec_value + ($spec->spec_value * 0.1));
				$this->db->between('b.bike_year_start', $bike->bike_year_start - 2, $bike->bike_year_end + 2);
				$this->db->join('bike b', 'b.bike_id = s.bike_id');
				$this->db->join('brand br', 'br.brand_id = b.brand_id');
				$this->db->limit('4');
				$bikes = $this->db->select('spec s', 'b.bike_id, b.bike_name');
				if($bikes) {
					$bikes->create_objects('bike_');
					return $bikes;
				}
			}
			return false;
		}

		/**
		 * Id of the first bike always come from mvc. Id of second bike comes from HTTP's GET.
		 * The quickest way to load attributes of both bikes is to run separate MySQL query. Use groupAttributes() to group attributes by type.
		 * @param $id [int]
		 */

		public function compare($id) {
			$bike = \bike_::getById($id);
			$data = getGetValues();
			if($bike) {
				$this->viewModel->add('bike', $bike);
				$this->db->where('s.bike_id', $bike->bike_id);
				$this->db->join('attribute a', 's.attribute_id = a.attribute_id'); // Join attributes.
				$this->db->join('attribute_type at', 'a.attribute_type_id = at.attribute_type_id'); // Join attribute types.
				$this->db->join('unit u', 'a.unit_id = u.unit_id'); // Join units.
				$this->db->order('a.attribute_priority');
				$bike_specs = $this->db->select('spec s');
				if($bike_specs) {
					$bike->loadSpecs($bike_specs); // Load specs into bike.
				}
			}
			else {
				$this->viewModel->error('Sorry, bike not found.'); // Bike not found, do nothing.
			}
			// Check if there is any bike to compare with.
			if(isset($data->compare)) {
				$bike_compare = \bike_::getById($data->compare);
				if($bike_compare) {
					$this->viewModel->add('bike_compare', $bike_compare);
					$this->db->where('s.bike_id', $bike_compare->bike_id);
					$this->db->join('attribute a', 's.attribute_id = a.attribute_id');
					$this->db->join('attribute_type at', 'a.attribute_type_id = at.attribute_type_id');
					$this->db->join('unit u', 'a.unit_id = u.unit_id');
					$this->db->order('a.attribute_priority');
					$bike_compare_specs = $this->db->select('spec s');
					if($bike_compare_specs) {
						$bike_compare->loadSpecs($bike_compare_specs);
					}
				}
			}
			// Load attributes of both bikes.
			if(isset($bike)) {
				$this->db->where('bike_id', $bike->bike_id);
				if(isset($bike_compare)) $this->db->whereOr('bike_id', $bike_compare->bike_id);
				$this->db->join('attribute a', 'a.attribute_id = s.attribute_id');
				$this->db->groupBy('a.attribute_id'); // Group attributes because they can duplicate.
				$this->db->order('a.attribute_priority');
				$attributes = $this->db->select('spec s', 'a.attribute_id, a.attribute_name, a.attribute_type_id');
				if($attributes) {
					$attributes = \attribute_::groupAttributes($attributes);
					$this->viewModel->add('attributes', $attributes);
				}
			}
			return $this->viewModel;
		}

		/**
		 * Display users reviews of the bike.
		 * @param $path [string]
		 */

		public function reviews($path) {
			$bike = \bike_::getByPath($path);
			if($bike) {
				$this->viewModel->add('bike', $bike);
				$this->db->join('user u', 'r.user_id = u.user_id');
				$this->db->join('bike b', 'r.bike_id = b.bike_id');
				$this->db->where('r.bike_id', $bike->getId());
				$reviews = $this->db->select('review r');
				if($reviews) {
					$reviews->create_objects('review_');
					$this->viewModel->add('reviews', $reviews);
				}
			}
			return $this->viewModel;
		}
	}

?>