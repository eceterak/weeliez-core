<?php

	/**
	 * @see image_model.php
	 * Two functions to delete or set featured image.
	 * Set message only if something went wrong.
	 * @param ajax $_POST['manage_image_id'] [int]
	 * @param ajax $_POST['image_action'] [string]
	 */

	if(isset($_POST['manage_image_id']) && isset($_POST['image_action'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$image_id = $_POST['manage_image_id'];
		$action = $_POST['image_action'];
		$response = array('status' => false);
		$db = new \mysqlib();
		$db->where('image_id', $image_id);
		$result = $db->selectOne('image');
		if($result) {
			switch($action) {
				case 'delete':
					$image = new \image_($result);
					$path = '../upload/images/'.$image->image_url;
					$db->where('image_id', $image_id);
					$result = $db->delete('image');
					if($result) {
						if(file_exists($path)) {
							$unlink = unlink($path);
							if($unlink) {
								$response['status'] = true;
							}								
							else {
								$response['message'] = 'File cannot be deleted.';		
							}
						}
						else {
							$response['message'] = 'Image not found.';
						}
					}
					else {
						$response['message'] = 'Database error';
					}
				break;
				case 'default':
					$image = new \image_($result);
					$db->set('image_default', 0);
					$db->where('item_id', $image->item_id);
					$db->where('image_item', $image->image_item);
					$result = $db->update('image');
					if($result) {
						$db->set('image_default', 1);
						$db->where('image_id', $image_id);
						$result = $db->update('image');
						if($result) {
							$response['status'] = true;
						}
						else {
							$response['message'] = 'Database error1';
						}
					}
					else {
						$response['message'] = 'Database error2';
					}
				break;
			}
		}
		else {
			$response['message'] = 'Image does not exists in a database.';
		}
		echo json_encode($response);
	}

?>