<?php

	/**
	 * @file review_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class review_ extends autoLoad {

		/**
		 * Id of the review.
		 * @var int
		 */

		public $review_id;

		/**
		 * Review's title.
		 * @var string
		 */

		public $review_title;

		/**
		 * Review's content.
		 * @var string
		 */

		public $review_content;

		/**
		 * Date and time of the rieview.
		 * @var string
		 */

		public $review_date;

		/**
		 * User object.
		 * @var user_
		 */

		public $user_;

		/**
		 * Bike object. 
		 * @var bike_
		 */

		public $bike_;

		/**
		 * Bike object. 
		 * @var bike_
		 */

		public $brand_;

		/**
		 * Load data into review, bike_ and user_. 
		 * @param $result [stdClass]
		 */

		public function cast(stdClass $result) {
			$this->load($result);
			$this->user_ = new user_($result);
			$this->bike_ = new bike_($result);
			$this->brand_ = new brand_($result);
		}

		/**
		 * Load and return a single review.
		 * @param $id [int]
		 */

		public function getById($id) {
			$this->db->join('user u', 'r.user_id = u.user_id');
			$this->db->join('bike b', 'r.bike_id = b.bike_id');
			$this->db->where('review_id', $id);
			$result = $this->db->selectOne('review r');
			if($result) {
				return new self($result);
			}
			else {
				return false;
			}
		}

		/**
		 * Return id.
		 * @return int
		 */

		public function getId() {
			return $this->review_id;
		}

		/**
		 * Make MySQL timestamp more readable by using dateTime->format().
		 */

		public function formatDate() {
			if(!empty($this->review_date)) {
				$date = new dateTime($this->review_date);
				return $date->format('M jS, Y - H:i');
			}
		}
	}
?>