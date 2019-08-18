<?php

	namespace admin\models;

	/**
	 * @file bikeModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class brand extends \admin\model {

		/**
		 * Default action. Display all brands.
		 * Also, count how many bikes are associated with every brand.
		 */

		public function index() {
			$this->db->join('bike b', 'b.brand_id = br.brand_id');
			$this->db->groupBy('br.brand_id');
			$this->db->sort(['name' => 'br.brand_name', 'year' => 'br.brand_year', 'bikes' => 'bikesAmount']);
			$brands = $this->db->select('brand br', 'br.brand_id, br.brand_name, br.brand_year, COUNT(b.brand_id) as bikesAmount');
			if($brands) {
				$brands->create_objects('brand_');
				$this->viewModel->add('brands', $brands); // Add array of bikes to viewModel.
			}
			else {
				$this->viewModel->error('No brands to display'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * Edit single brand.
		 * @param $id [int] // id of item to edit.
		 */

		public function edit($id) {
			$brand = \brand_::getById($id);
			if($brand) {
				$this->db->where('item_id', $id);
				$this->db->where('image_item', 'brand');
				$result = $this->db->select('image');
				if($result) {
					$brand->loadImages($result);
				}
				$this->db->join('brand br', 'b.brand_id = br.brand_id');
				$this->db->join('category c', 'b.category_id = c.category_id');
				$this->db->where('br.brand_id', $id);
				$bikes = $this->db->select('bike b', 'b.bike_id, b.bike_name, b.bike_year_start, b.bike_year_end, b.bike_sale, br.brand_id, br.brand_name, c.category_id, c.category_name');
				if($bikes) {
					$bikes->create_objects('bike_');
					$this->viewModel->add('bikes', $bikes);
				}
				else {
					$this->viewModel->notice('No bikes in this brand.'); // Set error message.		
				}
				$this->viewModel->add('brand', $brand);
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
			$this->db->set('brand_id', 0);
			$this->db->where('brand_id', $id);
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