<?php
/**
 * @package WordPress
 * @subpackage StayDry WP Theme
 * 
 * This is for displaying slideshows on the single product pages
 * 
 */
 
 $imgs = get_children(
     array(
             post_parent => $post->ID,
             post_type => 'attachment',
             post_mime_type => 'image',
             orderby => 'menu_order',
             order => 'ASC',
         )
     );

if ($imgs):

    $imgs_keys = array_keys($imgs);

?>
<div id="gallery">
    <div class="box">
        <div id="main-img">
<?php

    if ($slide_index = $_GET['img'])
        $slide_id = $imgs[$imgs_keys[$slide_index]]->ID;
    else
        $slide_id = $imgs[$imgs_keys[0]]->ID;
    
    echo wp_get_attachment_image( $slide_id, 'product-slide');

?>
        </div>
    </div>
    <div id="thumbnail-row" class="row">
<?php

    $i = 0;
    foreach($imgs as $img_id => $img):

?>
        <div class="box column">
            <div class="thumb">
                <a href="?img=<?php echo $i; ?>" class="thumb-link" id="slide_<?php echo $i; ?>">
<?php

        echo wp_get_attachment_image( $img_id, 'product-thumb');
    
        $temp = wp_get_attachment_image_src($img_id, 'product-slide');
        $imgs_to_preload[$i] = $temp[0]

?>
                </a>
            </div>
        </div>
<?php

        $i++;
    endforeach;

?>
    </div>
</div><!-- #gallery -->
<script type="text/javascript">

// loads up the img paths collected while running the php loops to display the images                                                                                           
var slideUrls = jQuery.parseJSON( '<?php echo json_encode($imgs_to_preload); ?>' );

preload(slideUrls);

</script>
<?php

endif; // end if($imgs):

?>