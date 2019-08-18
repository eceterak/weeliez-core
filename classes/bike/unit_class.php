<?php

	/**
	 * @file unit_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class unit_ extends autoLoad {

		/**
		 * Id of the unit.
		 * @var int
		 */

		public $unit_id;

		/**
		 * Name of the unit.
		 * @var string
		 */

		public $unit_name;

		/**
		 * Load and return object. Created to avoid duplication inside of methods, compare data when updating etc.
		 * @param $id [int] // id of object to load.
		 * @return self / bool
		 */

		static public function getById($id) {
			$db = new mysqlib();
			$db->where('unit_ID', $id);
			$result = $db->selectOne('unit');
			if($result) {
				return new self($result); // Return instance of new bike with loaded data.
			}
			else {
				return false;
			}
		}

		/**
		 * Static function to return all items. Usefull when displaying data in select input.
		 * @return array
		 */

		static public function getAll() {
			$db = new mysqlib();
			$result = $db->select('unit', 'unit_id, unit_name');
			$arr = []; // Initiate the array.
			if($result) {
				foreach($result->fetch_data() as $attribute) {
					$arr[] = new self($attribute);
				}
			} 			
			return $arr;
		}

		/**
		 * Convert units.
		 * @param $unit [string]
		 * @param $value [mix]
		 */

		static public function convert($unit, $value) {
			$unit = strtolower($unit);
			switch($unit) {
				case('mm'):
					$value = str_replace(str_split('.,'), '', $value);
					$inches = '';
					$values = explode(' ', $value);
					if(count($values) > 1) {
						foreach($values as $value) {
							if(is_numeric($value)) {
								$inches .= bcdiv($value * 0.0394, 1, 1);
							}
							else {
								$inches .= ' '.$value.' ';
							}
						}
					}
					else {
						$inches = bcdiv($value * 0.0394, 1, 1);
					}
					return '('.$inches.' inches)';
				break;
				case('ccm'):
					$cubic = bcdiv($value * 0.061024, 1, 2);
					return '('.$cubic.' cubic inches)';
				break;
				case('kg'):
					$pounds = bcdiv($value * 2.205, 1, 1);
					return '('.$pounds.' pounds)';
				break;
				case('litres'):
					$gallons = bcdiv($value * 0.2642, 1, 2);
					return '('.$gallons.' gallons)';
				break;
				case('l/100km'):
					$mpg = bcdiv(235.21 / $value, 1, 2);
					return '('.$mpg.' mpg)';
				break;
				default:
					return null;
				break;
			}
		}

	}
?>