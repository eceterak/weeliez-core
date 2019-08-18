<?php

	namespace admin\models;

	/**
	 * @file articleModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class article extends \admin\model {
		
		/**
		 * Display all articles. Sort them by data (most recet first).
		 */

		public function index() {
			$this->db->join('user u', 'a.user_id = u.user_id');
			$this->db->join('article_category c', 'c.article_category_id = a.article_category_id');
			$this->db->sort(['date' => 'a.article_date', 'title' => 'a.article_title', 'category' => 'c.article_category_name', 'author' => 'u.user_name'], 'DESC');
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
			return $this->viewModel;
		}

		/**
		 * Edit single article. Use getById() method to load item.
		 * If article is found, load all images associated with this article.
		 * @param $id [int] // id of item to edit.
		 */

		public function edit($id) {
			$article = \article_::getById($id, true);
			if($article) {
				// Load images
				$this->db->where('item_id', $id);
				$this->db->where('image_item', 'article');
				$result = $this->db->select('image');
				if($result) {
					$article->loadImages($result);
				}
				$this->viewModel->add('article', $article);
			}
			else {
				$this->viewModel->error('Sorry, article not found.'); // Set error message.		
			}
			return $this->viewModel;
		}

		/**
		 * Display articles that needs validation (if any).
		 */

		public function unvalidated() {
			$this->db->join('user u', 'a.user_id = u.user_id');
			$this->db->join('article_category c', 'c.article_category_id = a.article_category_id');
			$this->db->sort(['date' => 'a.article_date', 'title' => 'a.article_title', 'category' => 'c.article_category_name', 'author' => 'u.user_name'], 'DESC');
			$this->db->where('a.article_valid', 0); // Only those which needs validation.
			$articles = $this->db->select('article a', 'a.article_id, a.article_date, a.article_title, a.article_valid, c.article_category_id, c.article_category_name, u.user_id, u.user_name');
			if($articles) {
				$articles->create_objects('article_');
				$this->viewModel->add('articles', $articles);
			}
			else {
				$this->viewModel->error('No articles found.');
			}
			return $this->viewModel;			
		}

		/**
		 * If article is not validated it won't be shown on front page.
		 */

		public function validate($id) {
			$this->db->where('article_id', $id);
			$this->db->set('article_valid', 1);
			$result = $this->db->update('article');
			if($result) {
				return true;
			}
			else {
				throw new \Exception('Unknown database error. Try later.');
			}
		}

		/**
		 * This method is different from a global create() because instead of checking for a required field prefix_name, we checking for a prefix_title.
		 */

		public function create() {
			$data = getPostValues();
			if($data) {
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
				var_dump($this->prefix);
				$result = $this->db->insert($this->prefix);	
				if($result) {
					$id = $this->db->max($this->prefix, $this->prefix.'_id'); // Get id of inserted item.
					$path = $this->path($id);
					if($path) {
						$this->db->set($this->prefix.'_path', $path);
						$this->db->where($this->prefix.'_id', $id);
						$update = $this->db->update($this->prefix);
						if(!$update) {
							throw new \exception('Error in database query.');
						}
					}
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

		/**
		 * Update item in database. Run only when request method equals POST.
		 * First, get POST values. Then, check if any data was sent. $data object should contain redirect property which is a adress of the main page.
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
				$path = $this->path($id);
				if($path) {
					$this->db->set($this->prefix.'_path', $path);
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