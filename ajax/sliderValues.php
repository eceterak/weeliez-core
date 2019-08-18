<?php

	/**
	 * Get a minumum and a maximum values of spec attribute to setup a slider.
	 * @param ajax $_POST['slider_attribute'] [int]
	 */

	if(isset($_POST['slider_attribute'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$db = new \mysqlib();
		$response = array('success' => false);
		$attribute = $_POST['slider_attribute'];
		if($attribute == 'bike_year_start') {
			$values = $db->minMax('bike', 'bike_year_start');
		}
		else {
			$db->where('attribute_id', $attribute);
			$values = $db->minMax('spec', 'spec_value');
		}
		if($values) {
			$response = $values;
		}
		echo json_encode($response); // Need to echo it.
	}

?>