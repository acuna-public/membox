<?php
	
	if (!defined ('LISAS_CMS')) die ('Hacking attempt!');
  
	require LISAS_FRAMEWORK_DIR.'/libraries/images.php';
  
	$error = array ();
	
	if ($_POST['action'] == 'make') {
		
    //if (not_empty ($_POST['text_top']) or not_empty ($_POST['text_bottom'])) {
      
      $data = array (
        
        'action' => $_POST['action'],
        'ajax' => intval ($_POST['ajax']),
        'text_top' => $_POST['text_top'],
        'text_bottom' => $_POST['text_bottom'],
        'font_size_top' => intval_correct ($_POST['font_size_top'], 34),
        'font_size_bottom' => intval_correct ($_POST['font_size_bottom'], 34),
        
      );
      
      if (not_empty ($_POST['image_url'])) { // Проверяем картинку
        
        $image = create_image ($_POST['image_url']);
        $height = imagesy ($image);
        
        if (!is_image ($_POST['image_url'])) $error[] = 'Картинка <b>'.basename ($_POST['image_url']).'</b> не картинка! :)';
        //if ($image and (imagesx ($image) != 450 or imagesy ($image) != 450)) $error[] = 'Картинка должна быть равной 450 пикселей в длину и ширину.';
        
        $data['image_url'] = $_POST['image_url'];
        imagedestroy ($image);
        
      } elseif (is_array ($_FILES['image_hdd']) and $file = $_FILES['image_hdd'] and !$file['error']) {
        
        $file_type = get_filetype ($file['name']);
        
        if (is_image ($file['tmp_name']) and exif_imagetype ($file['tmp_name']) and in_array ($file_type, $image_types)) { // Проверяем картинку
          
          if (is_uploaded_file ($file['tmp_name'])) {
            
            $_POST['image'] = do_rand (11, 2).'.'.$file_type;
            
            if (!move_uploaded_file ($file['tmp_name'], ROOT_DIR.'/files/'.$_POST['image']))
            $error[] = 'Загрузка файла невозможна.';
            
            $file = ROOT_DIR.'/files/'.$_POST['image'];
            $image = new Image ($file);
            
            if ($_POST['image_size']) {
              
              $image->resize ([0, $_POST['image_size']]);
              $image->save ($file);
              
            }
            
            $data['image_hdd'] = $_POST['image'];
            
          } else $error[] = 'Загрузка файла невозможна.';
          
        }
        
      } else {
        
        if ($file['error'])
          $error[] = $file['error'];
        elseif (!not_empty ($_POST['image']))
          $error[] = 'Выберите картинку!';
        
        $data['image'] = $_POST['image'];
        
      }
      
      if (!$error) { // Все нормально, будем генерить картинку для юзера
        
        set_cookie ('image', $_POST['image']);
        set_cookie ('image_url', $_POST['image_url']);
        
        $link_image = HOME_URL.'/mem_image.php?'.http_build_fquery ($data);
        
        $data['action'] = 'save';
        $link_save = HOME_URL.'/mem_image.php?'.http_build_fquery ($data);
        
        $content = link_image ($height, $link_image, $link_save).'<br/><br/>';
        
        if ($_POST['ajax'])
        $content = '<a href="#">'.$content.'</a>';
        else
        $content = '<div style="text-align:center;">'.$content.'</div>';
        
      } else $content = '<div style="padding:5px;">'.message ($error).'</div>';
      
    //}
		
	} elseif ($_POST['action'] == 'change_image') {
		
		$link_image = HOME_URL.'/mem_image.php?action='.$_POST['action'].'&image='.$_POST['image'];
		
		$content = link_image ($height, $link_image);
		
	} else die ('Неверное действие!');
	
	echo $content;