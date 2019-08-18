<?php

	/**
	 * @file autoLoad_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	abstract class autoLoad {

		/**
		 * Prefix of columns in db table.
		 * @var string
		 */

		protected $prefix = null;

		/**
		 * Item class is a parent for few classes so get class name to be able to use it later in other methods.
		 * If cast method exist use it instead of load.
		 * @param $result [stdClass] // Load straight after creating new object to save a line of code.
		 */

		public function __construct($result = null) {
			$this->prefix = $this->setPrefix(); // Set prefix first.
			// Check if cast method exist.
			if(method_exists($this, 'cast')) $this->cast($result); // Cast exist so use it.
			else $this->load($result); // Cast does not exist so use load instead.
		}

		/**
		 * Create prefix. Prefix is alway same as class name minus '_'.
		 * @return string
		 */

		protected function setPrefix() {
			$class = get_class($this);
			return $prefix = preg_replace('/_/', '', $class); // Replace Model in class name (every model contain it) with ''.
		}

		/**
		 * Return prefix.
		 * @return string
		 */

		public function getPrefix() {
			return $this->prefix;
		}

		/**
		 * Load data into object from db. It's a quick way to fill up object with data. Before using this, make sure that all properties 
		 * of object are already setup because it will match properties names with data accured from database. 
		 * @param $res [object] stdClass 
		 */

		public function load(stdClass $res = null) {
			if(!is_null($res)) {
				// Loop through object like array.
				foreach($res as $key => $value) {
					if(property_exists($this, $key)) {
						if(!is_null($value)) {
							$this->{$key} = $value;
						}
					}
				}
			}
		}
	}
?>