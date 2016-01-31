<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage staydry-wp-theme
 */

get_header(); ?>

<div id="page-header">
	<div class="content">
		<div class="heading-block"><h1 class="page-title"><?php echo single_cat_title( '', false ); ?></h1></div>
	</div>
</div>

<div id="container">
	<div class="content">
				
				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<div class="category-desc">' . $category_description . '</div>';

				get_template_part( 'loop', 'products' );
				?>
</div>	
</div>

<div class="content-block-row">
    <div class="content-full">
        <div class="column third">
            <h3>Bulk Ordering</h3>
            <p>If you are an installer or contractor our products are available in bulk at a discount. See the <a href="/bulk-orders/">bulk ordering</a> page for more details.</p>
        </div>
        <div class="column third">
            <h3>Commercial Clients</h3>
            <p>Learn how StayDry can keep your facilities safe and dry for workers and prevent water damage to your facilities. <a href="/commercial-ordering/">Commercial Clients</a>. 
        </div>
        <div class="column third">
            <h3>Replacement Components</h3>
            <p>Replacement parts are available for our kits. See our <a href="/replacement-parts/">components page</a> for more info.</p>
        </div>

    </div>
</div>

<?php get_footer(); ?>
