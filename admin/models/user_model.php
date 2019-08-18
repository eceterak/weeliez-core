<?php

	namespace admin\models;

	/**
	 * @file userModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class user extends \admin\model {

		/**
		 * Block these id's from deleting.
		 * @var array
		 */

		protected $blocked = [1]; // Block deleting root account.

		/**
		 * Display login/create user form.
		 */

		public function form() {}

		/**
		 * Display all users.
		 * @viewModel $users [array] // Array of all users.
		 */

		public function index() {
			$this->db->join('access a', 'a.access_id = u.access_id');
			$this->db->sort(['level' => 'a.access_level', 'name' => 'u.user_name', 'email' => 'u.user_email'], 'DESC');
			$users = $this->db->select('user u', 'u.user_id, u.user_name, u.user_email, u.access_id, a.access_id, a.access_name, a.access_level'); 
			if($users) {
				$users->create_objects('user_');
				$this->viewModel->add('users', $users);
			}
			else {
				$this->viewModel->error('No users to display'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * Edit user.
		 */

		public function edit($id) {
			$this->db->where('user_id', $id);
			$result = $this->db->selectOne('user'); 
			if($result) {
				$user = new \user_($result);
				$this->viewModel->add('user', $user); // Add array of bikes to viewModel.
			}
			else {
				$this->viewModel->error('User not found to display'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * Display users registered in the last week.
		 */

		public function recent() {
			$this->db->join('access a', 'a.access_id = u.access_id');
			$this->db->between('u.user_created', date('Y-m-d H:i:s', time() - (60 * 60 *24 * 7)), date('Y-m-d H:i:s'));
			$users = $this->db->select('user u');
			if($users) {
				$users->create_objects('user_');
				$this->viewModel->add('users', $users);
			}
			else {
				$this->viewModel->error('No new users last week.'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * Try to login by name or email. If account is not blocked (brute force), check password. 
		 * If provided password is correct login user by setting session details.
		 * Get information about user's browser and connect it with password. This will prevent stealing session and login from different pc.
		 * If provided password is incorrect record failed login attempt.
		 * @return bool
		 */

		public function login() {
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$data = getPostValues();
				if($data) {
					$user = \user_::getByDetails($data->user_name, true);
					if($user !== false) {
						$authentication = new \authentication();
						$brute = $authentication->bruteForceProtection($user->user_id);
						if($brute) {
							if(password_verify($data->user_password, $user->user_password)) {
								$userBrowser = $_SERVER['HTTP_USER_AGENT']; // Get information about browser.
								$session = new \session('user');
								$session->start();
								$session->add('userId', $user->user_id);
								$session->add('userName', $user->user_name);
								$session->add('userSap', hash('sha512', $user->user_password.$userBrowser));
								return true;
							}
							else {
								$record = $authentication->loginAttemptFailed($user->user_id); // Someone tried to log in and failed. Record this attempt.
								throw new \exception('Wrong password.');
							}
						}
						else {
							throw new \exception('Account blocked due to safety reasons.');
						}
					}
					throw new \exception('Account not found.');
				}
			}
			else {
				exit(header('Location: '.ADMIN_URL.'/user/form')); // Extra protection from access from url not POST.		
			}
		}

		/**
		 * Validate user name, password and email. Also check if name and email are not already taken.
		 * All of those methods throws \exceptions. Catch those in the controller.
		 */

		public function create() {
			$data = getPostValues();
			if($data) {
				$user = new \user_();
				$userName = $user->checkName($data->user_name);
				$userPassword = $user->checkPassword($data->user_password);
				$userEmail = $user->checkEmail($data->user_email);
				$this->db->set('user_name', $userName);
				$this->db->set('user_password', $userPassword);
				$this->db->set('user_email', $userEmail);
				$token = $user->generateToken();
				$this->db->set('user_verification', $token);
				$this->db->set('user_verified', 1); // Adding user on back end - can auto verify.
				$this->db->set('access_id', $data->access_id);
				$result = $this->db->insert('user');
				if($result) {
					$id = $this->db->max($this->prefix, $this->prefix.'_id'); // Get id of inserted item.
					$path = $this->path($id);
					if($path) {
						$this->db->set($this->prefix.'_path', $path);
						$this->db->where($this->prefix.'_id', $id);
						$update = $this->db->update($this->prefix);
						if(!$update) {
							throw new \exception('Error in database query.');
						}
					}
					return true;
				}
				else {
					throw new \exception('Database error. Try again later.');
				}
			}
		}

		/**
		 * Verify user email.
		 */

		public function verify($id) {
			$this->db->where('user_verification', $id);
			$user = $this->db->checkRecordExists('user', 'user_id');
			if($user) {
				$this->db->where('user_verification', $id);
				$this->db->set('user_verification', 1);
				$result = $this->db->update('user');
				if($result) {
					return true;
				}
			}
		}
	
		/**
		 * Update item in database. Run only when request method equals POST.
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
				$user = \user_::getById($id);
				$userName = $user->checkName($data->user_name);
				$userEmail = $user->checkEmail($data->user_email);
				$path = $this->path($id);
				if($path) {
					$this->db->set($this->prefix.'_path', $path);
				}
				$this->db->set('user_name', $userName);
				$this->db->set('user_email', $userEmail);
				$this->db->set('access_id', $data->access_id);
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
	}

?>