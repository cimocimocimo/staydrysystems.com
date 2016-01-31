<?php
/**
 * @package WordPress
 * @subpackage staydry-wp-theme
 */

$faq = get_posts(
                array(
                    'numberposts' => -1,
                    'category_name' => 'faq',
                    )
                );

echo '<ul id="faq-question-list">';
foreach ($faq as $post):

?>
	<li><a href="#<?php echo $post->post_name; ?>"><?php the_title(); ?></a></li>
<?php
    
endforeach;
echo '</ul>';

foreach ($faq as $post):

?>
<hr/>
<div id="post-<?php echo $post->ID; ?>" class="faq-post">
	<h3><a name="<?php echo $post->post_name; ?>"><?php the_title(); ?></a></h3>
	<p><?php echo $post->post_content; ?></p>
<?php

    $tags = get_the_tags($post->ID);
    if ($tags){
        echo '<p>Applies to: ';
        $numb_of_tags = count($tags);
        if ($numb_of_tags) {
            $i = 1;
            foreach ($tags as $tag)
            {
                echo '<a href="/products/' . $tag->slug . '">' . $tag->name . '</a>';
                if ($i < $numb_of_tags) {
                    echo ', ';
                }
                $i++;
            }
        }
        unset($tags);
        echo '</p>';
    }
    
?>
    <p><a href="#top">Back to top ^</a></p>
</div>
<?php
    
endforeach;

?>