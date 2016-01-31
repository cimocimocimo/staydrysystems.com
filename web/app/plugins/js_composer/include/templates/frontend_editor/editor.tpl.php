<?php

global $menu, $submenu, $parent_file, $post_ID, $post;
$post_ID = $this->post_id;
$post = $this->post;
$post_type = $this->post->post_type;
$nonce_action = $nonce_action = 'update-post_' . $this->post_id;
$user_ID = isset($this->current_user) && isset($this->current_user->ID) ? (int) $this->current_user->ID : 0;
$form_action = 'editpost';
$menu = array();
add_thickbox();
wp_enqueue_media( array( 'post' => $this->post_id ) );
require_once( $this->adminFile('admin-header.php') );
$can_publish = current_user_can($this->post_type->cap->publish_posts);
?>
<div id="vc-preloader"></div>
<script type="text/javascript">document.getElementById('vc-preloader').style.height = window.screen.availHeight;</script>
<input type="hidden" name="vc_post_title" id="vc-title-saved" value="<?php echo esc_attr_e($this->post->post_title)?>" />
<input type="hidden" name="vc_post_id" id="vc-post-id" value="<?php echo $this->post_id ?>" />
<input type="hidden" name="vc_post_custom_css" id="vc-post-custom-css" value="<?php echo esc_attr_e($this->post_custom_css) ?>" autocomplete="off" />
<div class="navbar navbar-fixed-top" role="navigation" id="vc-navbar">
    <div class="navbar-header">
        <?php /*
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#vc-navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
 */ ?>
        <a class="navbar-brand" id="vc-logo" title="<?php esc_attr_e('Visual Composer', 'js_composer') ?>" href="<?php esc_attr_e($this->getBrandUrl()) ?>" target="_blank"><?php _e('Visual Composer', 'js_composer') ?></a>
    </div>
    <div<?php /*  class="navbar-collapse collapse" id="vc-navbar-collapse" */ ?>>
        <ul class="nav navbar-nav">
            <?php foreach($this->navbar_buttons as $button): ?>
            <li class="line"></li>
            <?php echo $button[1] ?>
            <?php endforeach; ?>
            <li class="line"></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="line"></li>
			<li><a id="vc-post-settings-button" class="vc_post_settings"
				   title="<?php esc_attr_e( 'Page settings', 'js_composer' ) ?>"><i class="icon"></i><span id="vc-post-css-badge" class="badge"><?php _e('CSS', 'js_composer') ?></a>
				</span>
			</li>
            <li class="line"></li>
			<li>
				<button id="vc-guides-toggle-button" class="btn-primary btn navbar-btn btn-sm vc_guides_toggle"
						title="<?php esc_attr_e( "Toggle editor's guides", 'js_composer' ) ?>"><?php _e( 'Guides ON', 'js_composer' ) ?></button>
			</li>
            <?php
			$disable_responsive = vc_settings()->get( 'not_responsive_css' );
            if($disable_responsive !== '1'): ?>
            <li class="line"></li>
            <li>
                <div class="vc-dropdown" id="vc-screen-size-control">
						<a href="#" class="vc-dropdown-toggle"
						   title="<?php _e( "Responsive preview", 'js_composer' ) ?>"><i class="icon default"
																						 id="vc-screen-size-current"></i><b
						  class="caret"></b></a>
                    <ul class="vc-dropdown-list">
							<li><a href="#" title="<?php esc_attr_e( 'Desktop', 'js_composer' ) ?>"
								   class="vc-screen-width active default" data-size="100%"></a></li>
							<li><a href="#" title="<?php esc_attr_e( 'Tablet landscape mode', 'js_composer' ) ?>"
								   class="vc-screen-width landscape-tablets" data-size="1024px"></a></li>
							<li><a href="#" title="<?php esc_attr_e( 'Tablet portrait mode', 'js_composer' ) ?>"
								   class="vc-screen-width portrait-tablets" data-size="768px"></a></li>
							<li><a href="#" title="<?php esc_attr_e( 'Smartphone landscape mode', 'js_composer' ) ?>"
								   class="vc-screen-width landscape-smartphones" data-size="480px"></a></li>
							<li><a href="#" title="<?php esc_attr_e( 'Smartphone portrait mode', 'js_composer' ) ?>"
								   class="vc-screen-width portrait-smartphones" data-size="320px"></a></li>
                    </ul>
                </div>
            </li>
            <?php endif; ?>
            <li class="line"></li>
            <li  class="vc-show-mobile">
				<button data-url="<?php esc_attr_e(get_edit_post_link( $this->post_id ).'&wpb_vc_js_status=true') ?>"
						class="btn btn-default navbar-btn btn-sm vc_button_cancel" id="vc-button-cancel"
						title="<?php _e( "Cancel all changes and return to WP dashboard", 'js_composer' ) ?>"><?php _e( 'Backend editor', 'js_composer' ) ?></button>
                <?php if(!in_array( $this->post->post_status, array('publish', 'future', 'private') )): ?>
                    <?php if($this->post->post_status === 'draft'): ?>
					<button type="button" class="btn btn-default navbar-btn btn-sm vc_button_save_draft"
							id="vc-button-save-draft"
							title="<?php esc_attr_e( "Save draft", 'js_composer' ) ?>"><?php _e( 'Save draft', 'js_composer' ) ?></button>
                    <?php elseif($this->post->post_status === 'pending'  && $can_publish): ?>
					<button type="button" class="btn btn-default navbar-btn btn-sm vc_button_save_draft"
							id="vc-button-save-as-pending"
							title="<?php esc_attr_e( "Save as Pending", 'js_composer' ) ?>"><?php _e( 'Save as Pending', 'js_composer' ) ?></button>
                    <?php endif; ?>
                    <?php if ( $can_publish ) : ?>
					<button type="button" class="btn btn-primary navbar-btn btn-sm vc_button_update"
							id="vc-button-update" title="<?php esc_attr_e( "Publish", 'js_composer' ) ?>"
							data-change-status="publish"><?php _e( 'Publish', 'js_composer' ) ?></button>
                    <?php else: ?>
					<button type="button" class="btn btn-primary navbar-btn btn-sm vc_button_update"
							id="vc-button-update" title="<?php esc_attr_e( "Submit for Review", 'js_composer' ) ?>"
							data-change-status="pending"><?php _e( 'Submit for Review', 'js_composer' ) ?></button>
                    <?php endif; ?>
                <?php else: ?>
				<button type="button" class="btn btn-primary navbar-btn btn-sm vc_button_update" id="vc-button-update"
						title="<?php esc_attr_e( "Update", 'js_composer' ) ?>"><?php _e( 'Update', 'js_composer' ) ?></button>
                <?php endif; ?>
<?php if(1==2): ?>
                <?php if($this->post->post_status==='draft'): ?>
					<button type="button" class="btn btn-default navbar-btn btn-sm vc_button_save_draft"
							id="vc-button-save-draft"
							title="<?php esc_attr_e( "Save draft", 'js_composer' ) ?>"><?php _e( 'Save draft', 'js_composer' ) ?></button>
					<button type="button" class="btn btn-primary navbar-btn btn-sm vc_button_update"
							id="vc-button-update" title="<?php esc_attr_e( "Publish", 'js_composer' ) ?>"
							data-change-status="publish"><?php _e( 'Publish', 'js_composer' ) ?></button>
                <?php else: ?>
					<button type="button" class="btn btn-primary navbar-btn btn-sm vc_button_update"
							id="vc-button-update"
							title="<?php esc_attr_e( "Update", 'js_composer' ) ?>"><?php _e( 'Update', 'js_composer' ) ?></button>
                <?php endif; ?>
<?php endif; ?>
            </li>
            <li class="line"></li>
            <li>
				<a href="<?php echo $this->post_url ?>" class="vc-back-button"
				   title="<?php esc_attr_e( "Exit Visual Composer edit mode", 'js_composer' ) ?>"></a>
            </li>
        </ul>
	</div>
	<!--/.nav-collapse -->
</div>
<div id="vc-inline-frame-wrapper">
	<iframe src="<?php echo htmlspecialchars( $this->url ) ?>" scrolling="auto" style="width: 100%;"
			id="vc-inline-frame"></iframe>
</div>

<div class="modal wpb-elements-list-modal fade" id="vc-add-element-dialog" tabindex="-1" role="dialog"
	 aria-labelledby="vc-add-element-dialog-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a href="#" class="vc-close" data-dismiss="modal" aria-hidden="true"><i class="icon"></i></a>
				<input id="vc_elements_name_filter" type="text" name="vc_content_filter"
					   placeholder="<?php echo __( 'Search by element name', "js_composer" ); ?>"/>

				<h3 class="modal-title"
					id="vc-add-element-dialog-title"><?php _e( 'Add element', 'js_composer' ) ?></h3>
            </div>
            <div class="modal-body wpb-elements-list">
                <?php echo $this->nav_bar->addElementsList() ?>
                <div class="clearfix"></div>
            </div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Modal -->
<div class="modal fade" id="vc-edit-element-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabelTitle"
	 aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabelTitle"></h4>
            </div>
            <div class="modal-body vc-properties-list wpb-edit-form">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary vc-save">Save changes</button>
            </div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/html" id="vc-controls-template-default">
<div class="controls-element">
    <div class="controls-cc">
        <!-- <a class="control-btn vc-element-name">{{ name }}</a> -->
        <a class="control-btn vc-element-name vc-element-move">
				<span class="vc-btn-content"
					  title="<?php printf( __( 'Drag to move %s', 'js_composer' ), '{{ name }}' ) ?>">{{ name }}</span>
        </a>
			<a class="control-btn control-btn-edit" href="#"
			   title="<?php printf( __( 'Edit %s', 'js_composer' ), '{{ name }}' ) ?>"><span
			  class="vc-btn-content"><span class="icon"></span></span></a>
			<a class="control-btn control-btn-clone" href="#"
			   title="<?php printf( __( 'Clone %s', 'js_composer' ), '{{ name }}' ) ?>"><span
			  class="vc-btn-content"><span class="icon"></span></span></a>
			<a class="control-btn control-btn-delete" href="#"
			   title="<?php printf( __( 'Delete %s', 'js_composer' ), '{{ name }}' ) ?>"><span
			  class="vc-btn-content"><span class="icon"></span></span></a>
    </div>
</div>
</script>
<script type="text/html" id="vc-controls-template-container">
    <div class="controls-container">
        <div class="controls-out-tl">
            <div class="element element-{{ tag }}">
				<a class="control-btn vc-element-name vc-element-move"
				   title="<?php printf( __( 'Drag to move %s', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content">{{ name }}</span></a>
				<a class="control-btn control-btn-edit" href="#"
				   title="<?php printf( __( 'Edit %s', 'js_composer' ), '{{ name }}' ) ?>"><span class="vc-btn-content"><span
				  class="icon"></span></span></a>
				<a class="control-btn control-btn-prepend" href="#"
				   title="<?php printf( __( 'Prepend to %s', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
				<a class="control-btn control-btn-clone" href="#"
				   title="<?php printf( __( 'Clone %s', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
				<a class="control-btn control-btn-delete" href="#"
				   title="<?php printf( __( 'Delete %s', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
            </div>
        </div>
        <div class="controls-bc">
			<a class="control-btn control-btn-append" href="#"
			   title="<?php printf( __( 'Append to %s', 'js_composer' ), '{{ name }}' ) ?>"><span
			  class="vc-btn-content"><span class="icon"></span></span></a>
        </div>
	</div>
	<!-- end controls-column -->
</script>
<script type="text/html" id="vc-controls-template-container-width-parent">
<div class="controls-column">
    <div class="controls-out-tl">
        <div class="parent parent-{{ parent_tag }}">
				<a class="control-btn vc-element-name vc-move-{{ parent_tag }}"
				   title="<?php printf( __( 'Drag to move %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content">{{ parent_name }}</span></a>
            <span class="advanced">
                <a class="control-btn control-btn-edit vc-edit" href="#"
				   title="<?php printf( __( 'Edit %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
                <a class="control-btn control-btn-prepend vc-edit" href="#"
				   title="<?php printf( __( 'Prpend to %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
                <a class="control-btn control-btn-clone" href="#"
				   title="<?php printf( __( 'Clone %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
                <a class="control-btn control-btn-delete" href="#"
				   title="<?php printf( __( 'Delete %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
            </span>
				<a class="control-btn control-btn-switcher"
				   title="<?php printf( __( 'Show %s controls', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
        </div>
        <div class="element element-{{ tag }} active">
				<a class="control-btn vc-element-name vc-move-{{ tag }}"
				   title="<?php printf( __( 'Drag to move %s', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content">{{ name }}</span></a>
            <span class="advanced">
                <a class="control-btn control-btn-edit" href="#"
				   title="<?php printf( __( 'Edit %s', 'js_composer' ), '{{ name }}' ) ?>"><span class="vc-btn-content"><span
				  class="icon"></span></span></a>
                <a class="control-btn control-btn-prepend" href="#"
				   title="<?php printf( __( 'Prepend to %s', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
            </span>
				<a class="control-btn control-btn-switcher"
				   title="<?php printf( __( 'Show %s controls', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
        </div>
    </div>
    <div class="controls-bc">
			<a class="control-btn control-btn-append" href="#"
			   title="<?php printf( __( 'Append to %s', 'js_composer' ), '{{ name }}' ) ?>"><span
			  class="vc-btn-content"><span class="icon"></span></span></a>
    </div>
	</div>
	<!-- end controls-column -->
</script>
<script type="text/html" id="vc-controls-template-vc_column">
    <div class="controls-column">
        <div class="controls-out-tl">
            <div class="parent parent-{{ parent_tag }}">
				<a class="control-btn vc-element-name vc-element-move vc-move-{{ parent_tag }}"
				   title="<?php printf( __( 'Drag to move %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content">{{ parent_name }}</span></a>
        <span class="advanced">
            <a class="control-btn control-btn-edit vc-edit" href="#"
			   title="<?php printf( __( 'Edit %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
			  class="vc-btn-content"><span class="icon"></span></span></a>
            <a class="control-btn control-btn-prepend vc-edit" href="#"
			   title="<?php printf( __( 'Change %s layout', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
			  class="vc-btn-content"><span class="icon"></span></span></a>
            <a class="control-btn control-btn-clone" href="#"
			   title="<?php printf( __( 'Clone %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
			  class="vc-btn-content"><span class="icon"></span></span></a>
            <a class="control-btn control-btn-delete" href="#"
			   title="<?php printf( __( 'Delete %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
			  class="vc-btn-content"><span class="icon"></span></span></a>
        </span>
				<a class="control-btn control-btn-switcher"
				   title="<?php printf( __( 'Show %s controls', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
            </div>
            <div class="element element-{{ tag }} active">
				<a class="control-btn vc-element-name vc-move-vc_column"
				   title="<?php printf( __( 'Drag to move %s', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content">{{ name }}</span></a>
                <span class="advanced">
                    <a class="control-btn control-btn-edit" href="#"
					   title="<?php printf( __( 'Edit %s', 'js_composer' ), '{{ name }}' ) ?>"><span
					  class="vc-btn-content"><span class="icon"></span></span></a>
                    <a class="control-btn control-btn-prepend" href="#"
					   title="<?php printf( __( 'Prepend to %s', 'js_composer' ), '{{ name }}' ) ?>"><span
					  class="vc-btn-content"><span class="icon"></span></span></a>
                </span>
				<a class="control-btn control-btn-switcher"
				   title="<?php printf( __( 'Show %s controls', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
            </div>
        </div>
        <div class="controls-bc">
			<a class="control-btn control-btn-append" href="#"
			   title="<?php printf( __( 'Append to %s', 'js_composer' ), '{{ name }}' ) ?>"><span
			  class="vc-btn-content"><span class="icon"></span></span></a>
        </div>
	</div>
	<!-- end controls-column -->
</script>
<script type="text/html" id="vc-controls-template-vc_tab">
<div class="controls-column">
    <div class="controls-out-tr">
        <div class="parent parent-{{ parent_tag }}">
				<a class="control-btn vc-element-name vc-move-{{ parent_tag }} vc-element-move"
				   title="<?php printf( __( 'Drag to move %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content">{{ parent_name }}</span></a>
            <span class="advanced">
                <a class="control-btn control-btn-edit vc-edit" href="#"
				   title="<?php printf( __( 'Edit %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
                <a class="control-btn control-btn-prepend vc-edit" href="#"
				   title="<?php _e( 'Add new Tab', 'js_composer' ) ?>"><span class="vc-btn-content"><span
				  class="icon"></span></span></a>
                <a class="control-btn control-btn-clone" href="#"
				   title="<?php printf( __( 'Clone %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
                <a class="control-btn control-btn-delete" href="#"
				   title="<?php printf( __( 'Delete %s', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
            </span>
				<a class="control-btn control-btn-switcher"
				   title="<?php printf( __( 'Show %s controls', 'js_composer' ), '{{ parent_name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
        </div>
        <div class="element element-{{ tag }} active">
				<a class="control-btn vc-element-name vc-move-{{ tag }}"
				   title="<?php printf( __( 'Drag to move %s', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content">{{ name }}</span></a>
            <span class="advanced">
                <a class="control-btn control-btn-edit" href="#"
				   title="<?php printf( __( 'Edit %s', 'js_composer' ), '{{ name }}' ) ?>"><span class="vc-btn-content"><span
				  class="icon"></span></span></a>
                <a class="control-btn control-btn-prepend" href="#"
				   title="<?php printf( __( 'Prepend to %s', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
                <a class="control-btn control-btn-clone" href="#"
				   title="<?php printf( __( 'Clone %s', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
                <a class="control-btn control-btn-delete" href="#"
				   title="<?php printf( __( 'Delete %s', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
            </span>
				<a class="control-btn control-btn-switcher"
				   title="<?php printf( __( 'Show %s controls', 'js_composer' ), '{{ name }}' ) ?>"><span
				  class="vc-btn-content"><span class="icon"></span></span></a>
        </div>
    </div>
    <div class="controls-bc">
			<a class="control-btn control-btn-append" href="#"
			   title="<?php printf( __( 'Append to %s', 'js_composer' ), '{{ name }}' ) ?>"><span
			  class="vc-btn-content"><span class="icon"></span></span></a>
    </div>
	</div>
	<!-- end controls-column -->
</script>

<script type="text/html" id="vc-controls-template-vc_row">
<div class="controls-row">
    <div class="controls-out-tl">
        <a class="control-btn vc-element-name">{{ name }}</a>
        <a class="control-btn switcher"><span class="icon"></span></a>
        <a class="control-btn control-btn-move" href="#"><span class="icon"></span></a>
        <a class="control-btn control-btn-append" href="#"><span class="icon"></span></a>
        <a class="control-btn control-btn-edit vc-edit" href="#"><span class="icon"></span></a>
        <a class="control-btn control-btn-clone" href="#"><span class="icon"></span></a>
        <a class="control-btn control-btn-delete" href="#"><span class="icon"></span></a>
    </div>
</div>
</script>

<div id="vc-row-layout-panel" class="panel" style="display: none;">
    <div class="panel-heading">
		<a title="<?php _e( 'Close panel', 'js_composer' ); ?>" href="#" class="vc-close" data-dismiss="panel"
		   aria-hidden="true"><i class="icon"></i></a>
		<a title="<?php _e( 'Hide panel', 'js_composer' ); ?>" href="#" class="vc-transparent" data-transparent="panel"
		   aria-hidden="true"><i class="icon"></i></a>

        <h3 class="panel-title"><?php echo __('Row layout', 'js_composer') ?></h3>
    </div>
    <div class="panel-body vc-properties-list wpb-edit-form">
        <div class="row vc-row wpb_edit_form_elements">
            <div class="col-md-12 vc-column vc-layout-switcher">
                <div class="wpb_element_label"><?php _e('Row layout', 'js_composer') ?></div>
                <?php global $vc_row_layouts; ?>
                <?php foreach($vc_row_layouts as $layout): ?>
                    <a class="vc-layout-btn <?php echo $layout['icon_class']
                                                        .'" data-cells="'.$layout['cells']
                                                        .'" data-cells-mask="'.$layout['mask']
                                                        .'" title="'.$layout['title'] ?>"><span class="icon"></span></a>
                    <?php endforeach; ?>
				<span
				  class="description clear"><?php _e( "Choose row layout from predefined options.", "js_composer" ); ?></span>
            </div>
            <div class="col-md-12 vc-column">
                <div class="wpb_element_label"><?php _e('Enter custom layout for your row', 'js_composer') ?></div>
                <div class="edit_form_line">
					<input name="padding" class="wpb-textinput vc_row_layout" type="text" value="" id="vc-row-layout">
					<button id="vc-row-layout-update"
							class="btn btn-primary"><?php _e( 'Update', 'js_composer' ) ?></button>
					<span
					  class="description clear"><?php _e( "Change particular row layout manually by specifying number of columns and their size value.", "js_composer" ); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="vc-post-settings-panel" class="panel" style="display: none;">
    <div class="panel-heading">
		<a title="<?php _e( 'Close panel', 'js_composer' ); ?>" href="#" class="vc-close" data-dismiss="panel"
		   aria-hidden="true"><i class="icon"></i></a>
		<a title="<?php _e( 'Hide panel', 'js_composer' ); ?>" href="#" class="vc-transparent" data-transparent="panel"
		   aria-hidden="true"><i class="icon"></i></a>

        <h3 class="panel-title"><?php _e('Page settings', 'js_composer') ?></h3>
    </div>
    <div class="panel-body wpb-edit-form">
        <div class="row vc-row wpb_edit_form_elements">
            <div class="col-md-12 vc-column" id="vc-settings-title-container">
                <div class="wpb_element_label"><?php _e('Page title', 'js_composer') ?></div>
                <span class="description"></span>

                <div class="edit_form_line">
					<input name="page_title" class="wpb-textinput vc_title_name" type="text" value=""
						   id="vc-page-title-field"
						   placeholder="<?php _e( 'Please enter page title', 'js_composer' ) ?>">
                </div>
            </div>
            <div class="col-md-12 vc-column">
                <div class="wpb_element_label"><?php _e('Custom CSS settings', 'js_composer') ?></div>
                <div class="edit_form_line">
                    <textarea class="wpb_custom_post_css_editor"></textarea>
					<span
					  class="description"><?php _e( 'Enter custom CSS code here. Your custom CSS will be outputted only on this particular page.', "js_composer" ) ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer">
		<button type="button" class="btn btn-default"
				data-dismiss="panel"><?php _e( 'Close', 'js_composer' ) ?></button>
		<button type="button" class="btn btn-primary"
				data-save="true"><?php _e( 'Save changes', 'js_composer' ) ?></button>
    </div>
</div>
<div id="vc-properties-panel" class="panel">
    <div class="panel-heading">
		<a title="<?php _e( 'Close panel', 'js_composer' ); ?>" href="#" class="vc-close" data-dismiss="panel"
		   aria-hidden="true"><i class="icon"></i></a>
		<a title="<?php _e( 'Hide panel', 'js_composer' ); ?>" href="#" class="vc-transparent" data-transparent="panel"
		   aria-hidden="true"><i class="icon"></i></a>

        <h3 class="panel-title"></h3>
    </div>
    <div class="panel-body vc-properties-list wpb-edit-form">
        Select content element to edit properties.
    </div>
    <div class="panel-footer">
		<button type="button" class="btn btn-default vc-close"
				data-dismiss="panel"><?php _e( 'Close', 'js_composer' ) ?></button>
		<button type="button" class="btn btn-primary"
				data-save="true"><?php _e( 'Save changes', 'js_composer' ) ?></button>
    </div>
</div>

<div id="vc-multi-properties-panel" class="panel">
    <div class="panel-heading">
		<a title="<?php _e( 'Close panel', 'js_composer' ); ?>" href="#" class="vc-close" data-dismiss="panel"
		   aria-hidden="true"><i class="icon"></i></a>
		<a title="<?php _e( 'Hide panel', 'js_composer' ); ?>" href="#" class="vc-transparent" data-transparent="panel"
		   aria-hidden="true"><i class="icon"></i></a>

        <h3 class="panel-title"><?php _e('Edit elements', 'js_composer') ?></h3>
    </div>
    <div class="panel-body vc-properties-list">
        Select content element to edit properties.
    </div>
    <div class="panel-footer">
		<button type="button" class="btn btn-default"
				data-dismiss="panel"><?php _e( 'Close', 'js_composer' ) ?></button>
		<button type="button" class="btn btn-primary vc-save"
				data-save="true"><?php _e( 'Save changes', 'js_composer' ) ?></button>
    </div>
</div>

<div id="vc-templates-editor" class="panel" style="display: none;">
    <div class="panel-heading">
		<a title="<?php _e( 'Close panel', 'js_composer' ); ?>" href="#" class="vc-close" data-dismiss="panel"
		   aria-hidden="true"><i class="icon"></i></a>
		<a title="<?php _e( 'Hide panel', 'js_composer' ); ?>" href="#" class="vc-transparent" data-transparent="panel"
		   aria-hidden="true"><i class="icon"></i></a>

        <h3 class="panel-title"><?php _e('Templates', 'js_composer') ?></h3>
    </div>
    <div class="panel-body wpb-edit-form vc-templates-body">
        <div class="row vc-row wpb_edit_form_elements">
            <div class="col-md-12 vc-column">
                <div class="wpb_element_label"><?php _e('Save current layout as a template', 'js_composer') ?></div>
                <div class="edit_form_line">
					<input name="padding" class="wpb-textinput vc_title_name" type="text" value="" id="vc-template-name"
						   placeholder="<?php _e( 'Template name', 'js_composer' ) ?>">
					<button id="vc-template-save"
							class="btn btn-primary"><?php _e( 'Save template', 'js_composer' ) ?></button>
                </div>
				<span
				  class="description"><?php _e( 'Save your layout and reuse it on different sections of your website', 'js_composer' ) ?></span>
            </div>
            <div class="col-md-12 vc-column">
                <div class="wpb_element_label"><?php _e('Load Template', 'js_composer') ?></div>
				<span
				  class="description"><?php _e( 'Append previosly saved template to the current layout', 'js_composer' ) ?></span>
                <ul class="wpb_templates_list" id="vc-template-list">
                   <?php echo $this->template_menu->getMenu(true) ?>
               </ul>
            </div>
        </div>
    </div>
    <div class="panel-footer">
		<button type="button" class="btn btn-default vc-close"
				data-dismiss="panel"><?php _e( 'Close', 'js_composer' ) ?></button>
    </div>
</div>


<ol id="vc-breadcrumb" class="breadcrumb">
    <li class="root">Visual Composer</li>
</ol>
<script type="text/javascript">
    var vc_user_mapper = <?php echo json_encode(WPBMap::getUserShortCodes()) ?>,
        vc_mapper = <?php echo json_encode(WPBMap::getShortCodes()) ?>;
</script>

<div data-type="text/html" id="vc-template-post-content" style="display: none;">
    <?php $this->getPageShortcodes() ?>
    <div id="vc-no-content-helper" class="vc-no-content-helper">
        <span class="icon"></span>
        <h5><?php _e('Welcome to Blank Page', 'js_composer') ?></h5>

        <h3><?php _e('You Have No Content Yet! Add Some...', 'js_composer') ?></h3>

        <div class="vc-buttons">
			<a id="vc-not-empty-add-element" class="vc-add-element-not-empty-button vc-add-element-action"
			   title="<?php _e( 'Add element', 'js_composer' ) ?>"></a>
			<a id="vc-no-content-add-element"
			   class="vc-add-element-button vc-add-element-action vc_btn vc_btn_pink vc_btn_md vc_btn_3d" href="#"
			   title="<?php _e( 'Add element', 'js_composer' ) ?>"><?php _e( 'Add element', 'js_composer' ) ?></a>
			<a id="vc-no-content-add-text-block" class="vc-add-text-block-button vc_btn vc_btn_sky vc_btn_md vc_btn_3d"
			   href="#"
			   title="<?php _e( 'Add text block', 'js_composer' )?>"><?php _e( 'Add text block', 'js_composer' )?></a>
        </div>

    </div>
</div>
<script type="text/javascript">
    vc_post_shortcodes = <?php echo json_encode($this->post_shortcodes) ?>
</script>
<script type="text/html" id="vc-settings-image-block">
    <li class="added">
        <div class="inner" style="width: 75px; height: 75px; overflow: hidden;text-align: center;">
            <img rel="<%= id %>" src="<%= url %>" />
        </div>
        <a href="#" class="icon-remove"></a>
    </li>
</script>
<div style="height: 1px; visibility: hidden; overflow: hidden;">
<?php
wp_editor( $post->post_content, 'content', array(
    'dfw' => true,
    'tabfocus_elements' => 'insert-media-button,save-post',
    'editor_height' => 360
) );
	// wp_enqueue_script( 'post' );
wp_nonce_field($nonce_action); ?>
    <input type="hidden" id="user-id" name="user_ID" value="<?php echo (int) $user_ID ?>" />
    <input type="hidden" id="hiddenaction" name="action" value="<?php echo esc_attr( $form_action ) ?>" />
	<input type="hidden" id="originalaction" name="originalaction" value="<?php echo esc_attr( $form_action ) ?>"/>
	<input type="hidden" id="post_author" name="post_author"
		   value="<?php echo esc_attr( $this->post->post_author ); ?>"/>
    <input type="hidden" id="referredby" name="referredby" value="<?php echo esc_url(wp_get_referer()); ?>" />
<?php
    if ( wp_is_mobile() )
    wp_enqueue_script( 'jquery-touch-punch' );
	do_action('vc_frontend_editor_render');
?>
</div>
<?php require_once( $this->adminFile('admin-footer.php') ); ?>