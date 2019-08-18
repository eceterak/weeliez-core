<?php

	/**
	 * @file image_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class image_ extends autoLoad {

		/**
		 * Image id.
		 * @var int
		 */

		public $image_id;

		/**
		 * Type of item.
		 * @var string
		 */

		public $image_item = 'bike'; // Bike by default.

		/**
		 * Id of the item.
		 * @var int
		 */

		public $item_id;

		/**
		 * Image url and actual name.
		 * @var string
		 */

		public $image_url;

		/**
		 * Original file name.
		 * @var string
		 */

		public $image_file_name;

		/**
		 * Number of image.
		 * @var int
		 */

		public $image_number;

		/**
		 * Image default flag.
		 * @var int
		 */

		public $image_default;

		/**
		 * If image does not exists, load no 'image not found' to avoid errors.
		 * @return image_
		 */

		static public function noImageFound() {
			$image = new self();
			$image->image_url = 'No-image-found.jpg';
			return $image;
		}
	}

?>