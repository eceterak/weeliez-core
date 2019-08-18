<?php

	namespace admin\controllers;

	/**
	 * @file user_controller.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class user extends \admin\controller {

		/**
		 * Display users registered in the last week.
		 */

		public function recent() {
			$this->returnView($this->model->recent());
		}

		/**
		 * Display login and new user forms.
		 */

		public function form() {
			$this->returnView($this->model->form(), false);
		}

		/**
		 * Login and return to the main page.
		 */

		public function login() {
			try {
				$login = $this->model->login();
				if($login === true) {
					$this->session->add('message', 'You are loged in');
				}
			}
			catch(\exception $e) {
				$this->session->add('message', $e->getMessage());
			}
			exit(header('Location: '.ADMIN_URL));
		}

		/**
		 * Create new user, a message and return to the main page. 
		 */

		public function create() {
			try {
				$create = $this->model->create();
				if($create === true) {
					$this->session->add('message', 'New user created!');
				}
			}
			catch(\exception $e) {
				$this->session->add('message', $e->getMessage());	
			}
			exit(header('Location: '.ADMIN_URL));
		}

		/**
		 * Verify email address. Run only if id is of the right length (that means its a token).
		 */

		public function verify() {
			if($this->id && strlen($this->id) == 32) {
				$this->returnView($this->model->verify($this->id));
			}
		}

		/**
		 * $logout would be a boolean. After logout, start a new session straight away to setup a message for a user.
		 * If session is not set, logout() will return false. That means that someone tried to enter logout site
		 * not beign logged in. Return to home page and show a message to a user.
		 */

		public function logout() {
			$logout = $this->model->logout();
			$session = new \session();
			if($logout) {
				$session->add('message', 'Logout successfull.');
			}
			else {
				$session->add('message', 'You are not logged in.');
			}
			exit(header('Location: '.ADMIN_URL));
		}
	}

?>