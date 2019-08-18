<?php

	namespace models;
	
	/**
	 * @file attribute_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class attribute extends \model {

		/**
		 * Default action. Display all attributes.
		 */

		public function index() {
			$this->db->join('attribute_type at', 'a.attribute_type_id = at.attribute_type_id');
			$result = $this->db->select('attribute a');
			if($attributes) {
				$attributes->create_objects('attribute_');
				$this->viewModel->add('attributes', $attributes);
			}
			else {
				$this->viewModel->error('There is no items to display.');
			}
			return $this->viewModel;
		}

		/**
		 * Edit single attribute.
		 * @param $id [int] // id of item to edit.
		 */

		public function edit($id) {
			$this->db->join('attribute_type at', 'a.attribute_type_id = at.attribute_type_id');
			$this->db->join('unit u', 'a.unit_id = u.unit_id');
			$this->db->where('a.attribute_id', $id);
			$result = $this->db->selectOne('attribute a');
			if($result) {
				$attribute = new attribute_($result);
				$this->viewModel->add('attribute', $attribute);
			}
			else {
				$this->viewModel->error('Sorry, attribute not found.'); // Set error message.		
			}
			return $this->viewModel;
		}

		/**
		 * This method is a little bit different than typical add method because priority must be set. 
		 * @param $id [int]
		 */

		public function create() {
			$data = getPostValues();
			if($data) {
				$required = requiredField($data->{$this->prefix.'_name'});
				if($required === false) {
					throw new \exception('Fill all inputs.');
				}
				$this->db->where($this->prefix.'_name', $data->{$this->prefix.'_name'});
				$result = $this->db->selectOne($this->prefix);
				if($result) {
					throw new \exception(ucfirst($this->prefix).' with this name already exists.');
				}
				else {
					// Set priority.
					$this->db->where('attribute_type_id', $data->attribute_type_id);
					$result = $this->db->max('attribute', 'attribute_priority');
					if($result) {
						$priority = $result + 1;
					}
					else {
						$priority = 1;
					}
					foreach($data as $key => $value) {
						$this->db->set($key, $value);
						$this->db->set('attribute_priority', $priority);
					}
					$result = $this->db->insert($this->prefix);	
				}
				if($result) {
					return true;
				}
				else {
					throw new \exception('Error in database query.');
				}
			}
			else {
				throw new \exception('Sorry, no data was sent. Try again later.');
			}
		}
	}

?>