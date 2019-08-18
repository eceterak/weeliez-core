<?php

	/**
	 * @file spec_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class spec extends autoLoad {

		/**
		 * Id of the spec.
		 * @var int
		 */

		public $spec_id;

		/**
		 * Spec's value.
		 * @var mix
		 */

		public $spec_value;

		/**
		 * Sub attribute (RPM) value. In most cases it will be 0.
		 * @var int
		 */

		public $spec_sub = 0;

		/**
		 * Id of the bike.
		 * @var int
		 */

		public $bike_id;

		/**
		 * Attribute object.
		 * @var attribute_ 
		 */

		public $attribute_;

		/**
		 * Load spec class data and attribute and unit objects.
		 * @var $result [stdClass]
		 */

		public function cast(stdClass $result) {
			$this->load($result);
			$this->attribute_ = new attribute_($result);
		}

		/**
		 * Get all specs related to a bike.
		 * @param $id [int] // id of object to load.
		 * @return self / bool
		 */

		static public function getById($id) {
			$db = new mysqlib();
			$db->where('category_id', $id);
			$result = $db->selectOne('type');
			if($result) {
				return new self($result); // Return instance of new bike with loaded data.
			}
			else {
				return false;
			}
		}

		/**
		 * Load all values of the attribute. Group them by value to avoid duplication.
		 * @param $id [int] // id of object to load.
		 * @return array
		 */

		static public function getValues($id) {
			$db = new mysqlib();
			$db->groupBy('spec_value');
			$db->where('attribute_id', $id);
			$result = $db->select('spec', 'spec_value, attribute_id');
			if($result) {
				foreach($result->fetch_data() as $spec) {
					$specs[] = new self($spec);
				}
				return $specs;
			}
			else {
				return false;
			}
		}

		/**
		 * Return a detailed spec including unit with it's conversion to make code within view look clearer.
		 * @return string
		 */

		public function getDetails() {
			$details = $this->spec_value;
			if(!is_null($this->attribute_->unit_->unit_name)) $details .= ' '.$this->attribute_->unit_->unit_name.' <small>'.unit_::convert($this->attribute_->unit_->unit_name, $this->spec_value).'</small>';
			return $details;
		}
	}

?>