<?php
/*
Template Name: Free Sample Page

This is a custom template file that I developed for the Commercial Shower Curtain Page.

*/

get_header('2');

while (have_posts()):
    the_post();

?>
<div id="body">
	<div class="row section">
		<div class="box">
		    <h1><?php the_title(); ?></h1>
		</div>
	</div><!-- #row -->
	<div class="row section">	
		<div id="main" class="column">
            <div class="description box">
                <?php the_content(); ?>
                
                
            </div><!-- .description -->
		</div><!-- #main -->
		<div id="right" class="column">
<?php

    the_block('side_column');

?>
		</div><!-- #right -->
	</div><!-- #row -->
</div>
<?php

endwhile;

get_footer('2');

?>