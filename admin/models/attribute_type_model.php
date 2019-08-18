<?php

	namespace admin\models;

	/**
	 * @file attribute_typeModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class attribute_type extends \admin\model {

		/**
		 * Display all records from database.
		 * @return array // Array of attribute_type_ objects.
		 */

		public function index() {
			//$this->db->sort(['name' => 'at.attribute_type_name']);
			$this->db->order('attribute_type_priority');
			$attribute_types = $this->db->select('attribute_type at', 'at.attribute_type_id, at.attribute_type_name');
			if($attribute_types) {
				$attribute_types->create_objects('attribute_type_');
				$this->viewModel->add('attribute_types', $attribute_types);
			}
			else {
				$this->viewModel->error('There is no items to display.');
			}
			return $this->viewModel;
		}

		/**
		 * Edit single attribute type.
		 * @param $id [int] // id of item to edit.
		 */

		public function edit($id) {
			$this->db->where('attribute_type_id', $id);
			$result = $this->db->selectOne('attribute_type');
			if($result) {
				$attribute_type = new \attribute_type_($result);
				$this->viewModel->add('attribute_type', $attribute_type); // Add to viewModel.;
				$this->db->where('attribute_type_id', $id);
				$this->db->order('attribute_priority');
				$attributes = $this->db->select('attribute');
				if($attributes) {
					$attributes->create_objects('attribute');
					$this->viewModel->add('attributes', $attributes);
				}
				else {
					$this->viewModel->notice('There is no attributes of this type.');
				}
			}
			else {
				$this->viewModel->error('Sorry, attribute type not found.');
			}
			return $this->viewModel;
		}

		/**
		 * This method is a little bit different than typical add method because priority must be set. 
		 */

		public function create() {
			$data = getPostValues();
			if($data) {
				$required = requiredField($data->{$this->prefix.'_name'});
				if($required === false) {
					throw new \exception('Fill all inputs.');
				}
				if(property_exists($data, 'redirect')) {
					$redirect = $data->redirect;
					unset($data->redirect);
				}
				$this->db->where($this->prefix.'_name', $data->{$this->prefix.'_name'});
				$result = $this->db->selectOne($this->prefix);
				if($result) {
					throw new \exception(ucfirst($this->prefix).' with this name already exists.');
				}
				else {
					// Set priority.
					$result = $this->db->max('attribute_type', 'attribute_type_priority');
					if($result) {
						$priority = $result + 1;
					}
					else {
						$priority = 1;
					}
					foreach($data as $key => $value) {
						$this->db->set($key, $value);
						$this->db->set('attribute_type_priority', $priority);
					}
					$result = $this->db->insert($this->prefix);	
				}
				if($result) {
					if(isset($redirect)) return $redirect;
					else return true;
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
		 * If change was done using up/down arrows, $_GET[direction] should be set.
		 * Load category of $id, then calculate new priority.
		 * Swap places between categories.
		 * @param $id [int]
		 */

		public function priority($id) {
			$attribute_type = \attribute_type_::getById($id);
			if($attribute_type === false) {
				throw new \exception('Item cannot be rearanged.');
			}
			if(isset($_GET['direction'])) {
				$direction = $_GET['direction'];
				if($direction == 'up') {
					if($attribute_type->attribute_type_priority > 1) {
						$priority = $attribute_type->attribute_type_priority - 1; // Goes up - number lower (ascending order).
					}
					else {
						throw new \exception('Cannot move up.');
					}
				}
				else {
					$priority = $attribute_type->attribute_type_priority + 1; // Goes down - number higher.
				}
			}
			// Swap items.
			$this->db->where('attribute_type_priority', $priority);
			$this->db->set('attribute_type_priority', $attribute_type->attribute_type_priority);
			$result = $this->db->update('attribute_type');
			if($result) {
				$this->db->where('attribute_type_id', $id);
				$this->db->set('attribute_type_priority', $priority);
				$result = $this->db->update('attribute_type');
				if($result) {
					return true;
				}
			}
			throw new \exception('Item cannot be rearanged.');
		}

		/**
		 * Delete an attribute type from database and all attributes associated to this type.
		 * @param $id [int] // id of item to delete.
		 */

		public function delete($id) {
			$this->db->where('attribute_type_id', $id);
			$result = $this->db->delete('attribute_type');
			if($result) {
				$this->db->where('attribute_type_id', $id);
				$res = $this->db->delete('attribute'); // Now delete specs related to this bike.
				if($res) {
					return deleteRedirect();
				}
			} 
			else {
				throw new \exception("Attribute can't be deleted.");
			}
		}
	}

?>