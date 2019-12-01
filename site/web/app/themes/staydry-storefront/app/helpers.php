<?php

namespace Tonik\Theme\App;

use Tonik\Gin\Asset\Asset;
use Tonik\Gin\Foundation\Theme;
use Tonik\Gin\Template\Template;

/**
 * Gets theme instance.
 *
 * @param string|null $key
 * @param array $parameters
 *
 * @return \Tonik\Gin\Foundation\Theme
 */
function theme($key = null, $parameters = [])
{
    if (null !== $key) {
        return Theme::getInstance()->get($key, $parameters);
    }

    return Theme::getInstance();
}

/**
 * Gets theme config instance.
 *
 * @param string|null $key
 *
 * @return array
 */
function config($key = null)
{
    if (null !== $key) {
        return theme('config')->get($key);
    }

    return theme('config');
}

/**
 * Renders template file with data.
 *
 * @param  string $file Relative path to the template file.
 * @param  array  $data Dataset for the template.
 *
 * @return void
 */
function template($file, $data = [])
{
    $template = new Template(config());

    return $template
        ->setFile($file)
        ->render($data);
}

/**
 * Gets asset instance.
 *
 * @param  string $file Relative file path to the asset file.
 *
 * @return \Tonik\Gin\Asset\Asset
 */
function asset($file)
{
    $asset = new Asset(config());

    return $asset->setFile($file);
}

/**
 * Gets asset file from public directory.
 *
 * @param  string $file Relative file path to the asset file.
 *
 * @return string
 */
function asset_path($file)
{
    return asset($file)->getUri();
}

/**
 * Returns an array of abbreviated posts with thumbnail data.
 *
 * @param array $posts Array of WP_Post objects.
 * @param string $thubm_size Size of post thumbnail to get.
 *
 * @return Array of stdClass objects
 */
function format_brief_posts($posts, $thumb_size = 'thumbnail')
{

    if ( !is_array($posts) ) {
        return;
    }

    $brief_posts = [];

    foreach ($posts as $key => $post){
        $brief_posts[$key] = format_brief_single_post($post, $thumb_size);
    }

    return $brief_posts;
}

/**
 * Returns abbreviated posts as a stdClass object with thumbnail data.
 *
 * @param WP_Post $post Wordpress Post object.
 * @param string $thubm_size Size of post thumbnail to get.
 *
 * @return Std Object with abbreviated data.
 */
function format_brief_single_post($post, $thumb_size = 'thumbnail')
{
    return (object)[
        'ID' => $post->ID,
        'thumbnail' => get_the_post_thumbnail($post->ID, $thumb_size),
        'title' => get_the_title($post->ID),
        'permalink' => get_the_permalink($post->ID),
    ];
}

/**
 * Matches the area of two rectangles
 *
 * When given the height and width of two rectangles it will return a new height and width
 * of the 2nd rectangle so that it's area matches the 1st.
 *
 * @return array([width], [height]) on success
 * @return bool false on failure
 */
function match_area_of_rectangles($target, $subject){
    // check $target is array and has 2 elements
    if (is_array($target) && count($target) == 2){
        // ensure we have two numbers
        if (is_numeric($target[0]) && is_numeric($target[1])){
            // calculate area
            $target_area = floatval($target[0]) * floatval($target[1]);
        } else {
            return false;
        }
    }
    // is $target a number?
    elseif (is_numeric($target)){
        // assume it's the length of one side of a square
        $target_area = pow(floatval($target), 2);
    } else {
        return false;
    }

    // check $subject is array and has 2 elements
    if (is_array($subject) && count($subject) == 2){
        // ensure we have 2 numbers
        if (is_numeric($subject[0]) && is_numeric($subject[1])){
            // cast as floats
            $sub_w = floatval($subject[0]);
            $sub_h = floatval($subject[1]);

            // calculate height and width
            return [
                // width
                sqrt(
                    $target_area * ($sub_w/$sub_h)
                ),
                // height
                sqrt(
                    $target_area * ($sub_h/$sub_w)
                ),
            ];
        }
    }

    // an error occured above
    return false;
}

/**
 * Responsive Image Helper Function
 *
 * @param string $image_id the id of the image (from ACF or similar)
 * @param string $image_size the size of the thumbnail image or custom image size
 * @param string $max_width the max width this image will be shown to build the sizes attribute
 */
function acf_responsive_image($image_id, $image_size, $max_width) {

	// check the image ID is not blank
	if($image_id != '') {

		// set the default src image size
		$image_src = wp_get_attachment_image_url( $image_id, $image_size );

		// set the srcset with various image sizes
		$image_srcset = wp_get_attachment_image_srcset( $image_id, $image_size );

		// generate the markup for the responsive image
        $sizes_format = '(max-width: %1$s) 100vw, %1$s';
        $sizes = sprintf($sizes_format, $max_width);

        $src_format = 'src="%s" srcset="%s" sizes="%s"';
        return sprintf($src_format, $image_src, $image_srcset, $sizes);
	}
}
