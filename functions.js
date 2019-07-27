  $(function(){
    
    $('#mems-form').on ('submit', function (event) {
      
      event.preventDefault ();
      event.stopImmediatePropagation ();
      
      $(this).ajaxSubmit ({
        
        target: '#mem_img',
        url: 'mem_make_ajax.php',
        method: 'post',
        
        data: { action:'make', ajax:1, },
        
      });
      
      return false;
      
    });
    
  });
  
  function change_image () {
    
    var image = $('#image').val ();
    
    $.post ('mem_make_ajax.php', { image:image, action:'change_image', ajax:1, }, function (c) {
      $('#mem_img').html (c);
    });
    
  }