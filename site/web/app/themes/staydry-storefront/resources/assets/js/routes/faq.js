import $ from 'jquery';

export default {
  init() {
    // JavaScript to be fired on the home page
    console.log('hello faq.')

    $( 'body' )
    // Tabs
      .on( 'init', '.wc-tabs-wrapper, .woocommerce-tabs', function() {
        $( '.wc-tab, .woocommerce-tabs .panel:not(.panel .panel)' ).hide();

        var hash  = window.location.hash;
        var url   = window.location.href;
        var $tabs = $( this ).find( '.wc-tabs, ul.tabs' ).first();

        if ( hash.toLowerCase().indexOf( 'comment-' ) >= 0 || hash === '#reviews' || hash === '#tab-reviews' ) {
          $tabs.find( 'li.reviews_tab a' ).click();
        } else if ( url.indexOf( 'comment-page-' ) > 0 || url.indexOf( 'cpage=' ) > 0 ) {
          $tabs.find( 'li.reviews_tab a' ).click();
        } else if ( hash === '#tab-additional_information' ) {
          $tabs.find( 'li.additional_information_tab a' ).click();
        } else {
          $tabs.find( 'li:first a' ).click();
        }
      } )
      .on( 'click', '.wc-tabs li a, ul.tabs li a', function( e ) {
        e.preventDefault();
        var $tab          = $( this );
        var $tabs_wrapper = $tab.closest( '.wc-tabs-wrapper, .woocommerce-tabs' );
        var $tabs         = $tabs_wrapper.find( '.wc-tabs, ul.tabs' );

        $tabs.find( 'li' ).removeClass( 'active' );
        $tabs_wrapper.find( '.wc-tab, .panel:not(.panel .panel)' ).hide();

        $tab.closest( 'li' ).addClass( 'active' );
        $tabs_wrapper.find( $tab.attr( 'href' ) ).show();
      } )

    // Init Tabs and Star Ratings
    $( '.wc-tabs-wrapper, .woocommerce-tabs' ).trigger( 'init' );

  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
