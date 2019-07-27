<?php
	
	if (!defined ('LISAS_CMS')) die ('Hacking attempt!');
  
	define ('LISAS_FRAMEWORK_DIR', '../lisas-framework');
  
  require LISAS_FRAMEWORK_DIR.'/functions/first.php';
  require LISAS_FRAMEWORK_DIR.'/functions/functions.php';
  
	$_ENV = '';
	
	$_GET = lisas_secure_data ($_GET);
	$_POST = lisas_secure_data ($_POST, POST);
	
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
	$action = $_POST['action'];
	
?>