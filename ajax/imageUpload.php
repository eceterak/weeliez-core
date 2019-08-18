<?php

	/**
	 * @see image_model.php
	 * Upload an image using AJAX. This is basically a copy-paste from image_model.
 	 * For each file, check for upload errors. use uploadMessageError to generate a error message.
	 * Because, this method may handle multiple uploads and some of them may fail, save result of each of them into an array.
	 * If upload was successfull, save true into array. If upload was unsuccessfull save a error message.
	 * Prepare file in the first place to use upload class methods. 
     * Validate file for size etc. 
	 * Check if uploaded file is already associated with $object with $id in database (use original name of the file).
	 * Use max() method to obtain image number (to prevent all images having the same name).
	 * Get info about object and use it to generate image name in conjuction with image number.
	 * Upload file providing a new file name.
	 * Insert informations about image into database.
	 * Set image as default if there is no default images for given object.
	 * If upload is successfull return url of image and it's id so it can be added on the front-end.
	 * @param ajax $_POST['object_id'] [int]
	 * @param ajax $_POST['object'] [string]
	 */

	if(isset($_FILES['image']) && isset($_POST['object_id']) && isset($_POST['object'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$data = getPostValues();
		$response = array(); // JSON response.
		$db = new mysqlib();
		$upload = new \upload('image', 'image');
		foreach($_FILES['image']['error'] as $key => $error) {
			if($error == UPLOAD_ERR_OK) {
				try {
					$upload->prepare($key);
					// validate
					$validate = $upload->validate();
					if($validate !== true) {
						throw new \uploadException($validate);
					}
					// database check
					$db->where('item_id', $data->object_id);
					$db->where('image_item', $data->object);
					$db->where('image_file_name', $upload->getFileName());
					$check = $db->checkRecordExists('image');
					if($check) {
						throw new \uploadException('File already exists.');
					}
					// max number
					$db->where('item_id', $data->object_id);
					$db->where('image_item', $data->object);
					$max = $db->max('image', 'image_number') + 1; // max() returns 0 if nothing found.
					// Default check
					$db->where('item_id', $data->object_id);
					$db->where('image_item', $data->object);
					$db->where('image_default', 1);
					$default = $db->checkRecordExists('image');
					// Upload file
					$object_ = $data->object.'_';
					$name = $object_::path($data->object_id).'_'.$max; // Create file name.
					$up = $upload->upload($name); // Upload file.
					if($up) {
						// database insert START
						$db->set('image_item', $data->object);
						$db->set('item_id', $data->object_id);
						$db->set('image_url', $upload->getFinalName());
						$db->set('image_file_name', $upload->getFileName());
						$db->set('image_number', $max);
						if(!$default) {
							$db->set('image_default', 1); // First uploaded image to this item. Set it as default.
						}
						$result = $db->insert('image');
						if($result) {
							$response[] = array('success' => true, 'url' => $upload->getUrl(), 'image_id' => $db->max('image', 'image_id')); // Last uploaded file == max id
						}
						else {
							throw new \uploadException('Database error.');
						}
						# database insert END
					}
					else {
						throw new \uploadException('File cant be uploaded.');
					}				
				}
				catch(\Exception $e) {
					$response[] = array('success' => false, 'message' => $e->getMessage());
				}
			}
			else {
				$response[] = array('success' => false, 'message' => uploadMessageError($_FILES['image']['error'][$key]));
			}
		}
		echo json_encode($response);
	}

?>