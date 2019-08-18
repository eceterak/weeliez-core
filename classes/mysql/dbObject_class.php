<?php

	/**
	 * @file dbObject_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class dbObject {

		/**
		 * Object/condition/method type.
		 * @var string
		 */

		public $type;

		/**
		 * Key or table name.
		 * @var string
		 */

		public $key;

		/**
		 * Value or condtion.
		 * @var array
		 */

		public $value;

		/**
		 * Sign, join type or order method.
		 * @var string
		 */

		public $sign;

		/**
		 * How different expresions are connected with each other.
		 * @var string
		 */

		public $connector;

		/**
		 * List of allowed types.
		 * @var string
		 */

		public $allowed = array(
			'select', 
			'where', 
			'whereOr', 
			'whereAnd', 
			'whereNot', 
			'between', 
			'like', 
			'join', 
			'set', 
			'values', 
			'limit', 
			'offset', 
			'order', 
			'groupBy', 
			'having'
		);

		/**
		 * Set type but before check if type is allowed.
		 * @param $type [string]
		 */

		public function __construct($type) {
			if(in_array($type, $this->allowed)) {
				$this->type = $type;
			}
		}

		/**
		 * Set most of the params in one method.
		 * @param $key [string]
		 * @param $value [mix]
		 * @param $sign [string] // '='
		 * @param $connector [string] // null
		 */

		public function init($key, $value, $sign = '=', $connector = null) {
			$this->setKey($key);
			$this->setValue($value);
			$this->sign = $sign;
			$this->connector = $connector;
		}

		/**
		 * Return object type. Use it to establish how query should look like.
		 * Query is gonna be different for BETWEEN and WHERE even if they belong to same family. 
		 * @see mysqlib->buildWhere()
		 * @return string
		 */

		public function objectType() {
			return $this->type;
		}		

		/**
		 * Set key.
		 * @param $value [mix]
		 */

		public function setKey($key) {
			//$key = pregRep($key);
			$this->key = $key;
		}

		/**
		 * Use when there is no need to set $key, also make sure that value is in array because mysqlib bindValues() method requires array.
		 * @see mysqlib->limit().
		 * @param $value [mix]
		 */

		public function setValue($value) {
			if(is_array($value)) {
				$this->value = $value;
			}
			else {
				$this->value = array($value);
			}
		}

		/**
		 * Use when there is no need to set $key.
		 * @see $mysqlib->order.
		 * @param $sign [string]
		 */

		public function setSign($sign) {
			$this->sign = $sign;
		}
	}

?>