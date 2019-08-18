<?php

	/**
	 * This script works in cooperation with Trumbowyg editor. 
	 * Upload file using ajax request. Only one image at the time.
	 * JSON response needs to contain fields: success [true/false] and file (url to the file).
	 * Upload all files to the 'article' folder.
	 * @param ajax $_POST['ajaxImage'] [file]
	 */

	if(isset($_FILES['ajaxImage'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$response = ['success' => false];
		if($_FILES['ajaxImage']['error'] == UPLOAD_ERR_OK) {
			$upload = new \upload('ajaxImage', 'image', 'article');
			$db = new \mysqlib();
			$upload->prepare();
			$validate = $upload->validate();
			if($validate == true) {
				$up = $upload->upload();
				if($up) {
					$response = [
						'success' => true,
						'file' => $upload->getUrl()
					];
				}
			}
		}
		echo json_encode($response); // Need to echo it.
	}

?>