<?php

	namespace models;

	/**
	 * @file typeModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class category extends \model {

		/**
		 * Default action. Display all.
		 */

		public function index() {
			$categories = $this->db->select('category', 'category_id, category_name, category_path');
			if($categories) {
				$categories->create_objects('category_');
				$this->viewModel->add('categories', $categories); // Add array of bikes to viewModel.
			}
			else {
				$this->viewModel->error('No types to display'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * Edit single type and show all bikes associated to this type.
		 * @param $path [string]
		 */

		public function display($path) {
			$category = \category_::getByPath($path);
			if($category) {
				$this->viewModel->add('category', $category);
				$this->db->join('brand br', 'b.brand_id = br.brand_id');
				$this->db->join('category c', 'b.category_id = c.category_id');
				$this->db->where('c.category_id', $category->getId());
				$bikes = $this->db->select('bike b', 'b.bike_id, b.bike_name, b.bike_year_start, b.bike_year_end, br.brand_id, br.brand_name');
				if($bikes) {
					$bikes->create_objects('bike_');
					$this->viewModel->add('bikes', $bikes);
				}
				else {
					$this->viewModel->notice('No bikes in this type.'); // Set error message.		
				}
			}			
			else {
				$this->viewModel->add('error', 'Object not found.');
			}
			return $this->viewModel;
		}

		/**
		 * Delete item and update bikes associated to deleted item with unassigned type.
		 * @param $id [int] // id of item to delete.
		 */

		public function delete($id) {
			$this->db->where('category_id', $id);
			$result = $this->db->delete('type');
			if($result) {
				$this->db->set('category_id', 1); // Set to ubranded.	
				$this->db->where('category_id', $id);
				$res = $this->db->update('bike'); // Update all bikes with new \brand.
				if($res) {
					return deleteRedirect(); // Redirect back to main folder.
				}
			} 
			else {
				throw new \exception("Item can't be deleted right now.");
			}
		}
	}

?>