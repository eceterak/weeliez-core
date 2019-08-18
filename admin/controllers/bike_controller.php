<?php

	namespace admin\controllers;

	/**
	 * @file bike_controller.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class bike extends \admin\controller {

		/**
		 * Display all specs.
		 */

		public function specs() {
			if($this->id) {
				$this->returnView($this->model->specs($this->id));
			} 
			else {
				error404();
			}
		}

		/**
		 * Update specs. Regardless of a result of update, always redirect to a previous page.
		 */

		public function updateSpecs() {
			if($this->id) {
				try {
					$result = $this->model->updateSpecs($this->id);
					if($result) {
						$this->session->add('message', 'Item updated.');
					}
				} catch(\exception $e) {
					$this->session->add('error', $e->getMessage());
				}
				exit(header('Location: '.$_SERVER['HTTP_REFERER']));
			}
			else {
				error404();
			}
		}

		/**
		 * Delete spec.
		 */

		public function deleteSpecs() {
			if($this->id) {
				try {
					$result = $this->model->deleteSpecs($this->id);
					if($result) {
						$this->session->add('message', 'Item deleted.');
					}
				} catch(\exception $e) {
					$this->session->add('message', $e->getMessage());
				}
				exit(header('Location: '.$_SERVER['HTTP_REFERER']));
			}
			else {
				error404();
			}
		}

		/**
		 * Duplicate bike with all specs and data but no images.
		 */

		public function duplicate() {
			if($_SERVER['REQUEST_METHOD'] == 'GET') {
				if($this->id) {
					try {
						$result = $this->model->duplicate($this->id);
						if($result) {
							$this->session->add('message', 'Bike duplicated.');
						}
					}
					catch(\exception $e) {
						$this->session->add('error', $e->getMessage());
					}
				}
				exit(header('Location: '.$_SERVER['HTTP_REFERER']));
			}
			else {
				exit(header('Location: '.ADMIN_URL.'/'.$this->prefix)); // Wrong request method.
			}
		}
		
		/**
		 * Display related bikes.
		 */

		public function related() {
			if($this->id) {
				$this->returnView($this->model->related($this->id));
			}
			else {
				error404();
			}
		}

		/**
		 * Add new bike to related.
		 */

		public function addRelated() {
			if($this->id) {
				try {
					$result = $this->model->addRelated($this->id);
				}
				catch(e\xception $e) {
					$this->session->add('error', $e->getMessage());
				}
			}
			exit(header('Location: '.$_SERVER['HTTP_REFERER']));
		}

		/**
		 * Delete related bike.
		 */

		public function deleteRelated() {
			if($this->id) {
				try {
					$result = $this->model->deleteRelated($this->id);
				}
				catch(\exception $e) {
					$this->session->add('error', $e->getMessage());
				}
			}
			exit(header('Location: '.$_SERVER['HTTP_REFERER']));
		}
	}

?>