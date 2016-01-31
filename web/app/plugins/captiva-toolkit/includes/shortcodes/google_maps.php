<?php
// [map]
function cap_map_sc($atts, $content=null, $code) {

    $mapsrandomid = rand();
	extract(shortcode_atts(array(
		'lat'  => '',
    'long' => '',
    'height' => '380px',
    'zoom' => '17'
	), $atts));
	ob_start();
	?> 
    
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript">
    
    function initialize() {
        var styles = {
            'captiva':  [{
            "featureType": "administrative",
            "stylers": [
              { "visibility": "on" }
            ]
          },
          {
            "featureType": "road",
            "stylers": [
              { "visibility": "on" },
            ]
          },
          {
            "stylers": [
			  { "visibility": "on" },            ]
          }
        ]};
        
        var myLatlng = new google.maps.LatLng(<?php echo $lat ?>, <?php echo $long ?>);
        var myOptions = {
            zoom: <?php echo $zoom ?>,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true,
            mapTypeId: 'captiva',
            draggable: true,
            zoomControl: false,
      			panControl: false,
      			mapTypeControl: false,
      			scaleControl: false,
      			streetViewControl: false,
      			overviewMapControl: false,
            scrollwheel: false,
            disableDoubleClickZoom: false
        }
        var map = new google.maps.Map(document.getElementById("<?php echo $mapsrandomid; ?>"), myOptions);
        var styledMapType = new google.maps.StyledMapType(styles['captiva'], {name: 'captiva'});
        map.mapTypes.set('captiva', styledMapType);
        
        var marker = new google.maps.Marker({
            position: myLatlng, 
            map: map,
            title:""
        });   
    }
    
    google.maps.event.addDomListener(window, 'load', initialize);
    google.maps.event.addDomListener(window, 'resize', initialize);
    
    </script>
      <div id="cap-map-container">
          <div id="<?php echo $mapsrandomid; ?>" style="height:<?php echo $height ?>;"></div>
          <div id="cap-map-overlay-top"></div>
          <div id="cap_map_overlay_bottom"></div>
           <?php if($content) {?>
           <div class="cap-map-info">
              <div class="row">
              <div class="col-lg-4 col-md-4">
                  <div class="map_inner">
                  <?php echo do_shortcode($content); ?>
                </div> <!-- map_inner -->
              </div><!-- large-4 -->
               </div><!-- row -->
          </div><!-- .map-info -->
         <?php }?>
      </div>


	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode('captiva_map', 'cap_map_sc');
