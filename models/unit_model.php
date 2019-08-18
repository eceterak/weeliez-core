<?php

	namespace models;

	/**
	 * @file unit_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class unit extends \model {

		/**
		 * Default action. Display all units.
		 */

		public function index() {
			$units = $this->db->select('unit');
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
				$unit = new unit_($result);
				$this->viewModel->add('unit', $unit);
			}
			else {
				$this->viewModel->error('Sorry, unit not found.'); // Set error message.		
			}
			return $this->viewModel;
		}
	}

?>