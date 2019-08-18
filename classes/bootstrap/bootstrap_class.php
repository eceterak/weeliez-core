<?php

	/**
	 * @file bootstrap_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class bootstrap {

		/**
		 * Request from browser (url in other words).
		 * @var string
		 */

		protected $request;

		/**
		 * Controller. Home by default.
		 * @var string
		 */

		protected $controller = '\\controllers\\home';

		/**
		 * Type of action for front end user. Display (index) by default.
		 * @var string
		 */

		protected $action = 'index';

		/**
		 * Id of item. Null by default.
		 * @var int
		 */

		protected $id = null;

		/**
		 * Get current request and set all neccessary parameters.
		 * @param $request [string] // Current URL.
		 */

		public function __construct($request) {
			$this->request = $request;
			if(isset($this->request['controller']) && $this->request['controller'] !== '') $this->controller = '\\controllers\\'.$this->request['controller'];
			if(isset($this->request['action']) && $this->request['action'] !== '') $this->action = $this->request['action'];
			if(isset($this->request['id']) && $this->request['id'] !== '')	$this->id = $this->request['id'];
		}

		/**
		 * Create new controller.
		 * @return controller
		 */

		public function createController() {
			if(class_exists($this->controller)) {
				$parents = class_parents($this->controller);
				// Only if class is a controller (parent of this class is a controller class).
				if(in_array('controller', $parents)) {
					if(method_exists($this->controller, $this->action)) {
						return new $this->controller($this->action, $this->id);
					}
					else {
						return error404();
					}
				}
			}
			else {
				return error404();
			}
		}
	}

?>