<?php

function captiva_custom_css() {
    global $captiva_options;
    ?>

    <style type="text/css">

        <?php
        $cap_bg_color = '';
        $cap_pagewrapper_color = '';
        $cap_bg_img = '';
        $cap_bg_img_attach = '';
        $cap_bg_pattern_img = '';
        $cap_bg_img_repeat = '';
        $cap_bg_pattern_img_repeat = '';
        $cap_skin_color = '';
        $cap_custom_css = '';

        if ( isset( $captiva_options['cap_background']['background-color'] ) ) {
            $cap_bg_color = $captiva_options['cap_background']['background-color'];
        }
        
        if ( isset( $captiva_options['cap_background']['background-image'] ) ) {
            $cap_bg_img = $captiva_options['cap_background']['background-image'];
        }

        if ( isset( $captiva_options['cap_pattern_background']['background-image'] ) ) {
            $cap_bg_pattern_img = $captiva_options['cap_pattern_background']['background-image'];
        }

        if ( isset( $captiva_options['cap_background']['background-repeat'] ) ) {
            $cap_bg_img_repeat = $captiva_options['cap_background']['background-repeat'];
        }

        if ( isset( $captiva_options['cap_pattern_background']['background-repeat'] ) ) {
            $cap_bg_pattern_img_repeat = $captiva_options['cap_pattern_background']['background-repeat'];
        }

        if ( isset( $captiva_options['cap_page_wrapper_color'] ) ) {
            $cap_page_wrapper_color = $captiva_options['cap_page_wrapper_color'];
        }

        $cap_skin_color = $captiva_options['cap_skin_color'];
        $cap_primary_color = $captiva_options['cap_primary_color'];
        $cap_active_link_color = $captiva_options['cap_active_link_color'];
        $cap_link_hover_color = $captiva_options['cap_link_hover_color'];
        $cap_topbar_bg_color = $captiva_options['cap_topbar_bg_color'];

        if ( !empty( $_SESSION['cap_skin_color'] ) ) {
            $cap_skin_color = $_SESSION['cap_skin_color'];

            if ( $cap_skin_color == '#169fda' ) {
                $cap_bag_color = CAP_THEMEURI . '/images/icons/bag_blue.png';
            } elseif ( $cap_skin_color == '#dd3333' ) {
                $cap_bag_color = CAP_THEMEURI . '/images/icons/bag_red.png';
            } elseif ( $cap_skin_color == '#208e3c' ) {
                $cap_bag_color = CAP_THEMEURI . '/images/icons/bag_green.png';
            } elseif ( $cap_skin_color == '#e9690c' ) {
                $cap_bag_color = CAP_THEMEURI . '/images/icons/bag_orange.png';
            }
            ?>

            .bag-icon {
                background: url( '<?php echo $cap_bag_color; ?>' );
                background-size: 23px 32px;
            }
            <?php
        }
        if ( isset( $cap_skin_color ) ) {
            if ( $cap_skin_color !== 'none' ) {
                $cap_primary_color = $cap_skin_color;
                $cap_active_link_color = $cap_skin_color;
                $cap_link_hover_color = $cap_skin_color;
                $cap_topbar_bg_color = $cap_skin_color;  
            }

        }

        $cap_first_footer_bg = $captiva_options['cap_first_footer_bg'];
        $cap_second_footer_bg = $captiva_options['cap_second_footer_bg'];
        $cap_last_footer_bg = $captiva_options['cap_last_footer_bg'];
        $cap_first_footer_text = $captiva_options['cap_first_footer_text'];
        $cap_second_footer_text = $captiva_options['cap_second_footer_text'];
        $cap_last_footer_text = $captiva_options['cap_last_footer_text'];
        $cap_header_height = $captiva_options['cap_header_height'];
        $cap_header_height_mobile = $captiva_options['cap_header_height_mobile'];
        $cap_fixed_header_height = $captiva_options['cap_fixed_header_height'];
        $cap_header_bg_color = $captiva_options['cap_header_bg_color'];
        $cap_header_fixed_bg_color = $captiva_options['cap_header_fixed_bg_color'];
        $cap_header_cart_text_color = $captiva_options['cap_header_cart_text_color'];
        $cap_submenu_border = $captiva_options['cap_submenu_border']['border-color'];

        if ( isset( $captiva_options['cap_custom_css'] ) ) {
            $cap_custom_css = $captiva_options['cap_custom_css'];
        }

        $cap_level2_font_color = $captiva_options['cap_level2_font']['color'];
        $cap_mobile_menu_padding = (($cap_header_height_mobile - 20) / 2);

        $cap_menu_arrow_color = $captiva_options['cap_main_menu_dropdown_bg']['color'];
        $cap_menu_arrow_color_alpha = $captiva_options['cap_main_menu_dropdown_bg']['alpha'];

        if ( $cap_menu_arrow_color ) {
            $cap_menu_arrow_color_rgb = hex2rgb( $cap_menu_arrow_color );
            ?>


            .cap-header-fixed .menu > li.menu-item-has-children:hover > a:before,
            .cap-primary-menu .menu > li.menu-item-has-children:hover > a:before 
            {
                border-bottom-color: <?php echo 'rgba(' . $cap_menu_arrow_color_rgb . ',' . $cap_menu_arrow_color_alpha . ')'; ?> !important; 
            } 

        <?php
        }

        if ( $cap_level2_font_color ) {
            ?>

            .cap-header-fixed .menu > li.menu-full-width .cap-submenu-ddown .container > ul > li > a:hover, .cap-primary-menu .menu > li.menu-full-width .cap-submenu-ddown .container > ul > li > a:hover 
            {
                color: <?php echo $cap_level2_font_color; ?>;
            }
        <?php } ?>

        <?php
        if ( $cap_submenu_border ) {
            ?>
            .cap-primary-menu .menu > li .cap-submenu-ddown .container ul .menu-item-has-children .cap-submenu ul li:last-child a, 
            .cap-header-fixed .menu > li .cap-submenu-ddown .container ul .menu-item-has-children .cap-submenu ul li:last-child a, 
            .cap-primary-menu > .menu > li > .cap-submenu-ddown > .container > ul > li:last-child a
            .cap-header-fixed .menu > li .cap-submenu-ddown, 
            .cap-primary-menu .menu > li .cap-submenu-ddown
            {
                border-bottom: 1px solid <?php echo $cap_submenu_border; ?>; 
            }

            .cap-header-fixed .menu > li.menu-full-width .cap-submenu-ddown .container > ul > li .cap-submenu ul li, .cap-primary-menu .menu > li.menu-full-width .cap-submenu-ddown .container > ul > li .cap-submenu ul li
            {
                border-top: 1px solid <?php echo $cap_submenu_border; ?>
            }

            .cap-header-fixed .menu > li.menu-full-width .cap-submenu-ddown, .cap-primary-menu .menu > li.menu-full-width .cap-submenu-ddown {
                border: 1px solid <?php echo $cap_submenu_border; ?>                
            }
        <?php } ?>

        <?php if ( $cap_bg_color ) {
            ?>
            body {
                background-color: <?php echo $cap_bg_color; ?>; 
            }
        <?php } ?>

        <?php if ( $cap_bg_img ) { ?>
            body {
                background-image: url('<?php echo $cap_bg_img; ?>'); 
                background-position: 0px 0px;
                background-attachment: fixed;
                background-size: cover;
            }
        <?php } ?>

        <?php if ( $cap_bg_img_repeat ) { ?>
            body {
                background-repeat: <?php echo $cap_bg_img_repeat; ?>; 
            }
        <?php } ?>

        <?php if ( $cap_bg_pattern_img ) { ?>
            body {
                background-image: url('<?php echo $cap_bg_pattern_img; ?>'); 
                background-position: 0px 0px;
            }
        <?php } ?>

        <?php if ( $cap_bg_pattern_img_repeat ) { ?>
            body {
                background-repeat: <?php echo $cap_bg_pattern_img_repeat; ?>; 
            }
        <?php } ?>

        <?php if ( $cap_page_wrapper_color ) { ?>
            #wrapper {
                background-color: <?php echo $cap_page_wrapper_color; ?>; 
            }
        <?php } ?>



        <?php if ( $cap_primary_color ) { ?>

            #top,
            .new.menu-item a:after, 
            .sb-icon-search, 
            .sb-search-submit,
            .sb-search input[type=submit],
            .sb-search.sb-search-open .sb-icon-search, 
            .no-js .sb-search .sb-icon-search,
            .faqs-reviews .accordionButton .icon-plus:before,
            .container .cap-product-cta a.button.added, 
            .container .cap-product-cta a.button.loading,
            .widget_price_filter .ui-slider .ui-slider-range,
            body.woocommerce .widget_layered_nav ul.yith-wcan-label li a:hover, 
            body.woocommerce-page .widget_layered_nav ul.yith-wcan-label li a:hover, 
            body.woocommerce-page .widget_layered_nav ul.yith-wcan-label li.chosen a,
            .content-area ul li:before,
            .container .mejs-controls .mejs-time-rail .mejs-time-current,
            .wpb_toggle:before, h4.wpb_toggle:before,
            /*.container .wpb_teaser_grid .categories_filter li.active a, 
            .container .wpb_teaser_grid .categories_filter li.active a:hover, */
            #filters button.is-checked,
            .ball:nth-child(1),
            .ball:nth-child(2),
            .ball:nth-child(3),
            .ball:nth-child(4),
            .ball:nth-child(5),
            .ball:nth-child(6),
            .ball:nth-child(7),
            .container .cap-product-cta a.button.added, .container .cap-product-cta a.button.loading,
            .tipr_content,
            .navbar-toggle .icon-bar,
            .woocommerce-page .container input.button

            {
                background-color: <?php echo $cap_primary_color; ?> !important; 
            }

            .woocommerce .container div.product form.cart .button:hover,
            .woocommerce-page .container div.product form.cart .button:hover

            {
                color: <?php echo $cap_primary_color; ?> !important;
                border-color: <?php echo $cap_primary_color; ?> !important;
            }

            a,
            .captiva-features i,
            .captiva-features h2,
            .widget_layered_nav ul.yith-wcan-list li a:before,
            .widget_layered_nav ul.yith-wcan-list li.chosen a:before,
            .widget_layered_nav ul.yith-wcan-list li.chosen a,
            blockquote:before,
            blockquote:after,
            article.format-link .entry-content p:before,
            .container .ui-state-default a, 
            .container .ui-state-default a:link, 
            .container .ui-state-default a:visited,
            .logo a,
            .page-numbers li a,
            .woocommerce-breadcrumb a,
            #captiva-articles h3 a,
            .cap-wp-menu-wrapper .menu li:hover > a,
            .cap-recent-folio-title a, 
            .content-area h2.cap-recent-folio-title a,
            .content-area .order-wrap h3 

            {
                color: <?php echo $cap_primary_color; ?>;
            }

            .owl-theme .owl-controls .owl-buttons div:hover,
            .content-area blockquote:hover, 
            article.format-link .entry-content p:hover,
            .blog-pagination ul li a:hover,
            .blog-pagination ul li.active a,
            .container .ui-state-hover,
            #filters button.is-checked,
            #filters button.is-checked:hover,
            .container form.cart .button:hover, 
            .woocommerce-page .container p.cart a.button:hover,
            .page-numbers li span:hover, 
            .page-numbers li a:hover, 
            .pagination li span:hover, 
            .pagination li a:hover,
            .map_inner,
            .order-wrap,
            .woocommerce-page .container .cart-collaterals input.checkout-button, 
            .woocommerce .checkout-button
            {
                border-color: <?php echo $cap_primary_color; ?>;
            }

            .woocommerce .woocommerce_tabs ul.tabs li.active a, 
            .woocommerce .woocommerce-tabs ul.tabs li.active a, 
            ul.tabNavigation li a.active 
            {
                border-top: 1px solid <?php echo $cap_primary_color; ?>;
            }

            .tipr_point_top:after {
                border-top-color: <?php echo $cap_primary_color; ?>;
            }

            .tipr_point_bottom:after {
                border-bottom-color: <?php echo $cap_primary_color; ?>;
            }

        <?php } ?>

        <?php if ( $cap_active_link_color ) { ?>

            a,
            .logo a,
            .navbar ul li.current-menu-item a, 
            .navbar ul li.current-menu-ancestor a, 
            #captiva-articles h3 a,
            .widget-area .widget.widget_rss ul li a,
            .widget-area .widget #recentcomments li a,
            .current_page_ancestor,
            .current-menu-item,
            .cap-primary-menu .menu > li.current-menu-item > a,
            .cap-primary-menu .menu > li.current-menu-ancestor > a
            {
                color: <?php echo $cap_active_link_color; ?>; 
            }
        <?php } ?>

        <?php if ( $cap_link_hover_color ) { ?>

            #top .dropdown-menu li a:hover, 
            .container form.cart .button:hover, 
            .woocommerce-page .container p.cart a.button:hover,
            ul.navbar-nav li .nav-dropdown li a:hover,
            .navbar ul li.current-menu-item a:hover, 
            .navbar ul li.current-menu-ancestor a:hover,
            .owl-theme .owl-controls .owl-buttons div:hover,
            .woocommerce ul.product_list_widget li a:hover,
            .summary .accordionButton p:hover,
            .content-area a.reset_variations:hover,
            .widget_recent_entries ul li a:hover,
            .content-area article h2 a:hover,
            .content-area footer.entry-meta a:hover,
            .content-area footer.entry-meta .comments-link:hover:before, 
            .content-area a.post-edit-link:hover:before
            .scwebsite:hover:before,

            .cap-wp-menu-wrapper .menu li a:hover,
            .cap-header-fixed .menu > li .cap-submenu-ddown .container > ul > li a:hover, 
            .cap-primary-menu .menu > li .cap-submenu-ddown .container > ul > li a:hover,
            a:hover, a:focus
            {
                color: <?php echo $cap_link_hover_color; ?>; 
            }
        <?php } ?>

        <?php if ( $cap_topbar_bg_color ) { ?>
            #top,             
            .sb-icon-search, 
            .sb-search-submit,
            .sb-search input[type=submit],
            .sb-search.sb-search-open .sb-icon-search, 
            .no-js .sb-search .sb-icon-search
            {
                background-color: <?php echo $cap_topbar_bg_color; ?> !important; 
            }
        <?php } ?>

        <?php if ( $cap_header_bg_color ) { ?>
            .header,
            .cap-menu-default,
            .cap-menu-below
            {
                background-color: <?php echo $cap_header_bg_color; ?>; 
            }
        <?php } ?>

        <?php if ( $cap_header_fixed_bg_color ) { ?>
            .cap-header-fixed-wrapper.cap-is-fixed
            {
                background-color: <?php echo $cap_header_fixed_bg_color; ?>; 
            }
        <?php } ?>

        <?php if ( $cap_header_cart_text_color ) { ?>
            ul.tiny-cart li a {
                color: <?php echo $cap_header_cart_text_color; ?> !important; 
            }
        <?php } ?>

        <?php if ( $cap_first_footer_bg ) { ?>
            .lightwrapper 

            {
                background-color: <?php echo $cap_first_footer_bg; ?>; 
            }

        <?php } ?>

        <?php if ( $cap_second_footer_bg ) { ?>
            .subfooter 

            {
                background-color: <?php echo $cap_second_footer_bg; ?>; 
            }

        <?php } ?>

        <?php if ( $cap_last_footer_bg ) { ?>
            .footer 

            {
                background-color: <?php echo $cap_last_footer_bg; ?>; 
            }

        <?php } ?>

        <?php if ( $cap_first_footer_text ) { ?>
            .lightwrapper h4, .lightwrapper ul li a 

            {
                color: <?php echo $cap_first_footer_text; ?>; 
            }

        <?php } ?>

        <?php if ( $cap_second_footer_text ) { ?>
            .subfooter h4, 
            .subfooter .textwidget, 
            .subfooter #mc_subheader,
            .subfooter .widget_recent_entries ul li a,
            .subfooter ul.product_list_widget li a,
            .subfooter ul.product_list_widget li span.amount,
            .subfooter #mc_signup_submit

            {
                color: <?php echo $cap_second_footer_text; ?>; 
            }

        <?php } ?>

        <?php if ( $cap_last_footer_text ) { ?>
            .footer p

            {
                color: <?php echo $cap_last_footer_text; ?>; 
            }

        <?php } ?>

        <?php if ( $cap_header_height ) { ?>
            .header,
            ul.tiny-cart,
            .mean-bar,
            .cap-menu-default,
            .cap-menu-default .logo,
            .cap-menu-below,
            .cap-menu-below .logo

            {
                /* $cap_header_height */
                height: <?php echo $cap_header_height; ?>px; 
            }

            .cap-menu-default .logo img, .cap-menu-below .logo img {
                max-height: <?php echo $cap_header_height; ?>px; 
            }

            ul.tiny-cart,
            ul.tiny-cart li, 
            .navbar ul li a,
            .text-logo h1,

            .cap-header-fixed .menu > li > a,
            .cap-primary-menu .menu > li > a
            {
                line-height: <?php echo $cap_header_height; ?>px; 
            }

            ul.tiny-cart li {
                height: <?php echo $cap_header_height; ?>px; 
            }

            ul.tiny-cart li ul li, .cap-header-fixed-wrapper.cap-is-fixed ul.tiny-cart li ul li {
                height: auto;
            }

            ul.tiny-cart li:hover ul.cart_list {
                top: <?php echo $cap_header_height; ?>px;
            }

        <?php } ?>

        <?php if ( $cap_fixed_header_height ) { ?>
            .cap-header-fixed-wrapper.cap-is-fixed .header, 
            .cap-header-fixed-wrapper.cap-is-fixed ul.tiny-cart,
            .cap-header-fixed-wrapper.cap-is-fixed ul.tiny-cart li, 
            .cap-header-fixed-wrapper.cap-is-fixed .mean-bar,
            .cap-header-fixed .menu, .cap-primary-menu .menu

            {
                height: <?php echo $cap_fixed_header_height; ?>px; 
            }

            .cap-header-fixed-wrapper.cap-is-fixed ul.tiny-cart li:hover ul.cart_list
            {
                top: <?php echo $cap_fixed_header_height; ?>px;
            }

            .cap-header-fixed-wrapper.cap-is-fixed .cap-header-fixed .menu > li > a,
            .cap-header-fixed-wrapper.cap-is-fixed .text-logo h1,
            .cap-header-fixed-wrapper.cap-is-fixed ul.tiny-cart,
            .cap-header-fixed-wrapper.cap-is-fixed ul.tiny-cart li,
            .cap-header-fixed-wrapper.cap-is-fixed .navbar ul li a
            {
                line-height: <?php echo $cap_fixed_header_height; ?>px;
            }

            .cap-header-fixed-wrapper.cap-is-fixed .logo img {
                max-height: <?php echo $cap_fixed_header_height; ?>px; 
            }

            .cap-header-fixed-wrapper.cap-is-fixed .logo {
                height: <?php echo $cap_fixed_header_height; ?>px; 
            }

        <?php } ?>

        <?php if ( $cap_header_height_mobile ) { ?>

            @media only screen and (max-width: 1100px) { 

                .header,
                ul.tiny-cart,
                ul.tiny-cart li,
                .mean-bar,
                .cap-menu-default,
                .cap-menu-default .logo,
                .cap-menu-below,
                .cap-menu-below .logo

                {
                    /* $cap_header_height_mobile */
                    height: <?php echo $cap_header_height_mobile; ?>px; 
                }

                .cap-menu-default .logo img, .cap-menu-below .logo img {
                    max-height: <?php echo $cap_header_height_mobile; ?>px; 
                }

                ul.tiny-cart, 
                .logo a,
                .navbar ul li a,
                .text-logo h1
                {
                    /* $cap_header_height_mobile */
                    line-height: <?php echo $cap_header_height_mobile; ?>px !important; 
                }

                ul.tiny-cart li {
                    line-height: inherit !important;
                }

                ul.tiny-cart li:hover ul.cart_list {
                    top: <?php echo $cap_header_height_mobile; ?>px;
                }

                .logo img {
                    max-height: <?php echo $cap_header_height_mobile; ?>px;
                }

                .mean-container a.meanmenu-reveal {
                    padding: <?php echo $cap_mobile_menu_padding; ?>px 15px;
                }

                .mean-container .mean-nav {
                    top: <?php echo $cap_header_height_mobile; ?>px;
                }

            }

        <?php } ?>

        <?php

        if ( $cap_custom_css ) {
            echo $cap_custom_css;
        }
        ?>

    </style>

    <?php
}

function hex2rgb( $hex ) {
    $hex = str_replace( "#", "", $hex );

    if ( strlen( $hex ) == 3 ) {
        $r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
        $g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
        $b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
    } else {
        $r = hexdec( substr( $hex, 0, 2 ) );
        $g = hexdec( substr( $hex, 2, 2 ) );
        $b = hexdec( substr( $hex, 4, 2 ) );
    }
    $rgb = array( $r, $g, $b );
    return implode( ",", $rgb ); // returns the rgb values separated by commas
    //return $rgb; // returns an array with the rgb values
}

add_action( 'wp_head', 'captiva_custom_css', 100 );
