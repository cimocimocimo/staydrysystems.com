<?php
/**
 * The template for fax promo page
 *
 * This is based on the page.php file. Used for displaying welcome info for people who arrive to the site
 * after receiving a fax from us.
 * 
 * @package WordPress
 * @subpackage staydry-wp-theme
 */

// load the data for the content blocks for this post into an object
$content_blocks = new block_set($post->ID);

get_header(); ?>

<div id="page-header">
	<div class="content-full">
		<div class="heading-block">
		    <h1><?php the_title(); ?></h1>
		</div>
		<h2>StayDry Stops Shower Leaks</h2>
	</div>
</div>

<div>
	<div class="content-full">
        <div class="column two-thirds">
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
    		<div id="post-<?php the_ID(); ?>" <?php post_class('landing-page-text'); ?>>
				<?php the_content(); ?>

    		</div><!-- #post-## -->
<?php endwhile; ?>
        </div>
        <div class="column third">
        
                <h3 class="side-header">Featured Products</h3>
    
                        <div class="round-corners block product-block"> 
                        	<div class="header-wrap"> 
                        		<div class="header-content"></div><!-- .header-content --> 
                        	</div><!-- .header-wrap --> 
                        	<div class="body-wrap"> 
                        		<div class="body-content">  
                        		
                                    <div class="prod-thumb-img column">
                                        <img width="92" height="109" src="http://www.staydrysystems.com/wp-content/uploads/2009/11/enviro-off-angle-processed-cropped-2-92x109.jpg" class="attachment-thumb-uncropped wp-post-image" alt="enviro-off-angle-processed-cropped-2" title="enviro-off-angle-processed-cropped-2">                            </div><!-- .column -->
                                    <div class="column prod-block-desc">
                                        <h4>Collapsible Water Dam</h4> 
                                        <p class="product-sub-text">
                                            For no-lip wheelchair accessible shower stalls                               
                                        </p>
                                    </div><!-- .column -->
                                    
                        		</div> <!-- .body-content --> 
                        	</div><!-- .body-wrap -->
                        	<a href="http://www.staydrysystems.com/products/enviro-shower-curtain-kit/" class="block-link">View Product</a>
                        </div><!-- .round-corners -->
                        
        
                    
                        <div class="round-corners block product-block"> 
                        	<div class="header-wrap"> 
                        		<div class="header-content"></div><!-- .header-content --> 
                        	</div><!-- .header-wrap --> 
                        	<div class="body-wrap"> 
                        		<div class="body-content">
                                    <div class="prod-thumb-img column">
                                        <img width="92" height="109" src="http://www.staydrysystems.com/wp-content/uploads/2009/11/deluxe-off-angle-processed-cropped-2-92x109.jpg" class="attachment-thumb-uncropped wp-post-image" alt="deluxe-off-angle-processed-cropped-2" title="deluxe-off-angle-processed-cropped-2">                            </div><!-- .column -->
                                    <div class="column prod-block-desc">
                                        <h4>SealTight Deluxe Shower Curtain</h4> 
                                        <p class="product-sub-text">
                                            Machine Washable &amp; Anti Microbial Treatment                               
                                        </p>
                                    </div><!-- .column -->
                        		</div> <!-- .body-content --> 
                        	</div><!-- .body-wrap -->
                        	<a href="http://www.staydrysystems.com/products/deluxe-shower-curtain-kit/" class="block-link">View Product</a>
                        </div><!-- .round-corners -->
                        <div class="round-corners block product-block"> 
                        	<div class="header-wrap"> 
                        		<div class="header-content"></div><!-- .header-content --> 
                        	</div><!-- .header-wrap --> 
                        	<div class="body-wrap"> 
                        		<div class="body-content">  
                        		
                                    <div class="prod-thumb-img column">
                                        <img width="92" height="109" src="http://www.staydrysystems.com/wp-content/uploads/2009/11/sealer-off-angle-processed-cropped-2-92x109.jpg" class="attachment-thumb-uncropped wp-post-image" alt="sealer-off-angle-processed-cropped-2" title="sealer-off-angle-processed-cropped-2">                            </div><!-- .column -->
                                    <div class="column prod-block-desc">
                                        <h4>Seal-It Curtain Sealer</h4> 
                                        <p class="product-sub-text">
                                            The simple solution to a leaky shower curtain                               
                                        </p>
                                    </div><!-- .column -->
                                    
                        		</div> <!-- .body-content --> 
                        	</div><!-- .body-wrap -->
                        	<a href="http://www.staydrysystems.com/products/shower-curtain-sealer/" class="block-link">View Product</a>
                        </div><!-- .round-corners -->
                        
                
        </div>
	</div><!-- #content -->
</div>


<?php

// print out the content blocks for the post or page
// $content_blocks->print_rows();

get_footer(); ?>
