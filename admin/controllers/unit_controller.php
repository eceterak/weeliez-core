<?php

	namespace admin\controllers;

	/**
	 * @file unit_controller.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class unit extends \admin\controller {

		/**
		 * Higher access level so only certain users can use this controller.
		 * @var int
		 */

		protected $accessLevel = 2;
	}

?>