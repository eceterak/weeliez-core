<?php

	/**
	 * @file user_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class user_ extends autoLoad {

		/**
		 * User id.
		 * @var int
		 */

		public $user_id;

		/**
		 * User name.
		 * @var string
		 */

		public $user_name;

		/**
		 * Hashed user password.
		 * @var mix
		 */

		public $user_password;

		/**
		 * User email.
		 * @var string
		 */

		public $user_email;

		/**
		 * Users whereabouts.
		 * @var string
		 */

		public $user_location;

		/**
		 * Indicates when account was created.
		 * Do not update!
		 * @var string
		 */

		public $user_created;

		/**
		 * Indicates if user has admin privileges (has access to back end).
		 * @var int
		 */

		public $user_admin;

		/**
		 * User's email address needs to be verified. 
		 * @var string
		 */

		public $user_verification;

		/**
		 * If $user_verified is not equal 1, it means that email needs to be verified.
		 * @var int
		 */

		public $user_verified;

		/**
		 * Display user by his/hers username not id.
		 * @var string
		 */

		public $user_path;

		/**
		 * @todo user levels.
		 * @var int
		 */

		public $access_;

		/**
		 * Indicates if user is 'fully' logged in or just returning (remember me cookie).
		 * In the case of user returning to the website without loggin in, restrict some actions like changing password etc.
		 * @var bool
		 */

		public $restrict = false;

		/**
		 * Minimum lenght of user's name.
		 * @var int
		 */

		public $nameMinLength;

		/**
		 * Maximum lenght of user's name.
		 * @var int
		 */

		public $nameMaxLength;

		/**
		 * Minimum lenght of user's password.
		 * @var int
		 */

		public $passwordMinLength;

		/**
		 * Maximum lenght of user's password.
		 * @var int
		 */

		public $passwordMaxLength;

		/**
		 * Load data and config.
		 * @param $result [stdClass]
		 */

		public function cast($result) {
			$this->load($result);
			$this->access_ = new access_($result);
			$this->loadConfig();
		}

		/**
		 * Return id.
		 * @return int
		 */

		public function getId() {
			return $this->user_id;
		}

		/**
		 * User is returning to the website. Set restrict to true to block some actions. 
		 */

		public function restrict() {
			$this->restrict = true;
		}

		/**
		 * Load config in one method.
		 */

		private function loadConfig() {
			$this->nameMinLength = mysqlib::getConfig('nameMinLength');
			$this->nameMaxLength = mysqlib::getConfig('nameMaxLength');
			$this->passwordMinLength = mysqlib::getConfig('passwordMinLength');
			$this->passwordMaxLength = mysqlib::getConfig('passwordMaxLength');
		}

		/**
		 * Load user data and create new user object if any user of $userId found in db.
		 * If user is requesting access to the back end, set $admin to true to check if user has admin privileges.
		 * @param $userId [int]
		 * @param $admin [bool]
		 * @return user_ / bool
		 */

		static public function getById($userId, $admin = false) {
			$db = new mysqlib();
			$db->join('access a', 'a.access_id = u.access_id');
			$db->where('user_id', $userId);
			if($admin == true) $db->where('user_admin', 1);
			$result = $db->selectOne('user u');
			if($result) {
				return new self($result);
			}
			else {
				return false;
			}
		}

		/**
		 * Load user data using using his name.
		 * @param $userName [string]
		 * @return user_ / bool
		 */		

		static public function getByName($userName) {
			$db = new mysqlib();
			$db->where('user_name', $userName);
			$result = $db->selectOne('user');
			if($result) {
				return new self($result);
			}
			else {
				return false;
			}
		}

		/**
		 * Load user data using his name or email address. Used in login().
		 * If user is requesting access to the back end, set $admin to true to check if user has admin privileges.
		 * @param $details [string]
		 * @param $admin [bool]
		 * @return user_ / bool 
		 */

		static public function getByDetails($details, $admin = false) {
			$db = new mysqlib();
			$db->subQuery();
			$sub = $db->returnObject($db->whereOr('user_email', $details));
			$db->where('user_name', $details, '=', $sub);
			if($admin == true) $db->where('user_admin', 1);
			$result = $db->selectOne('user');
			if($result) {
				return new self($result);
			}
			else {
				return false;
			}
		}

		/**
		 * Create a seo friendly path to the bike. When duplicating bike, add it's id to keep it unique.
		 * @param $id [int]
		 * @return string/null
		 */

		static public function path($id) {
			$db = new mysqlib();
			$db->where('user_id', $id);
			$result = $db->selectOne('user', 'user_name');
			if($result) {
				$user = new self($result);
				return replaceSpaces($user->user_name, '_');
			}
			else {
				return null;
			}
		}

		/**
		 * Check id name provided by the user is correct.
		 * Also, check in db if name isn't already taken.
		 * First of all, check if user_name is empty or not. If it's not, that means that we are updating user.
		 * If provided user name is same as current user name, just return it. It must be correct because all validation
		 * has taken place before.
		 * @param $userName [string]
		 * @return string
		 */

		public function checkName($userName) {
			if(!empty($this->user_name) && $userName == $this->user_name) return $userName;
			$userName = (string) $userName;
			$userNameLength = strlen($userName);
			if($userNameLength >= $this->nameMinLength && $userNameLength <= $this->nameMaxLength) {
				if(!preg_match(("/^[0-9a-zA-z_]+$/"), $userName)) {
					throw new Exception("User name not valid.");
				}
				$db = new mysqlib();
				$db->where('user_name', $userName);
				$result = $db->checkRecordExists('user');
				if(!$result) {
					return $userName; // Notice !$result instead of usual $result.
				}
				else {
					throw new Exception("Username not available."); // User name is already taken and found in DB.
				}
			}
			else {
				throw new Exception("Login needs to be between 3 and 20 characters long."); // User name is too short or too long.
			}
		}

		/**
		 * Check id password provided by the user is correct.
		 * Return hashed password.
		 * @param $userName [string]
		 * @return string
		 */

		public function checkPassword($userPassword) {
			$userPasswordLength = strlen($userPassword);
			if($userPasswordLength >= $this->passwordMinLength && $userPasswordLength <= $this->passwordMaxLength) {
				return $userPassword = password_hash($userPassword, PASSWORD_BCRYPT); // Hash and return the password.	
			}
			else {
				throw new Exception("Password must be between 6 and 20 characters long."); // Password is too short or too long. 			
			}
		}

		/**
		 * Check id password email provided by the user is correct.
		 * Use built in functions to validate email and check in db if email is not already used.
		 * @param $userName [string]
		 * @return string
		 */

		public function checkEmail($userEmail) {
			if(!empty($this->user_email) && $userEmail == $this->user_email) return $userEmail;
			$userEmail = filter_var($userEmail, FILTER_SANITIZE_EMAIL);
			if($userEmail) {
				$db = new mysqlib();
				$db->where('user_email', $userEmail);
				$check = $db->checkRecordExists('user');
				if(!$check) {
					return $userEmail;
				}
				else {
					throw new Exception("That e-mail address is already taken.");		
				}
			} 
			else {
				throw new Exception("Email address is not valid.");
			}
		}

		/**
		 * Each user can post only one review per bike. If user already posted one review return false and hide review form.
		 * @param $bike [bike_]
		 */

		public function checkReview($bike) {
			$db = new mysqlib();
			$db->where('user_id', $this->user_id);
			$db->where('bike_id', $bike->getId());
			$check = $db->checkRecordExists('review');
			if($check) {
				return true;
			}
			return false;
		}

		/**
		 * Indicates if review belongs to the user.
		 * @param $id [int]
		 */

		public function isUsers($id) {
			$db = new mysqlib();
			$db->where('user_id', $this->user_id);
			$db->where('review_id', $id);
			$check = $db->checkRecordExists('review');
			if($check) {
				return true;
			}
			return false;
		}
		/**
		 * Token is saved in database and used by user to validate his/her email.
		 * @param $length [int]
		 * @return string
		 */

		static public function generateToken($length = 18) {
			return bin2hex(openssl_random_pseudo_bytes($length));
		}

		/**
		 * Generate random string. Because 1 byte equals 2 characters (at least for openssl_random_pseudo_bytes()) return only a part (substr()) of generated string of $length.
		 * @param $length [int]
		 * @return string
		 */

		static public function generateRandom($length = 18) {
			return substr(bin2hex(openssl_random_pseudo_bytes($length)), 0, $length);
		}
	}

?>