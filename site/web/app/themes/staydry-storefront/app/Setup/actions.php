<?php

namespace Tonik\Theme\App\Setup;

/*
|-----------------------------------------------------------
| Theme Actions
|-----------------------------------------------------------
|
| This file purpose is to include your custom
| actions hooks, which process a various
| logic at specific parts of WordPress.
|
*/

/**
 * Adds store currency to the price of the products
 *
 * @return string
 */
add_action('woocommerce_price_format', function ($format, $currency_pos) {
    $price_format_array = [
        $format,
        '&nbsp;', // separator
        get_woocommerce_currency(), // currency
    ];

	if ( $currency_pos == 'right' ) {
        $price_format_array = array_reverse($price_format_array);
	}

	return implode($price_format_array);
}, 1, 2);
