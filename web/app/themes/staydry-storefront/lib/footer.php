<?php

// remove the Storefront theme credit.
add_filter('storefront_credit_link', function(){
    return false;
});

// Adds company slogan to footer.
add_filter('storefront_copyright_text', function($value){
    return sprintf('%s - %s', $value, get_bloginfo('description'));
});
