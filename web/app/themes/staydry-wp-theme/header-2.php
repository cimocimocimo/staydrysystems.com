<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage staydry-wp-theme
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php wp_title( '|', true, 'right' ) ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_directory' ); ?>/style-2.css" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_directory' ); ?>/style/fonts/open-sans/stylesheet.css" />
    <?php wp_head() ?>
    
    <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/style/img/favicon.png" />
    <script src="<?php bloginfo('stylesheet_directory'); ?>/style/js/slideshow.js" type="text/javascript"></script>
</head>
<body <?php body_class(); ?>>
<div id="wrapper">
<div id="non-footer">
	<div id="header">
		<div class="row section">
			<div id="logo">
				<a name="top" href="/" title="Home"><img src="<?php bloginfo('template_url'); ?>/style/img/staydry-logo-blue-header.png" alt="StayDry Systems" width="170" height="51" /></a>
			</div>
			<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */
			wp_nav_menu( array( 'container_id' => 'main-nav', 'theme_location' => 'primary' ) ); ?>			
			
			<div id="phone-header">
				<img src="<?php bloginfo('template_url'); ?>/style/img/order-phone-online-header.png" alt="Order online securely with PayPal, Visa, MasterCard, Discover, and American Express" height="51" width="190" />
			</div>
			<div id="cc-logos">
				<img src="<?php bloginfo('template_url'); ?>/style/img/paypal-visa-header.png" alt="Order online securely with PayPal, Visa, MasterCard, Discover, and American Express" height="51" width="94" />
			</div>
		</div><!-- .row -->
	</div><!-- #header -->
	
	<?php if ( ! is_home() ): ?>
	
	<div id="breadcrumbs">
		<div class="row section"><?php yoast_breadcrumb('',''); ?></div>
	</div>
	<?php endif; ?>