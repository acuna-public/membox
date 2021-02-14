<?php
	
	if (!defined ('LISAS_CMS')) die ('Hacking attempt!');
  
	define ('LISAS_FRAMEWORK_DIR', '../lisas-framework');
  
  require LISAS_FRAMEWORK_DIR.'/libraries/debug.php';
  require LISAS_FRAMEWORK_DIR.'/libraries/arrays.php';
  require LISAS_FRAMEWORK_DIR.'/libraries/strings.php';
  require LISAS_FRAMEWORK_DIR.'/libraries/network.php';
  require LISAS_FRAMEWORK_DIR.'/libraries/filesystem.php';
  require LISAS_FRAMEWORK_DIR.'/libraries/security.php';
  require LISAS_FRAMEWORK_DIR.'/libraries/core.php';
  require LISAS_FRAMEWORK_DIR.'/libraries/math.php';
  require LISAS_FRAMEWORK_DIR.'/libraries/outputs.php';
  require LISAS_FRAMEWORK_DIR.'/libraries/unicode.php';
  
	$_ENV = '';
	
	$_GET = secure_request ($_GET);
	$_POST = secure_request ($_POST, POST);
	
	require ROOT_DIR.'/config.php';
	
	define ('HOME_URL', home_url ($config['site_url']));
	define ('MEMS_DIR', ROOT_DIR.'/images/mems');
	
	require ROOT_DIR.'/mems_array.php';
	
	$global = array (
		
		'mod' => $_GET['mod'],
		'name' => $_GET['name'],
		'img_format' => 'jpg',
		
	);
	
	if (!$global['mod']) $global['mod'] = 'membox';
	
	$nav = array ();
	
	require ROOT_DIR.'/functions.php';
	
	$image_types = array ('jpg', 'jpeg', 'jpe', 'png', 'gif'); // Картинки
	if (isset ($_POST['action'])) $action = $_POST['action']; else $action = '';