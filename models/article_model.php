<?php

	namespace models;

	/**
	 * @file article_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class article extends \model {
		
		/**
		 * Display all articles.
		 */

		public function index() {
			$this->db->whereNot('article_category_active', 0);
			$this->db->order('article_category_id', 'DESC');
			$categories = $this->db->select('article_category', 'article_category_id, article_category_name, article_category_path');
			if($categories) {
				$categories->create_objects('article_category_');
				$arr = [];
				foreach($categories->fetch_data() as $category) {
				//	var_dump($category);
					//$category = new 
					$articles = '';
					$this->db->where('article_category_id', $category->article_category_id);
					$this->db->limit(3);
					$articles = $this->db->select('article');
					if($articles) {
						$articles->create_objects('article_');
						$arr[(string)$category] = $articles;
					}
				}
				$this->viewModel->add('articles', $arr);
			}
			return $this->viewModel;
		}

	
		/**
		 * Display single article.
		 * @param $path [string]
		 */

		public function display($path) {
			$article = \article_::getByPath($path);
			if($article) {
				$this->viewModel->add('article', $article);
			}
			else {
				$this->viewModel->error('Sorry, article not found.'); // Set error message.		
			}
			return $this->viewModel;
		}
	}

?>