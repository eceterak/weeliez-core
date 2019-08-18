<?php

	/**
	 * @file attribute_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class attribute_ extends autoLoad {

		/**
		 * Id of the attribute.
		 * @var int
		 */

		public $attribute_id;

		/**
		 * Name of the attribute.
		 * @var string
		 */

		public $attribute_name;

		/**
		 * Type object with all informations about attribute type.
		 * @var object [attribute_type]
		 */

		public $attribute_type_;

		/**
		 * Defines if attribute should have any sub-attribute (RPM's).
		 * @var bool (1/0)
		 */

		public $attribute_sub;

		/**
		 * Defines if attribute should be displayed in advanced search form.
		 * @var bool (1/0)
		 */
		 	
		public $attribute_search;

		/**
		 * If attribute is available in advanced search, method will specify the input type (slider/select or checbox).
		 * @var string
		 */
		 	
		public $attribute_search_method;

		/**
		 * Position on the list.
		 * @var int
		 */
		 	
		public $attribute_priority;

		/**
		 * Additional information about the attribute value.
		 * @var string
		 */

		public $attribute_postscript;

		/**
		 * Description. Optional. Must be empty by default.
		 * @var string
		 */
		 	
		public $attribute_description = '';

		/**
		 * Informations about the unit.
		 * @var object (unit)
		 */
		 	
		public $unit_;

		/**
		 * Apart from using default load method, load data into attribute_type as well.
		 * @param $result [stdClass]
		 */

		public function cast($result) {
			$this->load($result); // Load basic data first.
			$this->attribute_type_ = new attribute_type_($result);
			$this->unit_ = new unit_($result);
		}

		/**
		 * Load and return attribute. Created to avoid duplication inside of methods.
		 * @param $id [int] // id of bike to load.
		 * @return self / bool
		 */

		static public function getById($id) {
			$db = new mysqlib();
			$db->where('attribute_id', $id);
			$result = $db->selectOne('attribute');
			if($result) {
				return new self($result); // Return instance of new bike with loaded data.
			}
			else {
				return false;
			}
		}

		/**
		 * Static function to return all items. Usefull when displaying data in select input.
		 * Display only items that aren't already associated with bike otherwise they can be duplicated.
		 * @param $attribute_type_id [int]
		 * @param $values [array] // Attributes of bike from attribute_type_id.
		 * @return array
		 */

		static public function getAll($attribute_type_id = null, $values = null) {
			$db = new mysqlib();
			$temp = []; // Create empty array.
			if(!is_null($values)) {
				foreach($values as $key => $spec) {
					$db->whereNot('attribute_id', $spec->attribute_->attribute_id); // Ignore attributes already associated with this bike.
				}
			}
			if(!is_null($attribute_type_id)) {
				$db->where('attribute_type_id', $attribute_type_id);
			}
			$db->order('attribute_priority');
			$result = $db->select('attribute', 'attribute_id, attribute_name, attribute_search');
			$arr = []; // Initiate the array.
			if($result) {
				foreach($result->fetch_data() as $attribute) {
					$arr[strtolower($attribute->attribute_name)] = new self($attribute);
				}
			} 			
			return $arr;
		}

		/**
		 * Use provided data to group attributes it's types. Save them into multidimensional array where first level is a attribute type name and second attribute name.
		 * This way it's much easier to display them on front end.
		 * @param $result [array]
		 * @return array
		 */

		public function groupAttributes(dataObject $result = null) {
			if($result) {
				$arr = array();
				foreach($result->fetch_data() as $res) {
					$arr[$res->attribute_type_id][$res->attribute_name] = new self($res); // '\' To use in both admin and front end.
				}
				ksort($arr); // Sort by atrributeType_id.
				$attribute_types = attribute_type_::getAll();
				foreach($attribute_types as $attribute_type) {
					if(in_array($attribute_type->attribute_type_id, array_keys($arr))) {
						$arr[$attribute_type->attribute_type_name] = $arr[$attribute_type->attribute_type_id]; // Replace type id with name.
						unset($arr[$attribute_type->attribute_type_id]); // Unset old key and its data.
					}
				}
				return $arr;
			}
			return false;
		}
	}
?>