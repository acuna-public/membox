<?php
	
	@session_start ();
	ob_start ();
	@ob_implicit_flush ();
	
	define ('LISAS_CMS', true);
	define ('ROOT_DIR', dirname (__FILE__));
	
	require ROOT_DIR.'/init.php';
	require LISAS_FRAMEWORK_DIR.'/libraries/images.php';
  
	//if (clean_url ($_SERVER['HTTP_HOST']) != clean_url ($_SERVER['HTTP_REFERER'])) die ('Hacking Attempt!');
	
	$font = ROOT_DIR.'/fonts/impact.ttf'; // Файл шрифта
	$thickness = 2; // Толщина обводки
	
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
  if (not_empty ($_GET['image']))
	$image_path = MEMS_DIR.'/'.url_decode ($_GET['image']).'.'.$global['img_format'];
	elseif (not_empty ($_GET['image_url']))
	$image_path = url_decode ($_GET['image_url']);
	elseif (not_empty ($_GET['image_hdd']))
	$image_path = ROOT_DIR.'/files/'.$_GET['image_hdd'];
	
	$image = create_image ($image_path);
	
	if ($_GET['action'] != 'change_image') {
		
		$text_top_array = image_text2 ($image, $_GET['text_top'], $font, $_GET['font_size_top'], $thickness);
		$text_bottom_array = image_text2 ($image, $_GET['text_bottom'], $font, $_GET['font_size_bottom'], $thickness, 2);
		
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