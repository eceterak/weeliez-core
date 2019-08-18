<?php

	/**
	 * @file upload_exception.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class uploadException extends Exception {

		/**
		 * Create an error message and construct new Exception (parent).
		 * Can be used in two way. If message is a upload error send error number ($code) and transform it to a full message (uploadMessage()).
		 * @param $code [int]
		 * @param $isMessage [bool] // false
		 */

		public function __construct($code, $isMessage = false) {
			$message = ($isMessage === true) ? $this->uploadMessage($code) : $code;
			parent::__construct($message);
		}

		/**
		 * Transform error code to a full message.
		 * @param code [int]
		 * @return string
		 */

		private function uploadMessage($code) {
			switch($code) {
				case 1:
					$message = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
				break;
				case 2:
					$message = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
				break;
				case 3:
					$message = 'The uploaded file was only partially uploaded.';
				break;
				case 4:
					$message = 'No file was uploaded.';
				break;
				case 6:
					$message = 'Missing a temporary folder. Introduced in PHP 5.0.3.';
				break;
				case 7:
					$message = 'Failed to write file to disk. Introduced in PHP 5.1.0.';
				break;
				default:
					$message = 'Unknow upload error.';
				break;
			}
			return $message;
		}
	}


?>