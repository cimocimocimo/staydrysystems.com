
jQuery(document).ready(function ($) {
    $('.wpb_settings_accordion').accordion({
        active:(getCookie('wpb_js_composer_settings_group_tab') ? getCookie('wpb_js_composer_settings_group_tab') : false),
        collapsible:true,
        change:function (event, ui) {
            if (ui.newHeader.attr('id') !== undefined)
                setCookie('wpb_js_composer_settings_group_tab', '#' + ui.newHeader.attr('id'), 365 * 24 * 60 * 60);
            else
                setCookie('wpb_js_composer_settings_group_tab', '', 365 * 24 * 60 * 60);
        },
        heightStyle: 'content'
    });
    $('.wpb-settings-select-all-shortcodes').click(function (e) {
        e.preventDefault();
        $(this).parent().parent().find('[type=checkbox]').attr('checked', true);
    });
    $('.wpb-settings-select-none-shortcodes').click(function (e) {
        e.preventDefault();
        $(this).parent().parent().find('[type=checkbox]').removeAttr('checked');
    });
    $('.vc-settings-tab-control').click(function (e) {
        e.preventDefault();
        if ($(this).hasClass('nav-tab-active')) return false;
        var tab_id = $(this).attr('href');
        $('.vc-settings-tabs > .nav-tab-active').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.vc-settings-tab-content').hide().removeClass('vc-settings-tab-content-active');
        $(tab_id).fadeIn(400, function () {
            $(this).addClass('vc-settings-tab-content-active');
        });
    });
    $('.vc-settings-tab-content').submit(function () {
        setCookie('wpb_js_composer_settings_active_tab', $('.vc-settings-tab-control.nav-tab-active').attr('href'), 365 * 24 * 60 * 60);
        return true;
    });

    $('#vc-settings-disable-notification-button').click(function (e) {
        e.preventDefault();
        $.ajax({
            type:'POST',
            url:window.ajaxurl,
            data:{action:'wpb_remove_settings_notification_element_css_class'}
        });
        $(this).remove();
    });
    $('.vc_show_example').click(function (e) {
        e.preventDefault();
        var $helper = $('.vc_helper');
        if ($helper.is(':animated')) return false;
        $helper.toggle(100);
    });

    $('#vc-settings-custom-css-reset-data').click(function (e) {
        e.preventDefault();
        if (confirm(window.i18nLocaleSettings.are_you_sure_reset_css_classes)) {
            $('#vc-settings-element_css-action').val('remove_all_css_classes');
            $('#vc-settings-element_css').attr('action', window.location.href).trigger('submit');
        }
    });
    $('.color-control').wpColorPicker();
    $('#vc-settings-color-restore-default').click(function (e) {
        e.preventDefault();
        if (confirm(window.i18nLocaleSettings.are_you_sure_reset_color)) {
            $('#vc-settings-color-action').val('restore_color');
            $('#vc-settings-color').attr('action', window.location.href).find('[type=submit]').click();
        }
    });
    $('#wpb_js_use_custom').change(function () {
        if ($(this).is(':checked')) {
            $('#vc-settings-color').addClass('color_enabled');
        } else {
            $('#vc-settings-color').removeClass('color_enabled');

        }
    });
  var showUpdaterSubmitButton = function() {
          $('#vc-settings-updater [type=submit]').attr('disabled', false);
      },
      hideUpdaterSubmitButton = function() {
        $('#vc-settings-updater [type=submit]').attr('disabled', true);

      };

  $('#vc-settings-activate-license').click(function(e){
    var $button = $(this),
        $username = $('[name=wpb_js_envato_username]'),
        $key = $('[name=wpb_js_js_composer_purchase_code]'),
        status = $('#vc-settings-license-status').val(),
        $api_key = $('[name=wpb_js_envato_api_key]'),
        message_html = '<div id="vc-license-activation-message" class="updated vc-updater-result-message hidden"><p><strong>{message}</strong></p></div>';
    if( $button.attr('disabled')===true ) return false;
    $button.attr('disabled', true);
    $('#vc-license-activation-message').remove();
    e.preventDefault();
    $('#vc-updater-spinner').show();
    $.ajax({
      type: 'POST',
      url: window.ajaxurl,
      dataType: 'json',
      data: {
          action: status=== 'activated' ? 'wpb_deactivate_license' : 'wpb_activate_license',
          username: $username.val(),
          key: $key.val(),
          api_key: $api_key.val()
      }
    }).done(function(data){
        var code;
        if(data.result && status !== 'activated') {
          $('#vc-settings-license-status').val('activated');
          $key.addClass('vc-updater-passive').attr('disabled', true);
          $username.addClass('vc-updater-passive').attr('disabled', true);
          $api_key.addClass('vc-updater-passive').attr('disabled', true);
          $('#vc-settings-activate-license').html(i18nLocaleSettings.vc_updater_deactivate_license);
          message_html = message_html.replace('{message}', i18nLocaleSettings.vc_updater_license_activation_success);
          hideUpdaterSubmitButton();
        } else if(data.result && status === 'activated') {
          $('#vc-settings-license-status').val('not_activated');
          $key.removeClass('vc-updater-passive').attr('disabled', false);
          $username.removeClass('vc-updater-passive').attr('disabled', false);
          $api_key.removeClass('vc-updater-passive').attr('disabled', false);
          $('#vc-settings-activate-license').html(i18nLocaleSettings.vc_updater_activate_license);
          message_html = message_html.replace('{message}', i18nLocaleSettings.vc_updater_license_deactivation_success);
          showUpdaterSubmitButton();
        } else {
          code = typeof(data.code) === 'undefined' ? parseInt(data.code) : 300;
          if( data.code === 100 ) {
            // Empty data
            message_html = message_html.replace('{message}', i18nLocaleSettings.vc_updater_empty_data);
          } else if( data.code === 200 ){
            // Wrong purchase code.
            message_html = message_html.replace('{message}', i18nLocaleSettings.vc_updater_wrong_license_key);
          } else if( data.code === 300 ) {
            // Wrong data.
            message_html = message_html.replace('{message}', i18nLocaleSettings.vc_updater_wrong_data);
          } else if( data.code === 401 ) {
            // Already activated
            message_html = message_html.replace('{message}', i18nLocaleSettings.vc_updater_already_activated);
          } else if( data.code === 402 ) {
            // Activated on other wordpress site.
            message_html = message_html.replace('{message}', i18nLocaleSettings.vc_updater_already_activated_another_url).replace('{site}', data.activated_url);
          } else if(data.code === 500) {
            message_html = message_html.replace('{message}', i18nLocaleSettings.vc_deactivation);
          } else {
            message_html = message_html.replace('{message}', i18nLocaleSettings.vc_updater_error);
          }

        }
        $(message_html).insertAfter('#vc-settings-activate-license').fadeIn(100);
        $button.attr('disabled', false);
        $('#vc-updater-spinner').hide();
      }).error(function(data){
        $(message_html.replace('{message}', i18nLocaleSettings.vc_updater_error)).insertAfter('#vc-settings-activate-license').show(100);
        $('#vc-updater-spinner').hide();
        $button.attr('disabled', false);
      });
  });
});

