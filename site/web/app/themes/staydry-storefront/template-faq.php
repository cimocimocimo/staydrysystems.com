<?php

namespace Tonik\Theme\FAQ;

/**
 * Template Name: FAQ
 * The FAQ section, displays all the FAQ items by category.
 */

use function Tonik\Theme\App\{template, theme};
use function Tonik\Theme\App\Structure\get_page_content_blocks;

// get the faq categories
$terms = get_terms([
    'taxonomy' => 'faq_taxonomy',
    'hide_empty' => false,
]);

$faqs_by_category = [];

// get all the faqs for each category
foreach ($terms as $term) {
    $faqs_by_category[$term->slug] = ['term' => $term];
    $faqs_by_category[$term->slug]['posts'] = theme('faqs', [
        'filter' => [
            'category' => $term->slug
        ]
    ]);
}

// get the uncategorized items
$uncategorized_faqs = theme('faqs', [
    'filter' => [
        'category' => false,
    ]
]);
if ($uncategorized_faqs) {
    $faqs_by_category['uncategorized']['term'] = (object) [
        'name' => 'Uncategorized',
        'slug' => 'uncategorized',
    ];
    $faqs_by_category['uncategorized']['posts'] = $uncategorized_faqs;
}

foreach ($faqs_by_category as $category){
    foreach ($category['posts'] as $faq) {
        $prod_ids = get_field('related_products', $faq->ID);

        // skip faqs that don't have related_products
        if (!$prod_ids) continue;

        $faq->related_products = [];

        foreach ($prod_ids as $id) {
            array_push($faq->related_products, (object) [
                'id' => $id,
                'title' => get_the_title($id),
                'permalink' => get_permalink($id),
            ]);
        }
    }
}

$blocks = get_page_content_blocks();

template('faq', [
    'faqs' => $faqs_by_category,
    'blocks' => $blocks,
]);
