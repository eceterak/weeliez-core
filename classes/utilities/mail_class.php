<?php

	/**
	 * @file mail_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class mail {

		/**
		 * Receiver/s of the mail.
		 * @var string
		 */

		public $to;

		/**
		 * Subject of the mail to be sent.
		 * @var string
		 */

		public $subject;

		/**
		 * Message to be sent.
		 * @var string
		 */

		public $message;

		/**
		 * Email headers.
		 * @var array
		 */

		public $headers = array();

		/**
		 * Email type.
		 * @var string
		 */

		public $type = 'html';

		/**
		 * Prepare mail before sending.
		 * @param $to [string]
		 * @param $subject [string]
		 * @param $message [string]
		 */

		public function __construct($to, $subject, $message) {
			$this->to = $to;
			$this->subject = $subject;
			$this->message = $this->prepareMessage($message);
		}

		/**
		 * Prepare message.
		 * Each line should be separated with a CRLF (\r\n). Lines should not be larger than 70 characters.
		 * @param $message [sting]
		 */

		public function prepareMessage($message) {
			$message = str_replace("\n.", "\n..", $message);
			return wordwrap($message, 70);
		}

		/**
		 * Change email type. 
		 * @param $type [string]
		 */

		public function setType($type) {
			$this->type = $type;
		}

		/**
		 * Prepare email headers depending on email type. Thanks to headers message can be formated like a html.
		 * Header 'From' must be always set, otherwise sender's email will look very random like yqfho6nf7gg7@n3plcpnl0142.prod.ams3.secureserver.net
		 */

		private function prepareHeaders() {
			$this->headers[] = 'From: weeliez.com <admin@weeliez.com>';
			switch($this->type) {
				case 'html':
				default:
					$this->headers[] = 'MIME-Version: 1.0';
					$this->headers[] = 'Content-type: text/html; charset=iso-8859-1';
				break;
			}
		}

		/**
		 * Send a mail.
		 * @return bool
		 */

		public function send() {
			if(!empty($this->to) && !empty($this->subject) && !empty($this->message)) {
				$this->prepareHeaders();
				return mail($this->to, $this->subject, $this->message, implode("\n", $this->headers)); // \n must be in "" not ''.
			}
			else {
				return false;
			}
		}

		/**
		 * Send a email confirmation to a user.
		 * @param $to [string]
		 * @param $token [int]
		 */

		static public function sendVerificationEmail($to, $token) {
			$subject = 'Please confirm your email address';
			$message = '
				<html>
				<head>
				  <title>Please confirm your email address</title>
				</head>
				<body>
				  <p>Thanks for signing up to weeliez.com!</p>
				  <p>Please confirm your email address to complete your weeliez registration.</p>
				  <a href = "http://www.weeliez.com/user/verify/'.$token.'">Confirm your email</a>
				</body>
				</html>';
			$mail = new self($to, $subject, $message);
			return $mail->send();
		}

		/**
		 * Send a email confirmation to a user.
		 * @param $to [string]
		 * @param $token [int]
		 */

		static public function forgotPasswordEmail($to, $token) {
			$subject = 'Password recovery';
			$message = '
				<html>
				<head>
				  <title>Password recovery</title>
				</head>
				<body>
				  <p>Someone recently requested a password change for your weeliez account. If this was you, you can set a new password here: </p>
				  <a href = "http://www.weeliez.com/user/forgot/'.$token.'">Reset password</a>
				</body>
				</html>';
			$mail = new self($to, $subject, $message);
			return $mail->send();
		}
	}

?>