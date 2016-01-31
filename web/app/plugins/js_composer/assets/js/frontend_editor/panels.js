/* =========================================================
 * lib/panels.js v0.5.0
 * =========================================================
 * Copyright 2014 Wpbakery
 *
 * Visual composer panels & modals for frontend editor
 *
 * ========================================================= */
(function($) {
  if(_.isUndefined(window.vc)) window.vc = {};
  /**
   * Modal prototype
   * @type {*}
   */
  vc.ModalView = Backbone.View.extend({
    events: {
      'hidden.bs.modal': 'hide'
    },
    initialize: function() {
      _.bindAll(this, 'setSize');
    },
    setSize: function() {
      var height = $(window).height() - 150;
      this.$content.css('maxHeight', height);
    },
    render: function() {
      $(window).bind('resize.ModalView', this.setSize);
      this.setSize();
      vc.closeActivePanel();
      this.$el.modal('show');
      return this;
    },
    hide: function() {
      $(window).unbind('resize.ModalView');
    }
  });
  /**
   * Add element block to page or shortcodes container.
   * @type {*}
   */
  vc.AddElementBlockView = vc.ModalView.extend({
    el: $('#vc-add-element-dialog'),
    prepend: false,
    builder: '',
    events: {
      'click .vc-shortcode-link': 'createElement',
      'keyup #vc_elements_name_filter':'filterElements',
      'hidden.bs.modal': 'hide',
      'shown.bs.modal': 'buildFiltering',
      'click .wpb-content-layouts-container .isotope-filter a':'filterElements'
    },
    buildFiltering: function() {
      this.do_render = false;
      $('#vc_elements_name_filter').val('');
      if(!vc.is_mobile) $('#vc_elements_name_filter').focus();
      var item_selector, tag, not_in,
      item_selector = '.wpb-layout-element-button';
      tag = this.model ? this.model.get('shortcode') : 'vc_column';
      not_in = this._getNotIn(tag);
      // New vision
      var as_parent = tag && !_.isUndefined(vc.getMapped(tag).as_parent) ? vc.getMapped(tag).as_parent : false;
      if (_.isObject(as_parent)) {
        var parent_selector = [];
        if (_.isString(as_parent.only)) {
          parent_selector.push(_.reduce(as_parent.only.replace(/\s/, '').split(','), function (memo, val) {
            return memo + ( _.isEmpty(memo) ? '' : ',') + '[data-element="' + val.trim() + '"]';
          }, ''));
        }
        if (_.isString(as_parent.except)) {
          parent_selector.push(_.reduce(as_parent.except.replace(/\s/, '').split(','), function (memo, val) {
            return memo  + ':not([data-element="' + val.trim() + '"])';
          }, ''));
        }
        item_selector += parent_selector.join(',');
      } else {
        if(not_in) item_selector = not_in;
      }
      // OLD fashion
      if (tag !== false && tag !== false && !_.isUndefined(vc.getMapped(tag).allowed_container_element)) {
        if (vc.getMapped(tag).allowed_container_element === false) {
          item_selector += ':not([data-is-container=true])';
        } else if (_.isString(vc.getMapped(tag).allowed_container_element)) {
          item_selector += ':not([data-is-container=true]), [data-element=' + vc.getMapped(tag).allowed_container_element + ']';
        }
      }
      this.$buttons.removeClass('vc-visible').addClass('vc-inappropriate');
      $(item_selector, this.$content).removeClass('vc-inappropriate').addClass('vc-visible');
      this.hideEmptyFilters();
    },
    hideEmptyFilters: function() {
      this.$el.find('.vc-filter-content-elements .active').removeClass('active');
      this.$el.find('.vc-filter-content-elements > :first').addClass('active');
      this.$el.find('[data-filter]').each(function () {
        if (!$($(this).data('filter') + '.vc-visible:not(.vc-inappropriate)', this.$content).length) {
          $(this).parent().hide();
        } else {
          $(this).parent().show();
        }
      });
    },
    render: function(model, prepend) {
      var $list, item_selector, tag, not_in;
      this.builder = new vc.ShortcodesBuilder();
      this.prepend = _.isBoolean(prepend) ? prepend : false;
      this.place_after_id = _.isString(prepend) ? prepend : false;
      this.model = _.isObject(model) ? model : false;
      this.$content = this.$el.find('.wpb-elements-list');
      this.$buttons = $('.wpb-layout-element-button', this.$content);
      return vc.AddElementBlockView.__super__.render.call(this);
    },
    hide: function() {
      $(window).unbind('resize.vcAddElementModal');
      if(this.do_render) {
        if(this.show_settings) {
          vc.edit_element_block_view.render(this.builder.last());
        }
        this.builder.render();
      }
    },
    createElement: function(e) {
      this.do_render = true;
      e.preventDefault();
      var $control = $(e.currentTarget),
        tag = $control.data('tag'),
        model;
      if(this.model === false && tag !== 'vc_row') {
        this.builder
          .create({shortcode: 'vc_row'})
          .create({shortcode: 'vc_column', parent_id: this.builder.lastID(), params: {width: '1/1'}});
        this.model = this.builder.last();
      } else if(this.model !== false && tag === 'vc_row') {
        tag += '_inner';
      }
      var params = {shortcode: tag, parent_id: (this.model ? this.model.get('id') : false), params: vc.getDefaults(tag)};
      if(this.prepend) {
        params.order = 0;
        var shortcode_first = vc.shortcodes.findWhere({parent_id: this.model.get('id')});
        if(shortcode_first) params.order = shortcode_first.get('order') -1;
        vc.activity = 'prepend';
      } else if(this.place_after_id) {
        params.place_after_id = this.place_after_id;
      }
      this.builder.create(params);
      if(tag === 'vc_row') {
        this.builder.create({shortcode: 'vc_column', parent_id: this.builder.lastID(), params: {width: '1/1'}});
      } else if(tag === 'vc_row_inner') {
        this.builder.create({shortcode: 'vc_column_inner', parent_id: this.builder.lastID(), params: {width: '1/1'}});
      }
      if(_.isString(vc.getMapped(tag).default_content) && vc.getMapped(tag).default_content.length) {
        var new_data = this.builder.parse({}, vc.getMapped(tag).default_content, this.builder.last().toJSON());
        _.each(new_data, function(object){
          object.default_content = true;
          this.builder.create(object);
        }, this);
      }
      this.show_settings = _.isBoolean(vc.getMapped(tag).show_settings_on_create) && vc.getMapped(tag).show_settings_on_create === false ? false : true;
      this.$el.modal('hide');
    },
    getDefaultParams: function(tag) {
      var params = {};
      _.each(vc.getMapped(tag).params, function(param){
        if(!_.isUndefined(param.value)) params[param.param_name] = param.value;
      });
      return params;
    },
    _getNotIn:_.memoize(function (tag) {
      var selector = _.reduce(vc.map, function (memo, shortcode) {
        var separator = _.isEmpty(memo) ? '' : ',';
        if (_.isObject(shortcode.as_child)) {
          if (_.isString(shortcode.as_child.only)) {
            if (!_.contains(shortcode.as_child.only.replace(/\s/, '').split(','), tag)) {
              memo += separator + '.wpb-layout-element-button:not([data-element=' + shortcode.base + '])';
            }
          }
          if (_.isString(shortcode.as_child.except)) {
            if (_.contains(shortcode.as_child.except.replace(/\s/, '').split(','), tag)) {
              memo += separator + '.wpb-layout-element-button:not([data-element=' + shortcode.base + '])';
            }
          }
        } else if (shortcode.as_child === false) {
          memo += separator + '.wpb-layout-element-button:not([data-element=' + shortcode.base + '])';
        }
        return memo;
      }, '');

      return selector;
    }),
    filterElements:function (e) {
      e.stopPropagation();
      e.preventDefault();
      var $control = $(e.currentTarget),
        filter = '.wpb-layout-element-button',
        name_filter = $('#vc_elements_name_filter').val();
      if ($control.is('[data-filter]')) {
        $('.wpb-content-layouts-container .isotope-filter .active', this.$content).removeClass('active');
        $control.parent().addClass('active');
        filter += $control.data('filter');
        $('#vc_elements_name_filter').val('');
      } else if (name_filter.length > 0) {
        filter += ":containsi('" + name_filter + "')";
        $('.wpb-content-layouts-container .isotope-filter .active', this.$content).removeClass('active');
      } else if(name_filter.length == 0) {
        $('.wpb-content-layouts-container .isotope-filter [data-filter="*"]').parent().addClass('active');
      }
      $('.vc-visible', this.$content).removeClass('vc-visible');
      $(filter, this.$content).addClass('vc-visible');
    }
  });
  /**
   * Panel prototype
   */
  vc.PanelView = Backbone.View.extend({
    draggable: false,
    events: {
      'click [data-dismiss=panel]': 'hide',
      'mouseover [data-transparent=panel]': 'addOpacity',
      'mouseout [data-transparent=panel]': 'removeOpacity'
    },
    initialize: function() {
      _.bindAll(this, 'setSize');
    },
    addOpacity: function() {
      this.$el.addClass('vc-panel-opacity');
    },
    removeOpacity: function(){
      this.$el.removeClass('vc-panel-opacity');
    },
    message_box_timeout: false,
    init: function(){},
    show: function() {
      vc.closeActivePanel();
      this.init();
      vc.active_panel = this;
      $(window).bind('resize.vcPropertyPanel', this.setSize);
      this.setSize();
      this.$el.show();
      if(!this.draggable) {
        this.$el.draggable({iframeFix: true, handle: '.panel-heading'});
        this.draggable = true;
      }
    },
    hide: function(e) {
      e && e.preventDefault();
      $(window).unbind('resize.vcPropertyPanel');
      vc.active_panel = false;
      this.$el.hide();
    },
    content: function() {
      return this.$el.find('.panel-body');
    },
    setSize: function() {
      var height = $(window).height() - 200;
      this.content().css('maxHeight', height);
    },
    showMessage: function(text, type) {
      this.message_box_timeout && this.$el.find('.vc-panel-message').remove() && window.clearTimeout(this.message_box_timeout);
      this.message_box_timeout = false;
      var $message_box = $('<div class="vc-panel-message type-' + type +'"></div>').appendTo(this.$el.find('.panel-body'));
      $message_box.text(text).fadeIn();
      this.message_box_timeout = window.setTimeout(function(){
        $message_box.remove();
      }, 6000);
    },
    minimizeBody: function(e) {
      e && e.preventDefault && e.preventDefault();
      this.$el.find('.panel-body,.panel-footer').slideToggle();
    },
    isVisible: function() {
      return this.$el.is(':visible');
    }
  });
  /**
   * Shortcode settings panel
   * @type {*}
   */
  vc.EditElementPanelView = vc.PanelView.extend({
    el: $('#vc-properties-panel'),
    $content: false,
    dependent_elements:{},
    mapped_params:{},
    draggable: false,
    events: {
      'click [data-save=true]': 'save',
      'click [data-dismiss=panel]': 'hide',
      'mouseover [data-transparent=panel]': 'addOpacity',
      'mouseout [data-transparent=panel]': 'removeOpacity'
    },
    initialize: function() {
      _.bindAll(this, 'setSize');
    },
    setSize: function() {
      var height = $(window).height() - 190;
      this.$content.css('maxHeight', height);
    },
    render: function(model, not_request_template) {
      this.model = model;
      var tag = this.model.get('shortcode'),
        params = this.model.setting('params') || [];
      _.bindAll(this, 'hookDependent');
      this.mapped_params = {};
      this.dependent_elements = {};
      _.each(params, function (param) {
        this.mapped_params[param.param_name] = param;
      }, this);
      this.$content = not_request_template ? this.$el : this.$el.find('.vc-properties-list').removeClass('vc-with-tabs');
      this.$content.html('<span class="vc-spinner"></span>');
      this.show();
      !not_request_template &&
      $.ajax({
        type:'POST',
        url:vc.admin_ajax,
        data:{
          action:'wpb_show_edit_form',
          element:this.model.get('shortcode'),
          post_id: $('#post_ID').val(),
          shortcode: vc.builder.toString(this.model),
          vc_inline: true
        },
        context:this
      }).done(function (data) {
          this.$content.html(data);
          this.$content.scrollTop(0);
          this.init();
        });
      this.setTitle();
      return this;
    },
    init: function() {
      this.initDependency();
    },
    initDependency:function () {
      // setup dependencies
      _.each(this.mapped_params, function (param) {
        if (_.isObject(param) && _.isObject(param.dependency) && _.isString(param.dependency.element)) {
          var $masters = $('[name=' + param.dependency.element + '].wpb_vc_param_value', this.$content),
            $slave = $('[name= ' + param.param_name + '].wpb_vc_param_value', this.$content);
          _.each($masters, function (master) {
            var $master = $(master),
              rules = param.dependency;
            if (!_.isArray(this.dependent_elements[$master.attr('name')])) this.dependent_elements[$master.attr('name')] = [];
            this.dependent_elements[$master.attr('name')].push($slave);
            $master.bind('keyup change', this.hookDependent);
            this.hookDependent({currentTarget:$master}, [$slave]);
            if (_.isString(rules.callback)) {
              window[rules.callback].call(this);
            }
          }, this);
        }
      }, this);
    },
    hookDependent: function (e, dependent_elements) {
      var $master = $(e.currentTarget),
          $master_container = $master.closest('.vc-column'),
          master_value,
          is_empty,
          dependent_elements = _.isArray(dependent_elements) ? dependent_elements : this.dependent_elements[$master.attr('name')],
          master_value = $master.is(':checkbox') ? _.map(this.$content.find('[name=' + $(e.currentTarget).attr('name') + '].wpb_vc_param_value:checked'),
          function (element) {
            return $(element).val();
          })
          : $master.val();
      is_empty = $master.is(':checkbox') ? !this.$content.find('[name=' + $master.attr('name') + '].wpb_vc_param_value:checked').length
        : !master_value.length;
      if($master_container.hasClass('vc-dependent-hidden')) {
        _.each(dependent_elements, function($element) {
          $element.closest('.vc-column').addClass('vc-dependent-hidden');
        });
      } else {
        _.each(dependent_elements, function ($element) {
          var param_name = $element.attr('name'),
            rules = _.isObject(this.mapped_params[param_name]) && _.isObject(this.mapped_params[param_name].dependency) ? this.mapped_params[param_name].dependency : {},
            $param_block = $element.closest('.vc-column');
          if (_.isBoolean(rules.not_empty) && rules.not_empty === true && !is_empty) { // Check is not empty show dependent Element.
            $param_block.removeClass('vc-dependent-hidden');
          } else if (_.isBoolean(rules.is_empty) && rules.is_empty === true && is_empty) {
            $param_block.removeClass('vc-dependent-hidden');
          } else if (_.intersection((_.isArray(rules.value) ? rules.value : [rules.value]), (_.isArray(master_value) ? master_value : [master_value])).length) {
            $param_block.removeClass('vc-dependent-hidden');
          } else {
            $param_block.addClass('vc-dependent-hidden');
          }
          $element.trigger('change');
        }, this);
      }
      return this;
    },
    setActive: function() {
      this.$el.prev().addClass('active');
    },
    window: function() {
      return window;
    },
    getParams: function() {
      var attributes_settings = this.mapped_params;
      this.params = _.extend({}, this.model.get('params'));
      _.each(attributes_settings, function (param) {
        var value = vc.atts.parseFrame.call(this, param);
        if(_.isNull(value) || value === '') {
          delete this.params[param.param_name];
        } else {
          this.params[param.param_name] =  value;
        }
      }, this);
      _.each(vc.edit_form_callbacks, function(callback){
        callback.call(this);
      }, this);
      return this.params;
    },
    content: function() {
      return this.$content;
    },
    save: function(){
      this.model.save({params: this.getParams()});
      this.showMessage(window.sprintf(window.i18nLocale.inline_element_saved, vc.getMapped(this.model.get('shortcode')).name), 'success');
    },
    show: function() {
      if(this.$el.is(':hidden')) vc.closeActivePanel();
      vc.active_panel = this;
      $(window).bind('resize.vcPropertyPanel', this.setSize);
      this.setSize();
      this.$el.show();
      if(!this.draggable) {
        this.$el.draggable({iframeFix: true, handle: '.panel-heading'});
        this.draggable = true;
      }
    },
    hide: function(e) {
      e && e.preventDefault();
      vc.active_panel = false;
      $(window).unbind('resize.vcPropertyPanel');
      this._killEditor();
      this.$el.hide();
      this.$content.html('');
    },
    setTitle: function() {
      this.$el.find('.panel-title').text(vc.getMapped(this.model.get('shortcode')).name + ' settings');
      return this;
    },
    _killEditor:function () {
      if(!_.isUndefined(window.tinyMCE)) {
        $('textarea.textarea_html', this.$el).each(function () {
          var id = $(this).attr('id');
          if(tinymce.majorVersion === "4") {
            window.tinyMCE.execCommand('mceRemoveEditor', true, id);
          } else {
            window.tinyMCE.execCommand("mceRemoveControl", true, id);
          }
        });
      }
    }
  });
  /**
   * Post custom css
   * @type {Number}
   */
  vc.PostSettingsPanelView = vc.PanelView.extend({
    events: {
      'click [data-save=true]': 'save',
      'keydown .wpb_custom_post_css_editor': 'addTab',
      'click [data-dismiss=panel]': 'hide',
      'mouseover [data-transparent=panel]': 'addOpacity',
      'mouseout [data-transparent=panel]': 'removeOpacity'
    },
    saved_css_data: '',
    saved_title: '',
    initialize: function() {
      vc.$custom_css = $('#vc-post-custom-css');
      this.saved_css_data = vc.$custom_css.val();
      this.saved_title = vc.title;
    },
    render: function() {
      this.$title = this.$el.find('#vc-page-title-field');
      this.$title.val(vc.title);
      !vc.$title.length && $('#vc-settings-title-container').hide();
      this.$editor = this.$el.find('.wpb_custom_post_css_editor');
      this.$editor.val(vc.$custom_css.val());
      return this;
    },
    /**
     * Add /t symbol on tab key bu to next when focus on custom css textarea
     * @param e
     */
    addTab: function(e) {
      if(e.keyCode === 9) {
        // get caret position/selection
        var el = this.$editor.get(0),
          start = el.selectionStart,
          end = el.selectionEnd;
        this.$editor.val(this.$editor.val().substring(0, start)
          + "\t"
          + this.$editor.val().substring(end));
        el.selectionStart = el.selectionEnd = start + 1;
        // prevent the focus lose
        e.preventDefault();
      }
    },
    save: function() {
      var title = this.$title.val();
      if(title != vc.title) {
        vc.frame.setTitle(title);
      }
      this.setAlertOnDataChange();
      vc.$custom_css.val(this.$editor.val());
      vc.frame_window.vc_iframe.loadCustomCss(this.$editor.val());
      vc.app.updateSettingsBadge();
      this.showMessage(window.i18nLocale.css_updated, 'success');
      // this.hide();
    },
    /**
     * Set alert if custom css data differs from saved data.
     */
    setAlertOnDataChange: function() {
      if(this.saved_css_data !== this.$editor.val() || this.saved_title !== this.$title.val()) {
        vc.setDataChanged();
      }
    }
  });
  /**
   * Templates editor
   * @type {*}
   */
  vc.TemplatesEditorPanelView = vc.PanelView.extend({
    events: {
      'click [data-dismiss=panel]': 'hide',
      'mouseover [data-transparent=panel]': 'addOpacity',
      'mouseout [data-transparent=panel]': 'removeOpacity',
      'click .wpb_remove_template':'removeTemplate',
      'click [data-template_id]':'loadTemplate',
      'click #vc-template-save':'saveTemplate'
    },
    render: function() {
      this.$name = $('#vc-template-name');
      this.$list = $('#vc-template-list');
      return this;
    },
    /**
     * Remove template from server database.
     * @param e - Event object
     */
    removeTemplate:function (e) {
      e && e.preventDefault();
      var $button = $(e.currentTarget);
      var template_name = $button.closest('.wpb_template_li').find('a').text();
      var answer = confirm(window.i18nLocale.confirm_deleting_template.replace('{template_name}', template_name));
      if (answer) {
        // this.reloadTemplateList(data);
        $.post(window.ajaxurl, {
          action: 'wpb_delete_template',
          template_id: $button.attr('rel'),
          vc_inline: true
        });
        $button.closest('.wpb_template_li').remove();
      }
    },
    /**
     * Load saved template from server.
     * @param e - Event object
     */
    loadTemplate:function (e) {
      e && e.preventDefault();
      var $button = $(e.currentTarget);
      $.ajax({
        type:'POST',
        url:window.ajaxurl,
        data:{
          action:'vc_inline_template',
          template_id:$button.data('template_id'),
          vc_inline: true
        },
        context: this
      }).done(function (html) {
          var template, data;
          _.each($(html), function(element){
            if(element.id === "vc-template-data") {
              try {data = JSON.parse(element.innerHTML) } catch(e) {};
            }
            if(element.id === "vc-template-html") {
              template = element.innerHTML;
            }
          });
          template && data && vc.builder.buildFromTemplate(template, data);
          this.showMessage(window.i18nLocale.template_added, 'success');
          /*
           _.each(vc.filters.templates, function (callback) {
           shortcodes = callback(shortcodes);
           });
           */
          //vc.storage.append(shortcodes);
          //Shortcodes.fetch({reset: true});
        });
    },
    /**
     * Save current shortcode design as template with title.
     * @param e - Event object
     */
    saveTemplate:function (e) {
      e.preventDefault();
      var name = this.$name.val(),
        data, shortcodes;
      if (_.isString(name) && name.length) {
        shortcodes = vc.builder.getContent();
        if(!shortcodes.trim().length) {
          this.showMessage(window.i18nLocale.template_is_empty, 'error');
          return false;
        }
        data = {
          action:'wpb_save_template',
          template:shortcodes,
          template_name:name
        };
        this.$name.val('');
        this.showMessage(window.i18nLocale.template_save, 'success');
        this.reloadTemplateList(data);
      } else {
        this.showMessage(window.i18nLocale.please_enter_templates_name, 'error');
      }
    },
    reloadTemplateList:function (data) {
      this.$list.html(window.i18nLocale.loading).load(window.ajaxurl, data);
    }
  });
  vc.RowLayoutEditorPanelView = vc.PanelView.extend({
    events: {
      'click [data-dismiss=panel]': 'hide',
      'mouseover [data-transparent=panel]': 'addOpacity',
      'mouseout [data-transparent=panel]': 'removeOpacity',
      'click .vc-layout-btn': 'setLayout',
      'click #vc-row-layout-update': 'updateFromInput'
    },
    render: function(model) {
      this.$input = $('#vc-row-layout');
      this.builder = new vc.ShortcodesBuilder();
      if(model) this.model = model;
      this.addCurrentLayout();
      vc.column_trig_changes = true;
      return this;
    },
    hide: function(e) {
      e && e.preventDefault();
      vc.active_panel = false;
      this.$el.hide();
      vc.column_trig_changes = false;
    },
    addCurrentLayout: function() {
      vc.shortcodes.sort();
      var string = _.reduce(vc.shortcodes.where({parent_id: this.model.get('id')}), function(memo, model) {
        return memo + (memo!='' ? ' + ' : '') + model.getParam('width');
      }, '', this);
      this.$input.val(string);
    },
    setLayout: function(e) {
      e && e.preventDefault();
      if (!this.builder.isBuildComplete()) return false;
      var $control = $(e.currentTarget),
        layout = $control.attr('data-cells'),
        columns = this.model.view.convertRowColumns(layout, this.builder);
      this.$input.val(columns.join(' + '));
    },
    updateFromInput: function(e) {
      e && e.preventDefault();
      var layout,
        cells = this.$input.val();
      if((layout = this.validateCellsList(cells))!==false) {
        this.model.view.convertRowColumns(layout, this.builder);
      } else {
        window.alert(window.i18nLocale.wrong_cells_layout);
      }
    },
    validateCellsList: function(cells) {
      var return_cells = [],
        split = cells.replace(/\s/g, '').split('+'),
        b;
      var sum = _.reduce(_.map(split, function(c){
        if(c.match(/^[vc\_]{0,1}span\d{1,2}$/)) {
          var converted_c = vc_convert_column_span_size(c);
          if(converted_c===false) return 1000;
          b = converted_c.split(/\//);
          return_cells.push(b[0] + '' + b[1]);
          return 12*parseInt(b[0], 10)/parseInt(b[1], 10);
        } else if(c.match(/^[1-9]|1[0-2]\/[1-9]|1[0-2]$/)) {
          b = c.split(/\//);
          return_cells.push(b[0] + '' + b[1]);
          return 12*parseInt(b[0], 10)/parseInt(b[1], 10);
        }
        return 10000;

      }), function(num, memo) {
        memo = memo + num;
        return memo;
      }, 0);
      if(sum > 12) return false;
      return return_cells.join('_');
    }
  });
})(window.jQuery);
