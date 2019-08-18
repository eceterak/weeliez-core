<?php

	namespace admin\controllers;

	/**
	 * @file reviews_controller.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class review extends \admin\controller {

		/**
		 * Display users registered in the last week.
		 */

		public function recent() {
			$this->returnView($this->model->recent());
		}
	}

?>