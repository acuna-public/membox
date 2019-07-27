<?php
	
	@session_start ();
	ob_start ();
	@ob_implicit_flush ();
	
	@error_reporting (E_ALL ^ E_WARNING ^ E_NOTICE);
	
	@ini_set ('display_errors', true);
	@ini_set ('html_errors', false);
	@ini_set ('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);
	
	define ('LISAS_CMS', true);
	define ('ROOT_DIR', dirname (__FILE__));
	
	require ROOT_DIR.'/init.php';
	
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	if ($action != 'make') {
		
    //foreach (scandir (MEMS_DIR) as $file) echo $file.'<br/>';
    
		require ROOT_DIR.'/modules/'.$global['mod'].'.php';
		
		$headers = '';
		$scripts = '';
		
		$metas = array (
			
			'description' => $config['meta_descr'],
			'keywords' => $config['meta_keywords'],
			'revisit-after' => intval_correct ($config['meta_revisit'], 1).' days',
			
		);
		
		$navigation = array ();
		$navigation = implode (' - ', $nav);
		
		$site_title = array ();
		if ($navigation) $site_title[] = $navigation;
		$site_title[] = $config['site_title'];
		
		$site_title = implode (' - ', $site_title);
		
		$headers .= '<title>'.$site_title.'</title>

    <meta http-equiv="Content-Type" content="text/html; charset='.$config['charset'].'"/>';
		
		foreach ($metas as $name => $value)
		$headers .= '
    <meta name="'.$name.'" content="'.$value.'"/>';
		
		$headers .= NL.NL;
		
		$js_var = array (
			
			'root_dir' => HOME_URL,
			
		);
		
		$scripts .= 'js_var = { ';
		foreach ($js_var as $js_name => $js_value)
		$scripts .= '\''.$js_name.'\':\''.$js_value.'\', ';
		$scripts = trim ($scripts, ',');
		$scripts .= ' };';
		
		$headers .= echo_js ($scripts);
		
		$content = file_get_contents (ROOT_DIR.'/main.htm');
		
		$content = str_replace ('{headers}', $headers, $content);
		
		$content = str_replace ('{content}', $mod_content, $content);
		
		$content = str_replace ('{link_home}', HOME_URL, $content);
		$content = str_replace ('{THEME}', HOME_URL, $content);
		$content = str_replace ('{year}', date ('Y'), $content);
		
		echo $content;
		
    foreach (scandir (ROOT_DIR.'/files') as $file)
    @unlink (ROOT_DIR.'/files/'.$file);
    
	} else require ROOT_DIR.'/mem_make.php';
	
?>