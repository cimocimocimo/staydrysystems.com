<?php
/**
 * Atom Feed Template for displaying Atom Posts feed.
 *
 * This has been adapted to create a list of products for inclution into Google Merchant Center
 * http://www.seodenver.com/custom-rss-feed-in-wordpress/
 * 
 * Google Product feed specification
 * http://www.google.com/support/merchants/bin/answer.py?hl=en_US&answer=188494
 *
 * @package StayDry WP Theme
 */

$elements_to_prefix = array(
    'id',
    'price',
    'condition',
    'gtin',
    'brand',
    'mpn',
    'image_link',
    'product_type',
    'quantity',
    'availability',
    'shipping',
    'tax',
    'feature',
    'online_only',
    'manufacturer',
    'expiration_date',
    'shipping_weight',
    'product_review_average',
    'product_review_count',
    'genre',
    'featured_product',
    'excluded_destination',
    'color',
    'size',
    'year',
    'author',
    'edition',
);

function print_item($item, $prefix_table){

    echo "    <item>\n";
    foreach ($item as $name => $value){
        if (in_array($name, $prefix_table)){
            $tag = 'g:' . $name;
        } else {
            $tag = $name;
        }
        echo '        <' . $tag . '>' . $value . '</' . $tag . '>' . "\n";    
    }
    echo "    </item>\n";
}

// this array will hold all the product items for the data feed
$items = array();

// gather the data for creating the product entries
$product_posts = get_posts(
    array(
        'category_name' => 'products',
        'posts_per_page' => -1
        )  
    );

// move the data we need from the posts into the item array
foreach ($product_posts as $key => $prod_post)
{

    // this holds the data for the current product item
    $item = array();

    // fetch data related to current post
    $product_data = get_post_meta($prod_post->ID, '_cpo_product_data', true );
    $options = $product_data['product_options'];
    $attachment_id = get_post_thumbnail_id($prod_post->ID);
    if ($attachment_id){
        $item_img = wp_get_attachment_image_src( $attachment_id, 'full');
    }

    // got all the data we need to start processing
    // start by entering in the easy stuff
    
    $item['link'] = $prod_post->guid;
//    $item['description'] = $prod_post->post_content;
    $item['price'] = $product_data['price'];
    $item['id'] = $product_data['item_number'];
    $item['title'] = $prod_post->post_title;
    $item['price'] = $product_data['price'];
    $item['condition'] = 'new';
    $item['brand'] = 'StayDry Systems';
    if ($item_img){
        $item['image_link'] = $item_img[0];
    }
    $item['availability'] = 'in stock';
    
    
    foreach ($options as $opt){
        if ($opt['price']) {
            // make a copy of item
            $new_item = $item;
            // append the size to the id to make it unique
            $new_item['id'] .= 's' . $opt['label'];
            $new_item['title'] = $opt['label'] . '" ' . $item['title'];
            $new_item['price'] = $opt['price'];
            $items[] = $new_item;
            
            // flag to stop a new item from being created below
            $item_added = True;
        } elseif ($opt['label']) {
            if (!$sizes){
                $sizes = array();
            }
            $sizes[] = $opt['label'];
        }
    }
    if (!$item_added){
        if ($sizes){
            $item['size'] = $sizes;
        }
        $items[] = $item;
    }
}

// output the header of the feed

header('Content-Type: ' . feed_content_type('atom') . '; charset=' . get_option('blog_charset'), true);
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: -1");
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>' . "\n";

?>
<feed xmlns="http://www.w3.org/2005/Atom" xmlns:g="http://base.google.com/ns/1.0">
    <title type="text">Products by StayDry Systems</title>
    <link rel="self" type="application/atom+xml" href="<?php self_link(); ?>" />
    <updated><?php echo mysql2date('Y-m-d\TH:i:s\Z', get_lastpostmodified('GMT'), false); ?></updated>
    <author>
        <name>StayDry Systems</name>
    </author>
    <id>http://www.staydrysystems/feed/product-feed/</id>
<?php

// loop through the product items to output an entry for each one
foreach ($items as $item) {
     print_item($item, $elements_to_prefix);
}

// print_r($items);

?>
</feed>
