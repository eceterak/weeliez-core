<?php

	/**
	 * @file upload_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class upload {

		/**
		 * @var $targetDir
		 * @var $folder
		 * @var $input
		 * @var $key
		 * @var $fileName
		 * @var $tmpName
		 * @var $target
		 * @var $finalName
		 * @var $fileExt
		 * @var $allowed
		 * @var $size
		 * @var $maxSize
		 * @var $fileType
		 */

		/**
		 * Folder where uploading all files.
		 * @var string
		 */

		private $targetDir = UPLOAD_PATH;

		/**
		 * Upload sub folder.
		 * @var string
		 */

		private $folder = null;

		/**
		 * Name of input field from form.
		 * @var string
		 */
		
		private $input;

		/**
		 * If sending more than one file, this is a key of file in $_FILES array.
		 * @var int
		 */
		
		private $key = null;

		/**
		 * Name of uploaded file.
		 * $_FILES[$inptuName][name][$key]
		 * @var string
		 */
		 	
		public $fileName;

		/**
		 * Temporary file name of uploaded file.
		 * $_FILES[$inptuName][tmp_name][$key]
		 * @var string
		 */

		private $tmpName;

		/**
		 * Name of the file including folder name.
		 * ex. images/image.jpg
		 * @see move_uploaded_file()
		 * @var string
		 */

		private $target = null;

		/**
		 * New name of uploaded file. Unlike target file does not including folder name but includes extension.
		 * @var string
		 */
		
		public $finalName;

		/**
		 * Extension of uploaded file.
		 * @var string
		 */

		public $fileExt;

		/**
		 * Array with allowed extensions.
		 * @var array
		 */
		
		private $allowed = array();

		/**
		 * File size.
		 * @var int
		 */
		
		private $size;

		/**
		 * Maximum allowed file size.
		 * @var int
		 */
		
		private $maxSize = 2097152; // 2097152 == 2mb.

		/**
		 * Type of uploaded file.
		 * @see setAllowed().
		 * @var string // image
		 */

		private $fileType = 'image';

		/**
		 * Set name of input field and allowed object type (image, pdf, etc.).
		 * Use folders to keep different types of files separate from each other.
		 * @param $input [string]
		 * @param $fileType [string]
		 */
		
		public function __construct($input, $fileType = null, $folder = null) {
			$this->setInput($input);
			$this->setAllowed($fileType);
			$this->folder = $folder;
		}

		/**
		 * Get name of the file.
		 */

		public function setFileName() {
			$this->fileName = (isset($this->key)) ? basename($_FILES[$this->input]["name"][$this->key]) : basename($_FILES[$this->input]["name"]);
		}

		/**
		 * Get temporary name.
		 */

		public function setTmpName() {
			$this->tmpName = (isset($this->key)) ? $_FILES[$this->input]["tmp_name"][$this->key] : $_FILES[$this->input]["tmp_name"];
		}

		/**
		 * Get file extension.
		 * In case of working with external file, pass its url as a param.
		 * @param $file [string]
		 */

		public function setFileExt($file = null) {
			$this->fileExt = ($file !== null) ? strtolower(pathinfo($file, PATHINFO_EXTENSION)) : strtolower(pathinfo($this->fileName, PATHINFO_EXTENSION));
		}

		/**
		 * Get file size and update property.
		 * Can pass $file param if working with an external file.
		 * @param $file [string]
		 */

		public function setSize($file = null) {
			if(!is_null($file)) $this->size = filesize($file);
			else $this->size = (isset($this->key)) ? $_FILES[$this->input]["size"][$this->key] : $_FILES[$this->input]["size"];
		}

		/**
		 * Set allowed file extensions and folder where files are gonna be uploaded.
		 * @param $fileType [string]
		 */

		public function setAllowed($fileType) {
			switch($fileType) {
				case 'image':
					$this->allowed = array('jpg', 'jpeg', 'png', 'svg', 'gif');
					$this->fileType = 'image';
					$this->targetDir.= 'images/';
				break;
				default:
					$this->allowed = array('jpg', 'jpeg', 'png', 'svg');
				break;
			}
		}

		/**
		 * Setup full path to the file.
		 */

		public function target() {		
			$this->target = $this->targetDir.$this->finalName;
		}

		/**
		 * Set sub folder. If one doesn't exist, try to create it. Set chmod to 777 to give full premisions.
		 * If folder creation failed, retrun and leave targetDir as it is (save object in main folder).
		 */

		private function setDirectory() {
			if(!is_null($this->folder)) {
				if(!file_exists($this->targetDir.$this->folder)) {
					if(!mkdir($this->targetDir.$this->folder, 0777)) {
						return;
					}
				}
				$this->targetDir .= $this->folder.'/';
			}
		}

		/**
		 * Return full url to the uploaded file.
		 * As upload uses a raw file path not an url, it must be edited before returning.
		 * To obtain an url delete document root and add server variables.
		 * @return string
		 */

		public function getUrl() {
			$this->target = str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->target);
			return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/'.$this->target;
		}

		/**
		 * Set name of <input> field with file.
		 * @param $input [string]
		 */

		public function setInput($input) {
			$this->input = $input;
		}

		/**
		 * Set new file name (in upload folder).
		 * If there is no need to change a file name, just get original name of the file.
		 * @param $finalName [string]
		 */

		public function setFinalName($finalName = null) {
			if(!is_null($finalName)) {
				$this->finalName = $finalName.'.'.$this->fileExt;
			}
			else {
				$this->finalName = $this->fileName;
			}
		}

		/**
		 * Return full file path.
		 * @return string
		 */		

		public function getTargetDir() {
			return $this->targetDir;
		}

		/**
		 * Return file name after changes.
		 * @return string
		 */

		public function getFinalName() {
			return $this->finalName;
		}

		/**
		 * Return original file name.
		 * @return string
		 */		

		public function getFileName() {
			return $this->fileName;
		}

		/**
		 * Check if imagesize is not null. Works only with images!
		 */

		private function checkReal() {
			$check = (!is_null($this->target)) ? getimagesize($this->target) : getimagesize($this->tmpName);
			if($check == false) {
				throw new Exception("Image file broken.");
			}
		}

		/**
		 * Check if file already exists in the folder.
		 */

		public function checkExists() {
			if(file_exists($this->target)) {
				throw new uploadException("File already exists in the folder.");			
			}
		}

		/**
		 * Check if file size is within the limits.
		 */

		private function checkSize() {
			if($this->size > $this->maxSize) {
				throw new Exception("Sorry but file is too big. Max file size is ".$this->maxSize);
			}
		}

		/**
		 * Check if file extension is allowed.
		 */

		private function checkType() {
			if(!in_array($this->fileExt, $this->allowed)) {
				throw new Exception("Wrong file type.");
			}
		}

		/**
		 * Perform all validation checks. If file is a image, make additional test (real -> getimagesize).
		 * Do not perform checkExists() for image (it's already checked in db).
		 */

		public function validate() {
			try {
				if($this->fileType == 'image') $this->checkReal();
				if($this->fileType !== 'image') $this->checkExists();
				$this->checkType();
				$this->checkSize();
			} catch(Exception $e) {
				return $e->getMessage();
			}
			return true;
		}

		/**
		 * Validation for external file is carried after file is uploaded.
		 */

		public function validateExternal() {
			$this->setSize($this->target);
			try {
				if($this->fileType == 'image') $this->checkReal();
				$this->checkType();
				$this->checkSize();
			} catch(Exception $e) {
				$this->delete($this->target); // File is invalid - delete.
				return $e->getMessage();
			}
			return true;
		}

		/**
		 * Prepare the file for upload and validation.
		 * @param $key [int]
		 */

		public function prepare($key = null) {
			$this->key = $key;
			$this->setFileName();
			$this->setTmpName();
			$this->setSize();
			$this->setFileExt();
			$this->setDirectory();
		}

		/**
		 * Prepare the external file for upload.
		 */

		public function prepareExternal() {
			$this->setFileExt($this->input); // Method will work on the url.
			$this->setDirectory();
		}

		/**
		 * Upload file and set final name.
		 * @param $name [string]
		 */

		public function upload($name = null) {
			$this->setFinalName($name);
			$this->target();
			if(isset($this->key)) {
				if(move_uploaded_file($_FILES[$this->input]["tmp_name"][$this->key], $this->target)) {
					return true;
				}		
			}
			else {
				if(move_uploaded_file($_FILES[$this->input]["tmp_name"], $this->target)) {
					return true;
				}
			}
			return false;
		}

		/**
		 * Upload file from external location.
		 * @param $name [string]
		 */

		public function uploadExternal($name) {
			$this->setFinalName($name);
			$this->target();
			$upload = file_put_contents($this->target, fopen($this->input, 'r'));
			if($upload) {
				return true;
			}
			return false;
		}

		/**
		 * Delete file.
		 * @param $path [string] 
		 */

		static public function delete($path) {
			return unlink($path);
		}
	}

?>