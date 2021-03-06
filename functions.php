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
	
	function prepare_text ($text) {
		return explode ('<br />', nl2br (lisas_ucfirst (secure_text (url_decode ($text)))));
	}
	
	function secure_text ($str) {
		
		$str = trim (preg_replace ('~ +~', ' ', $str));
		//if ($ajax) $str = to_unicode ($str);
		
		return stripslashes ($str);
		
	}
	
	function virgin_text ($text) {
		return str_replace (array ('\"', "\'"), array ('"', "'"), trim ($text));
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
  
  function image_text2 ($image, string $text, string $font, int $font_size_top, int $thickness, int $pos = 1) {
    
    $text_top_array = [];
		
		$text_color = imagecolorallocate ($image, 255, 255, 255); // Текст
		$text_color_c = imagecolorallocate ($image, 0, 0, 0); // Обводка
    
		//$width2 = 450; $height2 = 450;
		$width2 = imagesx ($image);
    $height2 = imagesy ($image);
		
		$font_size_top = intval_correct ($font_size_top, 34);
    
		$x = array ($thickness, 0, $thickness, 0, -$thickness, -$thickness, $thickness, 0, -$thickness); 
		$y = array (0, -$thickness, -$thickness, 0, 0, -$thickness, $thickness, $thickness, $thickness); 
		
    $i = 0;
    
    $texts = prepare_text ($text);
    $texts_count = count ($texts);
    
		foreach ($texts as $text) { // Верх
			
			$text = virgin_text ($text);
			$box = imagettfbbox ($font_size_top, 0, $font, $text);
			
			$x_top = ceil (($width2 - $box[2]) / 2);
      
      if ($pos == 1)
        $y_top = (abs ($box[5] - $box[1] - 15) * ($i + 1));
			elseif ($pos == 2)
        $y_top = $height2 - ((abs ($box[5] - $box[1]) + 10) * ($texts_count - ($i + 1))) - 25; // 450 - (40 * 2)
      
			for ($i2 = 0; $i2 <= 8; ++$i2)
        imagettftext ($image, $font_size_top, 0, $x_top + $x[$i2], $y_top + $y[$i2], $text_color_c, $font, $text); // Обводка
			
			imagettftext ($image, $font_size_top, 0, $x_top, $y_top, $text_color, $font, $text); // Текст
			
			$text_top_array[] = $text;
			++$i;
      
		}
    
    return $text_top_array;
		
  }