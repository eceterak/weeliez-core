<?php

	// Get bikez data

	if(isset($_POST['copy_bikez'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$data = getPostValues();
		if($data->copy_bikez) {
			$bikez = utf8_encode(file_get_contents($data->copy_bikez));
			if($bikez) {
				echo json_encode($bikez); // Return source.
			}
			else {
				echo json_encode(false); // Can't fetch dataa from bikez.
			}
		}
	}

	// Insert into db.

	if(isset($_POST['copy_bikez_arr']) && isset($_POST['copy_brand_id'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$db = new mysqlib();
		$brand_id = $_POST['copy_brand_id'];
		$db->where('brand_id', $brand_id);
		$brand = \brand_::getById($brand_id);
		$data = json_decode($_POST['copy_bikez_arr']);
		if($data) {
			$res = [];
			foreach($data as $value) {
				$db->where('bike_name', $value->name);
				$db->where('bike_year_start', $value->year);
				$db->where('brand_id', $brand_id);
				if(!$db->checkRecordExists('bike')) {
					$name = str_replace($brand->brand_name, '', $value->name);
					$db->set('bike_name', $name);
					$db->set('bike_year_start', $value->year);
					$db->set('bike_year_end', $value->year);
					$db->set('brand_id', $brand_id);
					$db->set('category_id', 3);
					$db->set('bikez_path', 'https://bikez.com'.$value->url);
					$result = $db->insert('bike');
					if($result) {
						$id = $db->max('bike', 'bike_id');
						$db->where('bike_id', $id);
						if($id) {
							$path = bike_::path($id);
							$db->set('bike_path', $path);
							$res[] = $db->update('bike');
						}
						else {
							$db->delete('bike');
						}
					}
				}
			}
			if(in_array(false, $res)) {
				echo json_encode(false);
			}
			else {
				echo json_encode(true);
			}
		}
	}

?>