<?php

	/**
	 * @file autoLoad_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 * Item class extends autoLoad. It provides support for displaying images while preserving all autoLoad capabilities.
	 */

	abstract class item extends autoLoad {

		/**
		 * Image object. It must be an object and not array to use image methods like load default etc.
		 * @see setImages()
		 * @var object [images_]
		 */

		public $images_;

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
			$this->images_ = new images_();
		}

		/**
		 * Return objects id.
		 * @return int
		 */

		abstract public function getId();

		/**
		 * Load images into images_ object.
		 * @param $result [array]
		 */

		public function loadImages($result) {
			if($result !== false) {
				$this->images_->load($result);
			}
		}

		/**
		 * Get a default image and return it's url. If there is no default image set, it will return path to the "no image found".
		 * Pass objects id and prefix to load proper image.
		 * @return string
		 */

		public function defaultImage() {
			return $this->images_->defaultImage($this->getId(), $this->getPrefix());
		}
	}
?>