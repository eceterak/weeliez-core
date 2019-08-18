<?php

	/**
	 * A simple function to update priority of a attributes.
	 * It uses a ajax data. Update every single attribute priority of sent id with a new values.
	 * @param ajax $_POST['attribute_id'] [int]
	 */
	
	if(isset($_POST['add_attribute_id'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$attribute_id = $_POST['add_attribute_id'];
		$response = ['success' => false]; // False by default.
		$db = new mysqlib();
		$db->where('attribute_id', $attribute_id);
		$result = $db->selectOne('attribute');
		if($result) {
			$response = new attribute_($result);
		}
		echo json_encode($response);
	}
?>