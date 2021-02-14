  $(function () {
    
    $('#mems-form').on ('submit', function (event) {
      
      event.preventDefault ();
      event.stopImmediatePropagation ();
      
      var data = new FormData ();
      
      data.append ('action', 'make');
      data.append ('ajax', 1);
      
      $.each (['image', 'image_size', 'text_top', 'text_bottom', 'font_size_top', 'font_size_bottom'], function (index, value) {
        data.append (value, $('#' + value).val ());
      });
      
      data.append ('image_hdd', $('#image_hdd')[0].files[0]);
      
      $.ajax({
        
        url: 'mem_make_ajax.php',
        cache: false,
        contentType: false,
        processData: false,
        data: data,
        type: 'post',
        success: function (c) {
          $('#mem_img').html (c);
        }
        
      });
      
      return false;
      
    });
    
  });
  
  function change_image () {
    
    $.post ('mem_make_ajax.php', { image:$('#image').val (), action:'change_image', ajax:1, }, function (c) {
      $('#mem_img').html (c);
    });
    
  }