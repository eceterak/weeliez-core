<?php

	/**
	 * @file images_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class images_ {

		/**
		 * Reference to a default image in a $images array.
		 * @var object [image_]
		 */

		public $default = null;

		/**
		 * Array of image_ objects. 
		 * @var array
		 */

		public $images = array();

		/**
		 * Transform data provided from database into image_ objects and populate $images array with them.
		 * Set $default image if any found or noImageFound when there is no default image.
		 * Default image will always be the first image in the images array.
		 * @param $result [array]
		 */

		public function load($result) {
			if($result) {
				foreach($result->fetch_data() as $image) {
					$this->images[] = new \image_($image);
					if($image->image_default == 1) {
						$this->default = end($this->images); // Default var is only a reference. It's last item added to $this->images.
						// Default image is not first in array (because there is already more than one image in array).
						if(count($this->images) > 1) {
							$temp = $this->images[0];
							$this->images[0] = end($this->images);
							$this->images[count($this->images) - 1] = $temp;
						} 
					}
				}
				if(is_null($this->default)) {
					if(count($this->images) > 1) $this->default =& $this->images[0]; // Default image will be a first image from the images array.
					else $this->default = image_::noImageFound(); // No images found.
				}
			}
		}

		/**
		 * Load object's default image.
		 * @param $id [int]
		 * @param $item [string]
		 */

		public function loadDefault($id, $item) {
			$db = new mysqlib();
			$db->where('item_id', $id);
			$db->where('image_item', $item);
			$db->where('image_default', 1);
			$result = $db->selectOne('image');
			if($result) {
				$this->default = new \image_($result);
			}
			else $this->default = image_::noImageFound();
		}

		/**
		 * Check if default image is loaded and return it's url.
		 * @param $id [int]
		 * @param $item [string]
		 * @return string
		 */

		public function defaultImage($id, $item) {
			if(is_null($this->default)) {
				$this->loadDefault($id, $item);
			}
			return $this->default->image_url;
		}
	}

?>