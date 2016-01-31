// captiva js source 

;
( function( $ ) {
    "use strict";


    // Block Name: Sticky Header
    $( window ).scroll( function() {
        var capstickyHeader = $( '.cap-header-fixed-wrapper' );
        var scrollTop = $( this ).scrollTop();
        var capstickyHeaderHeight = $( '.header' ).height() + 200;

        if ( scrollTop > capstickyHeaderHeight ) {
            if ( !capstickyHeader.hasClass( 'cap-is-fixed' ) ) {
                capstickyHeader.stop().addClass( 'cap-is-fixed' );
            }
        } else {
            if ( capstickyHeader.hasClass( 'cap-is-fixed' ) ) {
                capstickyHeader.stop().removeClass( 'cap-is-fixed' );
            }
        }
    } );


    // Block Name: Main Menu
    $.fn.cap_primary_menu = function( options ) {
        var methods = {
            capshowmenuChildren: function( el ) {
                el.fadeIn( 100 ).css( {
                    display: 'list-item',
                    listStyle: 'none'
                } ).find( 'li' ).css( { listStyle: 'none' } );
            },
            capcalcColumns: function( el ) {
                var columnsCount = el.find(
                        '.container > ul > li.menu-item-has-children' ).length;
                var dropdownWidth = el.find( '.container > ul > li' ).
                        outerWidth();
                var padding = 20;
                if ( columnsCount > 1 ) {
                    dropdownWidth = dropdownWidth * columnsCount + padding;
                    el.css( {
                        'width': dropdownWidth
                    } );
                }

                var capmenuheaderWidth = $( '.cap-wp-menu-wrapper' ).outerWidth();
                var headerLeft = $( '.cap-wp-menu-wrapper' ).offset().left;
                var dropdownOffset = el.offset().left - headerLeft;
                var dropdownRight = capmenuheaderWidth - ( dropdownOffset + dropdownWidth );

                if ( dropdownRight < 0 ) {
                    el.css( {
                        'left': 'auto',
                        'right': 0
                    } );
                }
            },
            openOnClick: function( el, e ) {
                var timeOutTime = 0;
                var openedClass = "current";
                var header = $( '.header-wrapper' );
                var $this = el;

                if ( $this.parent().hasClass( openedClass ) ) {
                    e.preventDefault();
                    $this.parent().removeClass( openedClass );
                    $this.next().stop().slideUp( settings.animTime );
                    header.stop().animate( { 'paddingBottom': 0 },
                    settings.animTime );
                } else {

                    if ( $this.parent().find( '>div' ).length < 1 ) {
                        return;
                    }

                    e.preventDefault();

                    if ( $this.parent().parent().find(
                            '.' + openedClass ).length > 0 ) {
                        timeOutTime = settings.animTime;
                        header.stop().animate( { 'paddingBottom': 0 },
                        settings.animTime );
                    }

                    $this.parent().parent().find( '.' + openedClass ).
                            removeClass( openedClass ).find( '>div' ).stop().
                            slideUp( settings.animTime );

                    setTimeout( function() {
                        $this.parent().addClass( openedClass );
                        header.stop().animate( { 'paddingBottom': $this.next().
                                    height() + 50 }, settings.animTime );
                        $this.next().stop().slideDown( settings.animTime );
                    }, timeOutTime );
                }
            }
        };

        var settings = $.extend( {
            type: "default",
            animTime: 250,
            openByClick: true
        }, options );

        this.find( '>li' ).hover( function() {
            if ( !$( this ).hasClass(
                    'open-by-click' ) || ( !settings.openByClick && $( this ).
                    hasClass( 'open-by-click' ) ) ) {
                if ( settings.openByClick ) {
                    $( '.open-by-click.current' ).find( '>a' ).click();
                    $( this ).find( '>a' ).unbind( 'click' );
                }
                var dropdown = $( this ).find( '> .cap-submenu-ddown' );
                methods.capshowmenuChildren( dropdown );

                if ( settings.type == 'columns' ) {
                    methods.capcalcColumns( dropdown );
                }
            } else {
                $( this ).find( '>a' ).unbind( 'click' );
                $( this ).find( '>a' ).bind( 'click', function( e ) {
                    methods.openOnClick( $( this ), e );
                } );
            }
        }, function() {
            if ( !$( this ).hasClass(
                    'open-by-click' ) || ( !settings.openByClick && $( this ).
                    hasClass( 'open-by-click' ) ) ) {
                $( this ).find( '> .cap-submenu-ddown' ).fadeOut( 100 ).
                        attr( 'style', '' );
            }
        } );

        return this;
    };

    $( '.cap-primary-menu .menu' ).cap_primary_menu( {
        type: "default"
    } );

    $( '.cap-header-fixed .menu' ).cap_primary_menu( {
        openByClick: false
    } );

    $( window ).load( function() {
        $( ".product-cat-meta" ).addClass( "show animate" );
    } );


    // Block Name: Page Preloader
    //if ( !/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test( navigator.userAgent ) ) {
        $( window ).load( function() {
            $( '#status' ).fadeOut(); // will first fade out the loading animation
            $( '#preloader' ).delay( 350 ).fadeOut(
                    'slow' ); // will fade out the white DIV that covers the website.
            //$( 'body' ).delay( 350 ).css( { 'overflow': 'visible' } );
        } );
    //}


    // Block Name: Applies Chosen js to selects
    $( ".widget select" ).chosen( { width: "100%" } );
    $( "table.variations select" ).chosen( { width: "100%" } );
    $( ".wpcf7 select" ).chosen( { width: "100%" } );
    $( "select#calc_shipping_country" ).chosen( { width: "100%" } );


    // Block Name: Flexslider - Showcase
    $( '#showcaseimg .flexslider' ).flexslider( {
        controlNav: false,
        animation: "fade",
        slideshow: true,
        touch: true,
        slideshowSpeed: 4500,
        animationSpeed: 1600,
        pauseOnAction: true,
        pauseOnHover: false,
        start: function( slider ) {
            $( slider ).delay( 200 ).fadeTo( 600, 1 );
            $( '.scase' ).removeClass( 'preloading' );
        }
    } );


    // Block Name: Mean menu
    $( '#mobile-menu' ).meanmenu( {
        meanMenuContainer: '#load-mobile-menu',
        meanScreenWidth: "1100",
        meanMenuClose: "<span></span><span></span><span></span>"
    } );


     // Skrollr
    $( function() {
        if( !( /Android|iPhone|iPad|iPod|BlackBerry|Windows Phone/i ).test( navigator.userAgent || navigator.vendor || window.opera ) )
        {
            var skr = skrollr.init( { forceHeight: false } );
            skr.refresh( $( '.cap_parallax' ) );
        }       
    } );


    // Hide video on mobile/tablets

    if( navigator.userAgent.match( /(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/ ) ) {
        $( '.captiva-video' ).remove();
    }
    

    // Block Name: CSS3 Animations
    if ( !( /iPhone|iPad|iPod|Android|webOS|BlackBerry|Opera Mini|IEMobile/i.test(
            navigator.userAgent ) ) ) {
        $( ".animate" ).waypoint( function( direction ) {
            var animation = jQuery( this ).attr( "data-animate" );
            if ( direction == 'down' ) {
                jQuery( this ).addClass( animation );
                jQuery( this ).addClass( 'animated' );
            }
            else {
                jQuery( this ).removeClass( animation );
                jQuery( this ).removeClass( 'animated' );
            }
        }, { offset: '100%' } );
    }


    // Block Name: Product List/Grid Toggle 
    function productToggle() {
        var activeClass = 'toggle-active';
        var gridClass = 'grid-layout';
        var listClass = 'list-layout';
        $( '.toggleList' ).click( function() {
            if ( !$.cookie( 'product_layout' ) || $.cookie(
                    'product_layout' ) == 'grid' ) {
                toggleList();
            }
        } );
        $( '.toggleGrid' ).click( function() {
            if ( !$.cookie( 'product_layout' ) || $.cookie(
                    'product_layout' ) == 'list' ) {
                toggleGrid();
            }
        } );

        function toggleList() {
            $( '.toggleList' ).addClass( activeClass );
            $( '.toggleGrid' ).removeClass( activeClass );
            $( '.products' ).fadeOut( 300, function() {
                $( this ).removeClass( gridClass ).addClass( listClass ).
                        fadeIn( 300 );
                $.cookie( 'product_layout', 'list',
                        { expires: 3, path: '/' } );
            } );
        }

        function toggleGrid() {
            $( '.toggleGrid' ).addClass( activeClass );
            $( '.toggleList' ).removeClass( activeClass );
            $( '.products' ).fadeOut( 300, function() {
                $( this ).removeClass( listClass ).addClass( gridClass ).
                        fadeIn( 300 );
                $.cookie( 'product_layout', 'grid',
                        { expires: 3, path: '/' } );
            } );
        }
    }

    function setToggleOnLoad() {
        var activeClass = 'toggle-active';
        if ( $.cookie( 'product_layout' ) == 'grid' ) {
            $( '.products' ).removeClass( 'list-layout' ).addClass(
                    'grid-layout' );
            $( '.toggleGrid' ).addClass( activeClass );
        } else if ( $.cookie( 'product_layout' ) == 'list' ) {
            $( '.products' ).removeClass( 'grid-layout' ).addClass(
                    'list-layout' );
            $( '.toggleList' ).addClass( activeClass );
        } else {
            $( '.toggleGrid' ).addClass( activeClass );
        }
    }

    productToggle();
    setToggleOnLoad();

    // Block Name: Vertical center texts in banners
    $.fn.vAlign = function() {
        return this.each( function() {
            var d = $( this ).outerHeight();
            $( this ).css( 'margin-bottom', -d / 2 );
        } );
    };
    $( '.cap-strip .valign-center' ).vAlign();


    // Block Name: qTip for upsells
    $( '.product-tooltip' ).each( function() {
        $( this ).qtip( {
            content: {
                text: $( this ).next( '.tooltiptext' )
            },
            position: {
                my: 'bottom center',
                at: 'top center',
                container: $( 'div.product-tooltip' ),
                adjust: {
                    x: 39,
                    //y: 10 
                }
            },
            style: {
                classes: 'qtip-blue'
            }
        } );
    } );


    // Block Name: Tipr for main tooltips
    if ( !/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(
            navigator.userAgent ) ) {
        $( '.yith-wcwl-wishlistexistsbrowse.show' ).each( function() {
            var tipr_payload = $( this ).find( 'a' ).text();
            $( this ).find( 'a' ).attr( 'data-tip', tipr_payload ).addClass(
                    'cap-tip' );
        } );

        $( '.yith-wcwl-add-button.show' ).each( function() {
            var tipr_payload = $( this ).find( 'a.add_to_wishlist' ).text();
            $( this ).find( 'a.add_to_wishlist' ).attr( 'data-tip',
                    tipr_payload ).addClass( 'cap-tip' );
        } );

        $( '.cap-tip' ).tipr( {
            'mode': 'top',
            'speed': '300'
        } );
    }

    $( '.captiva-tooltip' ).tooltip();


    // Block Name: Popup for product slider
    $( '.cap-prod-lvl1' ).magnificPopup( {
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading....',
        mainClass: 'magnific-open',
        removalDelay: 200,
        closeOnContentClick: true,
        gallery: {
            enabled: true,
            navigateByImgClick: false,
            preload: [0, 1
            ]
        },
        image: {
            verticalFit: false,
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
        }
    } );


    // Block Name: Popup for size guide
    $( '.cap-size-guide' ).magnificPopup( {
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading....',
        mainClass: 'magnific-open',
        removalDelay: 200,
        closeOnContentClick: true,
        gallery: {
            enabled: false,
            navigateByImgClick: false,
            preload: [0, 1
            ]
        },
        image: {
            verticalFit: false,
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
        }
    } );


    // Block Name: jRespond breakpoints
    var jRes = jRespond( [{
            label: 'small',
            enter: 0,
            exit: 768
        }, {
            label: 'medium',
            enter: 768,
            exit: 980
        }, {
            label: 'large',
            enter: 980,
            exit: 10000
        }] );


    // Block Name: Accordion
    $( '.accordionButton' ).click( function() {
        $( '.accordionButton' ).removeClass( 'on' );
        $( '.accordionContent' ).slideUp( 'normal' );
        if ( $( this ).next().is( ':hidden' ) === true ) {
            $( this ).addClass( 'on' );
            $( this ).next().slideDown( 'normal' );
        }
    } );
    $( '.accordionContent' ).hide();


    // Block Name: Set banner position
    function scrollBanner() {
        $( document ).scroll( function() {
            var scrollPos = $( this ).scrollTop();
            $( '.banner-text' ).css( {
                'top': ( scrollPos / 3 ) + 'px',
                'opacity': 1 - ( scrollPos / 510 )
            } );
            $( '.category-wrapper' ).css( {
                'background-position': 'center ' + ( -scrollPos / 2 ) + 'px'
            } );
        } );
    }
    scrollBanner();


    // Block Name: Default bootstrap select 
    $( '.selectpicker' ).selectpicker();


    // Block Name: Flip effect
    $( document ).ready( function() {
        $( '.hover' ).hover( function() {
            $( this ).addClass( 'flip' );
        }, function() {
            $( this ).removeClass( 'flip' );
        } );
    } );


    // Block Name: Shipping block bg position
    $( window ).scroll( function() {
        var top = $( this ).scrollTop();
        if ( top > 550 ) {
            $( '.shipping-block' ).css( "background-position", parseInt( $(
                    this ).scrollTop() - 2000 * 0.20 ) );
        }
    } );

    // Close anon function.
}( jQuery ) );
