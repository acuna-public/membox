<?php
	
	@session_start ();
	ob_start ();
	@ob_implicit_flush ();
	
	define ('LISAS_CMS', true);
	define ('ROOT_DIR', dirname (__FILE__));
	
	require ROOT_DIR.'/init.php';
	
	if (get_domain ($_SERVER['HTTP_HOST']) != get_domain ($_SERVER['HTTP_REFERER'])) die ('Hacking Attempt!');
	
	require ROOT_DIR.'/mem_make.php';