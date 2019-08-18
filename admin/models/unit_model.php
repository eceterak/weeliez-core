<?php

	namespace admin\models;

	/**
	 * @file unitModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class unit extends \admin\model {

		/**
		 * Default action. Display all units.
		 */

		public function index() {
			$this->db->sort(['name' => 'u.unit_name']);
			$units = $this->db->select('unit u', 'u.unit_id, u.unit_name');
			if($units) {
				$units->create_objects('unit_');
				$this->viewModel->add('units', $units);
			} 
			else {
				$this->viewModel->error('No units to display.');
			}
			return $this->viewModel;
		}

		/**
		 * Edit single unit.
		 * @param $id [int] // id of item to edit.
		 */

		public function edit($id) {
			$this->db->where('unit_id', $id);
			$result = $this->db->selectOne('unit');
			if($result) {
				$unit = new \unit_($result);
				$this->viewModel->add('unit', $unit);
			}
			else {
				$this->viewModel->error('Sorry, unit not found.'); // Set error message.		
			}
			return $this->viewModel;
		}
	}

?>