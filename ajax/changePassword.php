<?php

	/**
	 * Before changing users password, check if he/she entered correct, old password.
	 * If everything is OK, reset the session and cookie to keep user logged in.
	 * @param ajax $_POST['auser_password_old'] [string]
	 * @param ajax $_POST['auser_password_new'] [string]
	 * @param ajax $_POST['auser_id'] [int]
	 * @return array
	 */

	if(isset($_POST['auser_password_old']) && isset($_POST['auser_password_new']) && isset($_POST['auser_id'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$data = getPostValues();
		$db = new mysqlib();
		$response = array('success' => false);
		if($data) {
			try {
				$user = user_::getById($data->auser_id);
				if($user !== false) {
					if(password_verify($data->auser_password_old, $user->user_password)) {
						$userPassword = $user->checkPassword($data->auser_password_new); // Throws an error.
						$db->set('user_password', $userPassword);
						$db->where('user_id', $data->auser_id);
						$result = $db->update('user');
						if($result) {
							$session = new session();
							$authentication = new authentication();
							$session->start(); // Update session with a new password.
							$userBrowser = $_SERVER['HTTP_USER_AGENT']; // Get information about browser.
							$session->add('userId', $user->user_id);
							$session->add('userName', $user->user_name);
							$session->add('userSap', hash('sha512', $userPassword.$userBrowser));
							$authentication->remember($user, $db); // Update the 'remember me' cookie.
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