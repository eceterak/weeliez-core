<?php

	namespace admin;

	/**
	 * @file controller_class.php
	 * @namespace admin
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	abstract class controller {

		/**
		 * Id of item.
		 * @var int
		 */

		protected $id;

		/**
		 * Type of action for front end user.
		 * @var string
		 */

		protected $action;

		/**
		 * Model associated with this controller.
		 * @var object [model]
		 */

		public $model;

		/**
		 * Prefix = controller name.
		 * Use prefix to redirect after adding/deleting etc..
		 * @var string
		 */

		protected $prefix;

		/**
		 * Keep an session object here.
		 * @var object [session]
		 */

		protected $session;

		/**
		 * User object.
		 * @var object [user]
		 */

		protected $user_ = null;

		/**
		 * User access level to use the controller.
		 * It secures ALL controller methods from access by users with low permission levels.
		 * 1 all users allowed (default).
		 * @var int
		 */

		protected $accessLevel = 1;	

		/**
		 * Set action and id parameters. Also setup the model, prefix, session and check if user logged and authorized to use the controller. 
		 * @param $action [string]
		 * @param $id [int] // null
		 */

		public function __construct($action, $id = null) {
			$this->action = $action;
			$this->id = $id;
			$this->prefix = $this->setPrefix();
			$this->model = $this->createModel();
			$this->session = new \session();
			$this->user_ = $this->authentication();
		}

		/**
		 * Execute action from url. No need to check if action exists because this check is already run in bootstrap.
		 * @return run controller method.
		 */

		public function executeAction() {
			return $this->{$this->action}();
		}

		/**
		 * Create prefix. Prefix is alway same as controller name.
		 * Use reflection class to obtain a short name without namespace.
		 * Use prefix to redirect after creating new object, deleting, updating etc.
		 * @return string
		 */

		protected function setPrefix() {
			$reflect = new \ReflectionClass($this);
			return $reflect->getShortName();
		}

		/**
		 * Create new model. Model has the same name as controller but lies in admin\models namespace.
		 * @return [model Object]
		 */

		public function createModel() {
			$model = "\\admin\\models\\".$this->prefix;
			if(class_exists($model)) {
				return new $model();
			}
			else {
				error404(); // Model not found.
			}
		}

		/**
		 * Check if user is logged and has adequate access level to use the controller (including methods that don't return a view).
		 * Authentication was moved here from a main.php file because of access level support provided by controllers.
		 * To avoid a infinite loop, stop redirecting to user/form once prefix = 'user' and action = 'form'.
		 * Don't check for authentication if user is trying to log in. Otherwise, it will be imposible to log in.
		 */

		public function authentication() {
			if($this->prefix == 'user' && $this->action == 'login') {
				return;
			}
			else {
				$auth = new \authentication();
				$user = $auth->isLogged(false, true);
				if($user === false) {
					if($this->prefix == 'user' && $this->action == 'form') {
						return;
					}
					else {
						exit(header('Location: '.ADMIN_URL.'/user/form'));
					}
				}
				else {
					if($user->access_->access_level < 2 || $this->accessLevel > $user->access_->access_level) {
						forbidden();
					}
					elseif((int)$user->user_verified !== 1) {
						if($this->prefix == 'user' && $this->action == 'verify') {
							return;
						}
						else {
							verification();
						}
					}
					else {
						return $user;
					}
				}		
			}
		}

		/**
		 * Use this one inside a specific controller. It will provide data to display (via $viewModel and models) and load
		 * a front-end html file. It will also transform viewModel proporties into independent variables so there is no need
		 * to refer to viewModel when displaying data in view file.
		 * Also, add a class to a html <body> tag to help to handle javascript including.
		 * If main.php is not necessary, set $fullView to false and load ONLY $view file.
		 * Reflection class is helping to obtain class name without namespace name.
		 * Set the <title> with a $title (site name by default, check config).
		 * @param $viewModel [viewModel object] // Object with data to display.
		 * @param $fullView [bool]
		 * @param $title [string] // NAME
		 */

		public function returnView(\viewModel $viewModel = null, $fullView = true, $title = NAME) {
			$view = 'views/'.$this->prefix.'/'.$this->action.'.php';
			if(!is_null($viewModel)) {
				$user_ = $this->user_;
				foreach($viewModel as $key => $value) {
					$$key = $value; // It will create new variable with name of $key and value of $value. Notice the $$ sign.
				}
			}
			if($fullView) {
				//$controllerName = $this->getName(); Just the idea for breadcrumbs controller name.
				require('views/main.php');
			}
			else {
				require($view);
			}
		}

		/**
		 * Default action. It will display all items. Unset any messages after displaying them.
		 */

		public function index() {
			$this->returnView($this->model->index($this->id));
		}

		/**
		 * Display new item form. Pass id if adding item to a specific parent.
		 */

		public function add() {
			$this->returnView($this->model->add($this->id));
		}

		/**
		 * Display edit form. Do it only if id is not null. Otherwise show error 404.
		 */

		public function edit() {
			if(isset($this->id)) {
				$this->returnView($this->model->edit($this->id));
			} 
			else {
				error404();
			}
		}

		/**
		 * Add new item to a database.
		 * model->create() can return true or redirect link. If returned value is true, redirect to main item page.
		 */

		public function create() {
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				try {
					$result = $this->model->create();
					if($result) {
						$this->session->add('message', 'Item has been added!');
						if($result === true) {
							$result = ADMIN_URL.'/'.$this->prefix; // $result === true - redirect to the items main page, otherwise proceed to the item edit page. 
						}
						exit(header('Location: '.$result));
					}
				} catch(\exception $e) {
					$this->session->add('error', $e->getMessage());
				}
				exit(header('Location: '.$_SERVER['HTTP_REFERER']));
			}
			else {
				exit(header('Location: '.ADMIN_URL.'/'.$this->prefix)); // Wrong request method.
			}
		}

		/**
		 * Model's update method will return true or a redirect value when update is successfull and false if not.
		 * If returned value is not a true or false, it must be an url so redirect to it. If value is true, just return to main page.
		 */

		public function update() {
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				if($this->id) {
					try {
						$result = $this->model->update($this->id);
						if($result) {
							$this->session->add('message', ucfirst($this->prefix).' updated.');
							if($result === true) {
								$result = ADMIN_URL.'/'.$this->prefix; // Return to the items main page.
							}
							exit(header('Location: '.$result));
						}
					} catch(\exception $e) {
						$this->session->add('message', $e->getMessage());
					}
					exit(header('Location: '.$_SERVER['HTTP_REFERER']));
				}
				else {
					error404(); // Id is not set.
				}
			}
			else {
				exit(header('Location: '.ADMIN_URL.'/'.$this->prefix)); // Wrong request method.
			}
		}

		/**
		 * Delete item from database. This method does not need to return anything on front-end so using returnView() is unnecessary.
		 */

		public function delete() {
			if($_SERVER['REQUEST_METHOD'] == 'GET') {
				if($this->id) {
					try {
						$result = $this->model->delete($this->id);
						if($result) {
							$this->session->add('message', 'Item deleted.');
							exit(header('Location: '.$result)); // Model should return deleteRedirect link.
						}
					} catch(\exception $e) {
						$this->session->add('error', $e->getMessage());
					}
					exit(header('Location: '.$_SERVER['HTTP_REFERER']));
				}	
				else {
					error404();
				}
			}
			else {
				exit(header('Location: '.ADMIN_URL.'/'.$this->prefix)); // Wrong request method.
			}
		}

		/**
		 * Perform a bulk action on multiple items.
		 */

		public function bulk() {
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				try {
					$result = $this->model->bulk();
					if($result) {
						$this->session->add('message', $result);
					}
				} catch(\exception $e) {
					$this->session->add('error', $e->getMessage());
				}
				exit(header('Location: '.$_SERVER['HTTP_REFERER']));
			}
			else {
				exit(header('Location: '.ADMIN_URL.'/'.$this->prefix)); // Wrong request method.
			}
		}
	}

?>