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
	<div class="content">

<?php get_sidebar( 'footer' ); ?>

	</div><!-- .content -->
	<div class="content">

		<div id="site-info">
			Copyright &copy; 2010
			<a href="<?php echo home_url( '/' ) ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a> - Keeps your bathroom Clean, Dry &amp; Safe
		</div><!-- #site-info -->

	</div><!-- .content -->
	
</div><!-- #footer -->
</div><!-- #footer-stick -->
<?php wp_footer(); ?>

<?php if (in_category('products')) { ?>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=staydrysystems"></script>
<?php } ?>

</body>
</html>
