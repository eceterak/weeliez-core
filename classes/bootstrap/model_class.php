<?php

	/**
	 * @file model_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	abstract class model {

		/**
		 * Keep database object here. Don't just extend mysqlib because some of it's methods names will collide with 
		 * model/controller methods names. 
		 * @var mysqlib
		 */

		public $db;

		/**
		 * Store all data to display on front-end here. 
		 * @var viewModel
		 */

		public $viewModel;

		/**
		 * Prefix of the model. In other words class name without 'Model'.
		 * Use prefix in pre-created methods like delete, add etc.
		 * @var string
		 */

		protected $prefix;

		/**
		 * Initialize all classes straight after creating new model object. Also set prefix for later use.
		 */

		public function __construct() {
			$this->db = new mysqlib();
			$this->viewModel = new viewModel();
			$this->prefix = $this->setPrefix();
		}

		/**
		 * Create prefix. Prefix is alway same as controller name so I can use class_exists to check if prefix is correct.
		 * Reflection class is helping to obtain class name without namespace name.
		 * Reflection class throws an exceptions so it's important to catch them.
		 * Can't use standard get_class because result would be like admin\class_name.
		 * @return string
		 */

		protected function setPrefix() {
			try {
				$reflect = new \ReflectionClass($this);
				$class = $reflect->getShortName();	
			}
			catch(\ReflectionException $e) {
				return ''; // Something went wrong
			}
			if(class_exists($class) || class_exists('\\models\\'.$class)) {
				return $class;
			}
		}

		/**
		 * This model don't do much because all of the back-end work is done by create() method.
		 * However, it returns an viewModel object with $parent parameter so it will be used to check proper select option in add form.
		 * @param $parent [int] // null
		 * @return object [viewModel]
		 */

		public function add($parent = null) {
			$this->viewModel->add('parent', $parent);
			return $this->viewModel;
		}

		/**
		 * Delete an item from database. Return url to redirect in controller.
		 * @param $id [int] // id of item to delete.
		 * @return string
		 */

		public function delete($id) {
			$where = $this->prefix.'_id';
			$this->db->where($where, $id);
			$result = $this->db->delete($this->prefix);
			if($result) {
				return deleteRedirect();
			} 
			else {
				throw new exception("Item can't be deleted right now.");
			}
		}

		/**
		 * Check if method path exists within a root class and if it does, return a seo friendly path to the item.
		 * @param $id [int]
		 * @return string/bool
		 */

		protected function path($id) {
			if(class_exists($this->prefix.'_')) {
				$class = $this->prefix.'_';
				try {
					$reflection = new ReflectionClass($class);
					$method = $reflection->getMethod('path'); // Check form method called 'path' - it creates a Seo friendly url for item.
				}
				catch(ReflectionException $e) {
					return false;
				}
				if(isset($method)) {
					return $class::path($id); // Return friendly url.
				}
			}
			return false;
		}
	}

?>