<?php

	namespace admin;

	/**
	 * @file bootstrap_class.php
	 * @namespace admin
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 * This is a back-end class, different from a front-end (global) one. 
	 * It's operating in a back-end namespace so it can share class name with a global class.
	 */

	class bootstrap {

		/**
		 * Request from browser (url in other words).
		 * @var string
		 */

		protected $request;

		/**
		 * Get a current namespace.
		 * All classes within this namespace are preceded with namespace name. 
		 * It's very important to remember about it when creating new classes or setting up controllers.
		 * Without namespace before controller/class global classes are invoked.
		 * @var string
		 */

		protected $namespace = __NAMESPACE__;

		/**
		 * Controller. Home by default but setting up in construct.
		 * @var string
		 */

		protected $controller;

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
		 * This object is not operating in a global namespace so to get a right controller name, add current namespace before controller name. 
		 * \\ acts like one \.
		 * @param $request [string] // Current URL.
		 */

		public function __construct($request) {
			$this->request = $request;
			if($this->request['controller'] !== '' && $this->request['controller'] !== '') $this->controller = $this->namespace.'\\controllers\\'.$this->request['controller'];
			else $this->controller = $this->namespace.'\\controllers\\home';
			if($this->request['action'] !== '' && $this->request['controller'] !== '') $this->action = $this->request['action'];
			if($this->request['id'] !== '' && $this->request['controller'] !== '')	$this->id = $this->request['id'];
		}

		/**
		 * Create new controller.
		 * Use namespace\controller instead of plain controller as not operating in global namespace.
		 * @return controller
		 */

		public function createController() {
			if(class_exists($this->controller)) {
				$parents = class_parents($this->controller);
				// Only if class is a controller (parent of this class is a controller class).
				if(in_array($this->namespace.'\controller', $parents)) {
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