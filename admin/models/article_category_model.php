<?php

	namespace admin\models;

	/**
	 * @file article_categoryModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class article_category extends \admin\model {

		/**
		 * Display all items.
		 */

		public function index() {
			$this->db->sort(['name' => 'ac.article_category_name']);
			$categories = $this->db->select('article_category ac', 'ac.article_category_id, ac.article_category_name');
			if($categories) {
				$categories->create_objects('article_category_');
				$this->viewModel->add('categories', $categories);
			}
			else {
				$this->viewModel->error('Nothing to display.'); // Set error message.
			}
			return $this->viewModel;
		}

		/**
		 * Edit single category.
		 * @param $id [int]
		 */

		public function edit($id) {
			$category = \article_category_::getById($id);
			if($category) {
				$this->viewModel->add('category', $category);
			}
			else {
				$this->viewModel->error('Category not found.'); // Set error message.
			}
			return $this->viewModel;			
		}
	}

?>