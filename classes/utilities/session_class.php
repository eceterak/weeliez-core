<?php

	/**
	 * @file session_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 * Custom and secure session control.	
	 */


	class session {

		/**
		 * Session name.
		 * @var string
		 */
	
		public $sessionName = 'custom_sesh';

		/**
		 * Curent cookie params.
		 * @var string
		 */

		public $cookieParams;

		/**
		 * Set $secure to true to hide session from users.
		 * @var string
		 */

		public $secure = false; // Hide session.

		/**
		 * Safety reasons. Cookie won't be available by JavaScript.
		 * @var string
		 */

		public $httponly = true;

		/**
		 * Make sure that session uses only cookies to get ir's id. Get current cookie params and start session.
		 */

		public function __construct($sessionName = null) {
			//if(!is_null($sessionName)) $this->sessionName = $sessionName;
			/*if(ini_set('session.use_only_cookies', 1) == FALSE) {
				exit(header("Location: index.php"));
			}*/
			$this->cookieParams = session_get_cookie_params();
			$this->start();
		}

		/**
		 * Set session name and cookie params. Set it by hand so there is possibilty to change some settings. 
		 * Then start session and delete old one if any exists.
		 */

		public function start() {
			if(!isset($_SESSION)) {
				session_name($this->sessionName);
				session_set_cookie_params($this->cookieParams['lifetime'], $this->cookieParams['path'], $this->cookieParams['domain'], $this->secure, $this->httponly);
				session_start();
				//session_regenerate_id(true); // Regenerate session and delete old one.
			}
		}

		/**
		 * End the current session if any exists. Unset and destroy session and cookie related to this session.
		 * If no session exists at the moment, return false (helpful to manage messages).
		 * @return bool
		 */

		static public function end() {
			if(isset($_SESSION)) {
				$_SESSION = array();
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 4200, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
				session_unset();
				session_destroy();
				return true;
			}
			return false;
		}

		/**
		 * Add new item to session.
		 * @param $key [string]
		 * @param $value [string/int]
		 */

		public function add($key, $value) {
			$_SESSION[$key] = $value;
		}

		/**
		 * Check if session property of $key exists and unset it.
		 * Can unset many session keys at the time, thanks to func_get_args() and as a static method.
		 */

		static public function sUnset() {
			$params = func_get_args();
			foreach($params as $key) {
				if(isset($_SESSION[$key])) {
					unset($_SESSION[$key]);
				}
			}
		}

		/**
		 * Add new cookie.
		 * Just for testing. In a production enviroment use $domain, $secure and $httponly.
		 * @param $key [string]
		 * @param $value [string/int]
		 */

		static public function cookie($key, $value, $time, $location = '/', $domain = NAME, $secure = 1, $httponly = 1) {
			if(!isset($_COOKIE[$key])) {
				setcookie($key, $value, $time, $location);
			}
			else {
				self::cUnset($key);
				self::cookie($key, $value, $time, $location);
			}
		}

		/**
		 * To unset cookie, set it's value to null.
		 * @param $key [string]
		 */

		static public function cUnset($key) {
			setcookie($key, null, -1, '/');
			unset($_COOKIE[$key]);
		}

		/**
		 * Testing.
		 */

		public function getCookiesParams() {
			var_dump(session_get_cookie_params());
		}
	}

?>