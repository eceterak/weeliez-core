<?php

	namespace admin\models;

	/**
	 * @file accessModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class access extends \admin\model {

		/**
		 * Block these id's from deleting.
		 * @var array
		 */

		protected $blocked = [11, 1, 4, 13]; // Block deleting basic accounts.

		/**
		 * Display all items.
		 */

		public function index() {
			$this->db->sort(['level' => 'a.access_level', 'name' => 'a.access_name'], 'DESC'); // DESC root first.
			$access = $this->db->select('access a', 'a.access_id, a.access_level, a.access_name');
			if($access) {
				$access->create_objects('access_');
				$this->viewModel->add('access', $access);
			}
			else {
				$this->viewModel->error('Nothing to display'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * Edit single access.
		 * @param $id [int]
		 */

		public function edit($id) {
			$access = \access_::getById($id);
			if($access) {
				$this->viewModel->add('access', $access);
			}
			else {
				$this->viewModel->error('No users access to display'); // Set error message.
			}
			return $this->viewModel;			
		}

		/**
		 * Assign all items associated with deleted object to 0 (unassigned).
		 * @param $id [int] 
		 */

		public function assign($id) {
			$this->db->set('access_id', 0);
			$this->db->where('access_id', $id);
			$result = $this->db->update('user');
			if($result) {
				return true;
			}
			else {
				return false;
			}
		}
	}

?>