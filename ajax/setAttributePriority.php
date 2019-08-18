<?php

	/**
	 * A simple function to update priority of a attributes.
	 * It uses a ajax data. Update every single attribute priority of sent id with a new values.
	 * Save all operations to array. If any of them failed return false ($response).
	 * @param ajax $_POST['item'] [string]
	 */

	if(isset($_POST['item'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$attributes = $_POST['item'];
		$db = new mysqlib();
		$response = array('success' => false);
		$i = 1;
		$arr = array();
		foreach($attributes as $attribute_id) {
			$db->where('attribute_id', $attribute_id);
			$db->set('attribute_priority', $i);
			$arr[] = $db->update('attribute');
			$i++;
		}
		if(!in_array(false, $arr)) {
			$response['success'] = true;
		}
		echo json_encode($response);
	}

?>