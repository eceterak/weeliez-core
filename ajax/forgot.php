<?php

	/**
	 * Reset user's password. Send an email with instructions. Set user_forgot to token.  
	 * @param ajax $_POST['user_email_forgot'] [string]
	 */

	if(isset($_POST['user_email_forgot'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$data = getPostValues();
		$db = new mysqlib();
		$response = array('success' => false);
		if($data) {
			$user = user_::getByDetails($data->user_email_forgot);
			if($user !== false) {
				$token = user_::generateToken();
				$mail = mail::forgotPasswordEmail($user->user_email, $token);
				if($mail) {
					$db->set('user_forgot', $token);
					$db->where('user_id', $user->getId());
					$result = $db->update('user');
					if($result) {
						$response['success'] = true;
					}
				}
			}
		}
		echo json_encode($response);
	}
?>