<?php

	namespace admin\models;

	/**
	 * @file homeModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class home extends \admin\model {

		/**
		 * Display admin dashboard.
		 */

		public function index() {
			/** Bikes **/
			$this->db->limit(6);
			$this->db->join('brand br', 'b.brand_id = br.brand_id'); // Join brands.
			$this->db->join('category c', 'b.category_id = c.category_id'); // Join types.
			$this->db->order('b.bike_id', 'DESC');
			$bikes = $this->db->select('bike b', 'b.bike_id, b.bike_name, b.bike_year_start, b.bike_year_end, br.brand_id, br.brand_name, c.category_id, c.category_name'); 
			if($bikes) {
				$bikes->create_objects('bike_');
				$this->viewModel->add('bikes', $bikes); // Add array of bikes to viewModel.
			}
			/** Articles **/
			$this->db->limit(6);
			$this->db->join('user u', 'a.user_id = u.user_id');
			$this->db->join('article_category c', 'c.article_category_id = a.article_category_id');
			$this->db->order('a.article_date', 'DESC');
			$articles = $this->db->select('article a', 'a.article_id, a.article_date, a.article_title, a.article_valid, c.article_category_id, c.article_category_name, u.user_id, u.user_name');
			if($articles) {
				$articles->create_objects('article_');
				$this->viewModel->add('articles', $articles);
				$this->db->where('article_valid', 0);
				$this->viewModel->add('unvalidated', $this->db->count('article'));
			}
			else {
				$this->viewModel->error('No articles found.');
			}
			/** Users **/
			$this->db->between('user_created', date('Y-m-d H:i:s', time() - (60 * 60 *24 * 7)), date('Y-m-d H:i:s'));
			$this->viewModel->add('new_users', $this->db->count('user'));
			/** Reviews **/
			$this->db->between('review_date', date('Y-m-d H:i:s', time() - (60 * 60 *24 * 7)), date('Y-m-d H:i:s'));
			$this->viewModel->add('reviews', $this->db->count('review'));
			return $this->viewModel;
		}
	}

?>