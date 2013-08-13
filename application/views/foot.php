</div>
<div class="row">
    <div class="span12">&nbsp;<br>&nbsp;<br>&nbsp;<br>&nbsp;</div>
</div>
<script src="http://code.jquery.com/jquery.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url('asset/js/bootstrap.min.js'); ?>" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo base_url('asset/js/jquery.wookmark.min.js'); ?>" type="text/javascript" charset="utf-8"></script>
<script>
    var handler = null;
    var page = 1;
    var last_id = 0;
    var isLoading = false;
    var apiURL = '<?php echo site_url("show/more");?>'
    var t ;
    // Prepare layout options.
    var options = {
      autoResize: true, // This will auto-update the layout when the browser window is resized.
      container: $('#show_picture'), // Optional, used for some extra CSS styling
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
      handler = $('#show_picture li');
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
        html += '<div class="media">';
            html += '<a class="pull-left" href="'+image.weibo+'" target="_blank">';
                html += '<img class="media-object img-circle" src="'+image.avatar+'" width="50" height="50" alt="'+image.nickname+'" title="'+image.nickname+'" style="border:1px solid">';
            html += '</a>';
            html += '<div class="media-body">';
            //html += '<h4 class="media-heading">Media heading</h4>';
                html += '<div class="popover right" style="display:block;position:relative;">';
                    html += '<div class="arrow"></div>';
                    html += '<div class="popover-content"><p>'+image.desc+'</p></div>';
                html += '</div>';
            html += '</div>';
        html += '</div>';

        html += '</li>';
        last_id = image.id;
      }
      // Add image HTML to the page.
      $('#show_picture').append(html);
      
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