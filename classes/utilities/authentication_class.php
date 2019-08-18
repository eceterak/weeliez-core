<?php

	/**
	 * @file authentication_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class authentication {

		/**
		 * Time is used in brute force protection method.
		 * @var int
		 */

		private $currentTime;

		/**
		 * Time in hours between logins.
		 * @var int
		 */

		private $bruteForceTime = 2;

		/**
		 * Maximum login attempts between blocking account.
		 * @var int
		 */

		private $loginAttemptsAllowed = 5;

		/**
		 * User object. Can manage only one user at the time.
		 * @var user_
		 */

		public $user_;

		/**
		 * Get current time and user_ if provided.
		 * @param user_
		 */

		public function __construct(user_ $user_ = null) {
			$this->currentTime = time();
			if(!is_null($user_)) {
				$this->user_ = $user_;
			}
		}

		/**
		 * This method secures all pages that need authentication.
		 * If user tries to enter secured page but he/she is not logged he/she would be redirected to login page.
		 * If session and user of $userId exists load his details and compare his password (from db) with password provided by session.
		 * Also, get user's browser details to prevent stealing a session. Those details needs to match details from set in session
		 * when user logged in. Password needs to be hashed before comparing with the one from session.
		 * If everything is OK, return authentication object with loaded user (to display user menu etc.).
		 * If 'logged' session does not exists, but 'remember' COOKIE does, that means that user is returning. 
		 * Check if his/her signature and tokens math those saved in database.
		 * In the case of user returning to the website without loggin in, restrict some actions like changing password etc.
		 * Use simple when woring with ajax, to simply get user data without updating the cookie etc.
		 * This method can work differently on front and back end. In case of back end, it will look if user has a admin privileges. If not it will return false.
		 * @param $admin [bool]
		 * @param $simple [bool]
		 * @return user [object] / bool [false]
		 */

	 	public function isLogged($simple = false, $admin = false) {
			if(isset($_SESSION['userId'], $_SESSION['userName'], $_SESSION['userSap'])) {
				$userId = $_SESSION["userId"];
				$userName = $_SESSION["userName"];				
				$userSap = $_SESSION["userSap"]; // This password should be hash'ed.
				$user = user_::getById($userId, $admin);
				if($user !== false) {
					$userBrowser = $_SERVER['HTTP_USER_AGENT']; // Get information about user browser.
					$sap = hash('sha512', $user->user_password.$userBrowser); // Hash password so you can compare it to hash'ed password stored in session.
					if(hash_equals($userSap, $sap)) {
						return $user;
					}
				}
			}
			elseif(isset($_COOKIE['tt'])) {
				$cookie = base64_decode($_COOKIE['tt']);
				if(!$cookie) {
					return false; // Cannot encode. Not base64?.
				}
				$cookie = json_decode($cookie);
				if(!$cookie) {
					return false;
				}
				if(!isset($cookie->user) || !isset($cookie->token) || !isset($cookie->signature)) {
					return false; // Check for all required informations.
				}
				$verify = $this->verify($cookie->user.$cookie->token, $cookie->signature);
				if(!$verify) {
					return false; // Something wrong with cookie provided signature.
				}
				$db = new mysqlib();
				$db->join('access a', 'a.access_id = u.access_id');
				$db->join('user_remember ur', 'ur.user_id = u.user_id');
				$db->where('ur.user_id', $cookie->user);
				if($admin == true) $db->where('u.user_admin', 1); // Back end.
				$db->where('ur.user_browser', $_SERVER['HTTP_USER_AGENT']);
				$user = $db->selectOne('user u');
				if(!$user) {
					return false; // User deleted account or don't have admin privileges (in case of back end).
				}
				$info = base64_decode($user->user_token);
				$info = json_decode($info);
				if(!$info) {
					return false;
				}
				if($info->token != $cookie->token) {
					return false; // Database and cookie token don't math.
				}
				$user = new \user_($user);
				if(!$simple) $this->remember($user, new mysqlib()); // Update cookie (token and signature) and record in database.
				$user->restrict(); // User is returning. Restrict access to some actions.
				return $user;
			}
			return false;
		}

		/**
		 * Set a cookie and insert/update user_remember record in database. Remember me cookie is called 'tt'.
		 * There can be only one user_remember record in database for user of $id, unless, user is logged from different browsers.
		 * @param $user [user_]
		 * @param $db [mysqlib]
		 */

		public function remember($user, mysqlib $db) {
			$cookie = array(
				'user' => $user->user_id,
				'token' => $user->generateToken(64), // Random 64 bit (128 characters) token.
				'signature' => null
			);
			$cookie['signature'] = $this->hash($cookie['user'].$cookie['token']);
			$cookie = json_encode($cookie);
			$cookie = base64_encode($cookie); // Decode the cookie so id of user won't be seen.
			session::cookie('tt', $cookie, time() + 60*60*24*30, '/');
			$userBrowser = $_SERVER['HTTP_USER_AGENT']; // Get information about user browser.
			$db->where('user_browser', $userBrowser);
			$db->where('user_id', $user->user_id);
			$record = $db->checkRecordExists('user_remember');
			$db->set('user_id', $user->user_id);
			$db->set('user_token', $cookie);
			$db->set('user_browser', $userBrowser);
			if($record) {
				$db->where('user_browser', $userBrowser);
				$db->where('user_id', $user->user_id);
				return $db->update('user_remember');
			}
			else {
				return $db->insert('user_remember');
			}
		}

		/**
		 * Generate a signature by combining random 4 characters string with hashed user id, token and again, same random 4 character string.
		 * Can also create a signature when random string is provided (use to verify signature and token).
		 * @param $userNToken [string]
		 * @param $rand [string]
		 */

		public function hash($userNToken, $rand = null) {
			if(is_null($rand)) $rand = user_::generateRandom(4); // Generate random 4 character string.
			$signature = $rand.bin2hex(hash_hmac('sha256', $userNToken.$rand, 'OWMyNmRlNzBkYmEwNWEzN2Y0MzNjNTRiYTIxYjJhNjZhMjg3YzE5YzgwMWVjZmUx', true));
			return $signature;
		}

		/**
		 * Verify token and signature.
		 * @param $token [string]
		 * @param $signature [string]
		 */

		public function verify($token, $signature) {
			$rand = substr($signature, 0, 4);
			$eq = $this->hash($token, $rand);
			return $eq === $signature;
		}

		/**
		 * Check if user's email address is verified.
		 * @param $user_id [int]
		 * @return bool
		 */

		public function isVerified($user_id) {
			$db = new mysqlib();
			$db->where('user_id', $user_id);
			$db->where('user_verified', 1);
			$verified = $db->checkRecordExists('user');
			if($verified) {
				return true;
			}
			else {
				return false;
			}
		}

		/**
		 * Check if user tried to log in too many times. If that happened, lock his account by returning false.
		 * @param $userId [int]
		 * @return bool
		 */

		public function bruteForceProtection($user_id) {
			$permitedTime = $this->currentTime - (60 * 60 * $this->bruteForceTime);
			$db = new mysqlib();
			$db->where('user_id', $user_id);
			$db->where('attempt_time', $permitedTime, '>');
			$result = $db->select('login_attempts');
			if($result) {
				if($result->num_rows >= $this->loginAttemptsAllowed) {
					return false;
				}
			}
			return true;
		}

		/**
		 * Record failed login attempt.
		 * @param $userId [int]
		 * @return bool
		 */

		public function loginAttemptFailed($userId) {
			$db = new mysqlib();
			$db->set('user_id', $userId);
			$db->set('attempt_time', $this->currentTime);
			$result = $db->insert('login_attempts');
			if($result) {
				return true;
			}
			return false;
		}
	}

?>