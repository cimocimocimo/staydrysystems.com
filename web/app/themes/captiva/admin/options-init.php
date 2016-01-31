<?php

/**
 * ReduxFramework Captiva Config
 * */
if ( !class_exists( 'redux_theme_Redux_Framework_config' ) ) {

    class redux_theme_Redux_Framework_config {

        public $args = array();
        public $sections = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if ( !class_exists( 'ReduxFramework' ) ) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if ( true == Redux_Helpers::isTheme( __FILE__ ) ) {
                $this->initSettings();
            } else {
                add_action( 'plugins_loaded', array( $this, 'initSettings' ), 10 );
            }
        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if ( !isset( $this->args[ 'opt_name' ] ) ) { // No errors please
                return;
            }

            // If Redux is running as a plugin, this will remove the demo notice and links
            add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

            // Function to test the compiler hook and demo CSS output.
            // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
            // Change the arguments after they've been declared, but before the panel is created
            //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );
            // Change the default value of a field after it's been set, but before it's been useds
            //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );
            // Dynamically add a section. Can be also used to modify sections/fields
            //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

            $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
        }

        /**
         * This is a test function that will let you see when the compiler hook occurs.
         * It only runs if a field   set with compiler=>true is changed.
         * */
        function compiler_action( $options, $css ) {
            //echo '<h1>The compiler hook has run!';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
              require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
              $wp_filesystem->put_contents(
              $filename,
              $css,
              FS_CHMOD_FILE // predefined mode settings for WP files
              );
              }
             */
        }

        /**
         * Custom function for filtering the sections array. Good for child themes to override or add to the sections.
         * Simply include this function in the child themes functions.php file.
         * NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
         * so you must use get_template_directory_uri() if you want to use any of the built in icons
         * */
        function dynamic_section( $sections ) {
            //$sections = array();
            $sections[] = array(
                'title' => __( 'Section via hook', 'redux-framework-demo' ),
                'desc' => __( '<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'redux-framework-demo' ),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**
         * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
         * */
        function change_arguments( $args ) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**
         * Filter hook for filtering the default value of any given field. Very useful in development mode.
         * */
        function change_defaults( $defaults ) {
            $defaults[ 'str_replace' ] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::instance(), 'plugin_metalinks' ), null, 2 );

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action( 'admin_notices', array( ReduxFrameworkPlugin::instance(), 'admin_notices' ) );
            }
        }

        public function setSections() {

            /**
             * Captiva Theme Options sections
             * */
            $this->sections[] = array(
                'title' => __( 'Global Settings', 'redux-framework-demo' ),
                'desc' => __( 'Changes to major global elements.', 'redux-framework-demo' ),
                'icon' => 'el-icon-home',
                'fields' => array(
                    array(
                        'desc' => 'Select a container layout style',
                        'id' => 'container_style',
                        'type' => 'select',
                        'options' => array(
                            'full-width' => 'Full Width Layout',
                            'boxed' => 'Boxed Layout',
                        ),
                        'title' => 'Container layout style',
                        'default' => 'full-width',
                    ),
                    array(
                        'desc' => 'Enable or disable responsiveness on smartphones',
                        'id' => 'cap_responsive',
                        'type' => 'select',
                        'options' => array(
                            'enabled' => 'Enabled',
                            'disabled' => 'Disabled',
                        ),
                        'title' => 'Responsive',
                        'default' => 'enabled',
                    ),
                    array(
                        'desc' => 'Enable or disable the Page preloader',
                        'id' => 'cap_preloader',
                        'type' => 'select',
                        'options' => array(
                            'enabled' => 'Enabled',
                            'disabled' => 'Disabled',
                        ),
                        'title' => 'Page Preloader',
                        'default' => 'disabled',
                    ),
                    array(
                        'desc' => 'Display comments on pages?',
                        'id' => 'cap_page_comments',
                        'type' => 'select',
                        'options' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'title' => 'Comments on pages?',
                        'default' => 'no',
                    ),
                    array(
                        'id' => 'cap_background',
                        'type' => 'background',
                        'title' => __( 'Body Background - Full image', 'captiva' ),
                        'subtitle' => __( 'Configure your theme background - use this option if you would like to use a full size image like a photo as your background.', 'captiva' ),
                        'background-position' => false,
                        'background-size' => false,
                        'background-attachment' => false,
                        'default' => array(
                            'background-color' => '#efefef',
                        ),
                    ),
                    array(
                        'id' => 'cap_pattern_background',
                        'type' => 'background',
                        'title' => __( 'Body Background - Pattern', 'captiva' ),
                        'subtitle' => __( 'Use this option if you want to use a repeating pattern for your background. Note: Do not try to use both a pattern background and a full size image background! ', 'captiva' ),
                        'background-position' => false,
                        'background-size' => false,
                        'background-attachment' => false,
                        'default' => array(
                            'background-color' => '#efefef',
                        ),
                    ),
                    array(
                        'id' => 'cap_page_wrapper_color',
                        'type' => 'color',
                        'title' => __( 'Main body wrapper color', 'captiva' ),
                        'subtitle' => __( 'Configure your theme wrapper.', 'captiva' ),
                        'default' => '#fff',
                    ),
                ),
            );

            $this->sections[] = array(
                'title' => __( 'Colors', 'captiva' ),
                'desc' => __( 'Customize your theme color palette.', 'captiva' ),
                'icon' => 'el-icon-tint',
                'fields' => array(
                    array(
                        'desc' => 'Select from one of the predefined color skins, or select your own colors below.',
                        'id' => 'cap_skin_color',
                        'type' => 'select',
                        'options' => array(
                            'none'        => 'No skin - use custom',
                            '#169fda' => 'Blue',
                            '#dd3333' => 'Red',
                            '#208e3c' => 'Green',
                            '#e9690c' => 'Orange',
                        ),
                        'title' => 'Color Skin',
                        'default' => 'none',
                    ),
                    array(
                        'id' => 'cap_primary_color',
                        'type' => 'color',
                        'title' => __( 'Primary theme color', 'captiva' ),
                        'subtitle' => __( 'This should be something unique about your site.', 'captiva' ),
                        'default' => '#169fda',
                    ),
                    array(
                        'id' => 'cap_active_link_color',
                        'type' => 'color',
                        'title' => __( 'Active link color', 'captiva' ),
                        'subtitle' => __( 'The color of active links.', 'captiva' ),
                        'default' => '#169fda',
                    ),
                    array(
                        'id' => 'cap_link_hover_color',
                        'type' => 'color',
                        'title' => __( 'Link hover color', 'captiva' ),
                        'subtitle' => __( 'The color of your links in the hover state.', 'captiva' ),
                        'default' => '#169fda',
                    ),
                    array(
                        'id' => 'cap_topbar_bg_color',
                        'type' => 'color',
                        'title' => __( 'Top Bar Background Color', 'captiva' ),
                        'subtitle' => __( 'The color of the top bar background.', 'captiva' ),
                        'default' => '#169fda',
                    ),
                    array(
                        'id' => 'cap_header_bg_color',
                        'type' => 'color',
                        'title' => __( 'Header Background Color', 'captiva' ),
                        'subtitle' => __( 'The color of the header background.', 'captiva' ),
                        'default' => '#fff',
                    ),
                    array(
                        'id' => 'cap_header_fixed_bg_color',
                        'type' => 'color',
                        'title' => __( 'Fixed Header Background Color', 'captiva' ),
                        'subtitle' => __( 'The color of the fixed header background.', 'captiva' ),
                        'default' => '#fff',
                    ),
                    array(
                        'id' => 'cap_header_cart_text_color',
                        'type' => 'color',
                        'title' => __( 'Header Cart Text Color', 'captiva' ),
                        'subtitle' => __( 'The color of the header cart text.', 'captiva' ),
                        'default' => '#111',
                    ),
                    array(
                        'id' => 'cap_first_footer_bg',
                        'type' => 'color',
                        'title' => __( 'First footer background color', 'captiva' ),
                        'subtitle' => __( 'The background color of the first (top) footer.', 'captiva' ),
                        'default' => '#f2f2f2',
                    ),
                    array(
                        'id' => 'cap_second_footer_bg',
                        'type' => 'color',
                        'title' => __( 'Second footer background color', 'captiva' ),
                        'subtitle' => __( 'The background color of the second (bottom) footer.', 'captiva' ),
                        'default' => '#111',
                    ),
                    array(
                        'id' => 'cap_last_footer_bg',
                        'type' => 'color',
                        'title' => __( 'Last footer background color', 'captiva' ),
                        'subtitle' => __( 'The background color of the last footer (where the Copyright notice normally appears.', 'captiva' ),
                        'default' => '#111',
                    ),
                    array(
                        'id' => 'cap_first_footer_text',
                        'type' => 'color',
                        'title' => __( 'First footer text color', 'captiva' ),
                        'subtitle' => __( 'The text color of the first (top) footer.', 'captiva' ),
                        'default' => '#222',
                    ),
                    array(
                        'id' => 'cap_second_footer_text',
                        'type' => 'color',
                        'title' => __( 'Second footer text color', 'captiva' ),
                        'subtitle' => __( 'The text color of the second (bottom) footer.', 'captiva' ),
                        'default' => '#ccc',
                    ),
                    array(
                        'id' => 'cap_last_footer_text',
                        'type' => 'color',
                        'title' => __( 'Last footer text color', 'captiva' ),
                        'subtitle' => __( 'The text color of the last footer (where the Copyright notice normally appears.', 'captiva' ),
                        'default' => '#777',
                    ),
                ),
            );

            $this->sections[] = array(
                'title' => __( 'Logos and icons', 'captiva' ),
                'desc' => __( 'Update your logos and icons.', 'captiva' ),
                'icon' => 'el-icon-photo',
                'fields' => array(
                    array(
                        'desc' => 'Upload logo here.',
                        'id' => 'site_logo',
                        'type' => 'media',
                        'title' => 'Logo',
                        'url' => true,
                    ),
                    array(
                        'desc' => 'Add your custom Favicon image. 16x16px .ico or .png.',
                        'id' => 'cap_favicon',
                        'type' => 'media',
                        'title' => 'Favicon',
                        'url' => true,
                    ),
                    array(
                        'desc' => 'The Retina/iOS version of your Favicon. 144x144px .png.',
                        'id' => 'cap_retina_favicon',
                        'type' => 'media',
                        'title' => 'Favicon retina',
                        'url' => true,
                    ),
                ),
            );

            $this->sections[] = array(
                'title' => __( 'Top bar', 'captiva' ),
                'desc' => __( 'Control settings for the top bar.', 'captiva' ),
                'icon' => 'el-icon-tasks',
                'fields' => array(
                    array(
                        'desc' => 'Display top bar?',
                        'id' => 'cap_topbar_display',
                        'type' => 'select',
                        'options' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'title' => 'Do you want to display a top bar in your header?',
                        'default' => 'yes',
                    ),
                    array(
                        'id' => 'cap_topbar_message',
                        'type' => 'text',
                        'title' => 'Top bar text message - keep it short!',
                        'default' => '<p>Shop womens and mens jeans, t-shirts, shoes, shirts and clothing online!</p>',
                    ),
                ),
            );

            $this->sections[] = array(
                'title' => __( 'Header settings', 'redux-framework-demo' ),
                'desc' => __( 'Manage your header.', 'redux-framework-demo' ),
                'icon' => 'el-icon-hand-up',
                'fields' => array(
                    array(
                        'desc' => 'Primary Menu Layout',
                        'id' => 'cap_primary_menu_layout',
                        'type' => 'select',
                        'title' => 'Select your preferred primary menu layout',
                        'options' => array(
                            'menuright' => 'Primary Menu to the right of the logo',
                            'menubelow' => 'Primary Menu underneath the logo',
                        ),
                        'default' => 'menuright',
                    ),
                    array(
                        'desc' => 'Set height of header in px.',
                        'id' => 'cap_header_height',
                        'min' => '80',
                        'step' => '1',
                        'max' => '500',
                        'type' => 'slider',
                        'title' => 'Header height',
                        'default' => '100',
                    ),
                    array(
                        'desc' => 'Set height of the fixed header in px.',
                        'id' => 'cap_fixed_header_height',
                        'min' => '50',
                        'step' => '1',
                        'max' => '500',
                        'type' => 'slider',
                        'title' => 'Sticky/Fixed header height',
                        'default' => '60',
                    ),
                    array(
                        'desc' => 'Set height of header in px for display on smartphones.',
                        'id' => 'cap_header_height_mobile',
                        'min' => '40',
                        'step' => '1',
                        'max' => '500',
                        'type' => 'slider',
                        'title' => 'Mobile device header height',
                        'default' => '60',
                    ),
                    array(
                        'desc' => 'Show cart in header?',
                        'id' => 'cap_show_cart',
                        'type' => 'select',
                        'title' => 'Show cart?',
                        'options' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'default' => 'yes',
                    ),
                ),
            );

            $this->sections[] = array(
                'title' => __( 'Main menu settings', 'redux-framework-demo' ),
                'desc' => __( 'Manage your main menu.', 'redux-framework-demo' ),
                'icon' => 'el-icon-cog-alt',
                'fields' => array(
                    array(
                        'id' => 'cap_level1_font',
                        'type' => 'typography',
                        'title' => __( 'Level 1 Typeface', 'redux-framework-demo' ),
                        'text-transform' => true,
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
                        'output' => array( '.cap-primary-menu .menu > li > a' ), // An array of CSS selectors to apply this font style to dynamically
                        'units' => 'px', // Defaults to px
                        'subtitle' => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                        'default' => array(
                            'color' => '#333',
                            'font-weight' => '700',
                            'font-family' => 'Lato',
                            'google' => true,
                            'font-size' => '14px',
                        ),
                    ),
                    array(
                        'id' => 'cap_level2_font',
                        'type' => 'typography',
                        'title' => __( 'Level 2 Typeface', 'redux-framework-demo' ),
                        'text-transform' => true,                        
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
                        'output' => array( '.cap-primary-menu .menu > li .cap-submenu-ddown .container > ul > li a, .cap-submenu-ddown .container > ul > li > a' ), // An array of CSS selectors to apply this font style to dynamically
                        'units' => 'px', // Defaults to px
                        'subtitle' => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                        'default' => array(
                            'color' => '#333',
                            'font-family' => 'Lato',
                            'google' => true,
                            'font-size' => '13px',
                        ),
                    ),
                    array(
                        'id' => 'cap_main_menu_dropdown_bg',
                        'type' => 'color_rgba',
                        'title' => __( 'Dropdown menu background color.', 'captiva' ),
                        'default'  => array(
                            'color' => '#fff', 
                            'alpha' => '0.97'
                        ),
                        'output' => array(
                            '.cap-header-fixed .menu > li .cap-submenu-ddown, .cap-primary-menu .menu > li .cap-submenu-ddown, .cap-header-fixed .menu > li.menu-full-width .cap-submenu-ddown, .cap-primary-menu .menu > li.menu-full-width .cap-submenu-ddown, .cap-header-fixed .menu > li .cap-submenu-ddown .container > ul .menu-item-has-children .cap-submenu li, .cap-primary-menu .menu > li .cap-submenu-ddown .container > ul .menu-item-has-children .cap-submenu li,.cap-header-fixed .menu > li.menu-full-width .cap-submenu-ddown,.cap-primary-menu .menu > li.menu-full-width .cap-submenu-ddown'
                        ),
                        // 'output' => array(
                        //     'background-color' => '
                        //     .cap-header-fixed .menu > li .cap-submenu-ddown, .cap-primary-menu .menu > li .cap-submenu-ddown, .cap-header-fixed .menu > li.menu-full-width .cap-submenu-ddown, .cap-primary-menu .menu > li.menu-full-width .cap-submenu-ddown, .cap-header-fixed .menu > li .cap-submenu-ddown .container > ul .menu-item-has-children .cap-submenu li, .cap-primary-menu .menu > li .cap-submenu-ddown .container > ul .menu-item-has-children .cap-submenu li,.cap-header-fixed .menu > li.menu-full-width .cap-submenu-ddown,.cap-primary-menu .menu > li.menu-full-width .cap-submenu-ddown',
                        //     'border-bottom-color' => '
                        //     .cap-header-fixed .menu > li.menu-item-has-children:hover > a:before, .cap-primary-menu .menu > li.menu-item-has-children:hover > a:before'
                        // ),
                        'mode' => 'background',
                    ),
                    array( 
                        'id'       => 'cap_submenu_border',
                        'type'     => 'border',
                        'title'    => __('Dropdown menu border color', 'redux-framework-demo'),
                        'subtitle' => __('Change the color of borders applied to menu items in the main menu dropdown', 'redux-framework-demo'),
                        'output'   => array('.cap-primary-menu .menu > li .cap-submenu-ddown .container > ul > li a, .cap-submenu-ddown .container > ul > li > a'),
                        'desc'     => __('Please bear in mind this border color should complement your dropdown background color.', 'redux-framework-demo'),
                        'default'  => array(
                            'border-color'  => '#eee', 
                            'border-style'  => 'solid', 
                            'border-top'    => '1px', 
                            'border-right'  => '1px', 
                            'border-bottom' => '0px', 
                            'border-left'   => '1px'
                        )
                    ),
                    array(
                        'desc' => 'Do you want to display the sticky menu? A sticky menu is a menu which fixes itself to the top of your screen as your scroll further down the page.',
                        'id' => 'cap_sticky_menu',
                        'type' => 'select',
                        'options' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'title' => 'Display sticky menu?',
                        'default' => 'yes',
                    ),
                ),
            );

            $this->sections[] = array(
                'title' => __( 'Footer settings', 'captiva' ),
                'desc' => __( 'Manage your footer.', 'captiva' ),
                'icon' => 'el-icon-hand-down',
                'fields' => array(
                    array(
                        'id' => 'cap_footer_message',
                        'type' => 'text',
                        'title' => 'Footer text',
                        'default' => '<p>&copy; 2014 Copyright CommerceGurus</p>',
                    ),
                    array(
                        'desc' => 'Show widget area just under body (and just before the footer?',
                        'id' => 'cap_below_body_widget',
                        'type' => 'select',
                        'title' => 'Show widget below body?',
                        'options' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'default' => 'no',
                    ),
                    array(
                        'desc' => 'Show top footer?',
                        'id' => 'cap_footer_top_active',
                        'type' => 'select',
                        'title' => 'Show top footer',
                        'options' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'default' => 'yes',
                    ),
                    array(
                        'desc' => 'Show bottom footer?',
                        'id' => 'cap_footer_bottom_active',
                        'type' => 'select',
                        'title' => 'Show bottom footer',
                        'options' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'default' => 'yes',
                    ),
                ),
            );

            $this->sections[] = array(
                'title' => __( 'Typography', 'redux-framework-demo' ),
                'desc' => __( 'Manage your fonts and typefaces.', 'redux-framework-demo' ),
                'icon' => 'el-icon-fontsize',
                'fields' => array(
                    array(
                        'id' => 'opt-typography-body',
                        'type' => 'typography',
                        'title' => __( 'Body/Main text font', 'redux-framework-demo' ),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        //'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
                        'output' => array( 'body', 'select', 'input', 'textarea', 'button' ), // An array of CSS selectors to apply this font style to dynamically
                        'units' => 'px', // Defaults to px
                        'subtitle' => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                        'default' => array(
                            'color' => '#444',
                            'font-weight' => '400',
                            'font-family' => 'Lato',
                            'google' => true,
                            'font-size' => '15px',
                            'line-height' => '23px'
                        ),
                    ),
                    array(
                        'id' => 'opt-typography-secondary',
                        'type' => 'typography',
                        'title' => __( 'Secondary font', 'redux-framework-demo' ),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        'font-size' => false,
                        'line-height' => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        'color' => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
                        'output' => array(
                            'a.btn', '.content-area a.btn', '.content-area a.btn:hover', '#respond input#submit', '.wpcf7 input.wpcf7-submit',
                            'ul.navbar-nav li .nav-dropdown > ul > li.menu-parent-item > a', 'ul.tiny-cart li ul.cart_list li.buttons .button', '#get-started .main h6', '.highlight-block h2 a', '.see-through', '.content-area .see-through', '.testimonials-wrap  span', '.faqs-reviews ul li h6', '.titlewrap h2', '.cap-product-info .category', '.page-numbers li span', '.page-numbers li a', '.pagination li span', '.pagination li a', '.products .onsale', '.woocommerce span.onsale', '.products .woocommerce-page span.onsale', '.onsale', '.woocommerce .container span.onsale', '.woocommerce-page .container span.onsale',
                            '.woocommerce .cart .quantity input.plus', '.woocommerce .cart .quantity input.minus', '.cart .quantity', '#respond h3', '.woocommerce .button',
                            '.woocommerce .container a.button', '.cap-product-cta',
                            '.woocommerce .container button.button',
                            '.woocommerce .container input.button',
                            '.woocommerce .container #respond input#submit',
                            '.woocommerce .container #content input.button',
                            '.woocommerce-page .container .cap-product-cta a.button',
                            '.cap-product-cta .button',
                            '.woocommerce-page .container a.button',
                            '.woocommerce-page .container button.button',
                            '.woocommerce-page .container input.button',
                            '.woocommerce-page .container #respond input#submit',
                            '.woocommerce-page .container #content input.button', '.added_to_cart', '.woocommerce .container div.product form.cart .button',
                            '.woocommerce .container #content div.product form.cart .button',
                            '.woocommerce-page .container div.product form.cart .button',
                            '.woocommerce-page .container #content div.product form.cart .button',
                            '.woocommerce-page .container p.cart a.button',
                            '.content-area .woocommerce .summary .button', '.woocommerce .container span.onsale', '.woocommerce-page .container span.onsale', '.woocommerce-tabs .entry-content h2', '.woocommerce-page .container a.button.small',
                            '.content-area .woocommerce a.button.small', '.widget_product_search input#searchsubmit', '.widget h4', '.widget h1', '.post-password-form input[type="submit"]', '.content-area .comments-area h2', '.content-area article a.more-link', '.blog-pagination ul li a', '.content-area table.cart tr th', '.content-area .cart_totals h2', '.content-area .coupon h3', '.content-area .order-wrap h3', '.woocommerce-page .container form.login input.button', '.subfooter h4', '.subfooter #mc_signup_submit', '.container .wpb_row .wpb_call_to_action a .wpb_button', '.container .vc_btn',
                            '.wpb_button', 'body .wpb_teaser_grid .categories_filter li a', '#filters button', '.cap-product-wrap a .category', '.lightwrapper h4'
                        ),
                        'compiler' => array( 'h2.site-description-compiler' ), // An array of CSS selectors to apply this font style to dynamically
                        'units' => 'px', // Defaults to px
                        'subtitle' => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                        'default' => array(
                            'font-weight' => '700',
                            'font-family' => 'Quicksand',
                            'google' => true,
                        ),
                    ),
                    array(
                        'id' => 'opt-typography-h1',
                        'type' => 'typography',
                        'title' => __( 'Heading 1 Style', 'redux-framework-demo' ),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        //'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
                        'output' => array( '.content-area h1' ), // An array of CSS selectors to apply this font style to dynamically
                        'units' => 'px', // Defaults to px
                        'subtitle' => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                        'default' => array(
                            'color' => '#444',
                            'font-weight' => '700',
                            'font-family' => 'Lato',
                            'google' => true,
                            'font-size' => '32px',
                            'line-height' => '45px'
                        ),
                    ),
                    array(
                        'id' => 'opt-typography-h2',
                        'type' => 'typography',
                        'title' => __( 'Heading 2 Style', 'redux-framework-demo' ),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        //'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
                        'output' => array( 'h2', '.content-area h2' ), // An array of CSS selectors to apply this font style to dynamically
                        'units' => 'px', // Defaults to px
                        'subtitle' => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                        'default' => array(
                            'color' => '#444',
                            'font-weight' => '400',
                            'font-family' => 'Lato',
                            'google' => true,
                            'font-size' => '30px',
                            'line-height' => '40px'
                        ),
                    ),
                    array(
                        'id' => 'opt-typography-h3',
                        'type' => 'typography',
                        'title' => __( 'Heading 3 Style', 'redux-framework-demo' ),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        //'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
                        'output' => array( 'h3', '.content-area h3' ), // An array of CSS selectors to apply this font style to dynamically
                        'units' => 'px', // Defaults to px
                        'subtitle' => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                        'default' => array(
                            'color' => '#444',
                            'font-weight' => '700',
                            'font-family' => 'Lato',
                            'google' => true,
                            'font-size' => '22px',
                            'line-height' => '28px'
                        ),
                    ),
                    array(
                        'id' => 'opt-typography-h4',
                        'type' => 'typography',
                        'title' => __( 'Heading 4 Style', 'redux-framework-demo' ),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        //'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
                        'output' => array( 'h4', '.content-area h4' ), // An array of CSS selectors to apply this font style to dynamically
                        'units' => 'px', // Defaults to px
                        'subtitle' => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                        'default' => array(
                            'color' => '#444',
                            'font-weight' => '400',
                            'font-family' => 'Lato',
                            'google' => true,
                            'font-size' => '18px',
                            'line-height' => '24px'
                        ),
                    ),
                    array(
                        'id' => 'opt-typography-h5',
                        'type' => 'typography',
                        'title' => __( 'Heading 5 Style', 'redux-framework-demo' ),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        //'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
                        'output' => array( 'h5', '.content-area h5' ), // An array of CSS selectors to apply this font style to dynamically
                        'units' => 'px', // Defaults to px
                        'subtitle' => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                        'default' => array(
                            'color' => '#444',
                            'font-weight' => '400',
                            'font-family' => 'Lato',
                            'google' => true,
                            'font-size' => '17px',
                            'line-height' => '22px'
                        ),
                    ),
                    array(
                        'id' => 'opt-typography-h6',
                        'type' => 'typography',
                        'title' => __( 'Heading 6 Style', 'redux-framework-demo' ),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        //'line-height'   => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
                        'output' => array( 'h6', '.content-area h6' ), // An array of CSS selectors to apply this font style to dynamically
                        'units' => 'px',
                        //'units'         => array('px', 'em'), // Defaults to px
                        //'units_extended' => true,
                        'subtitle' => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                        'default' => array(
                            'color' => '#444',
                            'font-weight' => '400',
                            'font-family' => 'Lato',
                            'google' => true,
                            'font-size' => '15px',
                            'line-height' => '20px'
                        ),
                    ),
                    array(
                        'id' => 'opt-typography-menu',
                        'type' => 'typography',
                        'title' => __( 'Menu', 'redux-framework-demo' ),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google' => true, // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup' => true, // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        'line-height' => false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles' => true, // Enable all Google Font style/weight variations to be added to the page
                        'output' => array( '.navbar ul li a' ), // An array of CSS selectors to apply this font style to dynamically
                        'units' => 'px',
                        //'units'         => array('px', 'em'), // Defaults to px
                        //'units_extended' => true,
                        'subtitle' => __( 'Typography option with each property can be called individually.', 'redux-framework-demo' ),
                        'default' => array(
                            'color' => '#444',
                            'font-weight' => '600',
                            'font-family' => 'Lato',
                            'google' => true,
                            'font-size' => '14px',
                        ),
                    ),
                ),
            );

            $this->sections[] = array(
                'title' => __( 'Blog settings', 'captiva' ),
                'desc' => __( 'Manage your blog settings.', 'captiva' ),
                'icon' => 'el-icon-file-edit',
                'fields' => array(
                    array(
                        'desc' => 'Blog thumbnails',
                        'id' => 'cap_blog_images',
                        'type' => 'select',
                        'options' => array(
                            'default' => 'Default - above blog post',
                            'right' => 'Right Thumbnail',
                            'left' => 'Left Thumbnail',
                        ),
                        'title' => 'Which layout would like for your blog thumbnails?',
                        'default' => 'default',
                    ),
                    array(
                        'desc' => 'Blog sidebar',
                        'id' => 'cap_blog_sidebar',
                        'type' => 'select',
                        'options' => array(
                            'default' => 'Default - left sidebar',
                            'right' => 'Right sidebar',
                            'none' => 'No sidebar',
                        ),
                        'title' => 'Where would you like your blog sidebar to appear?',
                        'default' => 'default',
                    ), 
                ),
            );

            $this->sections[] = array(
                'title' => __( 'WooCommerce General Settings', 'captiva' ),
                'desc' => __( 'Global shop settings.', 'captiva' ),
                'icon' => ' el-icon-shopping-cart',
                'fields' => array(
                    array(
                        'title' => 'Catalog Mode',
                        'desc' => 'Enabling catalog mode will hide the shopping cart and add to cart options.',
                        'id' => 'cap_catalog_mode',
                        'type' => 'select',
                        'options' => array(
                            'enabled' => 'Enable',
                            'disabled' => 'Disable',
                        ),
                        'default' => 'disabled',
                    ),
                    array(
                        'title' => 'Hide Prices?',
                        'desc' => 'Select if you would like to hide prices? Note: Catalog mode must also be enabled if you wish to hide prices.',
                        'id' => 'cap_hide_prices',
                        'type' => 'select',
                        'options' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'default' => 'no',
                    ),
                    array(
                        'title' => 'Hide Categories?',
                        'desc' => 'Select if you would like to hide categories from the main product display loop?',
                        'id' => 'cap_hide_categories',
                        'type' => 'select',
                        'options' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'default' => 'no',
                    ),
                    array(
                        'id' => 'cap_show_credit_cards',
                        'type' => 'button_set',
                        'title' => __( 'Show/hide credit cards?', 'redux-framework-demo' ),
                        'subtitle' => __( 'Do you wish to show images of credit cards you accept?', 'redux-framework-demo' ),
                        'desc' => __( 'This credit card images will appear in your bottom footer in the right hand side.', 'redux-framework-demo' ),
                        //Must provide key => value pairs for radio options
                        'options' => array(
                            'show' => 'Show',
                            'hide' => 'Hide',
                        ),
                        'default' => 'show'
                    ),
                    array(
                        'title' => 'Select credit cards to display',
                        'desc' => 'You can show/hide any of the 4 card types below. You can also change the order using drag and drop.',
                        'id' => 'cap_show_credit_card_values',
                        'type' => 'sortable',
                        'mode' => 'checkbox',
                        'options' => array(
                            '1' => 'Visa',
                            '2' => 'Mastercard',
                            '3' => 'Paypal',
                            '4' => 'Amex',
                        ),
                        'default' => array(
                            '1' => true,
                            '2' => true,
                            '3' => true,
                            '4' => true,
                        ),
                    ),
                ),
            );

            $this->sections[] = array(
                'title' => __( 'WooCommerce Product Details', 'redux-framework-demo' ),
                'desc' => __( 'Manage product details page settings.', 'redux-framework-demo' ),
                'icon' => ' el-icon-shopping-cart-sign',
                'fields' => array(
                    array(
                        'id' => 'upsell_title',
                        'type' => 'text',
                        'title' => 'Up-sell title',
                        'default' => 'Complete the collection',
                    ),
                    array(
                        'id' => 'wc_product_sidebar',
                        'type' => 'select',
                        'options' => array(
                            'wc_product_no_sidebar' => 'None',
                            'wc_product_left_sidebar' => 'Sidebar on the left',
                            'wc_product_right_sidebar' => 'Sidebar on the right',
                        ),
                        'title' => 'Product Sidebar Position',
                        'default' => 'no_sidebar',
                    ),
                    array(
                        'id' => 'wc_product_sku',
                        'type' => 'select',
                        'options' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'title' => 'Display the Product SKU?',
                        'default' => 'yes',
                    ),
                    array(
                        'id' => 'product_size_guide_title',
                        'type' => 'text',
                        'title' => 'Size Guide Title',
                    ),
                    array(
                        'desc' => 'Upload your size guide images here.',
                        'id' => 'product_size_guide',
                        'type' => 'media',
                        'title' => 'Size Guide',
                        'url' => true,
                    ),
                    array(
                        'id' => 'product_share_icons',
                        'type' => 'select',
                        'options' => array(
                            'yes' => 'Yes',
                            'no' => 'No',
                        ),
                        'title' => 'Display social sharing icons?',
                        'default' => 'yes',
                    ),
                    array(
                        'id' => 'returns_tab_title',
                        'type' => 'text',
                        'title' => 'Delivery and Returns tab title',
                        'default' => 'Delivery and Returns Information',
                    ),
                    array(
                        'id' => 'returns_tab_content',
                        'type' => 'textarea',
                        'desc' => 'Add your delivery and returns content here.',
                        'title' => 'Delivery and Returns tab content',
                        'default' => 'Delivery and Returns Content description.',
                    ),
                ),
            );

            $this->sections[] = array(
                'title' => __( 'WooCommerce Product Listings', 'redux-framework-demo' ),
                'desc' => __( 'Manage product listing page settings.', 'redux-framework-demo' ),
                'icon' => '  el-icon-list-alt',
                'fields' => array(
                    array(
                        'desc' => 'Select sidebar position for product listing pages.',
                        'id' => 'product_listing_sidebar',
                        'type' => 'select',
                        'options' => array(
                            'none' => 'No sidebar',
                            'left-sidebar' => 'Sidebar on the left',
                            'right-sidebar' => 'Sidebar on the right',
                        ),
                        'title' => 'Product listing sidebar position',
                        'default' => 'left-sidebar',
                    ),
                    array(
                        'desc' => 'Select which type of layout you prefer for your product listings.',
                        'id' => 'product_layout',
                        'type' => 'select',
                        'options' => array(
                            'grid-layout' => 'Grid Layout',
                            'list-layout' => 'List Layout',
                        ),
                        'title' => 'Grid or List Layout',
                        'default' => 'grid-layout',
                    ),
                    array(
                        'desc' => 'Change the number of products per row for product listing pages.',
                        'id' => 'product_grid_count',
                        'type' => 'select',
                        'options' => array(
                            2 => '2',
                            3 => '3',
                            4 => '4',
                            5 => '5',
                            6 => '6',
                            7 => '7',
                            8 => '8',
                            9 => '9',
                        ),
                        'title' => 'Number of products per row',
                        'default' => '4',
                    ),
                    array(
                        'id' => 'products_page_count',
                        'desc' => 'Number of products per page on product listings pages.',
                        'type' => 'text',
                        'title' => 'Products per page',
                        'default' => '12',
                    ),
                    array(
                        'desc' => 'Enable or disable product thumbnail flip.',
                        'id' => 'cap_product_thumb_flip',
                        'type' => 'select',
                        'options' => array(
                            'enabled' => 'Enabled',
                            'disabled' => 'Disabled',
                        ),
                        'title' => 'Product Thumbnail Flip',
                        'default' => 'enabled',
                    ),
                    array(
                        'desc' => 'Enable or disable shop announcements that appear at the top of your product listings pages.',
                        'id' => 'cap_shop_announcements',
                        'type' => 'select',
                        'options' => array(
                            'enabled' => 'Enabled',
                            'disabled' => 'Disabled',
                        ),
                        'title' => 'Shop Announcements',
                        'default' => 'enabled',
                    ),
                ),
            );

            $this->sections[] = array(
                'title' => __( 'Custom code', 'captiva' ),
                'desc' => __( 'Add some custom code.', 'captiva' ),
                'fields' => array(
                    array(
                        'title' => 'Custom CSS',
                        'desc' => 'Add some custom css to your site?',
                        'id' => 'cap_custom_css',
                        'type' => 'ace_editor',
                        'mode' => 'css',
                        'theme' => 'monokai'
                    ),
                ),
            );
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args[ 'help_tabs' ][] = array(
                'id' => 'redux-help-tab-1',
                'title' => __( 'Theme Information 1', 'redux-framework-demo' ),
                'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
            );

            $this->args[ 'help_tabs' ][] = array(
                'id' => 'redux-help-tab-2',
                'title' => __( 'Theme Information 2', 'redux-framework-demo' ),
                'content' => __( '<p>This is the tab content, HTML is allowed.</p>', 'redux-framework-demo' )
            );

            // Set the help sidebar
            $this->args[ 'help_sidebar' ] = __( '<p>This is the sidebar content, HTML is allowed.</p>', 'redux-framework-demo' );
        }

        /**
         * Redux config
         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire

                'opt_name' => 'captiva_reduxopt', // This is where your data is stored in the database and also becomes your global variable name.
                'display_name' => $theme->get( 'Name' ), // Name that appears at the top of your panel
                'display_version' => $theme->get( 'Version' ), // Version that appears at the top of your panel
                'menu_type' => 'menu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu' => true, // Show the sections below the admin menu item or not
                'menu_title' => __( 'Captiva Theme Options', 'redux-framework-demo' ),
                'page_title' => __( 'Captiva Theme Options', 'redux-framework-demo' ),
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => 'AIzaSyB9TDy0IOriQpR8gt2TmoaZ070oWgIhvcs', // Must be defined to add google fonts to the typography module
                'async_typography' => false, // Use a asynchronous font on the front end or font string
                'admin_bar' => true, // Show the panel pages on the admin bar
                'global_variable' => 'captiva_options', // Set a different name for your global variable other than the opt_name
                'dev_mode' => false, // Show the time the page took to load, etc
                'customizer' => true, // Enable basic customizer support
                //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
                //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field
                // OPTIONAL -> Give you extra features
                'page_priority' => null, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent' => 'themes.php', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions' => 'manage_options', // Permissions needed to access the options panel.
                'menu_icon' => '', // Specify a custom URL to an icon
                'last_tab' => '', // Force your panel to always open to a specific tab (by id)
                'page_icon' => 'icon-themes', // Icon displayed in the admin panel next to your menu_title
                'page_slug' => 'captiva_reduxopt', // Page slug used to denote the panel
                'save_defaults' => true, // On load save the defaults to DB before user clicks save or not
                'default_show' => false, // If true, shows the default value next to each field that is not the default value.
                'default_mark' => '*', // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true, // Shows the Import/Export panel when not used as a field.
                // CAREFUL -> These options are for advanced use only
                'transient_time' => 60 * MINUTE_IN_SECONDS,
                'output' => true, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag' => true, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                'footer_credit' => false, // Disable the footer credit of Redux. Please leave if you can help it.
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database' => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info' => false, // REMOVE
                // HINTS
                'hints' => array(
                    'icon' => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color' => 'lightgray',
                    'icon_size' => 'normal',
                    'tip_style' => array(
                        'color' => 'light',
                        'shadow' => true,
                        'rounded' => false,
                        'style' => '',
                    ),
                    'tip_position' => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect' => array(
                        'show' => array(
                            'effect' => 'slide',
                            'duration' => '500',
                            'event' => 'mouseover',
                        ),
                        'hide' => array(
                            'effect' => 'slide',
                            'duration' => '500',
                            'event' => 'click mouseleave',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            //$this->args[ 'share_icons' ][] = array(
            //    'url' => 'https://github.com/ReduxFramework/ReduxFramework',
            //    'title' => 'Visit us on GitHub',
            //    'icon' => 'el-icon-github'
            //    //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            //);
            //$this->args[ 'share_icons' ][] = array(
            //    'url' => 'https://www.facebook.com/pages/Redux-Framework/243141545850368',
            //    'title' => 'Like us on Facebook',
            //    'icon' => 'el-icon-facebook'
            //);
            $this->args[ 'share_icons' ][] = array(
                'url' => 'http://twitter.com/commercegurus',
                'title' => 'Follow us on Twitter',
                'icon' => 'el-icon-twitter'
            );
            //$this->args[ 'share_icons' ][] = array(
            //    'url' => 'http://www.linkedin.com/company/redux-framework',
            //    'title' => 'Find us on LinkedIn',
            //    'icon' => 'el-icon-linkedin'
            //);

            // Panel Intro text -> before the form
            if ( !isset( $this->args[ 'global_variable' ] ) || $this->args[ 'global_variable' ] !== false ) {
                if ( !empty( $this->args[ 'global_variable' ] ) ) {
                    $v = $this->args[ 'global_variable' ];
                } else {
                    $v = str_replace( '-', '_', $this->args[ 'opt_name' ] );
                }
                //$this->args[ 'intro_text' ] = sprintf( __( '<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'redux-framework-demo' ), $v );
            } else {
                //$this->args[ 'intro_text' ] = __( '<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'redux-framework-demo' );
            }

            // Add content after the form.
            //$this->args[ 'footer_text' ] = __( '<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'redux-framework-demo' );
        }

    }

    global $reduxConfig;
    $reduxConfig = new redux_theme_Redux_Framework_config();
}

/**
 * Custom function for the callback referenced above
 */
if ( !function_exists( 'redux_theme_my_custom_field' ) ):

    function redux_theme_my_custom_field( $field, $value ) {
        print_r( $field );
        echo '<br/>';
        print_r( $value );
    }

endif;

/**
 * Custom function for the callback validation referenced above
 * */
if ( !function_exists( 'redux_theme_validate_callback_function' ) ):

    function redux_theme_validate_callback_function( $field, $value, $existing_value ) {
        $error = false;
        $value = 'just testing';

        /*
          do your validation

          if(something) {
          $value = $value;
          } elseif(something else) {
          $error = true;
          $value = $existing_value;
          $field['msg'] = 'your custom error message';
          }
         */

        $return[ 'value' ] = $value;
        if ( $error == true ) {
            $return[ 'error' ] = $field;
        }
        return $return;
    }




endif;
