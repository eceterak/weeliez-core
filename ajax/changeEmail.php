<?php

	/**
	 * User needs to confirm his password to change email address.
	 * @param ajax $_POST['auser_email'] [string]
	 * @param ajax $_POST['auser_id'] [int]
	 * @param ajax $_POST['auser_password'] [string]
	 * @return array
	 */

	if(isset($_POST['auser_email']) && isset($_POST['auser_id']) && isset($_POST['auser_password'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$data = getPostValues();
		$db = new mysqlib();
		$response = array('success' => false);
		if($data) {
			try {
				$user = user_::getById($data->auser_id);
				if($user !== false) {
					if(password_verify($data->auser_password, $user->user_password)) {
						$userEmail = $user->checkEmail($data->auser_email);
						$db->set('user_email', $userEmail);
						$db->where('user_id', $user->getId());
						$result = $db->update('user');
						if($result) {
							$response['success'] = true;
						}
						else {
							throw new Exception('Database error. Please try again later.');
						}	
					}
					else {
						throw new Exception('Incorrect password.');	
					}
				}		
			}
			catch(Exception $e) {
				$response['message'] = $e->getMessage();
			}
		}
		echo json_encode($response);
	}
?>