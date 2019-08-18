<?php

	/**
	 * Add new bike, it's image (if any) and copy specs in one go.
	 * To update specs use copy.php.
	 * @see copy.php
	 * @param ajax $_POST['quickAdd_data'] [string]
	 */

	if(isset($_POST['quickAdd_data'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$db = new mysqlib();
		$session = new \session();
		$response = ['success' => true];
		$data = encode_serialize($_POST['quickAdd_data']);
		if($data) {
			try {
				$required = requiredField($data->bike_name, $data->bike_year_start);
				if($required === false) {
					throw new \exception('Fill all inputs.');
				}
				$db->where('bike_name', $data->bike_name);
				$db->where('bike_year_start', $data->bike_year_start);
				$db->where('brand_id', $data->brand_id);
				$result = $db->checkRecordExists('bike');
				if($result) {
					throw new \exception('Name and year combination already exists.');
				}
				if(empty($data->bike_year_end) || !isset($data->bike_year_end)) {
					$data->bike_year_end = NULL; // Bike still for sale.
				}
				if($data->bike_year_end < $data->bike_year_start) {
					$data->bike_year_end = $data->bike_year_start; // End of production date, cant be older than start of production date.
				}
				// Can't use foreach->set on $data because it contains values like img url, and links to bikez and motorcyclespecs. 
				$db->set('bike_name', $data->bike_name);
				$db->set('brand_id', $data->brand_id);
				$db->set('category_id', $data->category_id);
				$db->set('bike_year_start', $data->bike_year_start);
				$db->set('bike_year_end', $data->bike_year_end);
				$db->set('bike_sale', $data->bike_sale);
				$result = $db->insert('bike'); // Create new bike.
				if($result) {
					$id = $db->max('bike', 'bike_id');
					$path = \bike_::path($id);
					$db->set('bike_path', $path);
					$db->where('bike_id', $id);
					$update = $db->update('bike'); // Set bike path for friendly url.
					if($update) {
						$response['bike_id'] = $id; // Bike id needs to be passed to javascript.
						$response['bike_name'] = $data->bike_name;
						$response['bike_year'] = (!empty($bike_year_end)) ? $data->bike_year_start.' - '.$data->bike_year_end : $data->bike_year_start;
						$session->add('message', 'Item has been added!');
						$response['bike_added'] = true;
						if(!empty($data->bikez)) {
							$response['bikez'] = utf8_encode(file_get_contents($data->bikez));
						}
						if(!empty($data->motorcyclespecs)) {
							$response['motorcyclespecs'] = utf8_encode(file_get_contents($data->motorcyclespecs));
						}
						if(!empty($data->image_url)) {
							$name = $path.'_1'; // Create file name.
							$upload = new \upload($data->image_url, 'image');
							$upload->prepareExternal();
							$upload->uploadExternal($name);
							$validate = $upload->validateExternal();
							if($validate === true) {
								$db->set('image_item','bike');
								$db->set('item_id', $id);
								$db->set('image_url', $upload->getFinalName());
								$db->set('image_file_name', $upload->getFinalName());
								$db->set('image_number', 1);
								$db->set('image_default', 1);
								$result = $db->insert('image');
							}
							else {
								throw new \exception('Bike added but there was a problem when uploading an image: '.$validate); // $validate holds an upload error.
							}
						}
					}
					else {
						throw new \exception('Error in database query.');
					}
				}
				else {
					throw new \exception('Error in database query.');
				}
			} 
			catch (\exception $e) {
				$response['message'] = $e->getMessage();
				$response['success'] = false;
			}
		}
		echo json_encode($response);
	}
?>