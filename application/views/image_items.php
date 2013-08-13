<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>七夕</title>
    <script src="http://code.jquery.com/jquery-1.7.1.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo base_url('/asset/js/jquery.wookmark.min.js');?>" type="text/javascript" charset="utf-8"></script>
    <link rel="stylesheet" href="<?php echo base_url('/asset/css/reset.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('/asset/css/style.css'); ?>">
</head>
<body>
    <div id="main" role="main">
        <ul id="tiles"></ul>
        <div id="loader">
            <div id="loaderCircle"></div>
        </div>
    </div>
    <script type="text/javascript">
    var handler = null;
    var page = 1;
    var last_id = 0;
    var isLoading = false;
    var apiURL = '<?php echo site_url("show/more");?>'
    var t ;
    // Prepare layout options.
    var options = {
      autoResize: true, // This will auto-update the layout when the browser window is resized.
      container: $('#tiles'), // Optional, used for some extra CSS styling
      offset: 12, // Optional, the distance between grid items
      itemWidth: 210 // Optional, the width of a grid item
    };
    
    /**
     * When scrolled all the way to the bottom, add more tiles.
     */
    function onScroll(event) {
      // Only check when we're not still waiting for data.
      if(!isLoading) {
        // Check if we're within 100 pixels of the bottom edge of the broser window.
        var closeToBottom = ($(window).scrollTop() + $(window).height() > $(document).height() - 100);
        if(closeToBottom) {
          clearTimeout(t);
          t=setTimeout('loadData()',500);
        }
      }
    };
    
    /**
     * Refreshes the layout.
     */
    function applyLayout() {
      // Clear our previous layout handler.
      if(handler) handler.wookmarkClear();
      
      // Create a new layout handler.
      handler = $('#tiles li');
      handler.wookmark(options);
    };
    
    /**
     * Loads data from the API.
     */
    function loadData() {
      isLoading = true;
      $('#loaderCircle').show();
      
      $.ajax({
        url: apiURL,
        dataType: 'json',
        data: {page: page,last_id:last_id}, // Page parameter to make sure we load new data
        type:'GET',
        success: onLoadData
      });
    };
    
    /**
     * Receives data from the API, creates HTML for images and updates the layout
     */
    function onLoadData(data) {
      isLoading = false;
      $('#loaderCircle').hide();
      
      // Increment page index for future calls.
      page++;
      
      // Create HTML for the images.
      var html = '';
      
      //alert(last_id);
      if(data.end === 1){
        isLoading = true;
        return false;
      }

      var i=0, length=data.num, image;
      for(; i<length; i++) {
        image = data.images[i];
        
        html += '<li>';
        
        // Image tag (preview in Wookmark are 200px wide, so we calculate the height based on that).
        //html += '<img src="'+image.preview+'" width="200" height="'+Math.round(image.height/image.width*200)+'">';
        html += '<img src="'+image.thumb+'" width="200" height="'+Math.round(image.height/image.width*200)+'">';

        // Image title.
        html += '<p>'+image.nickname+'</p>';
        
        html += '</li>';
        last_id = image.id;
      }
      // Add image HTML to the page.
      $('#tiles').append(html);
      
      // Apply layout.
      applyLayout();
    };
  
    $(document).ready(new function() {
      // Capture scroll event.
      $(document).bind('scroll', onScroll);
      
      // Load first data from the API.
      loadData();
    });
  </script>
</body>
</html>