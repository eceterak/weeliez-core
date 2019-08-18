<?php

	namespace admin\controllers;
	
	/**
	 * @file image_controller.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class image extends \admin\controller {

		/**
		 * Display all images associated with bike.
		 */

		public function bike() {
			if($this->id) {
				$this->returnView($this->model->bike($this->id));
			}
			else {
				error404();
			}
		}

		/**
		 * Display all images associated with brand.
		 */

		public function brand() {
			if($this->id) {
				$this->returnView($this->model->brand($this->id));
			}
			else {
				error404();
			}
			
		}

		/**
		 * Display all images associated with type.
		 */

		public function type() {
			if($this->id) {
				$this->returnView($this->model->type($this->id));
			}
			else {
				error404();
			}
		}


		/**
		 * Upload a image.
		 * Model is returning an array of results of upload. True if upload was successfull and a error message if not.
		 * Loop through whole array and create a message to user with result with all of the uploads.
		 */

		public function upload() {
			try {
				$upload = $this->model->upload();
				$msg = '';
				foreach($upload as $message) {
					if($message === true) {
						$msg .= 'File uploaded.<br />';
					}
					else {
						$msg .= $message.'<br />';
					}
					$this->session->add('message', $msg);
				}
			} catch(\exception $e) {
				$this->session->add('error', $e->getMessage());
			}
			exit(header('Location: '.$_SERVER['HTTP_REFERER']));
		}

		/**
		 * Delete image.
		 */

		public function delete() {
			try {
				if($this->id) {
					$result = $this->model->delete($this->id);
					if($result) {
						$this->session->add('message', 'Image deleted.');
					}
				}
			} catch (\exception $e) {
				$this->session->add('error', $e->getMessage());
			}
			exit(header('Location: '.$_SERVER['HTTP_REFERER']));	
		}	

		/**
		 * Set image to be a default one. 'Default' word is a reserved word in php, that's why using def instead.
		 */

		public function def() {
			try {
				if($this->id) {
					$result = $this->model->def($this->id);
					if($result) {
						$this->session->add('message', 'Default image changed.');
					}
				}
			} catch (\exception $e) {
				$this->session->add('error', $e->getMessage());				
			}
			exit(header('Location: '.$_SERVER['HTTP_REFERER']));	
		}
	}

?>