<?php

	namespace admin\controllers;

	/**
	 * @file article_controller.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class article extends \admin\controller {

		/**
		 * Validate article for it's content.
		 */

		public function validate() {
			if($_SERVER['REQUEST_METHOD'] == 'GET') {
				if($this->id) {
					try {
						$result = $this->model->validate($this->id);
					} catch(\Exception $e) {
						$this->session->add('error', $e->getMessage());
					}
					exit(header('Location: '.$_SERVER['HTTP_REFERER']));
				}
			}
			else {
				exit(header('Location: '.ADMIN_URL.'/'.$this->prefix)); // Wrong request method.
			}
		}

		/**
		 * Display articles that needs validation (if any).
		 */

		public function unvalidated() {
			$this->returnView($this->model->unvalidated());
		}
	}

?>