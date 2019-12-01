<?php

namespace Tonik\Theme\App\Setup;

/*
  |-----------------------------------------------------------
  | Theme Custom Services
  |-----------------------------------------------------------
  |
  | This file is for registering your third-parity services
  | or custom logic within theme container, so they can
  | be easily used for a theme template files later.
  |
*/

use function Tonik\Theme\App\theme;
use Tonik\Gin\Foundation\Theme;
use WP_Query;

/**
 * Service handler for retrieving posts of specific post type.
 *
 * @return void
 */
function bind_books_service()
{
    /**
     * Binds service for retrieving posts of specific post type.
     *
     * @param \Tonik\Gin\Foundation\Theme $theme  Instance of the service container
     * @param array $parameters  Parameters passed on service resolving
     *
     * @return \WP_Post[]
     */
    theme()->bind('books', function (Theme $theme, $parameters) {
        return new WP_Query([
            'post_type' => 'book',
        ]);
    });
}
// add_action('init', 'Tonik\Theme\App\Setup\bind_books_service');


/**
 * FAQ service handler.
 *
 * @return void
 */
add_action('init', function () {

    /**
     * Retrieves FAQ posts with an optional filter.
     *
     * Filters by related product, FAQ Category. Default is to return all FAQs.
     *
     * @param \Tonik\Gin\Foundation\Theme $theme  Instance of the service container
     * @param array $parameters  Parameters passed on service resolving
     *
     * @return \WP_Post[]
     */
    theme()->factory('faqs', function (Theme $theme, $parameters) {

        $taxonomy = 'faq_taxonomy';

        // get all faqs
        $faqs = get_posts([
            'post_type' => 'faq',
            'posts_per_page' => -1,
        ]);

        // get the terms for each faq
        $faqs = array_map(function ($faq) use ($taxonomy){
            $terms = get_the_terms($faq->ID, $taxonomy);
            if ($terms) {
                $faq->terms = $terms;
            }
            return $faq;
        }, $faqs);

        // apply filters
        if ($parameters && array_key_exists('filter', $parameters)) {

            // filter by product id
            if (array_key_exists('product_id', $parameters['filter'])) {
                // need to pull all the FAQs and then filter by ACF field.
                $faqs = array_values(array_filter($faqs, function ($faq) use ($parameters) {
                    // Check this faq for the related product field
                    $related_products = get_field('related_products', $faq->ID);

                    if ($related_products) {
                        foreach ($related_products as $id) {
                            if ($id  == $parameters['filter']['product_id']) {
                                return true;
                            }
                        }
                    }

                    return false;
                }));
            }

            // filter by faq_category
            if (array_key_exists('category', $parameters['filter'])) {
                // If we have a string we filter by term slugs
                // If we have a false value for category, then we return categorized faqs.
                $faqs = array_values(array_filter($faqs, function ($faq) use ($parameters) {
                    // if we have terms for this faq and the category filter is true
                    if ($faq->terms && $parameters['filter']['category']) {
                        foreach ($faq->terms as $term) {
                            if ($term->slug  == $parameters['filter']['category']) {
                                return true;
                            }
                        }
                    }
                    // we don't have terms for this faq and category is false
                    elseif (!$faq->terms && !$parameters['filter']['category']) {
                        return true;
                    }

                    return false;
                }));
            }
        };

        return $faqs;
    });
});
