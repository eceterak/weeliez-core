<?php

	namespace admin\controllers;
	
	/**
	 * @file controller_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class home extends \admin\controller {

		/**
		 * Display admin dashboard.
		 */

		public function index() {
			$this->returnView($this->model->index());
		} 
	}

?>