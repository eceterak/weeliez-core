<?php

	/**
	 * @file viewModel_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class viewModel {

		/**
		 * This class does not have any properties yet. To add them use this method. All of the properties added to this object
		 * will be casted to variables and displayed on front-end later on.
		 * @param $key [string] // Name of variable to use in view file.
		 * @param $value [mix]
		 */

		public function add($key, $value) {
			$this->{$key} = $value;
		}

		/**
		 * Create new error property and set error message. Don't initialize this property before any error occured so you can check if 
		 * error exist with isset(). 
		 * @param $content [string]
		 */

		public function error($content) {
			$this->error = $content;
		}

		/**
		 * Sometimes, error is too much.
		 * @param $content [string]
		 */

		public function notice($content) {
			$this->notice = $content;
		}
	}

?>