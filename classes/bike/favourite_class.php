<?php

	/**
	 * @file favourite_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class favourite_ extends autoLoad {

		/**
		 * Favourite id.
		 * @var int
		 */

		public $favourite_id;

		/**
		 * User object.
		 * @var object [user]
		 */

		public $user_;

		/**
		 * Bike object.
		 * @var object [bike]
		 */

		public $bike_;

		/**
		 * Load basic bike data and data of all sub items.
		 * @param $result [stdClass]
		 */

		public function cast(stdClass $result) {
			$this->load($result); // Load basic data first, to get bike id.
			$this->bike_ = new bike_($result);
			$this->user_ = new user_($result);
		}

	}
?>