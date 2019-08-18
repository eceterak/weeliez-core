<?php
	require('../inc.php');

	$bootstrap = new admin\bootstrap($_GET);
	$tt = $bootstrap->createController();
	if($tt) {
		$tt->executeAction();
	}
?>