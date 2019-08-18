<?php

	namespace models;

	/**
	 * @file brand_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class brand extends \model {

		/**
		 * Default action. Display all brands and show how many brands displaying.
		 * Group brands by its first letter. 
		 */

		public function index() {
			$this->db->groupBy('brand_id');
			$this->db->join('brand br', 'b.brand_id = br.brand_id');
			$this->db->order('brand_name');
			$brands = $this->db->select('bike b', 'b.brand_id, br.brand_name, br.brand_path, COUNT(*) as bikesAmount');
			if($brands) {
				$brands->create_objects('brand_');
				foreach($brands->data as $brand) {
					$arr[substr($brand->brand_name, 0, 1)][] = $brand; // Multidimensional array where brands are grouped by its first letter.
				}
				$brands->data = $arr; // Replace array from dataObject with grouped array.
				$this->viewModel->add('brands', $brands);
			}
			else {
				$this->viewModel->error('No brands to display'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * Display a brand and all bikes associated with it.
		 * @param $path [int]
		 */

		public function display($path) {
			$brand = \brand_::getByPath($path);
			if($brand) {
				$this->viewModel->add('brand', $brand);
				$this->db->join('category c', 'b.category_id = c.category_id');
				$this->db->where('b.brand_id', $brand->getId());
				$this->db->sort(['name' => 'b.bike_name', 'year' => 'b.bike_year_start', 'category' => 'c.category_name']);
				$bikes = $this->db->select('bike b', 'b.bike_id, b.bike_name, b.bike_year_start, b.bike_year_end, c.category_id, c.category_name', 20);
				if($bikes) {
					$bikes->create_objects('bike_');
					$this->viewModel->add('bikes', $bikes);
					$this->db->where('brand_id', $brand->getId());
					$this->viewModel->add('bikes_count', $this->db->count('bike')); // Count bike by brand.
				}
				else {
					$this->viewModel->notice('No bikes in this brand.'); // Set error message.		
				}
			}
			else {
				$this->viewModel->add('error', 'Brand not found.');
			}
			return $this->viewModel;
		}
	}

?>