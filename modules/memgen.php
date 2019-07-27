<?php
	
	if (!defined ('LISAS_CMS')) die ('Hacking attempt!');
	
	$mod_content = file_get_content (ROOT_DIR.'/memgen.htm');
	
	$mems = '';
	$mems_list = array ();
  
	foreach ($mems_array as $name => $title)
  if (file_exists (MEMS_DIR.'/'.$name.'.jpg'))
	$mems_list[$name] = $title;
  
	$mod_content = str_replace ('{images}', selector ($mems_list, 0, 0, 1), $mod_content);
	$mod_content = str_replace ('{image_url}', '', $mod_content);
	$mod_content = str_replace ('{mems_list}', implode (', ', $mems_list), $mod_content);
	
	$mod_content = str_replace ('[mem_not_selected]', '', $mod_content);
	$mod_content = str_replace ('[/mem_not_selected]', '', $mod_content);
	
	$mod_content = preg_replace ('#\[mem_selected\](.*?)\[/mem_selected\]#si', '', $mod_content);
	
	$mod_content = str_replace ('{image_selected}', '', $mod_content);
	
	$fonts_sizes = array (24, 26, 28, 30, 32, 34, 38, 46);
	$mod_content = str_replace ('{font_sizes}', selector ($fonts_sizes, 34, 1), $mod_content);
  
	$mod_content = str_replace ('{speedbar}', 'MemBox - Генератор мемов', $mod_content);
  
?>