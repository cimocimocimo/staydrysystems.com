<?php 

// add notice to checkout page
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title' 	=> 'StayDry Theme Settings',
        'menu_title'	=> 'Theme Settings',
        'menu_slug' 	=> 'theme-general-settings',
        'capability'	=> 'edit_posts',
        'redirect'		=> false
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Checkout Settings',
        'menu_title'	=> 'Checkout',
        'parent_slug'	=> 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title' 	=> 'Homepage Settings',
        'menu_title'	=> 'Homepage',
        'parent_slug'	=> 'theme-general-settings',
    ));

}

if( function_exists('acf_add_local_field_group') ):

    acf_add_local_field_group(array (
        'key' => 'group_573aa44477932',
        'title' => 'Checkout Notice',
        'fields' => array (
            array (
                'key' => 'field_573aa66b7515a',
                'label' => 'Notice Header',
                'name' => 'checkout_notice_header',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'prepend' => '',
                'append' => '',
                'maxlength' => '',
                'readonly' => 0,
                'disabled' => 0,
            ),
            array (
                'key' => 'field_573aa6a07515b',
                'label' => 'Notice Body',
                'name' => 'checkout_notice_body',
                'type' => 'wysiwyg',
                'instructions' => '',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array (
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'tabs' => 'all',
                'toolbar' => 'basic',
                'media_upload' => 0,
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'options_page',
                    'operator' => '==',
                    'value' => 'acf-options-checkout',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
    ));

endif;

add_action('woocommerce_before_checkout_form', function(){
    $header = get_field('checkout_notice_header', 'option');
    $body = get_field('checkout_notice_body', 'option');
    wc_print_notice( sprintf('<h4>%s</h4>%s', $header, $body), 'notice' );
}, 10);
