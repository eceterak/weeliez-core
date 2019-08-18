<?php

	namespace admin\controllers;
	
	/**
	 * @file attribute_controller.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	class attribute extends \admin\controller {

		/**
		 * Move attribute to change it's priority.
		 */

		public function priority() {
			if($this->id) {
				try {
					$move = $this->model->priority($this->id);
					if($move) {
						$this->session->add('message', 'Item moved.');
					}
				} catch(\exception $e) {
					$this->session->add('message', $e->getMessage());
				}
			}
			exit(header('Location: '.$_SERVER['HTTP_REFERER']));
		}
	}

?>