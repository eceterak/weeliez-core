<?php

	namespace admin\models;

	/**
	 * @file blogModel_model.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class blog extends \admin\model {

		/**
		 * Don't validate data for duplicates when creating/updating blog title.
		 * @var bool
		 */

		protected $validate = false;	

		/**
		 * Display all items.
		 */

		public function index() {
			$this->db->sort(['title' => 'blog_title']);
			$blogs = $this->db->select('blog');
			if($blogs) {
				$blogs->create_objects('blog_');
				$this->viewModel->add('blogs', $blogs);
			}
			return $this->viewModel;
		}

		/**
		 * Edit blog.
		 * @param $id [int]
		 */

		public function edit($id) {
			$blog = \blog_::getById($id);
			if($blog) {
				$this->viewModel->add('blog', $blog);
			}
			else {
				$this->viewModel->error('Nothing to display'); // Set error message.
			}
			return $this->viewModel;			
		}
	}

?>