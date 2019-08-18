<?php

	/**
	 * Change category on the go. 
	 */
	
	if(isset($_POST['qe_category_id']) && isset($_POST['qe_bike_id'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$category_id = $_POST['qe_category_id'];
		$bike_id = $_POST['qe_bike_id'];
		$response = ['success' => false]; // False by default.
		$db = new mysqlib();
		$db->where('bike_id', $bike_id);
		$db->set('category_id', $category_id);
		$result = $db->update('bike');
		if($result) {
			
		}
		echo json_encode($response);
	}
?>