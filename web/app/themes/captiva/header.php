<?php
/**
 * The header for our theme.
 *
 *
 * @package captiva
 */
global $captiva_options;
$protocol = ( !empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https:" : "http:";

$cap_responsive_status = '';

if ( isset( $captiva_options['cap_responsive'] ) ) {
    $cap_responsive_status = $captiva_options['cap_responsive'];
}

$cap_preloader_status = '';
$cap_logo = '';

$cap_favicon = '';

if ( isset( $captiva_options['cap_favicon']['url'] ) ) {
    $captiva_options['cap_favicon']['url'] = $protocol . str_replace( array( 'http:', 'https:' ), '', $captiva_options['cap_favicon']['url'] );
    $cap_favicon = $captiva_options['cap_favicon']['url'];
}

$cap_retina_favicon = '';

if ( isset( $captiva_options['cap_retina_favicon']['url'] ) ) {
    $captiva_options['cap_retina_favicon']['url'] = $protocol . str_replace( array( 'http:', 'https:' ), '', $captiva_options['cap_retina_favicon']['url'] );
    $cap_retina_favicon = $captiva_options['cap_retina_favicon']['url'];
}

$cap_topbar_display = '';

if ( isset( $captiva_options['cap_topbar_display'] ) ) {
    $cap_topbar_display = $captiva_options['cap_topbar_display'];
}

$cap_topbar_message = '';

if ( isset( $captiva_options['cap_topbar_message'] ) ) {
    $cap_topbar_message = $captiva_options['cap_topbar_message'];
}

$cap_display_cart = '';

if ( isset( $captiva_options['cap_show_cart'] ) ) {
    $cap_display_cart = $captiva_options['cap_show_cart'];
}

$cap_catalog = '';

if ( isset( $captiva_options['cap_catalog_mode'] ) ) {
    $cap_catalog = $captiva_options['cap_catalog_mode'];
}

$cap_primary_menu_layout = '';

if ( isset( $captiva_options['cap_primary_menu_layout'] ) ) {
    $cap_primary_menu_layout = $captiva_options['cap_primary_menu_layout'];
}

$cap_sticky_menu = '';

if ( isset( $captiva_options['cap_sticky_menu'] ) ) {
    $cap_sticky_menu = $captiva_options['cap_sticky_menu'];
}

if (!empty($_SESSION['cap_header_top'])){
    $cap_topbar_display = $_SESSION['cap_header_top'];
}

?>
<!DOCTYPE html>
<!--[if IE 9 ]><html class="no-js ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <?php
        if ( $cap_responsive_status == 'enabled' ) {
            ?>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php } ?>
        <title><?php wp_title( '|', true, 'right' ); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">        
        <link rel="shortcut icon" href="<?php
        if ( $cap_favicon ) { 
            echo $cap_favicon; 
        } else { ?><?php echo get_template_directory_uri(); ?>/favicon.png<?php }?>"/>

         <link rel="shortcut icon" href="<?php
        if ( $cap_retina_favicon ) { 
            echo $cap_retina_favicon; 
        } else { ?><?php echo get_template_directory_uri(); ?>/apple-touch-icon-precomposed.png<?php }?>"/>
        <!--[if lte IE 9]><script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.js"></script><![endif]-->
       <?php wp_head(); ?>
    </head>
    <body id="skrollr-body" <?php body_class(); ?>>
        <?php
        $cap_preloader_status = $captiva_options['cap_preloader'];
        if ( $cap_preloader_status == 'enabled' ) {
            ?>
            <div id="preloader">
                <div id="status">
                    <div class="loader-container">
                        <div class="ball"></div>
                        <div class="ball"></div>
                        <div class="ball"></div>
                        <div class="ball"></div>
                        <div class="ball"></div>
                        <div class="ball"></div>
                        <div class="ball"></div>
                    </div>
                </div>
            </div>
            <!-- /preloader -->
        <?php } ?>

        <div id="wrapper">
            <?php
            if ( $cap_topbar_display == 'yes' ) {
                ?>
                <div id="top">
                    <div class="container">
                        <div class="row">
                            <div class="top-msg-wrap col-lg-6 col-md-6 col-sm-6 hidden-xs">
                                <div class="payment-acceptance-logos">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/staydry/images/visa-logo.svg" alt="Order online securely with Visa">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/staydry/images/mastercard-logo.svg" alt="Order online securely with Mastercard">
                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/staydry/images/paypal-p-logo.svg" alt="Order online securely with PayPal">
                                </div>
                                <div class="topbar-phone-number">
                                    <a href="tel:1-866-944-0449">
                                        <span class="top-line">Order by Phone or Online</span>
                                        <br>
                                        <span class="bottom-line">1-866-944-0449</span>
                                    </a>
                                </div>
                                <?php
                                if ( $cap_topbar_message ) {
                                    ?>
                                    <div class="top-bar-msg"><?php echo $cap_topbar_message; ?></div>
                                <?php } ?>
                            </div>
                            <div class="top-nav-wrap col-lg-6 col-md-6 col-sm-6 hidden-xs">
                                <!-- Single button -->
                                <div id="top-menu-wrap">
                                    <ul class="nav-pills top-menu pull-right">
                                        <?php
                                        wp_nav_menu( array(
                                            'menu' => 'top',
                                            'theme_location' => 'top',
                                            'depth' => 2,
                                            'container' => false,
                                            'container_class' => false,
                                            'items_wrap' => '%3$s',
                                            'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                                            'walker' => new wp_bootstrap_navwalker()
                                                )
                                        );
                                        ?>
                                    </ul>
                                </div>
                                <div id="top-bar-wrap">
                                    <?php if ( is_active_sidebar( 'top-bar-search' ) ) : ?>
                                        <div id="top-bar-search">
                                            <?php dynamic_sidebar( 'top-bar-search' ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <!-- primary menu below logo layout -->
            <?php if ( $cap_primary_menu_layout == 'menubelow' ) { ?>
            <div class="cap-menu-below">
                <div class="container">
                    <div class="cap-logo-cart-wrap">
                        <div class="cap-logo-inner-cart-wrap">
                            <div class="row">
                                <div class="container">
                                    <div class="cap-wp-menu-wrapper">
                                        <div id="load-mobile-menu">
                                        </div>
                                        <?php if ( $cap_display_cart !== 'no' ) { ?>
                                            <?php if ( $cap_catalog == 'disabled' ) { ?>
                                                <div class="cart-wrap">
                                                    <?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                                                        ?>
                                                        <?php echo captiva_woocommerce_cart_dropdown(); ?>
                                                    <?php }
                                                    ?>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php
                                        if ( isset( $captiva_options['site_logo']['url'] ) ) {
                                            $captiva_options['site_logo']['url'] = $protocol . str_replace( array( 'http:', 'https:' ), '', $captiva_options['site_logo']['url'] );
                                            $cap_logo = $captiva_options['site_logo']['url'];
                                        }

                                        if ( !empty ( $_SESSION['cap_skin_color'] ) ){
                                            $cap_skin_color = $_SESSION['cap_skin_color'];
                                            if ( $cap_skin_color == '#169fda' ) {
                                                $cap_logo = CAP_THEMEURI . '/images/logo_blue.png';
                                            } elseif ( $cap_skin_color == '#dd3333' ) {
                                                $cap_logo = CAP_THEMEURI . '/images/logo_red.png';
                                            } elseif ( $cap_skin_color == '#208e3c' ) {
                                                $cap_logo = CAP_THEMEURI . '/images/logo_green.png';
                                            } elseif ( $cap_skin_color == '#e9690c' ) {
                                                $cap_logo = CAP_THEMEURI . '/images/logo_orange.png';
                                            }
                                        }

                                        if ( $cap_logo ) {
                                            $cap_logo_width = $captiva_options['site_logo']['width'];
                                            $cap_logo_max_width = $cap_logo_width / 2;
                                            ?>

                                            <div class="logo image">
                                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" style="max-width: <?php echo $cap_logo_max_width; ?>px;">
                                                    <span class="helper"></span><img src="<?php echo $cap_logo; ?>" alt="<?php bloginfo( 'name' ); ?>"/></a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="logo text-logo">
                                                <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div><!--/container -->
                            </div><!--/row -->
                        </div><!--/cap-logo-inner-cart-wrap -->
                    </div><!--/cap-logo-cart-wrap -->
                </div><!--/container -->
            </div><!--/cap-menu-below -->
            <div class="cap-primary-menu cap-wp-menu-wrapper cap-primary-menu-below-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="container">
                            <?php if ( has_nav_menu( 'primary' ) ) { ?>
                                <?php
                                wp_nav_menu( array(
                                    'theme_location' => 'primary',
                                    'before' => '',
                                    'after' => '',
                                    'link_before' => '',
                                    'link_after' => '',
                                    'depth' => 4,
                                    'container' => 'div',
                                    'container_class' => 'cap-main-menu',
                                    'fallback_cb' => false,
                                    'walker' => new primary_cg_menu() )
                                );
                                ?>
                            <?php } else { ?>
                                <p class="setup-message">You can set your main menu in <strong>Appearance &gt; Menus</strong></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- close primary menu below logo layout -->
            <?php } else { ?>
            <!-- primary menu to the right of logo layout -->
            <div class="cap-menu-default">
                <div class="container">
                    <div class="cap-wp-menu-wrapper">
                        <div class="cap-primary-menu">
                            <div class="row">
                                <div class="container">
                                    <div class="cap-wp-menu-wrapper">
                                    <div id="load-mobile-menu">
                                </div>
                                        <?php if ( $cap_display_cart !== 'no' ) { ?>
                                            <?php if ( $cap_catalog == 'disabled' ) { ?>
                                                <div class="cart-wrap">
                                                    <?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                                                        ?>
                                                        <?php echo captiva_woocommerce_cart_dropdown(); ?>
                                                    <?php }
                                                    ?>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>

                                        <?php
                                        if ( isset( $captiva_options['site_logo']['url'] ) ) {
                                            $captiva_options['site_logo']['url'] = $protocol . str_replace( array( 'http:', 'https:' ), '', $captiva_options['site_logo']['url'] );
                                            $cap_logo = $captiva_options['site_logo']['url'];
                                        }

                                        if ( !empty ( $_SESSION['cap_skin_color'] ) ){
                                            $cap_skin_color = $_SESSION['cap_skin_color'];
                                            if ( $cap_skin_color == '#169fda' ) {
                                                $cap_logo = CAP_THEMEURI . '/images/logo_blue.png';
                                            } elseif ( $cap_skin_color == '#dd3333' ) {
                                                $cap_logo = CAP_THEMEURI . '/images/logo_red.png';
                                            } elseif ( $cap_skin_color == '#208e3c' ) {
                                                $cap_logo = CAP_THEMEURI . '/images/logo_green.png';
                                            } elseif ( $cap_skin_color == '#e9690c' ) {
                                                $cap_logo = CAP_THEMEURI . '/images/logo_orange.png';
                                            }
                                        }

                                        if ( $cap_logo ) {
                                            $cap_logo_width = $captiva_options['site_logo']['width'];
                                            $cap_logo_max_width = $cap_logo_width / 2;
                                            ?>

                                            <div class="logo image">
                                                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" style="max-width: <?php echo $cap_logo_max_width; ?>px;">
                                                    <span class="helper"></span><img src="<?php echo $cap_logo; ?>" alt="<?php bloginfo( 'name' ); ?>"/></a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="logo text-logo">
                                                <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                                            </div>
                                        <?php } ?>
                                    </div><!--/cap-wp-menu-wrapper -->
                                        <?php if ( has_nav_menu( 'primary' ) ) { ?>
                                            <?php
                                            wp_nav_menu( array(
                                                'theme_location' => 'primary',
                                                'before' => '',
                                                'after' => '',
                                                'link_before' => '',
                                                'link_after' => '',
                                                'depth' => 4,
                                                'container' => 'div',
                                                'container_class' => 'cap-main-menu',
                                                'fallback_cb' => false,
                                                'walker' => new primary_cg_menu() )
                                            );
                                            ?>
                                        <?php } else { ?>
                                            <p class="setup-message">You can set your main menu in <strong>Appearance &gt; Menus</strong></p>
                                        <?php } ?>
                                    <!-- end primary menu below logo layout -->
                                </div><!--/container -->
                            </div><!--/row -->
                        </div><!--/cap-primary-menu -->
                    </div><!--/cap-wp-menu-wrapper -->
                </div><!--/container -->
            </div><!--/cap-menu-default -->
            <!-- close primary menu to the right of logo layout -->
            <?php } ?>

            <?php 
            if ( $cap_sticky_menu == 'yes' ) {
            ?>
            <!--FIXED -->
            <div class="cap-header-fixed-wrapper">
                <div class="cap-header-fixed">
                    <div class="container">
                        <div class="cap-wp-menu-wrapper">
                            <div class="cap-primary-menu">
                                <div class="row">
                                    <div class="container">
                                        <div class="cap-wp-menu-wrapper">
                                            <?php if ( $cap_display_cart !== 'no' ) { ?>
                                                <?php if ( $cap_catalog == 'disabled' ) { ?>
                                                    <div class="cart-wrap">
                                                        <?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                                                            ?>
                                                            <?php echo captiva_woocommerce_cart_dropdown(); ?>
                                                        <?php }
                                                        ?>
                                                    </div>
                                                <?php } ?>
                                            <?php } ?>

                                            <?php

                                            if ( isset( $captiva_options['site_logo']['url'] ) ) {
                                                $captiva_options['site_logo']['url'] = $protocol . str_replace( array( 'http:', 'https:' ), '', $captiva_options['site_logo']['url'] );
                                                $cap_logo = $captiva_options['site_logo']['url'];
                                            }
                                            
                                            if ( $cap_logo ) {
                                                $cap_logo_width = $captiva_options['site_logo']['width'];
                                                $cap_logo_max_width = $cap_logo_width / 2;
                                                ?>

                                                <div class="logo image">
                                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" style="max-width: <?php echo $cap_logo_max_width; ?>px;">
                                                        <span class="helper"></span><img src="<?php echo $cap_logo; ?>" alt="<?php bloginfo( 'name' ); ?>"/></a>
                                                </div>
                                            <?php } else { ?>
                                                <div class="logo text-logo">
                                                    <h1><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                                                </div>
                                            <?php } ?>
                                            <?php if ( has_nav_menu( 'primary' ) ) { ?>
                                                <?php
                                                wp_nav_menu( array(
                                                    'theme_location' => 'primary',
                                                    'before' => '',
                                                    'after' => '',
                                                    'link_before' => '',
                                                    'link_after' => '',
                                                    'depth' => 4,
                                                    'fallback_cb' => false,
                                                    'walker' => new primary_cg_menu() )
                                                );
                                                ?>
                                            <?php } else { ?>
                                                <p class="setup-message">You can set your main menu in <strong>Appearance &gt; Menus</strong></p>
                                            <?php } ?>
                                        </div><!--/cap-wp-menu-wrapper -->
                                    </div><!--/container -->
                                </div><!--/row -->
                            </div><!--/cap-primary-menu -->
                        </div><!--/cap-wp-menu-wrapper -->
                    </div><!--/container -->
                </div><!--/cap-header-fixed -->
            </div><!--/cap-header-fixed-wrapper. -->
            <?php }
            ?>
            

            <div id="mobile-menu">
                <a id="skip" href="#cap-page-wrap" class="hidden" title="<?php esc_attr_e( 'Skip to content', 'captiva' ); ?>"><?php _e( 'Skip to content', 'captiva' ); ?></a> 
                <?php

                if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'mobile' ) ) { 
                    wp_nav_menu( array('theme_location' => 'mobile', 'container' => 'ul', 'menu_id' => 'mobile-cap-mobile-menu', 'menu_class' => 'mobile-menu-wrap', 'walker' => new mobile_cg_menu()) );
                }
                elseif ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array('theme_location' => 'primary', 'container' => 'ul', 'menu_id' => 'mobile-cap-primary-menu', 'menu_class' => 'mobile-menu-wrap', 'walker' => new mobile_cg_menu()) );
                }
                ?>
            </div><!--/mobile-menu -->

            <div id="cap-page-wrap" class="hfeed site">
                <?php do_action( 'before' ); ?>
