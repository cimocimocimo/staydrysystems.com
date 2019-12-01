<?php

namespace Tonik\Theme\App\Structure;

/*
  |----------------------------------------------------------------
  | Data processing for the ACF page content blocks.
  |----------------------------------------------------------------
  |
  | Page content blocks created require additional processing
  | before the data can be presented in a template to the user.
  | The templates should generally only need to echo variables
  | passed to them from these functions.
  |
*/

use function Tonik\Theme\App\config;
use function Tonik\Theme\App\{match_area_of_rectangles, format_brief_posts};
use function Tonik\Theme\App\acf_responsive_image;

/**
 * Fetches and processes the page content blocks.
 *
 * @return Array
 */
function get_page_content_blocks () {

    $blocks = [];

    // Get ACF Fields
    $content = get_field('page_content');

    while (have_rows('page_content')) {

        // call first to setup the loop, save the row data, cast as object
        $row = (object) the_row(true);
        $layout = get_row_layout();

        if (!isset($row->meta)) {
            $row->meta = [];
        }

        if (isset($row->meta['template'])) {
            $template = $layout . '-' . $row->meta['template'];
        } else {
            $template = $layout;
        }

        $row->meta['style'] = false;

        if (!isset($row->heading)) {
            $row->heading = false;
        }

        if (!isset($row->image)) {
            $row->image = false;
        }

        $block_main_class = $layout;
        $block_classes = [
            $block_main_class,
            'storefront-section',
            'template-' . $template,
        ];

        if (array_key_exists('extra_classes', $row->meta) && $row->meta['extra_classes']) {
            $block_classes = array_merge($block_classes, explode(' ', $row->meta['extra_classes']));
        }

        // Additional row processing
        switch ($layout) {

        case 'products-block':
            $block_classes[] = 'storefront-product-section';
            process_product_block($row);
            break;

        case 'corporate-customers-block':
            $block_classes[] = 'corporate-customer-logos';
            process_corporate_customers_block($row);
            break;

        case 'header-and-text-block':
            $block_classes = array_merge($block_classes, [
                $block_main_class . '--align-' . $row->align_content,
                $block_main_class . '--' . $row->color_scheme . '-color-scheme',
            ]);
            if ($row->image) {
                $row->image_src_string = acf_responsive_image($row->image['id'], 'full', '900px');
            }
            if ($row->background_image){
                $row->meta['style'] = 'background-image:url(' . $block->background_image . ');';
            }
            break;
        }

        $row->meta = (object) array_merge($row->meta, [
            'layout' => $layout,
            'classes_array' => $block_classes,
            'class' => $block_main_class,
            'classes' => implode(' ', $block_classes),
            'template' => $template,
        ]);

        $blocks[] = $row;
    }

    return $blocks;
}

function process_product_block (&$row) {

    $args = $row->product_args;
    $args['orderby'] = implode(' ', $args['orderby_array']);

    if ($args['category_array']) {
        // get string of slugs separated by commas from array of category taxonomy objects.
        $args['category'] = array_reduce($args['category_array'], function ($carry, $item) {
            // add separator after the first item.
            if (!$carry) {
                return $item->slug;
            }
            return $carry . ',' . $item->slug;
        });
    }

    // These are mutually exclusive and added using a flag like: attribute=true
    if ($args['special_attributes']) {
        $args[$args['special_attributes']] = true;
    }

    $row->product_args = $args;
    $row->shortcode_content = storefront_do_shortcode('products', $args);
}

function process_corporate_customers_block (&$row) {
    $customers = format_brief_posts($row->customers, 'medium_large');

    // Used to store the percentage height of the tallest logo. We use that
    // value to set the logo row height.
    $max_height = (float) 0;

    foreach ($customers as $cust){
        $logo_metadata = wp_get_attachment_metadata(
            get_post_thumbnail_id($cust->ID)
        );

        $matched_dimensions = match_area_of_rectangles(
            37,
            [
                $logo_metadata['width'],
                $logo_metadata['height']
            ]
        );
        $cust->logo_width_percent = $matched_dimensions[0];
        $cust->logo_height_percent = $matched_dimensions[1];

        if ($max_height < $matched_dimensions[1]) {
            $max_height = $matched_dimensions[1];
        }
    }

    // replace the original corp_customers for the row with the processed ones
    $row->customers = $customers;
    $row->max_height = $max_height;
}
