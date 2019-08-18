<?php

	namespace models;

	/**
	 * @file user_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class user extends \model {

		/**
		 * Display user's menu.
		 */

		public function index() {
			return $this->viewModel;
		}

		/**
		 * Display account informations. 
		 * @param $user [user_]
		 */

		public function account($user) {
			$this->viewModel->add('user', $user);
			return $this->viewModel;
		}

		/**
		 * Display user.
		 * @param $id [int] 
		 */

		public function display($id) {
			$user = user_::getById($id);
			if($user) {
				$this->viewModel->add('user', $user);
			}
			return $this->viewModel;
		}

		/**
		 * Display user's favourite bikes.
		 * @param $id [int]
		 */

		public function favourites($user) {
			$this->db->join('bike b', 'f.bike_id = b.bike_id');
			$this->db->where('f.user_id', $user->user_id);
			$favourites = $this->db->select('favourite f');
			if($favourites) {
				$favourites->create_objects('bike_');
				$this->viewModel->add('favourites', $favourites);
			}
			return $this->viewModel;
		}

		/**
		 * Delete account and all data associated with this account (favourites etc.). Do not delete user's review and loves as they can be helpfull to others.
		 * @param $user [user_]
		 */

		public function delete($user) {
			$this->db->where('user_id', $user->getId());
			$fav = $this->db->delete('favourite'); // Delete user favourites.
			$this->db->where('user_id', $user->getId());
			$rem = $this->db->delete('user_remember'); // Delete remember me records.
			$this->db->where('user_id', $user->getId());
			$del = $this->db->delete('user'); // Delete user account.
			session::cUnset('tt'); // Unset remember me cookies.
			return session::end();
		}

		/**
		 * If there is no session it will return false, otherwise true.
		 * Destroy remember me cookie before logout.
		 * Remember me cookie is called 'tt'.
		 * @see session->end()
		 * @return bool 
		 */

		public function logout() {
			\session::cUnset('tt');
			return \session::end();
		}

		/**
		 * Update item in the database. Run only when request method equals POST.
		 * First, get POST values. Then, check if any data was sent. $data object should contain redirect property which is a adress of the main page.
		 * @param $id [int] // id of item to update.
		 * @return string
		 */

		public function update($id) {				
			$data = getPostValues();
			if($data) {
				if(property_exists($data, 'redirect')) {
					$redirect = $data->redirect;
					unset($data->redirect);
				}
				$required = requiredField($data->{$this->prefix.'_name'});
				if($required === false) {
					throw new \exception('Fill all inputs.');
				}
				$user = user_::getById($id);
				$userName = $user->checkName($data->user_name);
				$userEmail = $user->checkEmail($data->user_email);
				$path = $this->path($id);
				if($path) {
					$this->db->set($this->prefix.'_path', $path);
				}
				$this->db->set('user_name', $userName);
				$this->db->set('user_email', $userEmail);
				$this->db->where($this->prefix.'_id', $id); // Id comes from url not form.
				$result = $this->db->update($this->prefix);
				if($result) {
					return $redirect;
				}
				else {
					throw new \exception('Error in database query.');
				}
			}
			else {
				throw new \exception('Sorry, no data was sent. Try again later.');
			}
		}

		// Login

		/**
		 * Display login form.
		 */

		public function login() {}

		/**
		 * Try to login by name or email. If account is not blocked (brute force), check password. 
		 * If provided password is correct login user by setting session details.
		 * Get information about user's browser and connect it with password. This will prevent stealing session and login from different pc.
		 * If provided password is incorrect record failed login attempt.
		 * Return 'Incorrect username OR password' message to not give a hints about username. 
		 * @return bool
		 */

		public function log() {
			$data = getPostValues();
			if($data) {
				$user = \user_::getByDetails($data->user_name); // Search for a user.
				if($user !== false) {
					$authentication = new \authentication();
					$brute = $authentication->bruteForceProtection($user->user_id);
					if($brute) {
						$verified = $authentication->isVerified($user->user_id);
						if($verified) {
							if(password_verify($data->user_password, $user->user_password)) {
								$session = new \session();
								$session->start();
								$userBrowser = $_SERVER['HTTP_USER_AGENT']; // Get information about browser.
								$session->add('userId', $user->user_id);
								$session->add('userName', $user->user_name);
								$session->add('userSap', hash('sha512', $user->user_password.$userBrowser));
								$authentication->remember($user, $this->db); // Set a new 'remember me' cookie.
								return true;
							}
							else {
								$record = $authentication->loginAttemptFailed($user->user_id); // Someone tried to log in and failed. Record this attempt.
								throw new \Exception('Incorrect username or Password.');
							}					
						}
						else {
							throw new \Exception("Please verify your email address. If you didn't receive a verification email, please contact the administration.");
						}
					}
					else {
						throw new \Exception('Account blocked due to safety reasons.');
					}
				}
				throw new \Exception('Incorrect username or Password.');
			}
		}

		// Register

		/**
		 * Display login/create user form.
		 */

		public function register() {}

		/**
		 * Validate user name, password and email. Also check if name and email are not already taken.
		 * All of those methods throws Exceptions. Catch those in the controller.
		 */

		public function create() {
			$data = getPostValues();
			if($data) {
				$user = new \user_();
				$userName = $user->checkName($data->user_name);
				$userPassword = $user->checkPassword($data->user_password);
				$userEmail = $user->checkEmail($data->user_email);
				$token = $user->generateToken(); // Generate token before seting database values.
				$mail = \mail::sendVerificationEmail($userEmail, $token);
				$mail = true;
				if(!$mail) {
					throw new Exception("We're unable to send the verification email. Please try again later.");
				}
				$this->db->set('user_name', $userName);
				$this->db->set('user_password', $userPassword);
				$this->db->set('user_email', $userEmail);
				$this->db->set('user_verification', $token);
				$this->db->set('user_verified', 0);
				$this->db->set('access_id', 13); // New user - 'user' access by default.
				$result = $this->db->insert('user');
				if($result) {
					$id = $this->db->max($this->prefix, $this->prefix.'_id'); // Get id of inserted item.
					$path = $this->path($id);
					if($path) {
						$this->db->set($this->prefix.'_path', $path);
						$this->db->where($this->prefix.'_id', $id);
						$update = $this->db->update($this->prefix);
						if(!$update) {
							throw new \exception('Database error. Please try again later.');
						}
					}
				}
				else {
					throw new \exception('Database error. Please try again later.');
				}
			}
			return $this->viewModel;
		}

		/**
		 * Check if token exists in a database. If yes, user account will be verified.
		 * @param $id [int]
		 */

		public function verify($id) {
			$this->db->where('user_verification', $id);
			$result = $this->db->selectOne('user', 'user_id');
			if($result) {
				$this->db->set('user_verified', 1); // Set column value to 1. User is verified.
				$this->db->where('user_id', $result->user_id);
				$verified = $this->db->update('user');
				if($verified) {
					$this->viewModel->add('verification', true);
				}
			}
			return $this->viewModel;
		}

		// Forgot password

		/**
		 * Display forgot password form.
		 */

		public function forgot() {}

		/**
	 	 * Reset user's password. Send an email with instructions.
	 	 * Don't throw any errors if account of given email does not exists to not give a hint for email address thievs.
	 	 * If for some reson, password can't be reseted, redirect to previous page and display error.
	 	 * If everything went right, display success message.
		 */

		public function forg() {
			$data = getPostValues();
			if($data) {
				$user = \user_::getByDetails($data->user_email);
				if($user !== false) {
					$token = \user_::generateToken();
					$mail = \mail::forgotPasswordEmail($user->user_email, $token);
					$mail = true;
					if($mail) {
						$this->db->where('user_id', $user->getId());
						$check = $this->db->checkRecordExists('password_reset');					
						if($check) {
							$this->db->where('user_id', $user->getId());
							$this->db->delete('password_reset'); // Delete record.
						}
						$this->db->set('user_id', $user->getId());
						$this->db->set('password_token', $token);
						$this->db->set('password_time', time());
						$result = $this->db->insert('password_reset');
						if(!$result) {
							throw new \exception('Database error. Please try again later.');
						}
					}
					else {
						throw new \exception("We're unable to send an reset password email. Please try again later.");
					}
				}
				$this->viewModel->add('email', $data->user_email);
				return $this->viewModel;
			}
		}

		/**
		 * Display reset password form (to set a new password).
		 * Add token to viewModel so it can be included into the form.
		 * Token is required be the res() method (where condition).
		 * Check if token has not expired.
		 * @param $token [string]
		 */

		public function reset($token) {
			$this->db->where('password_token', $token);
			$reset = $this->db->selectOne('password_reset');
			if($reset) {
				$time = $reset->password_time - (time() - 60 * 60 * 24); // Token can be used for only 24 hours after generating it.
				if($time <= 0) {
					$this->viewModel->add('error', 'Token expired! Tokens are valid for only 24 hours after requesting a password change.');
				}
				else {
					$this->viewModel->add('token', $token);
				}
			}
			else {
				$this->viewModel->add('error', 'Token not found!');
			}
			return $this->viewModel;
		}

		/**
		 * Set new password for user of id coming from form. To ensure that password is reseted for a right user, set two conditions of user_id and user_forgot (token from url).
		 * After reseting the password, delete token (set user_forgot to NULL).
		 * @param $id [int]
		 */

		public function res() {
			$data = getPostValues();
			if($data) {
				$user_ = new \user_();
				$userPassword = $user_->checkPassword($data->new_password); // Check if password is valid.
				if($data->new_password !== $data->confirm_password) {
					throw new \exception('Passwords do not match.'); // Passwords are not the same.
				}
				$this->db->where('password_token', $data->token);
				$user = $this->db->selectOne('password_reset', 'user_id');
				if($user) {
					$this->db->set('user_password', $userPassword);
					$this->db->where('user_id', $user->user_id);
					$result = $this->db->update('user');
					if(!$result) {
						throw new \exception('Database error. Please try later.');	
					}
					$this->db->where('user_id', $user->user_id);
					$this->db->delete('password_reset');
				}
				else {
					throw new \exception('Database error. Please try later.');
				}
			}
			else {
				throw new \exception('Cannot change right now. Please try later.');	
			}
			return $this->viewModel;
		}	

		// Reviews

		/**
		 * Add new \review. Check if user already added review of bike.
		 * @param $user [user_]
		 */

		public function review($user) {
			$data = getPostValues();
			if($data) {
				$this->db->where('bike_id', $data->bike_id);
				$this->db->where('user_id', $user->getId());
				$check = $this->db->checkRecordExists('review');
				if($check) {
					throw new \exception('Sorry, you have already posted a review of this bike.');
				}
				foreach($data as $key => $value) {
					$this->db->set($key, $value);
				}	
				$this->db->set('user_id', $user->getId());
				$result = $this->db->insert('review');
				if($result) {
					return true;
				}
			}
		}

		/**
		 * Display all users reviews.
		 * @param $user [user_]
		 */

		public function reviews($user) {
			$this->db->join('bike b', 'b.bike_id = r.bike_id');
			$this->db->where('r.user_id', $user->getId());
			$reviews = $this->db->select('reviews r');
			if($reviews) {
				$reviews->create_objects('review_');
				$this->viewModel->add('reviews', $reviews);
			}
			return $this->viewModel;
		}

		/**
		 * Edit review..
		 * @param $id [int]
		 * @param $user [user_]
		 */

		public function reviewe($id, $user) {
			$data = getPostValues();
			if($data) {
				$this->db->where('review_id', $id);
				$this->db->set('review_title', $data->review_title);
				$this->db->set('review_content', $data->review_content);
				$update = $this->db->update('reviews');
			}
		}

		/**
		 * Delete a review.
		 * @param $id [int]
		 * @param $user [user_]
		 */

		public function reviewd($id, $user) {
			$this->db->where('user_id', $user->getId());
			$this->db->where('review_id', $id);
			$check = $this->db->checkRecordExists('reviews');
			if($check) {
				$this->db->where('review_id', $id);
				$this->db->delete('reviews');
				return true;
			}
		}
	}
?>