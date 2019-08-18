<?php

	/**
	 * @file controller_class.php
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
		 * Set action and id parameters. Also setup the model.
		 * @param $action [string]
		 * @param $id [int] // null
		 */

		public function __construct($action, $id = null) {
			$this->action = $action;
			$this->id = $id;
			$this->prefix = $this->setPrefix();
			$this->model = $this->createModel();
			$this->session = new session();
			$auth = new authentication();
			$this->user_ = $auth->isLogged();
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
		 * Create new model. Model should always have name like "controller name + 'Model'". Also check if model even exists.
		 * @return [model Object]
		 */

		public function createModel() {
			$model = "\\models\\".$this->prefix;
			if(class_exists($model)) {
				return new $model;
			}
			else {
				//error404(); // Model not found.
			}
		}

		/**
		 * Execute action from url. No need to check if action exists because this check is already run in bootstrap.
		 * @return run controller method.
		 */

		public function executeAction() {
			return $this->{$this->action}();
		}

		/**
		 * Use this one inside a specific controller. It will provide data to display (via $viewModel and models) and load
		 * a front-end html file. It will also transform viewModel proporties into independent variables so there is no need
		 * to refer to viewModel when displaying data in view file.
		 * Also, add a class to a html <body> tag to help to handle javascript including.
		 * Add user variable to be able to display user menu.
		 * If main.php is not necessary, set $fullView to false and load ONLY $view file. 
		 * @param $viewModel [viewModel object] // Object with data to display.
		 * @param $fullView [bool]
		 * @param $class [string] // 'default'
		 */

		public function returnView(\viewModel $viewModel = null, $fullView = true, $class = 'default') {
			$view = 'views/'.$this->prefix.'/'.$this->action.'.php';
			if(!is_null($viewModel)) {
				$user_ = $this->user_; // Add user variable.
				foreach($viewModel as $key => $value) {
					$$key = $value; // It will create new variable with name of $key and value of $value. Notice the $$ sign.
				}
			}
			if($fullView) {
				require('views/main.php');
			}
			else {
				require($view);
			}
		}

		/**
		 * Display error 404 (not found) page.
		 */

		public function error404() {
			require('views/404.php');
		}

		/**
		 * Default action. It will display all items. Try/unset any messages.
		 */

		public function index() {
			$this->returnView($this->model->index($this->id));
			$this->session->sUnset('message');
		}

		/**
		 * Display single item. Do it only if id is not null. Otherwise show error 404.
		 */

		public function display() {
			try {
				if(!is_null($this->id)) {
					$this->returnView($this->model->display($this->id));
				} else {
					$this->error404();
				}
			} catch(Exception $e) {
				$this->session->add('error', $e->getMessage());
				exit(header('Location: '.$_SERVER['HTTP_REFERER']));
			}
			$this->session->sUnset('error');
		}
	}

?>