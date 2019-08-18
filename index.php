<?php
	require('./inc.php');
	$bootstrap = new bootstrap($_GET);
	$tt = $bootstrap->createController();
	if($tt) {
		$tt->executeAction();
	}
?>