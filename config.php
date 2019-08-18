<?php

	/**
	 * @file config.php
	 * @author Marek Bartula <bartula.marek@gmail.com>
	 */

	/**
	 * Website name
	 */ 

	//DEFINE("NAME", "weeliez.com", true);
	DEFINE("NAME", "whelzie.local", true);

	/**
	 * Database connection details. DO NOT UPDATE.
	 */ 

	DEFINE("SERVER", "localhost", true);
	DEFINE("DB", "weeliez", true);
	DEFINE("USER", "root", true);
	DEFINE("PASS", "marro2", true);

	
	/*DEFINE("SERVER", "localhost", true);
	DEFINE("DB", "weeliez", true);
	DEFINE("USER", "ecetera", true);
	DEFINE("PASS", "marro2A500#@", true);*/
	

	/**
	 * Root folder.
	 */ 

	DEFINE("ROOT_PATH", $_SERVER["DOCUMENT_ROOT"], true); // Front end.
	DEFINE("ADMIN_ROOT_PATH", $_SERVER["DOCUMENT_ROOT"]."/admin", true); // Back end.

	/**
	 * Site url's.
	 */ 

	DEFINE("SITE_URL", '/'.NAME, true); // Front end.
	DEFINE("ADMIN_URL", '/admin', true); // Back end.

	/**
	 * Classes
	 */

	// Admin.

	DEFINE("ADMIN_CLASS_PATH", ADMIN_ROOT_PATH."/classes", true);
	DEFINE("ADMIN_BOOTSTRAP_CLASS_PATH", ADMIN_CLASS_PATH."/bootstrap", true);

	// Front end.

	DEFINE("CLASS_PATH", ROOT_PATH."/classes", true);
	DEFINE("MYSQL_CLASS_PATH", CLASS_PATH."/mysql", true);
	DEFINE("STRUCTURES_CLASS_PATH", CLASS_PATH."/structures", true);
	DEFINE("BOOTSTRAP_CLASS_PATH", CLASS_PATH."/bootstrap", true);
	DEFINE("UTILITIES_CLASS_PATH", CLASS_PATH."/utilities", true);
	DEFINE("BIKE_CLASS_PATH", CLASS_PATH."/bike", true);

	/**
	 * MVC
	 */ 

	// Admin

	DEFINE("ADMIN_MODEL_PATH", ADMIN_ROOT_PATH."/models", true);
	DEFINE("ADMIN_CONTROLLER_PATH", ADMIN_ROOT_PATH."/controllers", true);

	// Front end.

	DEFINE("MODEL_PATH", ROOT_PATH."/models", true);
	DEFINE("CONTROLLER_PATH", ROOT_PATH."/controllers", true);

	/**
	 * Utilities
	 */ 

	DEFINE("FUNCTION_PATH", ROOT_PATH."/functions", true);
	DEFINE("JS_PATH", "/utilities/js", true);
	DEFINE("CSS_PATH", "/utilities/css", true);
	DEFINE("UPLOAD_PATH", ROOT_PATH."/upload/", true);
	DEFINE("AJAX_PATH", ROOT_PATH."/ajax/", true);
	DEFINE("EXCEPTION_PATH", ROOT_PATH."/exceptions/", true);
	DEFINE("PROTOTYPE_PATH", ROOT_PATH."/prototypes/", true);

	// Admin

	DEFINE("ADMIN_JS_PATH", "/utilities/js/admin", true);

	/**
	 * Tests.
	 */ 

	DEFINE("TEST", "I'm here!", true);
	DEFINE("ELSE", "Here I am!", true);

?>