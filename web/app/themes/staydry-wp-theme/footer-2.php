<?php

/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage staydry-wp-theme
 */

?>
</div><!-- #non-footer -->
</div><!-- #wrapper -->
<div id="footer-stick">
<div id="footer">
	<div class="row section">
<?php

get_sidebar( 'footer' );

?>
	</div><!-- .row -->
	<div class="row section">
		<div id="site-info">
			Copyright &copy; 2010
			<a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a> - Keeps your bathroom Clean, Dry &amp; Safe
		</div><!-- #site-info -->
	</div><!-- .row -->
</div><!-- #footer -->
</div><!-- #footer-stick -->
<?php

wp_footer();

?>
</body>
</html>
