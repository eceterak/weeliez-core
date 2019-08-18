<?php

	/**
	 * Thanks to MySQL CONCAT_WS functions, user can start typing a brand name and get bikes associated with this brand.
	 * @param ajax $_POST['bike_name_autocomplete'] [string]
	 */

	if(isset($_POST['bike_name_autocomplete'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$db = new \mysqlib();
		$phrase = htmlspecialchars($_POST['bike_name_autocomplete']);
		$phrase = trim($phrase); // Clear spaces.
		$phrase = strtolower($phrase);
		$db->like('CONCAT_WS(" ", br.brand_name, b.bike_name)', $phrase, false); // Combine bike and brand name so user can look for BRAND BIKE NAME.
		$db->like('CONCAT_WS(" ", b.bike_name, br.brand_name)', $phrase, false); // Or BIKE NAME BRAND.
		$db->limit(10);
		$db->join('brand br', 'br.brand_id = b.brand_id');
		$bikes = $db->select('bike b', 'b.bike_id, CONCAT(br.brand_name, " ", b.bike_name) AS bike_name, b.bike_year_start, b.bike_year_end'); // Result will look like SUZUKI GS 500.
		if($bikes) {
			echo json_encode($bikes->fetch_data());
		}
		else echo json_encode([]); // Return an empty array. Any other result will cause not hidding the jquery autocomplete dropdown.
	}

?>