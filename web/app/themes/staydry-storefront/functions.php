<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$staydry_includes = [
    'lib/assets.php', // Scripts and stylesheets
    'lib/acf.php', 
    'lib/timber.php',
    'lib/admin.php',
    'lib/header.php',
    'lib/sidebar.php',
    'lib/footer.php',
    'lib/homepage.php',
    'lib/products.php',
    'lib/setup.php', // Theme setup
];

foreach ($staydry_includes as $file) {
    if (!$filepath = locate_template($file)) {
        trigger_error(sprintf(__('Error locating %s for inclusion', 'staydry'), $file), E_USER_ERROR);
    }

    require_once $filepath;
}
unset($file, $filepath);
