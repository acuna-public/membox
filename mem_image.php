<?php
	
	@session_start ();
	ob_start ();
	@ob_implicit_flush ();
	
	define ('LISAS_CMS', true);
	define ('ROOT_DIR', dirname (__FILE__));
	
	require ROOT_DIR.'/init.php';
	
	//if (clean_url ($_SERVER['HTTP_HOST']) != clean_url ($_SERVER['HTTP_REFERER'])) die ('Hacking Attempt!');
	
	$font = ROOT_DIR.'/fonts/impact.ttf'; // Файл шрифта
	$thickness = 2; // Толщина обводки
	
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
  if (not_empty ($_GET['image']))
	$image_path = MEMS_DIR.'/'.$_GET['image'].'.'.$global['img_format'];
	elseif (not_empty ($_GET['image_url']))
	$image_path = url_decode ($_GET['image_url']);
	elseif (not_empty ($_GET['image_hdd']))
	$image_path = ROOT_DIR.'/files/'.$_GET['image_hdd'];
	
	$image = create_image (url_decode ($image_path));
	
	if ($_GET['action'] != 'change_image') {
		
		$text_color_c = imagecolorallocate ($image, 0, 0, 0); // Обводка
		$text_color = imagecolorallocate ($image, 255, 255, 255); // Текст
		$width = 450; $height = 450;
		$width2 = imagesx ($image); $height2 = imagesy ($image);
		
		$font_size_top = intval_correct ($_GET['font_size_top'], 34);
		$font_size_bottom = intval_correct ($_GET['font_size_bottom'], 34);
    
		$text_tops = prepare_text ($_GET['text_top'], $_GET['ajax']);
		$text_bottoms = prepare_text ($_GET['text_bottom'], $_GET['ajax']);
		
    $text_top_array = array ();
		$text_bottom_array = array ();
		
		$x = array ($thickness, 0, $thickness, 0, -$thickness, -$thickness, $thickness, 0, -$thickness); 
		$y = array (0, -$thickness, -$thickness, 0, 0, -$thickness, $thickness, $thickness, $thickness); 
		
    $i = 0;
    
		foreach ($text_tops as $text_top) { // Верх
			++$i;
      
			$text_top = virgin_text ($text_top);
			$box_top = imagettfbbox ($font_size_top, 0, $font, $text_top);
			
			$x_top = ceil (($width2 - $box_top[2]) / 2);
      
      $y_top = 30 + ($font_size_top * $i);
			
			for ($i2 = 0; $i2 <= 8; ++$i2)
			imagettftext ($image, $font_size_top, 0, $x_top + $x[$i2], $y_top + $y[$i2], $text_color_c, $font, $text_top); // Обводка
			
			imagettftext ($image, $font_size_top, 0, $x_top, $y_top, $text_color, $font, $text_top); // Текст
			
			$text_top_array[] = $text_top;
			
		}
		
    $i = 0;
    
    $bottom_count = intval_correct ((sizeof ($text_bottoms) - 1), 1);
    
    if (sizeof ($text_bottoms) == 1)
    $i3 = 0;
    else
    $i3 = $bottom_count;
    
    $pos = array ();
    
		foreach ($text_bottoms as $text_bottom) { // Низ
			++$i;
      
			$text_bottom = virgin_text ($text_bottom);
			$box_bottom = imagettfbbox ($font_size_bottom, 0, $font, $text_bottom);
			
			$x_bottom = ceil (($width2 - $box_bottom[2]) / 2);
      
      $y_bottom = $height2 - ($font_size_bottom * $i);
			
      $pos[$i3] = array ($x_bottom, $y_bottom);
			$text_bottom_array[] = $text_bottom;
			
      --$i3;
      
		}
    
    foreach ($text_bottom_array as $id => $text) {
      
			for ($i2 = 0; $i2 <= 8; ++$i2)
			imagettftext ($image, $font_size_bottom, 0, ($pos[$id][0] + $x[$i2]), ($pos[$id][1] + $y[$i2]), $text_color_c, $font, $text); // Обводка
			
			imagettftext ($image, $font_size_bottom, 0, $pos[$id][0], $pos[$id][1], $text_color, $font, $text); // Текст
			
    }
    
		// Ватермарк
    
    if (substr ($text_bottom_array[$bottom_count], -3) != 'bbb') {
      
      $rgb = imagecolorat ($image, ($width2 - 5), ($height2 - 5));
      
      $r = ($rgb >> 16) & 0xFF;
      $g = ($rgb >> 8) & 0xFF;
      $b = $rgb & 0xFF;
      
      $max = min ($r, $g, $b);
      $min = max ($r, $g, $b);
      $lightness = (double) (($max + $min) / 510.0);
      
      if ($lightness < 0.5) $color = $text_color; else $color = $text_color_c;
      
      if ($config['watermark'] and $config['watermark_type'] == 1 and $config['watermark_text'])
      imagestring ($image, 1, (($width2 - lisas_strlen ($config['watermark_text']) * 5) - 3), ($height2 - 10), $config['watermark_text'], $color);
      
    }
		
	}
	
	imagejpeg ($image);
	imagedestroy ($image);
  
  // Имя файла картинки
  
  if ($_GET['action'] == 'save') {
    
    $file_name = 'mem-'.alt_name (implode ('-', $text_top_array), 20).'-'.alt_name (implode ('-', $text_bottom_array), 20).'-'.$font_size_top.'-'.$font_size_bottom.'.'.$global['img_format'];
    
    file_header ($file_name);
    
    if (not_empty ($_GET['image_hdd']))
    @unlink ($image_path);
    
  }
  
?>