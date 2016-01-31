<?php
/**
 * @package WordPress
 * @subpackage staydry-wp-theme
 */
 
$args = array(
 'category_name' => 'products'
);
$products = get_posts($args);
 
?>
<h3>Our Products</h3>

<?php
foreach ($products as $prod)
{
    echo '<h4><a href="' . get_permalink($prod->ID) . '">' . $prod->post_title . "</a></h4>\n";
}