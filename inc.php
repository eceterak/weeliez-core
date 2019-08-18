<?php

	/**
	 * @file inc.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	/**
	 * Config file. Must be included first.
	 */

	require_once($_SERVER['DOCUMENT_ROOT'].'/config.php');

	/**
	 * Classes
	 */

	// MVC - bootstrap, controller, model, viewModel

	foreach(glob(BOOTSTRAP_CLASS_PATH.'/*.php') as $file) {
		require_once($file); // Front end
	}

	foreach(glob(ADMIN_BOOTSTRAP_CLASS_PATH.'/*.php') as $file) {
		require_once($file); // Admin
	}

	// mysqlib & dbObject

	foreach(glob(MYSQL_CLASS_PATH.'/*.php') as $file) {
		require_once($file);
	}

	// Structure

	foreach(glob(STRUCTURES_CLASS_PATH.'/*.php') as $file) {
		require_once($file);
	}

	// Utilities

	foreach(glob(UTILITIES_CLASS_PATH.'/*.php') as $file) {
		require_once($file);
	}

	// Bike classes

	foreach(glob(BIKE_CLASS_PATH.'/*.php') as $file) {
		require_once($file);
	}

	// Rest of classes

	foreach(glob(CLASS_PATH.'/*.php') as $file) {
		require_once($file); // Front end
	}

	foreach(glob(ADMIN_CLASS_PATH.'/*.php') as $file) {
		require_once($file); // Admin
	}

	/**
	 * Controllers
	 */

	foreach(glob(CONTROLLER_PATH.'/*.php') as $file) {
		require_once($file);
	}

	foreach(glob(ADMIN_CONTROLLER_PATH.'/*.php') as $file) {
		require_once($file);
	}

	/**
	 * Models
	 */

	foreach(glob(MODEL_PATH.'/*.php') as $file) {
		require_once($file);
	}

	foreach(glob(ADMIN_MODEL_PATH.'/*.php') as $file) {
		require_once($file);
	}

	/**
	 * Ajax
	 */

	foreach(glob(AJAX_PATH.'/*.php') as $file) {
		require_once($file);
	}

	/**
	 * Exceptions
	 */

	foreach(glob(EXCEPTION_PATH.'/*.php') as $file) {
		require_once($file);
	}

	/**
	 * Prototypes
	 */

	foreach(glob(PROTOTYPE_PATH.'/*.php') as $file) {
		require_once($file);
	}

	/** 
	 * Functions
	 */

	foreach(glob(FUNCTION_PATH.'/*.php') as $file) {
		require_once($file);
	}

?>