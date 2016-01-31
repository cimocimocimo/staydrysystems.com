<?php
/**
 * @package WordPress
 * @subpackage staydry-wp-theme
 */

get_header(); 

$content_blocks = new block_set($post->ID);
?>

<div>
	<div class="content-full">
		<div class="heading-block">
		    <h1><?php the_title(); ?></h1>
		</div>
	</div>
</div>


<div id="replacement-components-order-form">
    <div class="content-full">

        
<div class="round-corners block">
   	<div class="header-wrap">
   		<div class="header-content">
   
   			<h3>Order Replacement Componets</h3>
   
   		</div><!-- .header-content -->
   	</div><!-- .header-wrap -->
   	<div class="body-wrap">
   		<div class="body-content">

<table border="0" cellpadding="0" cellspacing="0">
<?php
$args = array(
    'category_name' => 'product-components',
);
$product_components = get_posts($args);

cpo_multi_product_form($product_components);

?>
</table>

     		</div> <!-- .body-content -->
     	</div><!-- .body-wrap -->
     </div><!-- .order-form -->
     
      
    </div>
</div>


<?php

$content_blocks->print_rows();

 get_footer(); ?>
