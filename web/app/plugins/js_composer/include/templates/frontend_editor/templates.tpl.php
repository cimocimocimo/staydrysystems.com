<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li>
					<button id="vc-builder-moder" type="button" class="btn btn-default btn-sm navbar-btn">Composer /
						Preview
					</button>
				</li>
				<li>&nbsp;</li>
				<li>
					<button id="vc-add-new-element" type="button" class="btn btn-default btn-sm navbar-btn">Add
						element
					</button>
				</li>
				<li>&nbsp;</li>
				<li>
					<button id="vc-add-new-row" type="button" class="btn btn-default btn-sm navbar-btn">Add row</button>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Templates <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="#">Test template 1 <span class="glyphicon glyphicon-remove-sign"></span></a></li>
					</ul>
				</li>
				<li>&nbsp;</li>
				<li>
					<div class="btn-group btn-group-xs">
						<button type="button" class="btn btn-default btn-sm navbar-btn vc-screen-width"
								data-size="100%">Default
						</button>
						<button type="button" class="btn btn-default btn-sm navbar-btn vc-screen-width"
								data-size="768px">Portrait tablets
						</button>
						<button type="button" class="btn btn-default btn-sm navbar-btn vc-screen-width"
								data-size="480px">Phones
						</button>
					</div>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li>
					<button type="button" class="btn btn-default btn-sm navbar-btn" id="vc-button-cancel">Cancel
					</button>
				</li>
				<li>&nbsp;</li>
				<li>
					<button type="button" class="btn btn-primary btn-sm navbar-btn" id="vc-button-update">Update
					</button>
				</li>
			</ul>
		</div>
		<!--/.nav-collapse -->
	</div>
</div>
<iframe src="" scrolling="no" style="width: 100%;vc" id="vc-inline-frame"></iframe>

<div class="modal fade" id="vc-add-element-dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	 aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Add element</h4>
			</div>
			<div class="modal-body">
				<ul class="wpb-content-layouts clearfix">
					<?php foreach ( WPBMap::getUserShortCodes() as $shortcode ): ?>
					<?php if ( empty( $shortcode['is_container'] ) ): ?>
						<li class="wpb-layout-element-button">
							<a href="#" class="dropable_el clickable_action vc-shortcode-link"
							   data-shortcode="<?php echo $shortcode['base'] ?>"><i
							  class="vc-element-icon <?php echo $shortcode['icon'] ?>"></i> <?php echo $shortcode['name'] ?><?php echo isset( $shortcodes['description'] ) && strlen( $shortcode['description'] ) ? '<i class="vc-element-description">' . htmlspecialchars( $shortcode['description'] ) . '</i>' : '' ?>
							</a>
						</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
				<h4 class="modal-title" id="myModalLabelTitle">Edit element</h4>
			</div>
			<div class="modal-body vc-properties-list">

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
			<a class="control-btn control-btn-edit" href="#"></a><a class="control-btn control-btn-clone"
																	href="#"></a><a
		  class="control-btn control-btn-delete" href="#"></a>
		</div>
	</div>
</script>
<script type="text/html" id="vc-controls-template-vc_column">
	<div class="controls-column">
		<div class="controls-tl">
			<a class="control-btn control-btn-edit" href="#"></a><a class="control-btn control-btn-move" href="#"></a>
		</div>
		<div class="controls-tc">
			<a class="control-btn control-btn-prepend" href="#"></a><a class="control-btn" href="#"></a>
		</div>
		<div class="controls-tr">
			<a class="control-btn" href="#"></a><a class="control-btn" href="#"></a>
		</div>

		<div class="controls-bl">
			<a class="control-btn" href="#"></a><a class="control-btn" href="#"></a>
		</div>
		<div class="controls-bc">
			<a class="control-btn control-btn-append" href="#"></a><a class="control-btn vc-prepend-element"
																	  href="#"></a>
		</div>
		<div class="controls-br">
			<a class="control-btn" href="#"></a><a class="control-btn" href="#"></a>
		</div>
	</div>
	<!-- end controls-column -->
</script>

<script type="text/html" id="vc-controls-template-vc_row">
	<div class="controls-row">
		<div class="controls-out-l">
                <span class="control-btn control-btn-layout">
                    <span class="vc-layout-switcher">
                        <a class="vc-layout-btn l_1" href="#" title="" data-columns="12"></a>
                        <a class="vc-layout-btn l_12_12" href="#" title="" data-columns="6 6"></a>
                        <a class="vc-layout-btn l_23_13" href="#" title="" data-columns="8 4"></a>
                        <a class="vc-layout-btn l_13_13_13" href="#" title="" data-columns="4 4 4"></a>
                        <a class="vc-layout-btn l_14_14_14_14" href="#" title="" data-columns="3 3 3 3"></a>
                        <a class="vc-layout-btn l_14_34" href="#" title="" data-columns="3 9"></a>
                        <a class="vc-layout-btn l_14_12_14" href="#" title="" data-columns="3 6 3"></a>
                        <a class="vc-layout-btn l_56_16" href="#" title="" data-columns="10 2"></a>
                        <a class="vc-layout-btn l_16_46_16" href="#" title="" data-columns="2 8 2"></a>
                        <a class="vc-layout-btn l_16_16_16_12" href="#" title="" data-columns="2 2 2 6"></a>
                        <a class="vc-layout-btn l_16_16_16_16_16_16" href="#" title="" data-columns="2 2 2 2 2 2"></a>
                        <a class="vc-layout-btn vc-custom-layout-btn control-btn-add" href="#" title="">Custom
							Layout</a>
                    </span>
                </span>
			<a class="control-btn control-btn-move" href="#"></a>
		</div>
		<div class="controls-out-r">
			<a class="control-btn control-btn-edit vc-edit" href="#"></a><a class="control-btn control-btn-clone"
																			href="#"></a><a
		  class="control-btn control-btn-delete" href="#"></a>
		</div>
	</div>
	<!-- end controls-row -->
</script>
<div id="vc-properties-panel" class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Panel title</h3>
	</div>
	<div class="panel-body vc-properties-list">
		Select content element to edit properties.
	</div>
	<div class="panel-footer">
		<button type="button" class="btn btn-default vc-close" data-dismiss="panel">Close</button>
		<button type="button" class="btn btn-primary vc-save">Save changes</button>
	</div>
</div>

<ol id="vc-breadcrumb" class="breadcrumb">
	<li class="root">Visual Composer</li>
</ol>
<script type="text/javascript">
	var vc_user_mapper = <?php echo json_encode( WPBMap::getUserShortCodes() ) ?>,
	  vc_mapper = <?php echo json_encode( WPBMap::getShortCodes() ) ?>;
</script>

<script type="text/html" id="vc-template-post-content">
	<?php $this->getPageShortcodes() ?>
</script>
<script type="text/javascript">
	vc.post_shortcodes = <?php echo json_encode( $this->post_shortcodes ) ?>
</script>