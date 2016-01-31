<?php
/**
 * @package WordPress
 * @subpackage staydry-wp-theme
 */

get_header(); 

$content_blocks = new block_set($post->ID);
?>

<div id="how-it-works-header">
	<div class="content-full">
		<div class="img-heading-block">
		    <img src="<?php bloginfo('template_directory') ?>/style/img/how-the-staydry-system-works.png" width="620" height="39" alt="How The StayDry System Works" />
		</div>
	</div>
</div>


<div id="how-it-works-sealer" class="product-group">
    <div class="content-full">
        <div class="column third how-it-works-desc">
            <div class="how-it-works-sub-header">
                <img src="<?php bloginfo('template_directory') ?>/style/img/with-just-a-pinch.png" width="300" height="23" alt="With Just A Pinch" />
            </div>
        
            <p>Our StayDry Shower Kits consist of 3 main parts:</p>
            <ul>
                <li>Wall Channels</li>
                <li>Rubber Sealer Tubes</li>
                <li>StayDry Shower Curtain</li>
            </ul>
            <p>The rubbers tube fit into special sleeves on either side of the staydry shower curtain.  The tube and curtain fit into the wall channel and are pinched in place.</p>
            
        </div>
        <div class="column third">
        
            <div class="img-main"></div>
        
        </div>
        <div class="column third">
        
<?php
$args = array(
    'category_name' => 'products',
    'tag' => 'sealer',
);
$sealer_products = get_posts($args);
foreach ($sealer_products as $prod)
{
?>
        <div class="round-corners block product-block"> 
        	<div class="header-wrap"> 
        		<div class="header-content"></div><!-- .header-content --> 
        	</div><!-- .header-wrap --> 
        	<div class="body-wrap"> 
        		<div class="body-content">  
        		
                    <div class="prod-thumb-img column">
                        <?php echo get_the_post_thumbnail( $prod->ID, 'thumb-uncropped'); ?>
                    </div><!-- .column -->
                    <div class="column prod-block-desc">
                        <h4><?php echo $prod->post_title; ?></h4> 
                        <p class="product-sub-text">
                            <?php echo get_post_meta($prod->ID, 'product-subtext', true); ?>
                       
                        </p>
                    </div><!-- .column -->
                    
        		</div> <!-- .body-content --> 
        	</div><!-- .body-wrap -->
        	<a href="<?php echo get_permalink($prod->ID); ?>" class="block-link">View Product</a>
        </div><!-- .round-corners -->
<?php  
} // end foreach
?>   

        
        
        
        
        </div>
    </div>
</div>

<div id="water-dam-header">
	<div class="content-full">
		<div class="img-heading-block">
    		<img src="<?php bloginfo('template_directory') ?>/style/img/seal-the-bottom-of-your-curtain-too.png" width="717" height="33" alt="How The StayDry System Works" />
		</div>
	</div>
</div>


<div id="how-it-works-water-dam" class="product-group">
    <div class="content-full">
        <div class="column third how-it-works-desc">
            <div class="how-it-works-sub-header">
                <img src="<?php bloginfo('template_directory') ?>/style/img/controll-flooding-of-no-lip-stalls.png" width="300" height="64" alt="Controll Flooding of No-Lip Stalls" />
            </div>

        <p>Accessible shower stalls are easy to roll into but that also means it's easy for water to flow out of them too. Our Collapsible Water Dam stops water from flowing out of our curbless shower stall yet collapses down to only a quarter inch high when rolled over or stepped on.</p>
        
        
        </div>
        <div class="column third">
        
            <div class="img-main"></div>
        
        </div>
        <div class="column third">
        
<?php
$args = array(
    'category_name' => 'products',
    'tag' => 'dam, rings',
);
$sealer_products = get_posts($args);
foreach ($sealer_products as $prod)
{
?>
        <div class="round-corners block product-block"> 
        	<div class="header-wrap"> 
        		<div class="header-content"></div><!-- .header-content --> 
        	</div><!-- .header-wrap --> 
        	<div class="body-wrap"> 
        		<div class="body-content">  
        		
                    <div class="prod-thumb-img column">
                        <?php echo get_the_post_thumbnail( $prod->ID, 'thumb-uncropped'); ?>
                    </div><!-- .column -->
                    <div class="column prod-block-desc">
                        <h4><?php echo $prod->post_title; ?></h4> 
                        <p class="product-sub-text">
                            <?php echo get_post_meta($prod->ID, 'product-subtext', true); ?>
                       
                        </p>
                    </div><!-- .column -->
                    
        		</div> <!-- .body-content --> 
        	</div><!-- .body-wrap -->
        	<a href="<?php echo get_permalink($prod->ID); ?>" class="block-link">View Product</a>
        </div><!-- .round-corners -->
<?php  
} // end foreach
?>   


        
        
        </div>
    </div>
</div>




<?php

$content_blocks->print_rows();

 get_footer(); ?>
