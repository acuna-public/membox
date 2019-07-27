<?php
	
	if (!defined ('LISAS_CMS')) die ('Hacking attempt!');
	
	function selector ($array, $is_selected = 0, $key_is_value = 0, $first = 0, $first_name = '', $onchange = '') {
		
		$sel = '';
    
    if (!$first_name) $first_name = '&nbsp;';
    
		if ($first) $sel .= '
                <option value="">'.$first_name.'</option>';
		
		foreach ($array as $key => $value) {
      
			$selected = '';
			
			if ($key_is_value) $key = $value;
			
			if ($is_selected and $key == $is_selected) $selected .= ' selected="selected"';
			
			$sel .= '
                <option value="'.$key.'"'.$selected.'>'.$value.'</option>';
			
		}
		
		return $sel;
		
	}
	
	function prepare_text ($text, $type) {
		return explode ('<br />', nl2br (lisas_ucfirst (secure_text (url_decode ($text), $type))));
	}
	
	function secure_text ($str, $ajax = 1) {
		
		$str = trim (str_replace (array ('  '), array (' '), $str));
		//if ($ajax) $str = to_unicode ($str);
		
		return stripslashes ($str);
		
	}
	
	function virgin_text ($text) {
		return str_replace (array ('\"', "\'"), array ('"', "'"), $text);
	}
	
	function show_image ($file, $image) {
		
		$type = get_filetype ($file);
		
		if ($type == 'jpg' or $type == 'jpeg' or $type == 'jpe') {
			
			@header ('Content-type: image/jpeg');
			imagejpeg ($image);
			
		} elseif ($type == 'png') {
			
			@header ('Content-type: image/png');
			imagepng ($image);
			
		} elseif ($type == 'gif') {
			
			@header ('Content-type: image/gif');
			imagegif ($image);
			
		} else die ($file.' is unknown image type!');
		
	}
	
	function link_image ($height, $link_image, $link_save = '') {
		
		$height = intval ($height);
		$style = '';
		if ($height > 450) $style = 'height:450px;';
		
    if ($link_save)
		$output = '<a href="'.$link_save.'"><img alt="" src="'.$link_image.'" style="'.$style.'" title="Щелкните по картинке, чтобы сохранить ее"/></a>';
		else
    $output = '<img alt="" src="'.$link_image.'" style="'.$style.'"/>';
    
    return $output;
    
	}
	
	function is_image_type ($file) {
		global $image_types;
		
		$type = get_filetype ($file);
		if (in_array ($type, $image_types)) return $type; else return false;
		
	}
	
	function this_link ($name) {
		global $config;
		
		$link_htaccess = '';
		$link_no_htaccess = '';
		
		$link_htaccess = HOME_URL.'/'.$name;
		$link_no_htaccess = '';
		
		if ($config['alt_url']) $link = $link_htaccess; else $link = $link_no_htaccess;
		
		return $link;
		
	}
	
	function message ($mess) {
		if (is_array ($mess)) $mess = mess2br ($mess);
		return $mess;
	}
	
?>