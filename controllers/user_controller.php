<?php

	namespace controllers;

	/**
	 * @file user_controller.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class user extends \controller {

		/**
		 * If user is logged in, display user menu.
		 * If user is not logged in, but request access to this page, redirect her to login form.
		 * This action takes place in the most of user actions.
		 */

		public function index() {
			if($this->user_) {
				$this->returnView($this->model->index());
			}
			else {
				exit(header('Location: /user/login'));
			}
		}

		/**
		 * Display account information.
		 */

		public function account() {
			if($this->user_) {
				$this->returnView($this->model->account($this->user_));
			}
			else {
				exit(header('Location: /user/login'));
			}			
		}

		/**
		 * Display user.
		 */

		public function display() {
			$this->returnView($this->model->display($id));
		}

		/**
		 * Display user's favourite bikes.
		 */

		public function favourites() {
			if($this->user_) {
				$this->returnView($this->model->favourites($this->user_));
			}
			else {
				exit(header('Location: /user/login'));
			}
		}

		/**
		 * Delete account.
		 */

		public function delete() {
			if($this->user_) {
				$this->model->delete($this->user_);
			}
			else {
				exit(header('Location: /user/login'));
			}
			exit(header('Location: /'));
		}

		/**
		 * $logout would be a boolean. After logout, start a new session straight away to setup a message for a user.
		 * If session is not set, logout() will return false. That means that someone tried to enter logout site
		 * not beign logged in. Return to home page and show a message to a user.
		 */

		public function logout() {
			if($this->user_) {
				$this->model->logout();
			}
			exit(header('Location: '.$_SERVER['HTTP_REFERER']));
		}

		// Login

		/**
		 * Display login form.
		 * If user is already logged in and requests a login page, reditect her to user menu.
		 */

		public function login() {
			if(!$this->user_) {
				$this->returnView($this->model->login(), false);
				$this->session->sUnset('error');
			}
			else {
				//exit(header('Location: /user/'));
			}
		}

		/**
		 * Try to login. If login is successfull, redirect straight to user menu.
		 */

		public function log() {
			try {
				$this->model->log();
			}
			catch(\exception $e) {
				$this->session->add('error', $e->getMessage());
				//exit(header('Location: '.$_SERVER['HTTP_REFERER'])); // Redirect back to login form to display errors.
			}
			//exit(header('Location: /user/'));
		}

		// Register

		/**
		 * Display a register new account form.
		 */

		public function register() {
			if(!$this->user_) {
				$this->returnView($this->model->register(), false);
				$this->session->sUnset('error');
			}
			else {
				exit(header('Location: /user/'));
			}
		}

		/**
		 * Create new user, and return a message.
		 */

		public function create() {
			if($_SERVER['REQUEST_METHOD'] !== 'POST') exit(header('Location: /')); // Secure this method from access from 'outside'.
			try {
				$this->returnView($this->model->create(), false);
			}
			catch(\exception $e) {
				$this->session->add('message', $e->getMessage());	
				exit(header('Location: '.$_SERVER['HTTP_REFERER'])); // Redirect back to display errors.
			}
		}

		/**
		 * To verify an email address, token (represented by the id) needs to be set and to be exactly 36 character long.
		 * If there is no token set or it's length is incorrect, redirect to the main page.
		 */

		public function verify() {
			if($this->id && strlen($this->id) == 36) {
				$this->returnView($this->model->verify($this->id));
			}
			else {
				exit(header('Location: /'));
			}
		}

		// Forgot password

		/**
		 * Display forgot password form.
		 */

		public function forgot() {
			if(!$this->user_) {
				$this->returnView($this->model->forgot(), false);
				$this->session->sUnset('error');
			}
			else {
				exit(header('Location: /user/'));
			}
		}

		/**
		 * Send an email to the user with instructions how to reset the password.
		 * Display message that email was sent. 
		 */

		public function forg() {
			if($_SERVER['REQUEST_METHOD'] !== 'POST') exit(header('Location: /')); // Secure this method from access from 'outside'.
			try {
				$this->returnView($this->model->forg(), false);	
			}
			catch(\exception $e) {
				$this->session->add('error', $e->getMessage());
				exit(header('Location: '.$_SERVER['HTTP_REFERER'])); // Redirect back to display errors.
			}
		}

		/**
		 * Display a reset password form. 
		 * Return to the main page if no id (token) is set, or token is not 36 characters long.
		 */

		public function reset() {
			if($this->id && strlen($this->id) == 36) {
				$this->returnView($this->model->reset($this->id), false);
				$this->session->sUnset('error');
			}
			else {
				exit(header('Location: /'));
			}
		}

		/**
		 * Reset the password and display a success message.
		 */

		public function res() {
			if($_SERVER['REQUEST_METHOD'] !== 'POST') exit(header('Location: /')); // Secure this method from access from 'outside'.
			try {
				$this->returnView($this->model->res(), false);	
			}
			catch(\exception $e) {
				$this->session->add('error', $e->getMessage());
				exit(header('Location: '.$_SERVER['HTTP_REFERER'])); // Redirect back to display errors.
			}
		}

		// Reviews

		/**
		 * Post a new review.
		 */

		public function review() {
			if($this->id && $this->user_) {
				try {
					$this->model->review($this->user_);
				}
				catch(\exception $e) {
					$this->session->add('error', $e->getMessage());
				}
				exit(header('Location: '.$_SERVER['HTTP_REFERER']));
			}
			else {
				exit(header('Location: /user/form'));
			}			
		}

		/**
		 * Display all of the users reviews.
		 */

		public function reviews() {
			if($this->user_) {
				$this->returnView($this->model->reviews($this->user_));
			}
			else {
				exit(header('Location: /user/form'));
			}			
		}

		/**
		 * Delete review.
		 */

		public function reviewe() {
			if($this->id && $this->user_) {
				try {
					$this->model->reviewe($this->id, $this->user_);
				}
				catch(\exception $e) {
					$this->session->add('error', $e->getMessage());
				}
				exit(header('Location: '.$_SERVER['HTTP_REFERER']));
			}
			else {
				exit(header('Location: /user/form'));
			}			
		}

		/**
		 * Delete review.
		 */

		public function reviewd() {
			if($this->id && $this->user_) {
				try {
					$this->model->reviewd($this->id, $this->user_);
				}
				catch(\exception $e) {
					$this->session->add('error', $e->getMessage());
				}
				exit(header('Location: '.$_SERVER['HTTP_REFERER']));
			}
			else {
				exit(header('Location: /user/form'));
			}			
		}
	}

?>