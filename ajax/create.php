<?php

	/**
	 * All the user_ methods throw an exceptions. Catch them here and return on front-end.
	 * @param ajax $_POST['user_name_new'] [string]
	 * @param ajax $_POST['user_password_new'] [string]
	 * @param ajax $_POST['user_email_new'] [string]
	 */

	if(isset($_POST['user_name_new']) && isset($_POST['user_password_new']) && isset($_POST['user_email_new'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$data = getPostValues();
		$db = new mysqlib();
		$response = array('success' => false);
		if($data) {
			try {
				$user = new user_();
				$userName = $user->checkName($data->user_name_new); // Throws an exception.
				$userPassword = $user->checkPassword($data->user_password_new);
				$userEmail = $user->checkEmail($data->user_email_new);
				$token = $user->generateToken(); // Generate token before seting database values.
				$mail = mail::sendVerificationEmail($userEmail, $token);
				$mail = true;
				if(!$mail) {
					throw new Exception("We're unable to send verification email. Please try again later.");
				}
				$db->set('user_name', $userName);
				$db->set('user_password', $userPassword);
				$db->set('user_email', $userEmail);
				$db->set('user_verification', $token);
				$db->set('user_verified', 0);
				$db->set('access_id', 13); // New user - 'user' access by default.
				$result = $db->insert('user');
				if($result) {
					$response['success'] = true;
				}
				else {
					throw new Exception('Database error. Please try again later.');
				}		
			}
			catch(Exception $e) {
				$response['message'] = $e->getMessage();
			}
		}
		echo json_encode($response);
	}
?>