<?php

	/**
	 * @file breadcrumbs_class.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class breadcrumbs {

		/**
		 * Display the navigation/breadcrumbs.
		 */

		static public function navigation() {
			$request = $_GET;
			$breadcrumbs = '<span><small><a href = "'.ADMIN_URL.'">home</a> / ';
			if($request['action'] == 'edit') {
				$class = $request['controller'].'_';
				$item = $class::getById($request['id'], true);
				if(method_exists($item, 'breadcrumbs')) {
					$breadcrumbs .= $item->breadcrumbs();
				}
			}
			else {
				$breadcrumbs .= '<a href = "'.ADMIN_URL.'/'.$request['controller'].'">'.$request['controller'].'</a>';
			}
			$breadcrumbs .= '</small></span>';
			echo $breadcrumbs;
		}
	}

?>