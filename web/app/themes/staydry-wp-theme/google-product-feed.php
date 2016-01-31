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

/**
 * gItemProperty
 *
 * defines the structure for the properties of the product items for the google product feed
 * aggregated in gItem
 * 
 * @author Aaron Cimolini
 */
class gItemProperty
{
            public $label;
    public $value;
    public $gPrefix = False; // weather or not to include the 'g:' when printing the properties XML tag
    
    function print_xml(){
        $tag = $this->label;
        if ($this->gPrefix) {
            $tag = 'g:' . $tag;
        }
        echo '        <' . $tag . '>';
        $this->print_value();
        echo '</' . $tag . '>' . "\n";
    }
    
    function print_value(){
        // if the value is an array, we have 
        if (is_array($this->value)){
            foreach($this->value as $value){
                $value->print_xml();
            }
        } else {
            echo $this->value;
        }
    }
}

/**
 * gItem
 *
 * contains product item data and outputs it into xml for a google merchant center data feed
 * 
 * @author Aaron Cimolini
 */
class gItem
{
    // item properties
    // first element in the array is the property value
    // second is the flag indicating if a 'g:' is needed to preceed the tag name when outputting XML
    public $id = array(0, False);
    public $title = array('Product Name', False);
    public $link = array('product url', False);
    public $price = array('$price USD', False);
    public $description = array('description', False);
    public $condition = array('new', True); // we only sell new products
    public $gtin = array('UPC code', True); // UPC code
    public $brand = array('StayDry Systems', True);
//    public $mpn; // Manufacturers Product Number, optional perhaps Tom has this
    // there can be a number of these things
    // find a way to define multiple opbjects
    public $image_link; // product image url. upto 10 images. 400px by 400px minimum
    public $product_type;
    public $quantity; // number of items in stock. Unable to implemet this right now
    public $availability = 'in stock'; // other allowed variables 'limited availability' or 'out of stock'
    public $shipping; // can have multiple shipping groups with xml child nodes
    public $tax; // same as shipping
    public $feature; // used for product features, eg: "anti-microbial treatment". can have multiple features
    public $expiration_date; // exp. date for the listing, not the product. I think I need to specify this for some reason.
    public $shipping_wieght; // number (integer or float? not sure) followed by unit [lb, pound, oz, ounce, g, gram, kg, kilogram]
    public $featured_product; // used for products that are on sale or have a promotion. normally leave out, define as 'y' if featured.
    public $size; // create an entry for each length of shower rings. I don't think I need this for the curtain widths.

    public function add_property($label, $value){
        // add a new gItemProperty object to the $properties array
        $new_property = new gItemProperty;
        $new_property->label = $label;
        $new_property->value = $value;
        $this->properties[] = $new_property;
    }

    public function update_property($label, $value){
        for ($i = 0; $i < count($this->properties); $i++){
            if ($this->properties[$i]->label == $label){
                $this->properties[$i]->value = $value;
            }
        }
    }

/*
    // output the <item> xml entry
    function print_xml() {
        echo '    ' . '<item>' . "\n";
        foreach ($this->properties as $property) {
            $property->print_xml();
        }
        echo '    ' . '</item>' . "\n";        
    }
*/


    // output the <item> xml entry
    function print_xml() {
        echo '    ' . '<item>' . "\n";
        foreach ($this as $name => $property) {
            $this->print_property_xml($name, $property);
        }
        echo '    ' . '</item>' . "\n";        
    }

    function print_property_xml($name, $property) {
    
    }

}



// gather the data for creating the product entries

$product_posts = get_posts(
    array(
        'category_name' => 'products',
        'posts_per_page' => -1
        )  
    );

foreach ($product_posts as $key => $prod_post)
{
    // in this loop we are going to copy the data we need from the posts into an associative array
    
    print_r($prod_post);
    // get the meta data for each of these posts
    $product_data = get_post_meta($prod_post->ID, '_cpo_product_data', true );
    $options = $product_data['product_options'];
    // don't need the options after this.
    unset($product_data['product_options']);
    foreach ($options as $opt){
        if ($opt['price']){
            // we'll need to create a seperate product entry for this
            
            $new_prod = $prod_post;
            $new_prod->product_data = $product_data;
            
            // add the price 
            $new_prod->product_data['price'] = $opt['price'];
            
            $new_prod->post_title = $prod_post->post_title . ' - ' . $opt['label'];
            
            $first_half = array_slice($product_posts, 0, $key+1);
            $second_half = array_slice($product_posts, $key+1);
            $product_posts = array_merge( $first_half, array($new_prod), $second_half);
            unset($new_prod);
        }
    }
    

    $product_posts[$key]->product_data = $product_data;
}

  print_r($product_posts);


// convert the post data into the product items for the data feed

// this array will hold all the product items for the data feed
$items = array();

// this loop scans each product and generates each entry needed
foreach ($product_posts as $prod_post){

    $item = new gItem;

/*
    product_options // find a way to remove this and the blank options if they are not set. **todo**
    min_quantity // use this for computing the price for the collapsible water dam, use this for computing  prices. when the min quant is greater than one
*/


    $item->add_property('id', $product_data['item_number']);
    $item->add_property('link', $prod_post->guid);
    // This text describes each item. As laid out in our policies 
    // you may not use promotional or boiler plate text, such as 
    // "Free Shipping" or "Click Here Now". Max of 10K characters
//    $item->add_property('description', $prod_post->post_content);
    $item->add_property('condition', 'new'); // This is always new, we don't sell refurbished or used products
    $item->add_property('brand', 'StayDry Systems');

    $attachment_id = '';
    $attachment_id = get_post_thumbnail_id($prod_post->ID);
    if ($attachment_id){
        $item_img = wp_get_attachment_image_src( $attachment_id, 'full');
        $item->add_property('image_link', $item_img[0]); // post thumbnail
    }
    
    // let's implement this as a switch in the product data. simple drop down. **todo**
    $item->add_property('availability', 'in stock');
    
    // this can have regions and things, I think we can base this off of the shipping data we already have.
    // there can be many shipping properties
    // each property has sub properties that define it.
    
//    $item->add_property('shipping']['price'] = $product_data['shipping'];
    
    
    // for anything that is sold in bulk and has a minimum order
    // add the units of measure to the product title
    // also set the price to the minimum order number times the price
    $title = $prod_post->post_title;
    $price = $product_data['price'];
    if ($product_data['min_quantity'] > 1){
        $title .= ' - ' . $product_data['min_quantity'];
        $title .= " " . $product_data['quantity_units'];
        $price = $product_data['price'] * $product_data['min_quantity'];
    }
    $item->add_property('title', $title);
    // this depends on if there are product options with associated prices
    $item->add_property('price', $price);


// tis is should be the UPC code for the product **todo**
// find out the UPC codes for all the products and then enter
// them into the product database. Why don't I have this in a spreadsheet?
// *** for the curtain kits these will have different UPC codes for each 
// $item->add_property('gtin', $product_data['UPC']);


// this can be a single string or a list of strings **todo**    
//    $item->add_property('product_type', $product_data['content']);


// number of items in stock. we don't have inventory tracking so we don't need to worry about this. perhaps later
//    $item->add_property('quantity', $product_data['content']);

    // not sure how tax collecting works or even if tom is charging it. leave this out for now
//    $item->add_property('tax', $product_data['content']);

    
    // this is a set of features unique to the product. each in it's own 'feature' tag. 0 to many.
    // we don't have a list of features as of yet. **todo**
//    $item->add_property('feature', $product_data['content']);


    // not sure if I need this. it's for when the item LISTING will expire. defaults to 30 days if left out.
//    $item->add_property('experation_date', $product_data['content']);

    // we don't have this data yet. assuming this may be used for calculating shipping
//    $item->add_property('shipping_weight', $product_data['content']);

    // need to ad product reviews for our products. leving this out for now.
//    $item->add_property('product_review_average', $product_data['content']);
//    $item->add_property('product_review_average', $product_data['content']);

    // weather or not this product is on sale or being promoted or not. Not sure the effect setting this to 'y' would be. disabling for now.
//    $item->add_property('featured_product', $product_data['content']);

    

    // set this for any product that has a different size 
    // this is going to be useful for the extra long shower rings
    // can have upto 30 attributes
    // for each of the sizes of the extra long shower rings we'll add an additional size
    // for each of the curtain sizes we'll have a seperate item object for these 
    // $item->add_property('size', $product_data['content']);
    
    
    // check for product options with prices
    // set a flag to true if we create an item for any priced options
    // use the existing $item obj to create copies and then add those copies to the
    // $items array
    $created_option_items = False;
    foreach ($options as $option) {
        if ($option['price']) {


    //***************************************************************************
    // this is buggy, has something to do with objects being passed as references

                $tmp_item = $item;

                $new_title = $option['label'] . ' - ' . $title;
                $tmp_item->update_property('title', $new_title);

//                print_r($tmp_item);
                $items[] = $tmp_item;
//                print_r($items);
                $created_option_items = True;
                
        }
    }
    if (!$created_option_items){
            $items[] = $item;
    }


}

/*
// remove this code

    
		<title><?php echo $product_name ?></title>
		<description><?php the_excerpt_rss(); ?></description>
		<id><?php the_guid() ; ?></id>
		<link><?php the_permalink_rss() ?></link>
		<g:price><?php echo $price; ?> USD</g:price>
		<g:condition>new</g:condition>
<?php
    
*/

// output the header of the feed

/* uncomment this for production
header('Content-Type: ' . feed_content_type('atom') . '; charset=' . get_option('blog_charset'), true);
header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: -1");
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>' . "\n";
*/

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
//     $item->print_xml();
}

?>
</feed>
