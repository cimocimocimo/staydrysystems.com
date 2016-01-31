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

if ( !empty($imgs) ) :

?>
         <div id="slideshow-single">
             <div id="gallery">
<?php
 
    foreach ( $imgs as $img_id => $img ) :
        
        $slide_classes = 'slide';
        if ( $first_slide_rendered ) { // hide additional slides after the first for people w/o javascript
            $slide_classes .= ' hidden';
        }
        
?>
                <div class="<?php echo $slide_classes ?>">
                    <div class="vertical-center">
                    	<?php echo wp_get_attachment_image( $img_id, 'slideshow-large' ) ?>
                    </div>
<?php

            if ( $img->post_excerpt ) :

?>
                	<div class="slide-caption">
                	    <p><?php echo $img->post_excerpt ?></p>
                	</div>
<?php

            endif;

?>
                </div><!-- .<?php echo $slide_classes ?> -->
<?php

        $first_slide_rendered = true;
    endforeach;

?>
            </div><!-- #gallery -->
        </div><!-- #slideshow -->
<?php

endif; // !empty( $imgs )

?>

<div id="gallery">
    <div class="box">
        <div id="main-img">
            main gallery image
        </div>
    </div>
    <div id="thumbnail-row" class="row">
        <div class="box column">
            <div class="thumb">thumbnail</div>
        </div>
        <div class="box column">
            <div class="thumb">thumbnail</div>
        </div>
        <div class="box column">
            <div class="thumb">thumbnail</div>
        </div>
        <div class="box column">
            <div class="thumb">thumbnail</div>
        </div>
    </div>
</div><!-- #gallery -->
