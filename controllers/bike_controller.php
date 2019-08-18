<?php

	namespace controllers;

	/**
	 * @file bike_controller.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class bike extends \controller {

		/**
		 * Compare two bikes.
		 */

		public function compare() {
			if($this->id) {
				$this->returnView($this->model->compare($this->id));
			}
		}

		/**
		 * To make url SEO friendly, instead of using display as an action to show bike, use specs.
		 * Thanks to that url will look like eg.: www.weeliez.com/bike/specs/KTM_690_SMC_2008.
		 */

		public function specs() {
			if(!is_null($this->id)) {
				$this->returnView($this->model->specs($this->id));
			} 
			else {
				$this->error404();
			}			
		}

		/**
		 * Display bike reviews.
		 */

		public function reviews() {
			if($this->id) {
				$this->returnView($this->model->reviews($this->id));
			}			
		}
	}

?>