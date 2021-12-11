<?php echo logtivity_view('_admin-header'); ?>

<div class="postbox">
	<div class="logtivity-settings">

		<div class="inside">
			<h1 style="padding-top: 20px;">Logs</h1>
		</div>

		<form id="logtivity-log-index-search-form" method="GET" action="<?php echo admin_url( 'admin-ajax.php' ) ?>">
			<input type="hidden" name="action" value="logtivity_log_index_filter">
		</form>

		<div id="logtivity-log-index">
			<!-- Populated with AJAX -->
		</div>

	</div>
</div>

<?php echo logtivity_view('_admin-footer', compact('options')); ?>