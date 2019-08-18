<?php

	namespace models;

	/**
	 * @file home_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class home extends \model {

		/**
		 * Home page or index. Load data to populate it, including newly added bikes, news, most popular brands and other.
		 */

		public function index() {
			// Load new bikes
			//$this->db->where('b.bike_year_start', date('Y')); // Current year, use php's date.
			$this->db->limit(6);
			$this->db->join('brand br', 'b.brand_id = br.brand_id');
			$this->db->order('b.bike_id', 'DESC'); // New first.
			$new = $this->db->select('bike b', 'b.bike_id, b.bike_name, b.bike_path, br.brand_id, br.brand_name, br.brand_path');
			if($new) {
				$new->create_objects('bike_');
				$this->viewModel->add('bikes', $new); // Add array of bikes to viewModel.
			}
			// Load popular brands
			$this->db->groupBy('brand_id');
			$this->db->join('brand br', 'b.brand_id = br.brand_id');
			$this->db->order('bikesAmount', 'DESC');
			$this->db->limit(6);
			$brands = $this->db->select('bike b', 'b.brand_id, COUNT(*) as bikesAmount, br.brand_name, br.brand_path');
			if($brands) {
				$brands->create_objects('brand_');
				$this->viewModel->add('brands', $brands);
			}
			// Load most loved bikes
			$this->db->join('love l', 'l.item_id = b.bike_id');
			$this->db->join('brand br', 'b.brand_id = br.brand_id');
			$this->db->where('l.love_item', 'bike');
			$this->db->limit(6);
			$this->db->groupBy('b.bike_id');
			$this->db->order('bike_loves', 'DESC'); // New first.
			$loves = $this->db->select('bike b', 'b.bike_id, b.bike_name, b.bike_path, b.bike_year_start, b.bike_year_end, br.brand_id, br.brand_name, br.brand_path, COUNT(l.love_id) as bike_loves');
			if($loves) {
				$loves->create_objects('bike_');
				$this->viewModel->add('loves', $loves); // Add array of bikes to viewModel.
			}
			// Load new \reviews
			$this->db->join('user u', 'u.user_id = r.user_id');
			$this->db->join('bike b', 'b.bike_id = r.bike_id');
			$this->db->join('brand br', 'br.brand_id = b.brand_id');
			$this->db->limit(6);
			$this->db->order('r.review_id', 'DESC');
			$reviews = $this->db->select('review r', 'r.review_id, r.review_title, u.user_name, u.user_path, b.bike_name, b.bike_path, br.brand_id, br.brand_name, br.brand_path');
			if($reviews) {
				$reviews->create_objects('review_');
				$this->viewModel->add('reviews', $reviews);
			}
			// Load news
			$this->db->whereNot('ac.article_category_active', 0);
			$this->db->where('a.article_valid', 1);
			$this->db->order('a.article_date', 'DESC');
			$this->db->join('article_category ac', 'ac.article_category_id = a.article_category_id');
			$this->db->join('user u', 'a.user_id = u.user_id');
			$articles = $this->db->select('article a', '*', 4);
			if($articles) {
				$articles->create_objects('article_');
				$this->viewModel->add('articles', $articles);
			}
			return $this->viewModel;
		}
	}

?>