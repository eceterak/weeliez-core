<?php

	namespace admin\models;

	/**
	 * @file categoryModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class category extends \admin\model {

		/**
		 * Default action. Display all.
		 * Get amount of bikes associated with caregory. Count bikes with category_id instead of categories with category_id (it will always be at least one).
		 */

		public function index() {
			$this->db->join('bike b', 'b.category_id = c.category_id');
			$this->db->groupBy('c.category_id');
			$this->db->sort(['name' => 'c.category_name', 'bikes' => 'bikesAmount']);
			$categories = $this->db->select('category c', 'c.category_id, c.category_name, COUNT(b.category_id) as bikesAmount');
			if($categories) {
				$categories->create_objects('category_');
				$this->viewModel->add('categories', $categories);
			}
			else {
				$this->viewModel->error('No types to display'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * Edit single type and show all bikes associated to this type.
		 * @param $id [int] // id of item to edit.
		 */

		public function edit($id) {
			$category = \category_::getById($id);
			if($category) {
				$this->db->where('item_id', $id);
				$this->db->where('image_item', 'category');
				$result = $this->db->select('image');
				if($result) {
					$category->loadImages($result);
				}
				$this->db->join('brand br', 'b.brand_id = br.brand_id');
				$this->db->join('category c', 'b.category_id = c.category_id');
				$this->db->where('c.category_id', $id);
				$bikes = $this->db->select('bike b', 'b.bike_id, b.bike_name, br.brand_id, br.brand_name, c.category_id, c.category_name');
				if($bikes) {
					$categories->create_objects('bike_');
					$this->viewModel->add('bikes', $bikes);		
				}
				else {
					$this->viewModel->notice('No bikes in this type.'); // Set error message.		
				}
				$this->viewModel->add('category', $category);
			}			
			else {
				$this->viewModel->add('error', 'Object not found.');
			}
			return $this->viewModel;
		}

		/**
		 * Assign all items associated with deleted object to 0 (unassigned).
		 * @param $id [int] 
		 */

		public function assign($id) {
			$this->db->set('category_id', 0);
			$this->db->where('category_id', $id);
			$result = $this->db->update('bike');
			if($result) {
				return true;
			}
			else {
				return false;
			}
		}
	}

?>