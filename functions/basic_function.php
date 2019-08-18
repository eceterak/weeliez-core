<?php

	/*****************
	STRING
	*****************/

	/**
	 * Display error 404 (not found) page.
	 */

	function error404() {
		exit(require('views/404.php'));
	}

	/**
	 * Display access forbidden page.
	 */

	function forbidden() {
		exit(require('views/forbidden.php'));
	}

	/**
	 * Display
	 */

	function verification() {
		exit(require('views/verification.php'));
	}


	/**
	 * More advanced variation of print_r function which makes reading the output of print_r a lot easier.
	 * It also distinct array values from any other values.
	 * @param $value [mix]
	 */

	function print_rr($value) {
		if(is_array($value)) {
			foreach($value as $val) {
				echo '<pre>';
				print_r($val);
				echo '</pre>';
			}
		}
		else {
			echo '<pre>';
			print_r($value);
			echo '</pre>';
		}
	}

	/**
	 * Small workaround of php's isset function.
	 * @param $key [var] 
	 */

	function iset($key) {
		if(isset($key)) {
			return $key;
		}
		else return '';
	}

	/**
	 * Crop content to the desirable lenght.
	 * @param $lenght [int]
	 * @return string
	 */

	function crop($content, $length = 20) {
		$contentLength = strlen($content);
		if($contentLength > $length) {
			$content = substr($content, '0', $length);
			$content .= '...';
		}
		return $content;
	}

	/**
	 * Get string and delete everything except alpha-numerical characters.
	 */

	function pregRep($string) {
		return $string = preg_replace("/[^a-zA-Z_. ()]+/", "", $string);
	}

	function back_link($method = NULL) {
		switch($method) {
			case 'bike':
				if(isset($_SERVER['HTTP_REFERER'])) {
					echo '<p><a href = "bikes.php">Back</a></p>';
				}
			break;
			case 'brand':
				if(isset($_SERVER['HTTP_REFERER'])) {
					echo '<p><a href = "brands.php">Back</a></p>';
				}
			break;
			default:
				if(isset($_SERVER['HTTP_REFERER'])) {
					echo '<p><a href = "'.$_SERVER['HTTP_REFERER'].'">Back</a></p>';
				}
			break;
		}
	}

	/**
	 * This function will prevent situation where item cont starts from 1 when navigating to the next page.
	 * It uses global perPage config.
	 * Ex. page=2, perPage = 10:
	 * 10 * (2-1) + 1 = 11.
	 */

	function itemNumeration() {
		if(isset($_GET['page']) && $_GET['page'] != 1) {
			$perPage = mysqlib::getConfig('perPage');
			$page = $_GET['page'];
			return $nextNumber = $perPage * ($page - 1) + 1;
		}
		else {
			return 1;
		}
	}

	/**
	 * To sort the html table, use MySQL's ORDER BY instead of JS.
	 * ORDER BY needs two parameters: name of the column to order by and order direction. These are set up in url as GET values.
	 * Sometimes default $direction might be different from 'ASC' because table is by default sorted in ASC order by this column.
	 * It will return a clickable link with name of $option. To change the name set $name propety.
	 * @param $option [string]
	 * @param $direction [string]
	 * @param $name [string]
	 * @return string
	 */

	function sortLink($option, $direction = 'asc', $name = '') {
		$data = getGetValues();
		$link = '?sort='.$option;
		if($name != '') {
			$option = $name; // Set link's name to a custom one.
		}
		if(isset($data->direction)) {
			if($data->direction == 'asc') {
				$direction = 'desc';
			}
			else {
				$direction = 'asc';
			}
		}
		$link .= '&direction='.$direction;
		echo '<a href = "'.$link.'">'.ucfirst($option).' <i class="fas fa-sort fa-sm" style = "padding-bottom: 1px;"></i></a>';
	}

	/*****************
	HELPER FUNCTIONS
	*****************/

	/**
	 * Show navigation buttons. Use paginationURI() to create link to a new page.
	 * Display only if there is more than one page of results.
	 * Add all of the content to the $nav variable and in the end echo it. It will display a whole pagination bar at once.
	 * @param $totalPages [int]
	 * @param $currentPage [int]
	 * @return string
	 */

	function pagination_nav($totalPages, $currentPage) {
		if($totalPages > 1) {
			$nav = '<nav aria-label = "Page navigation">';
			$nav .= '<ul class = "pagination">';
			// FIRST and BACK link's. Show only if there is more than one page and you are not on the last one already.
			if($currentPage > 1) {
				$nav .= '<li class = "page-item"><a href = "'.paginationURI().'" class = "page-link">FIRST</a>';				
				$nav .= '<li class = "page-item"><a href = "'.paginationURI($currentPage - 1).'" class = "page-link">BACK</a>';
			}
			for($i = 1; $i <= $totalPages; $i++) {
				if($currentPage == $i) {
					$nav .= '<li class = "page-item active"><span class = "page-link">'.$i;
					$nav .= '<span class = "sr-only">(current)</span></span>';
				}
				else {
					$nav .= '<li class = "page-item"><a href = "'.paginationURI($i).'" class = "page-link">'.$i.'</a>';
				}
			}
			// NEXT and LAST link's. Show only if there is more than one page and you are not on the last one already.
			if($currentPage < $totalPages) {
				$nav .= '<li class = "page-item"><a href = "'.paginationURI($currentPage + 1).'" class = "page-link">NEXT</a>';
				$nav .= '<li class = "page-item"><a href = "'.paginationURI($totalPages).'" class = "page-link">LAST</a>';
			}
			//$nav .= '<li><select name = "perPage" class = "form-control ml-2"><option value = "10">10</option><option value = "25">25</option><option value = "50">50</option></select></li>';
			$nav .= '</ul></nav>';
			return $nav;
		}
	}

	/**
	 * Create link for pagination. Get current url, if there is ?page set, get read it's value and update with $newPage.
	 * If $newPage is null, remove ?page from url (redirect to main catalog).
	 * @param $newPage [int] // null
	 * @return string
	 */

	function paginationURI($newPage = null) {
		$uri = $_SERVER['REQUEST_URI'];
		$data = getGetValues('page');
		$char = (!empty($data)) ? '&' : '?'; // If any $_GET values already set (like ?sort=).
		if(isset($_GET['page'])) {
			if(!is_null($newPage)) {
				$currentPage = substr($uri, -1, strpos($uri, $char.'page=')); // Get everything after ?page= (index -1 = start from the end).
				$uri = str_replace($char.'page='.$currentPage, $char.'page='.$newPage, $uri);
			}
			else {
				$uri = str_replace(substr($uri, strpos($uri, $char.'page=')), '', $uri); // Return clean url.
			}
		} 
		else {
			$uri .= $char.'page='.$newPage;
		}
	 	return $uri;
	}

	/**
	 * This function helps to redirect to right site after deleting an item.
	 * It uses static pagination countPages() method to check.
	 */

	function deleteRedirect2() {
		$uri = $_SERVER['HTTP_REFERER']; // Get the url of previous page.
		if($_GET['last']) {
			$lastPage = substr($uri, -1, strpos($uri, '?page='));
			if($lastPage > 2) {
				$newPage = $lastPage - 1;
				$uri = str_replace('?page='.$lastPage, '?page='.$newPage, $uri);
			}
			else {
				$uri = str_replace('?page='.$lastPage, '', $uri);
			}
		}
		exit(header('Location:'.$uri));
	}

	/**
	 * This function returns a url with updated '?page=' number which is later used by controller.
	 * It prevents redirection to an 'empty' page (one with no items).
	 * @todo clean up.
	 * @see controller_class.php @delete()
	 * @return string
	 */

	function deleteRedirect() {
		$uri = $_SERVER['HTTP_REFERER']; // Get the url of previous page.
		$queryString = $_SERVER['QUERY_STRING'];
		parse_str($queryString, $qs); // It will save result into $qs (an associative array).
		$pagination = new pagination($qs['controller']);
		if(strpos($uri, '?page=')) {
			$page = substr($uri, strpos($uri, '?page=') + strlen('?page=')); // Get a page number.
			$uri = str_replace('?page='.$page, '', $uri);
		} else {
			$page = 1;
		}
		$clear = substr($uri, strpos($uri, NAME) + (strlen(NAME) + 1));
		$request = explode('/', $clear);
		if(!in_array($qs['controller'], $request)) {
			$pagination->where($request[1].'_id', $request[3]); // Deleting from a parent directory (eg. bike when on brand page).
		}
		$totalPages = $pagination->countPages();
		if($page > $totalPages) {
			$page = $page - 1;
			if($page != 0) {
				$uri .= '?page='.$page;
			}
		}
		else {
			$uri = $_SERVER['HTTP_REFERER']; // Get the url of previous page.
		}
		return $uri;
	}

	/**
	 * Class: attribute_class
	 * Too long story to explain it here.
	 */

	function variable_to_name($variable) {
		$variable = strtolower($variable);
		$variable = str_replace(' ', '_', $variable); // Replace _ with space .
		return $variable;
	}

	/**
	 *
	 */

	function replaceSpaces($variable, $sign) {
		$variable = str_replace(' ', $sign, $variable); // Replace spaces with _ .
		return $variable;
	}

	/****************
	 * FORM FUNCTIONS
	 ****************/

	/**
	 * Get all post values and save them into array. Then cast array into object and return it.
	 * When dealing with multiple checkboxes $value is a an array.
	 * @return [bool/std Class object]
	 */

	function getPostValues() {
		if(!empty($_POST)) {
			foreach($_POST as $key => $value) {
				if(is_array($value)) {
					foreach($value as $val) {
						$postValues[$key][] = $val;
					}
				}
				else {
					$postValues[$key] = trim($value); // Trim value to remove white spaces.					
				}
			}
			return (object)$postValues; // Cast array into stdClass (required by load methods).
		}
		else {
			return false;
		}
	}

	/**
	 * Encode serialized form sent trough ajax request.
	 * @param $data [object]
	 * @return [stdClass object]
	 */

	function encode_serialize($data) {
		$values = [];
		for($i = 0; $i < count($data); $i++) {
			$values[$data[$i]['name']] = $data[$i]['value'];
		}
		return (object)$values;
	}

	/**
	 * Same as above but with $_GET values.
	 * This function is ignoring all pagination and MVC proporties.
	 * Also, if any arguments are passed into this function, they are gonna be ignored as well. 
	 * @return [bool/std Class object]
	 */

	function getGetValues() {
		$request = $_GET;
		unset($request['controller']);
		unset($request['action']);
		unset($request['id']);
		if(isset($request['page'])) {
			unset($request['page']);
		}
		foreach(func_get_args() as $arg) {
			if(isset($request[$arg])) {
				unset($request[$arg]);
			}
		}
		if(!empty($request)) {
			foreach($request as $key => $value) {
				if(is_array($value)) {
					foreach($value as $val) {
						$getValues[$key][] = $val;
					}
				}
				else {
					$getValues[$key] = trim($value); // Trim value to remove white spaces.
				}
			}
			return (object)$getValues; // Cast array into stdClass (required by load methods).
		}
		else {
			return false;
		}
	}

	/**
	 * Return single or multi string depending on $int.
	 * @param $int [int]
	 * @param $string_1 [string]
	 * @param $string_2 [string]
	 * @return string
	 */

	function multi_int($int, $singular, $multiple) {
		if(is_numeric($int)) {
			if($int == 1) {
				return $singular;
			}
			else {
				return $multiple;
			}
		}
	}


	/**
	 * Check if 
	 */

	function requiredField() {
		$params = func_get_args();
		foreach($params as $param) {
			if($param !== '') {
				continue;
			}
			else {
				return false;
			}
			return true;
		}
	}

	function check_txt($input) {
		$input = trim($input);
		$input = stripslashes($input);
		$input = htmlspecialchars($input);
		return $input;
	}

	/*****************
	UNITS FUNCTIONS
	*****************/

	/*
		@mmToInch, @hpToKw, @ccmToCubic, @torqueTokgfmAndftlbs
		Bcdiv function is used to show numbers in XXX.00 format. It divides first number by second number (here 1) and displaying 2 numbers after comma.
	*/

	function ffloat($value, $numbers = 2) {
		$value = bcdiv($value, 1, $numbers);
		return $value;
	}
	function mmToInch($mm) {
		$inches = bcdiv($mm/INCH, 1, 1);
		return $mm.' mm ('.$inches.' inches)';
	}
	function kgToPound($kg){
		$pounds = bcdiv($kg/POUND, 1, 1);
		return $kg.' kg ('.$pounds.' pounds)';
	}
	function ltrToGAll($ltr){
		$gallons = bcdiv($ltr/GALLON, 1, 2);
		return $ltr.' litres ('.$gallons.' gallons)';
	}
	function hpToKw($hp) {
		$kw = bcdiv($hp*kW, 1, 2);
		$hp = bcdiv($hp, 1, 2);
		return $hp." HP (".$kw." kW)";
	}
	function ccmToCubic($ccm) {
		$cubic = bcdiv($ccm*CUBIC, 1, 2);
		$ccm = bcdiv($ccm, 1, 2);
		return $ccm." ccm (".$cubic." cubic inches)";
	}
	function torqueTokgfmAndftlbs($torque) {
		$kgfm = bcdiv($torque*KGFM, 1, 2);
		$ftlbs = bcdiv($torque*FTLBS, 1, 2);
		$torque = bcdiv($torque, 1, 2);
		return $torque." Nm (".$kgfm." kgf-m or ".$ftlbs." ft.lbs)";
	}

?>