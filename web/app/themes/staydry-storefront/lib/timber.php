<?php

// setup the Timber contexts
$timber = new \Timber\Timber();
add_filter( 'timber_context', function($context){
    // add top-bar to all contexts
    $context['top_bar'] = get_fields('top-bar');
    // process all the images
    foreach ($context['top_bar']['accepted_payment_logos'] as $key => $image){
        $context['top_bar']['accepted_payment_logos'][$key] = new TimberImage($image['logo_image']['ID']);
    }
    return $context;
});
