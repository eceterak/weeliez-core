<?php

	/**
	 * @file pagination_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class pagination {

		/**
		 * Keep database object here.
		 * @var mysqlib
		 */

		public $db;

		/** 
		 * Page obtained from url ($_GET). Thanks to this variable, it's possible to calculate current offset.
		 * @var int
		 */

		protected $currentPage = 1;
		
		/** 
		 * Total number of pages.
		 * @var int
		 */

		protected $totalPages;

		/** 
		 * Number of items per single page.
		 * @var int
		 */

		protected $perPage = 30;
		
		/** 
		 * Offset tels mysql how many records ignore when retrieving data. It works in association with $perPage.
		 * It's important to set offset to 0 by default, otherwise LIMIT function in MySQL query will not work.
		 * Ex.: without offset set to 0: ORDER BY b.* bike_id LIMIT , 25.
		 * @var int
		 */

		protected $offset = 0;

		/**
		 * Total number of items.
		 * @var int
		 */

		protected $itemCount;

		/**
		 * If pagination 
		 * @var bool
		 */

		protected $_start = false;

		/**
		 * Get and set current page, count how many rows of data is available. Celi() function always rounds value up.
		 * Get perPage from database.
		 * @param $table [string]
		 * @param $table [int]
		 */

		public function __construct($table, $perPage = null) {
			$this->db = new mysqlib();
			$check = $this->db->checkTableExists($table);
			if($check) {
				$this->table = $table;
			}
			if(isset($_COOKIE['perpage'])) {
				$this->perPage = $_COOKIE['perpage'];
			}
			else {
				if(!is_null($perPage)) {
					$this->perPage = $perPage;
				}
				else {
					$this->perPage = mysqlib::getConfig('perpage');
				}
			}
		}

		/**
		 * Get and set current page, count how many rows of data is available. Celi function always rounds value up.
		 * Set the flag to true.
		 */

		public function start() {
			//if($this->_start !== true) {
				//$this->_start = true;
				$this->itemCount = $this->db->count($this->table);
				if($this->itemCount) {
					$this->totalPages = ceil($this->itemCount/$this->perPage);
					if(isset($_GET['page'])) {
						$this->currentPage = $_GET['page'];
						$this->offset = ($this->currentPage - 1) * $this->perPage; // OFFSET - how far away in table you are. You want to start from index[0].
					}
				}
			//}
		}

		/**
		 *	This method is used to count a number of total pages so you can redirect correctly when using delete function.
		 */

		public function countPages() {
			$this->itemCount = $this->db->count($this->table);
			if($this->itemCount) {
				$this->totalPages = ceil($this->itemCount/$this->perPage);
			}
			return $this->totalPages;
		}

		/**
		 * @return int
		 */

		public function get_currentPage() {
			return $this->currentPage;
		}

		/**
		 * @return int
		 * @todo change in model class
		 */

		public function getPerPage() {
			return $this->perPage;
		}

		/**
		 * @return int
		 * @todo change in model class
		 */

		public function getOffset() {
			return $this->offset;
		}

		/**
		 * Show navigation buttons. Use paginationURI() to create link to a new page.
		 * Display only if there is more than one page of results.
		 * Add all of the content to the $nav variable and in the end echo it. It will display a whole pagination bar at once.
		 */

		public function navigation() {
			if($this->totalPages > 1) {
				$nav = '<nav aria-label = "Page navigation">';
				$nav .= '<ul class = "pagination">';
				// FIRST and BACK link's. Show only if there is more than one page and you are not on the last one already.
				if($this->currentPage > 1) {
					$nav .= '<li class = "page-item"><a href = "'.paginationURI().'" class = "page-link">FIRST</a>';				
					$nav .= '<li class = "page-item"><a href = "'.paginationURI($this->currentPage - 1).'" class = "page-link">BACK</a>';
				}
				for($i = 1; $i <= $this->totalPages; $i++) {
					if($this->currentPage == $i) {
						$nav .= '<li class = "page-item active"><span class = "page-link">'.$i;
						$nav .= '<span class = "sr-only">(current)</span></span>';
					}
					else {
						$nav .= '<li class = "page-item"><a href = "'.paginationURI($i).'" class = "page-link">'.$i.'</a>';
					}
				}
				// NEXT and LAST link's. Show only if there is more than one page and you are not on the last one already.
				if($this->currentPage < $this->totalPages) {
					$nav .= '<li class = "page-item"><a href = "'.paginationURI($this->currentPage + 1).'" class = "page-link">NEXT</a>';
					$nav .= '<li class = "page-item"><a href = "'.paginationURI($this->totalPages).'" class = "page-link">LAST</a>';
				}
				//$nav .= '<li><select name = "perPage" class = "form-control ml-2"><option value = "10">10</option><option value = "25">25</option><option value = "50">50</option></select></li>';
				$nav .= '</ul></nav>';
				echo $nav;
			}
		}

		/**
		 * Reference to mysqlib class.
		 * @see mysqlib > where()
		 */		

		public function where($key, $value) {
			$this->db->where($key, $value);
		}
	}

?>