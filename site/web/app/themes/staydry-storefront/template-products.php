<?php

namespace Tonik\Theme\FAQ;

/**
 * Template Name: Products
 * The FAQ section, displays all the FAQ items by category.
 */

use function Tonik\Theme\App\{template, theme};
use function Tonik\Theme\App\Structure\get_page_content_blocks;

$blocks = get_page_content_blocks();

template('products', [
    'blocks' => $blocks,
]);
