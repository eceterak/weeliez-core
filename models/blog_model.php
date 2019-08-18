<?php

	namespace models;

	/**
	 * @file blog_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class blog extends \model {

		/**
		 *
		 */

		public function index() {
			$this->db->where('article_category_name', 'Archive');
			$this->db->subQuery();
			$sub = $this->db->select('article_category', 'article_category_id');
			$this->db->whereNot('article_category_id', $sub);
			$result = $this->db->select('article');
			if($result) {
				foreach($result as $article) {
					$articles[] = new \article_($article);
				}
				$this->viewModel->add('articles', $articles);
			}
			return $this->viewModel;
		}

		/**
		 * Display a single blog item.
		 * @param $id [int]
		 */

		public function display($id) {
			$blog = \blog_::getById($id);
			if($blog) {
				$this->viewModel->add('blog', $blog);
			}
			else {
				$this->viewModel->error('No users access to display'); // Set error message.
			}
			return $this->viewModel;			
		}
	}

?>