<?php

	namespace admin\models;

	/**
	 * @file bikeModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class bike extends \admin\model {

		/**
		 * Default action. Display all bikes.
		 * @viewModel $bikes [array] // Array of all bikes.
		 * @todo filters
		 */

		public function index() {
			$filters = getGetValues();
			if($filters) {
				foreach($filters as $key => $value) {
					if(!empty($value)) {
						switch($key) {
							case 'bike_name':
								$this->db->like('b.bike_name', $value, true);
							break;
							case 'brand':
								$this->db->where('b.brand_id', $value);
							break;
							case 'year_to':
								
							break;
							case 'year_from':

							break;
							case 'category':
								$this->db->where('b.category_id', $value);
							break;
						}
					}
				}
			}
			$this->db->join('brand br', 'b.brand_id = br.brand_id'); // Join brands.
			$this->db->join('category c', 'b.category_id = c.category_id'); // Join types.
			$this->db->sort(['name' => 'b.bike_name', 'year' => 'b.bike_year_start', 'brand' => 'br.brand_name', 'category' => 'c.category_name']);
			$bikes = $this->db->select('bike b', 'b.bike_id, b.bike_name, b.bike_year_start, b.bike_year_end, b.bike_sale, br.brand_id, br.brand_name, c.category_id, c.category_name', 25); 
			if($bikes) {
				$bikes->create_objects('bike_');
				$this->viewModel->add('bikes', $bikes); // Add array of bikes to viewModel.
			}
			else {
				$this->viewModel->error('No bikes to display'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * Edit single bike. Use bike() method to load item.
		 * @viewModel $bike
		 * @param $id [int] // id of item to edit.
		 */

		public function edit($id) {
			$bike = \bike_::getById($id, false); // Load basic data.
			if($bike) {
				// Load images
				$this->db->where('item_id', $id);
				$this->db->where('image_item', 'bike');
				$images = $this->db->select('image');
				if($images) {
					$bike->loadImages($images);
				}
				// Load specs
				$this->db->where('s.bike_id', $id);
				$this->db->join('attribute a', 's.attribute_id = a.attribute_id'); // Join attributes.
				$this->db->join('attribute_type at', 'a.attribute_type_id = at.attribute_type_id'); // Join attribute types.
				$this->db->join('unit u', 'a.unit_id = u.unit_id'); // Join units.
				$this->db->order('a.attribute_priority');
				$specs = $this->db->select('spec s');
				if($specs) {
					$bike->loadSpecs($specs); // Load specs into bike.
					if(isset($bike->specs_['Engine']['Displacement'])) {
						$autoRelated = $this->autoRelated($bike, $bike->specs_['Engine']['Displacement']);
						if($autoRelated) $this->viewModel->add('relatedBikes', $autoRelated);
					}
				}
				$this->viewModel->add('bike', $bike); // Add bike
			}
			else {
				$this->viewModel->error('Sorry, bike not found.'); // Set error message.		
			}
			return $this->viewModel;
		}

		/**
		 * Get and display all specs related to current bike.
		 * Order attributes by priority to set them in the right order.
		 * @param $id [int]
		 * @viewModel $specs // Array of all specs related to current bike.
		 * @viewModel $bike // Current bike.
		 */

		public function specs($id) {
			$bike = \bike_::getById($id);
			if($bike) {
				$this->db->where('s.bike_id', $id);
				$this->db->join('attribute a', 's.attribute_id = a.attribute_id'); // Join attributes.
				$this->db->join('attribute_type at', 'a.attribute_type_id = at.attribute_type_id'); // Join attribute types.
				$this->db->join('unit u', 'a.unit_id = u.unit_id'); // Join units.
				$this->db->order('a.attribute_priority');
				$result = $this->db->select('spec s');
				if($result) {
					$bike->loadSpecs($result); // Load specs into bike.
				}
				$this->viewModel->add('bike', $bike); // Add after loading specs.
			}
			else {
				$this->viewModel->error('Sorry, bike not found.'); // Set error message.		
			}
			return $this->viewModel;
		}

		/**
		 * Update specs of current bike. It's important to check whatever attribute is already associated with bike or not.
		 * This way you can decide if you need to update existing spec or insert a new one.
		 * Also, store results of each query in array. Thoes queries will always return true or false. 
		 * Then test if any of queries failed with in_array().
		 * @param $id [int] // Id of the bike.
		 * @param $data
		 * @return bool
		 */

		public function updateSpecs($id, $data) {
			foreach($data as $key => $value) {
				// Check and insert sub value.
				if(strpos($key, '_sub') !== false && !empty($value)) {
					$key = preg_replace('/_sub/', '', $key);
					$this->db->set('spec_sub', $value);
					$this->db->where('bike_id', $id);
					$this->db->where('attribute_id', $key);
					$result = $this->db->update('spec'); // Spec already exists. Update it.
				}
				else {
					if(!empty($value)) {							
						$this->db->where('bike_id', $id);
						$this->db->where('attribute_id', $key);
						$checkExists = $this->db->checkRecordExists('spec');
						if($checkExists) {
							$this->db->set('spec_value', $value);
							$this->db->where('bike_id', $id);
							$this->db->where('attribute_id', $key);
							$result = $this->db->update('spec'); // Spec already exists. Update it.
						}
						else {
							$this->db->set('spec_value', $value);
							$this->db->set('attribute_id', $key);
							$this->db->set('bike_id', $id); // Dont forget about bike id.
							$result = $this->db->insert('spec'); // Insert new spec.
						}	
					}
				}
				$check[] = $result; // Store result of query.
			}
			if(!in_array(false, $check)) {
				return true;
			}
			else {
				throw new \exception('Error in database query.');
			}
		}

		/**
		 * Delete one spec from Database.
		 * Not in use right now. Using ajax instead to prevent page roload.
		 * @param $id [int] // id of item to delete.
		 */

		public function deleteSpecs($id) {
			$this->db->where('spec_id', $id);
			$result = $this->db->delete('spec');
			if($result) {
				return true;
			} 
			else {
				throw new \exception("Spec can't be deleted.");
			}
		}

		/**
		 * Display similar bikes.
		 * As this method is big, it's divided into three parts.
		 * @param $id [int]
		 */

		public function related($id) {
			# Load bike
			$bike = \bike_::getById($id);
			if($bike) {
				$this->viewModel->add('bike', $bike);
				# Load related bikes
				$this->db->where('r.bike_id', $id);
				$this->db->join('bike b', 'b.bike_id = r.related_id');
				$related = $this->db->select('bike_related r');
				if($related) {
					$releated->create_object('related_');
					$this->viewModel->add('related', $related);
				}
				else {
					$this->viewModel->notice('No related bikes.');
				}
				# Load bikes to select.
				$this->db->join('brand br', 'b.brand_id = br.brand_id');
				$this->db->where('b.category_id', $bike->category_->category_id);
				$this->db->whereNot('b.bike_id', $id);
				if(isset($related)) {
					foreach($related as $bik) {
						$this->db->whereNot('b.bike_id', $bik->bike_->bike_id);
					}
				}
				$bikes = $this->db->select('bike b', 'b.bike_id, b.bike_name, b.bike_year_start, b.bike_year_end, br.brand_name');
				$bikes = []; // Always return array.
				if($bikes) {
					$bikes->create_object('bike_');
					$this->viewModel->add('bikes', $bikes);
				}
			}
			else {
				$this->viewModel->error('Bike not found.');
			}
			$autoRelated = $this->autoRelated($bike, 'Displacement');
			if($autoRelated) {
				$this->viewModel->add('autoRelated', $autoRelated);
			}
			return $this->viewModel;
		}

		/**
		 * First, duplicate basic data, and then all specs. Not copying images as different generations usually differ in looks.
		 * @param $id [int]
		 */

		public function duplicate($id) {
			$this->db->where('bike_id', $id);
			$original = $this->db->selectOne('bike');
			if($original) {
				foreach($original as $key => $value) {
					if($key == 'bike_id') continue; // Don't copy the id.
					if($key == 'bike_path') continue; // Don't copy the path.
					else $this->db->set($key, $value);
				}
				$insert = $this->db->insert('bike');
				if($insert) {
					$newBikeId = $this->db->max('bike', 'bike_id'); // Get id of a cloned bike.
					$path = \bike_::path($newBikeId); // Set bike path for friendly url.
					$this->db->set('bike_path', $path);
					$this->db->where('bike_id', $newBikeId);
					$update = $this->db->update('bike');
					$this->db->where('bike_id', $id);
					$specs = $this->db->select('spec');
					if($specs) {
						foreach($specs->fetch_data() as $spec) {
							foreach($spec as $key => $value) {
								if($key == 'spec_id') continue;
								if($key == 'bike_id') {
									$this->db->set('bike_id', $newBikeId); // Insert new id.
								}
								else {
									$this->db->set($key, $value);
								}
							}
						$this->db->insert('spec');
						}
					}
					return $update; // Returns true/false.
				}
				else {
					throw new \Exception('Database error.');
				}
			}
			else {
				throw new \Exception('Bike not found.');
			}
		}

		/**
		 * Add a bike to related list.
		 * @param $id [int]
		 */

		public function addRelated($id) {
			$data = getPostValues();
			if($data) {
				$this->db->set('related_id', $data->related_id);
				$this->db->set('bike_id', $id);
				$result = $this->db->insert('bike_related');
				if($result) {
					return true;
				}
				else {
					throw new \Exception('Bike cannot be added.');
				}
			}
		}

		/**
		 * Delete a related bike.
		 * @param $id [int]
		 */

		public function deleteRelated($id) {
			$this->db->where('bike_related_id', $id);
			$result = $this->db->delete('bike_related');
			if($result) {
				return true;
			}
			else {
				throw new \Exception('Bike cannot be deleted.');
			}
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
				if($bike->bike_year_end == '') {
					$this->db->where('b.bike_year_start', $bike->bike_year_start - 2, '>='); // Only bikes from same category.
				}
				else {
					$this->db->between('b.bike_year_start', $bike->bike_year_start - 2, $bike->bike_year_end + 2);
				}
				$this->db->join('bike b', 'b.bike_id = s.bike_id');
				$this->db->limit('4');
				$result = $this->db->select('spec s', 's.bike_id');
				if($result) {
					foreach($result->fetch_data() as $res) {
						$bikes[] = \bike_::getById($res->bike_id, true);
					}
					return $bikes;
				}
			}
			return false;
		}

		/**
		 * Custom create method. It's different from any other because there is no need to check if bike with $name already exists
		 * in a database. There can be more than one bike with the same name (and different year).
		 */

		public function create() {
			$data = getPostValues();
			if($data) {
				$required = requiredField($data->bike_name, $data->bike_year_start);
				if($required === false) {
					throw new \exception('Fill all inputs.');
				}
				$this->db->where('bike_name', $data->bike_name);
				$this->db->where('bike_year_start', $data->bike_year_start);
				$this->db->where('brand_id', $data->brand_id);
				$result = $this->db->checkRecordExists('bike');
				if($result) {
					throw new \exception('Name and year combination already exists.');
				}
				if(empty($data->bike_year_end) || !isset($data->bike_year_end)) {
					$data->bike_year_end = NULL; // Bike still for sale.
				}
				if($data->bike_year_end < $data->bike_year_start) {
					$data->bike_year_end = $data->bike_year_start; // End of production date, cant be older than start of production date.
				}
				foreach($data as $key => $value) {
					$this->db->set($key, $value);
				}
				$result = $this->db->insert('bike');	
				if($result) {
					$newBikeId = $this->db->max('bike', 'bike_id');
					$path = \bike_::path($newBikeId); // Set bike path for friendly url.
					$this->db->set('bike_path', $path);
					$this->db->where('bike_id', $newBikeId);
					$update = $this->db->update('bike');
					if($update) {
						return ADMIN_URL.'/bike/edit/'.$newBikeId;
					}
					else {
						throw new \exception('Error in database query.');
					}
				}
				else {
					throw new \exception('Error in database query.');
				}
			}
			else {
				throw new \exception('Sorry, no data was sent. Try again later.');
			}
		}

		/**
		 * Update bike general data and it's specs.
		 * 
		 * @see updateSpecs()
		 * @param $id [int] // id of item to update.
		 * @return string
		 */

		public function update($id) {
			$data = getPostValues();
			if($data) {
				$bike = \bike_::getById($id);
				if(property_exists($data, 'redirect')) {
					$redirect = $data->redirect;
					unset($data->redirect);
				}
				else {
					$redirect = $_SERVER['HTTP_REFERER'];
				}
				$required = requiredField($data->bike_name);
				if($required === false) {
					throw new \exception('Fill all inputs.');
				}
				if($data->bike_name !== $bike->bike_name) {
					$this->db->where('bike_name', $data->bike_name);
					$this->db->where('bike_year_start', $data->bike_year_start);
					$this->db->where('brand_id', $data->brand_id);
					$this->db->whereNot('bike_id', $bike->getId());
					$result = $this->db->checkRecordExists('bike');
					if($result) {
						throw new \exception('Name and year combination already exists.');
					}
				}
				if(empty($data->bike_year_end) && $bike->sale == 1) {
					$data->bike_year_end = NULL; // Bike still for sale.
				}
				elseif(!empty($data->bike_year_end) && $data->bike_year_end < $data->bike_year_start) {
					$data->bike_year_end = $data->bike_year_start; // End of production date, cant be older than start of production date.
				}
				elseif(empty($data->bike_year_end)) {
					$data->bike_year_end = $data->bike_year_start;
				}
				foreach($data as $key => $value) {
					if(strpos($key, 'spec_') !== false) {
						$key = str_replace('spec_', '', $key); // Spec keys contain 'spec_'.
						$specs[$key] = $value;
					}
					else {
						$this->db->set($key, $value);
					}
				}
				$this->db->where('bike_id', $id);
				$result = $this->db->update('bike');
				if(isset($specs)) {
					$specs = (object)$specs; // Data provided to updateSpecs must be an object.
					$specs = $this->updateSpecs($id, $specs);
				}
				if($result) {
					$path = \bike_::path($id);
					$this->db->set('bike_path', $path);
					$this->db->where('bike_id', $id);
					$result = $this->db->update('bike'); // Update path after updating bike (data could change).
					if($result) {
						return $redirect;
					}
				}
				else {
					throw new \exception('Error in database query.');
				}
			}
			else {
				throw new \exception('Sorry, no data was sent. Try again later.');
			}
		}

		/**
		 * Delete an bike from bike table and all specs associated with this bike.
		 * @param $id [int] // id of item to delete.
		 */

		public function delete($id) {
			$this->db->where('bike_id', $id);
			$result = $this->db->delete('bike');
			if($result) {
				$this->db->where('bike_id', $id);
				$res = $this->db->delete('spec'); // Now delete specs related to this bike.
				$this->db->where('bike_id', $id);
				$fav = $this->db->delete('favourite');
				$this->db->where('item_id', $id);
				$this->db->where('love_item', 'bike');
				$lov = $this->db->delete('love');
				$this->db->where('bike_id', $id);
				$rev = $this->db->delete('review');
				$this->db->where('item_id', $id);
				$this->db->where('image_item', 'bike');
				$image = $this->db->delete('image');
				if($res) {
					return deleteRedirect();
				}
			} else {
				throw new \exception("Bike can't be deleted.");
			}
		}
	}

?>