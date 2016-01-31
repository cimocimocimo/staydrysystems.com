/* =========================================================
 * build.js v1.0.1
 * =========================================================
 * Copyright 2013 Wpbakery
 *
 * Visual composer builder backbone/underscore version
 * ========================================================= */

if(_.isUndefined(vc)) var vc = {};
(function ($) {
  "use strict";
  vc.createPreLoader = function() {
    vc.$preloader = $('#vc-preloader');

  };
  vc.removePreLoader = function() {
    vc.$preloader && vc.$preloader.remove();
  };
  vc.createPreLoader();
  vc.build = function() {
    vc.loaded = true;
    // Get current content data
    vc.post_shortcodes = window.vc_post_shortcodes;
    vc.map = window.vc_user_mapper;

    $('#wpadminbar').remove();
    $('body').attr('data-vc', true);
    vc.post_id = $('#vc-post-id').val();
    vc.is_mobile = $('body.mobile').length > 0;
    vc.title = $('#vc-title-saved').val();
    // Create Modals & panels
    vc.add_element_block_view = new vc.AddElementBlockView({el: '#vc-add-element-dialog'});
    vc.edit_element_block_view = new vc.EditElementPanelView({el: '#vc-properties-panel'});
    vc.post_settings_view = new vc.PostSettingsPanelView({el: '#vc-post-settings-panel'});
    vc.templates_editor_view = new vc.TemplatesEditorPanelView({el: '#vc-templates-editor'});
    vc.app = new vc.View();
    vc.buildRelevance();
    // Build Frame {{
    vc.$frame_wrapper = $('#vc-inline-frame-wrapper');
    vc.$frame = $('#vc-inline-frame');
    vc.setFrameSize('100%');
    vc.frame = new vc.FrameView({el: $(vc.$frame.get(0).contentWindow.document).find('body').get(0)});
    vc.app.render();
    vc.$page.html($('#vc-template-post-content').html());
    vc.frame.render();
    // }}
    // Build content of the page
    vc.builder.buildFromContent();
    vc.removePreLoader();
  };
})(window.jQuery);