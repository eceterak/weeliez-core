<?php

	namespace admin\models;

	/**
	 * @file imageModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class image extends \admin\model {

		/**
		 * Upload object.
		 * @var upload
		 */

		private $upload;

		/**
		 * Custom construct method. Initialize upload object.
		 */

		public function __construct() {
			$this->db = new \mysqlib();
			$this->viewModel = new \viewModel();
			$this->upload = new \upload('image', 'image');
			$this->prefix = $this->setPrefix();
		}

		/**
		 * Delete na image.
		 * @param $id [int]
		 */

		public function delete($id) {
			$this->db->where('image_id', $id);
			$result = $this->db->selectOne('image');
			if($result) {
				$image = new \image_($result);
				$path = '../upload/images/'.$image->image_url;
				if(file_exists($path)) {
					$unlink = unlink($path);
					if($unlink) {
						$this->db->where('image_id', $id);
						$result = $this->db->delete('image');
						if($result) {
							//return deleteRedirect();
							return true;
						}
						else {
							throw new \exception('Database error.');
						}
					}
					else {
						throw new \exception('Image cannot be deleted.');
					}
				}
				else {
					throw new \exception('Image not found.');
				}
			}
			else {
				throw new \exception('Image does not exists in a database.');
			}
		}

		/**
		 * Set image to be a default one. For all other images associated with item, set default to 0.
		 * @param $id [int]
		 */

		public function def($id) {
			$this->db->where('image_id', $id);
			$result = $this->db->selectOne('image');
			if($result) {
				$image = new \image_($result);
				$this->db->set('image_default', 0);
				$this->db->where('item_id', $image->item_id);
				$this->db->where('image_item', $image->image_item);
				$result = $this->db->update('image');
				if($result) {
					$this->db->set('image_default', 1);
					$this->db->where('image_id', $id);
					$result = $this->db->update('image');
					if($result) {
						return true;
					}
					else {
						throw new \exception('Database error.');
					}
				}
				else {
					throw new \exception('Database error.');
				}
			}
			else {
				throw new \exception('Image does not exists in a database.');
			}
		}

		/**
		 * Upload an image.
		 * This method is divided into two parts.
		 * For each file, check for upload errors. use uploadMessageError to generate a error message.
		 * Because, this method may handle multiple uploads and some of them may fail, save result of each of them into an array.
		 * If upload was successfull, save true into array. If upload was unsuccessfull save a error message.
		 * @see upl()
		 * @return array
		 */

		public function upload() {
			$data = getPostValues();
			$arr = array();
			if($data) {
				foreach($_FILES['image']['error'] as $key => $error) {
					if($error == UPLOAD_ERR_OK) {
						try {
							$arr[] = $this->upl($key, $data->id, $data->object);
						} catch(\uploadException $e) {
							$arr[] = $e->getMessage();
						}
					}
					else {
						$arr[] = uploadMessageError($_FILES['image']['error'][$key]);
					}
				}
				return $arr;
			}
			else {
				throw new \exception('No data sent.');
			}
		}

		/**
		 * Second part of upload method.
		 * Prepare file in the first place to use upload class methods. 
		 * Validate file for size etc. 
		 * Check if uploaded file is already associated with $object with $id in database (use original name of the file).
		 * Use max() method to obtain image number (to prevent all images having the same name).
		 * Get info about object and use it to generate image name in conjuction with image number.
		 * Upload file providing a new file name.
		 * Insert informations about image into database.
		 * Extra comments provided to improve readability.
		 * @param $key [int]
		 * @param $id [int]
		 * @param $object [string]
		 * @return true OR throw \exception
		 */

		public function upl($key, $id, $object) {
			$this->upload->prepare($key);
			# validate start
			$validate = $this->upload->validate();
			if($validate !== true) {
				throw new \uploadException($validate);
			}
			# valudate end
			# database check start
			$this->db->where('item_id', $id);
			$this->db->where('image_item', $object);
			$this->db->where('image_file_name', $this->upload->getFileName());
			$check = $this->db->checkRecordExists('image');
			if($check) {
				throw new \uploadException('File already exists.');
			}
			# database check end
			# max number start
			$this->db->where('item_id', $id);
			$this->db->where('image_item', $object);
			$max = $this->db->max('image', 'image_number') + 1;
			# max number end
			$object_ = $object.'_';
			$name = $object_::path($id).'_'.$max;
			$up = $this->upload->upload($name);
			if($up) {
				# database insert start
				$this->db->set('image_item', $object);
				$this->db->set('item_id', $id);
				$this->db->set('image_url', $this->upload->getFinalName());
				$this->db->set('image_file_name', $this->upload->getFileName());
				$this->db->set('image_number', $max);
				$result = $this->db->insert('image');
				if($result) {
					return true;
				}
				else {
					throw new \uploadException('Database error.');
				}
				# database insert end
			}
			else {
				throw new \uploadException('File cant be uploaded.'); 
			}
		}
	}

?>