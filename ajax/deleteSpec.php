<?php

	/**
	 * Delete spec. Ajax is very helpfull here because it prevents reloading a whole page when deleting one spec.
	 * @param ajax $_POST['spec_delete_id'] [int]
	 * @param ajax $_POST['ajax'] [bool]
	 */
	
	if(isset($_POST['spec_delete_id']) && isset($_POST['ajax'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$id = $_POST['spec_delete_id'];
		$response = ['success' => false]; // False by default.
		$db = new mysqlib();
		$db->where('spec_id', $id);
		$result = $db->delete('spec');
		if($result) {
			$response['success'] = true;
		}
		echo json_encode($response);
	}
?>