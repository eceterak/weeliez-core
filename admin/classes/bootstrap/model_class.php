<?php

	namespace admin;
	
	/**
	 * @file model_class.php
	 * @namespace admin
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

		protected $prefix = '';

		/**
		 * Keep an class object here.
		 * @var [object]
		 */

		protected $class_ = '';

		/**
		 * Get a current namespace.
		 * All classes within this namespace are preceded with namespace name. 
		 * @var string
		 */

		protected $namespace = __NAMESPACE__;

		/**
		 * Should items be validated when updated/created.
		 * @var bool
		 */

		protected $validate = true;	

		/**
		 * Initialize all classes straight after creating new model object. Also set prefix for later use.
		 */

		public function __construct() {
			$this->db = new \mysqlib();
			$this->viewModel = new \viewModel();
			$this->prefix = $this->setPrefix();
			//$this->class_ = $this->setClass();
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
			if(class_exists($class) || class_exists($this->namespace.'\\models\\'.$class)) {
				return $class;
			}
		}

		/**
		 * Root classes are usually named same as a controller with additional underscore.
		 * @return [object]
		 */

		protected function setClass() {
			if(isset($this->prefix) && $this->prefix !== '') {
				$class = $this->prefix.'_';
				if(class_exists($class) || class_exists($this->namespace.'\\'.$class)) {
					return new $class;
				}
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
		 * Add new item to database. It's reading all data sent with post (via getPostValues()) so names of form fields must be same as 
		 * names of columns in database. Otherwise mysql will return an error.
		 * Throw \exceptions and catch them in controller.
		 * If path for SEO is needed, check if class of $this->prefix + _ has a method called 'path', then call this method and update database with a result.
		 * To prevent validation and, set $this->validate within a model to false.
		 */

		public function create() {
			$data = getPostValues();
			if($data) {
				if($this->validate === true) {
					$required = requiredField($data->{$this->prefix.'_name'});
					if($required === false) {
						throw new \exception('Fill all inputs.');
					}
					$this->db->where($this->prefix.'_name', $data->{$this->prefix.'_name'});
					$result = $this->db->checkRecordExists($this->prefix);
					if($result) {
						throw new \exception(ucfirst($this->prefix).' with this name already exists.');
					}
				}
				if(property_exists($data, 'redirect')) {
					$redirect = $data->redirect;
					unset($data->redirect);
				}
				foreach($data as $key => $value) {
					if(!empty($value)) {
						$this->db->set($key, $value);
					}
				}
				$result = $this->db->insert($this->prefix);	
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
					if(isset($redirect)) return $redirect;
					else return true;	
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
		 * Update item in database. Run only when request method equals POST.
		 * First, get POST values. Then, check if any data was sent. $data object should contain redirect property which is a adress of the main page.
		 * When updating item, check if path to this item needs updating. Check create() method for explenation.
		 * @param $id [int] // id of item to update.
		 * @return string
		 */

		public function update($id) {
			$data = getPostValues();
			if($data) {
				if($this->validate === true) {
					$class = $this->prefix.'_';
					$object = $class::getById($id);
					$required = requiredField($data->{$this->prefix.'_name'});
					if($required === false) {
							throw new \exception('Fill all inputs.');
					}
					if($data->{$this->prefix.'_name'} !== $object->{$this->prefix.'_name'}) {
						$this->db->where($this->prefix.'_name', $data->{$this->prefix.'_name'});
						$result = $this->db->checkRecordExists($this->prefix);
						if($result) {
							throw new \exception(ucfirst($this->prefix).' with this name already exists.');
						}			
					}
				}
				if(property_exists($data, 'redirect')) {
					$redirect = $data->redirect;
					unset($data->redirect);
				}
				foreach($data as $key => $value) {
					if($value == '') $value = null;
					$this->db->set($key, $value);
				}
				$path = $this->path($id);
				if($path) {
					$this->db->set($this->prefix.'_path', $path);
				}
				$this->db->where($this->prefix.'_id', $id); // Id comes from url not form.
				$result = $this->db->update($this->prefix);
				if($result) {
					if(isset($redirect)) return $redirect;
					else return true;
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
		 * Check if method path exists within a root class and if it does, return a seo friendly path to the item.
		 * @param $id [int]
		 * @return string/bool
		 */

		protected function path($id) {
			if(class_exists($this->prefix.'_')) {
				$class = $this->prefix.'_';
				try {
					$reflection = new \ReflectionClass($class);
					$method = $reflection->getMethod('path'); // Check form method called 'path' - it creates a Seo friendly url for item.
				}
				catch(\ReflectionException $e) {
					return false;
				}
				if(isset($method)) {
					return $class::path($id); // Return friendly url.
				}
			}
			return false;
		}

		/**
		 * Delete an item from database. Return url to redirect in controller.
		 * There items that shouldn't be deleted like root account. To block possibility to delete those items,
		 * add id's to $blocked array.
		 * If deleted item is a part of other object, check if method assign exists to replace deleted object with a new value.
		 * @param $id [int] // id of item to delete.
		 * @param $blocked [array]
		 * @return string
		 */

		public function delete($id) {
			if(isset($this->blocked) && in_array($id, $this->blocked)) {
				throw new \exception("Can't delete this item.");
			}
			$where = $this->prefix.'_id';
			$this->db->where($where, $id);
			$result = $this->db->delete($this->prefix);
			if($result) {
				if(method_exists($this, 'assign')) {
					$this->assign($id);
				}
				return deleteRedirect();
			}
			else {
				throw new \exception("Item can't be deleted right now.");
			}
		}

		/**
		 * Depending on the action, perform a bulk action on multiple items.
		 * @todo finish
		 */

		public function bulk() {
			$data = getPostValues();
			if($data->action) {
				switch($data->action) {
					case 'delete':
						foreach($data->item_id as $item_id) {
							try {
								$this->delete($item_id);
							}
							catch(\exception $e) {

							}
						}
					break;
				}
			}
			else {
				throw new \exception("Action not set.");
			}
			return true;
		}
	}

?>