<?php

	namespace controllers;

	/**
	 * @file search_controller.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class search extends \controller {

		/**
		 * Display search results.
		 */

		public function results() {
			$this->session->sUnset('message');
			try {
				$this->returnView($this->model->results());
			}
			catch(\exception $e) {
				$this->session->add('message', $e->getMessage());
				exit(header('Location: ./search/results'));
			}
		}

		/**
		 * Dislay advanced search form.
		 */

		public function advanced() {
			$this->session->sUnset('message');
			try {
				$this->returnView($this->model->advanced());
			}
			catch(\exception $e) {
				$this->session->add('message', $e->getMessage());
				//exit(header('Location: '.ADMIN_URL.'/search/results'));
			}
		}

		/**
		 * Display advanced search results.
		 */

		public function advresults() {
			$this->session->sUnset('message');
			try {
				$this->returnView($this->model->advresults());
			}
			catch(\exception $e) {
				$this->session->add('message', $e->getMessage());
				exit(header('Location: '.ADMIN_URL.'/search/advresults'));
			}			
		}
	}

?>