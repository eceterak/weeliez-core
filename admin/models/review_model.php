<?php

	namespace admin\models;

	/**
	 * @file reviewsModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class review extends \admin\model {

		/**
		 * Display all reviews.
		 */

		public function index() {
			$this->db->join('user u', 'r.user_id = u.user_id');
			$this->db->join('bike b', 'r.bike_id = b.bike_id');
			$reviews = $this->db->select('review r');
			if($reviews) {
				$reviews->create_objects('review_');
				$this->viewModel->add('reviews', $reviews);
			}
			return $this->viewModel;
		}

		/**
		 * Edit review.
		 */

		public function edit($id) {
			$this->db->join('user u', 'r.user_id = u.user_id');
			$this->db->join('bike b', 'r.bike_id = b.bike_id');
			$this->db->where('review_id', $id);
			$result = $this->db->selectOne('review r');
			if($result) {
				$this->viewModel->add('review', new \review_($result));
			}
			return $this->viewModel;
		}

		/**
		 * Display reviews added in the last week.
		 */

		public function recent() {
			$this->db->join('user u', 'r.user_id = u.user_id');
			$this->db->join('bike b', 'r.bike_id = b.bike_id');
			$this->db->between('r.review_date', date('Y-m-d H:i:s', time() - (60 * 60 *24 * 7)), date('Y-m-d H:i:s'));
			$reviews = $this->db->select('review r');
			if($reviews) {
				$reviews->create_objects('review_');
				$this->viewModel->add('reviews', $reviews);
			}
			else {
				$this->viewModel->error('No new reviews last week.'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * Update user's review.
		 * @param $id [int] // id of item to update.
		 * @return string
		 */

		public function update($id) {
			$data = getPostValues();
			if($data) {
				$class = $this->prefix.'_';
				$object = $class::getById($id);
				$required = requiredField($data->{$this->prefix.'_title'});
				if($required === false) {
					throw new \exception('Fill all inputs.');
				}
				if(property_exists($data, 'redirect')) {
					$redirect = $data->redirect;
					unset($data->redirect);
				}
				foreach($data as $key => $value) {
					$this->db->set($key, $value);
				}
				$this->db->where($this->prefix.'_id', $id); // Id comes from url not form.
				$result = $this->db->update($this->prefix);
				if($result) {
					if(isset($redirect)) return $redirect;
					else return true;
				}
				else {
					throw new \exception('Error in database query.');
				}
			}
			else {
				throw new \exception('Sorry, no data was sent. Try again later.');
			}
		} 
	}

?>