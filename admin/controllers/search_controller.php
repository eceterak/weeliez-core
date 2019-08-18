<?php

	namespace admin\controllers;

	/**
	 * @file search_controller.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class search extends \admin\controller {

		/**
		 * Display search results.
		 */

		public function results() {
			try {
				$this->returnView($this->model->results());
			}
			catch(\Exception $e) {
				$this->session->add('message', $e->getMessage());
				exit(header('Location: '.ADMIN_URL.'/search/results'));
			}
		}

		/**
		 * Display search results.
		 */

		public function advanced() {
			try {
				$this->returnView($this->model->advanced());
			}
			catch(\Exception $e) {
				$this->session->add('message', $e->getMessage());
				//exit(header('Location: '.ADMIN_URL.'/search/results'));
			}
		}

		/**
		 * Advanced search results.
		 */

		public function advresults() {
			try {
				$this->returnView($this->model->advresults());
			}
			catch(\Exception $e) {
				$this->session->add('message', $e->getMessage());
				exit(header('Location: '.ADMIN_URL.'/search/advresults'));
			}			
		}
	}

?>