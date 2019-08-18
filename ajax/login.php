<?php

	/**
	 * Try to login by name or email. If account is not blocked (brute force), check password. 
	 * If provided password is correct login user by setting session details and reload page in javascript.
	 * Get information about user's browser and connect it with password. This will prevent stealing session and login from different pc.
	 * If provided password is incorrect record failed login attempt.
	 * Return 'Incorrect username OR password' message to not give a hints about username. 
	 * @param ajax $_POST['ajax_user_name'] [string]
	 * @param ajax $_POST['ajax_user_password'] [string]
	 * @return array
	 */

	if(isset($_POST['ajax_user_name']) && isset($_POST['ajax_user_password'])) {
		header('Content-type:application/json;charset=utf-8');
		require_once('../inc.php');
		$data = getPostValues();
		$db = new mysqlib();
		$response = array('success' => false);
		if($data) {
			try {
				$user = user_::getByDetails($data->ajax_user_name);
				if($user !== false) {
					$authentication = new authentication();
					$brute = $authentication->bruteForceProtection($user->user_id);
					if($brute) {
						$verified = $authentication->isVerified($user->user_id);
						if($verified) {
							if(password_verify($data->ajax_user_password, $user->user_password)) {
								$session = new session();
								$session->start();
								$userBrowser = $_SERVER['HTTP_USER_AGENT']; // Get information about browser.
								$session->add('userId', $user->user_id);
								$session->add('userName', $user->user_name);
								$session->add('userSap', hash('sha512', $user->user_password.$userBrowser));
								$authentication->remember($user, $db); // Set a new 'remember me' cookie.
								$response['success'] = true;
							}
							else {
								$record = $authentication->loginAttemptFailed($user->user_id); // Someone tried to log in and failed. Record this attempt.
								throw new Exception('Incorrect username or Password.');
							}					
						}
						else {
							throw new Exception("Please verify your email address. If you didn't received a verification email, please contact the administration.");
						}
					}
					else {
						throw new Exception('Account blocked due to safety reasons.');
					}
				}
				throw new Exception('Incorrect username or Password.');			
			}
			catch(Exception $e) {
				$response['message'] = $e->getMessage();
			}
		}
		echo json_encode($response);
	}
?>