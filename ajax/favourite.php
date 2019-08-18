<?php

	/**
	 * This function works only if user is logged into a website. If not, login/signup dialog will be shown.
	 * Check if user already loved the bike. If yes, that means that he/she don't love the bike any more and record must be deleted.
	 * Otherwise insert a new 'love' into database. Lastly count amount of loves for this bike and return it to update on the front-end by javascript.
	 * @param ajax $_POST['favourite_bike_id'] [int]
	 */

	if(isset($_POST['favourite_bike_id'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$db = new mysqlib();
		$session = new session(); // Need to start the session to check if user is logged.
		$data = getPostValues();
		$response = array('success' => false);
		if($data) {
			$bike_id = $data->favourite_bike_id;
			try {
				$auth = new authentication();
				$user = $auth->isLogged(); // Check if user is logged.
				if($user) {
					$db->where('bike_id', $bike_id);
					$db->where('user_id', $user->user_id);
					$isFavourite = $db->selectOne('favourite');
					if($isFavourite) {
						$db->where('favourite_id', $isFavourite->favourite_id);
						$delete = $db->delete('favourite');
						if($delete) {
							$response['success'] = true;
						}
					}
					else {
						$db->set('bike_id', $bike_id);
						$db->set('user_id', $user->user_id);
						$insert = $db->insert('favourite');
						if($insert) {
							$response['success'] = true;	
						}
					}
				}
				else {
					throw new Exception('login');
				}
			}
			catch(Exception $e) {
				$response['error'] = $e->getMessage();
			}
		}
		echo json_encode($response);
	}
?>