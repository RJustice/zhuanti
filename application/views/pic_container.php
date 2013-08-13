<style type="text/css" media="screen">
    /**
 * Grid container
 */
#show_picture {
list-style-type: none;
position: relative; /** Needed to ensure items are laid out relative to this container **/
margin: 0;
}

/**
 * Grid items
 */
#show_picture li {
width: 200px;
background-color: #ffffff;
border: 1px solid #dedede;
-moz-border-radius: 2px;
-webkit-border-radius: 2px;
border-radius: 2px;
display: none; /** Hide items initially to avoid a flicker effect **/
  cursor: pointer;
padding: 4px;
}

#show_picture li img {
display: block;
}

/**
 * Grid item text
 */
#show_picture li p {
color: #666;
font-size: 12px;
margin: 7px 0 0 7px;
}

/**
 * Some extra styles to randomize heights of grid items.
 */
#show_picture ali:nth-child(3n) {
height: 175px;
}

#show_picture ali:nth-child(4n-3) {
padding-bottom: 30px;
}

#show_picture ali:nth-child(5n) {
height: 250px;
}
</style>
<div class="row">
    <div class="span12">&nbsp;</div>
</div>
<div class="row">
    <ul id="show_picture" class="">
        
    </ul>
    <div id="loaderCircle" class="span6 offset3">
        <a href="#" class="btn btn-block btn-large disabled">加载中...</a>
    </div>
</div>