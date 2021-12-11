<table class="form-table">
	<thead>
		<tr>
			<th style="padding: 8px 10px">Action</th>
			<th style="padding: 8px 10px">Context</th>
			<th style="padding: 8px 10px">User</th>
			<th style="padding: 8px 10px" colspan="2">Date</th>
		</tr>
	</thead>
	<tbody>
		
		<?php if (count($logs)): ?>
			<?php foreach ($logs as $key => $log): ?>

				<tr class="<?php echo ( $key % 2 == 0 ? 'alternate' : '') ?>">
					<td><?php echo sanitize_text_field($log->action) ?></td>
					<td><?php echo sanitize_text_field($log->context) ?></td>
					<td>
						<div style="margin-bottom: 5px;"><?php echo sanitize_text_field($log->action_username ?? $log->action_user_id) ?></div>
						<?php echo sanitize_text_field($log->ip_address) ?>
					</td>
					<td>
						<?php echo sanitize_text_field(date('M d Y', strtotime($log->occurred_at))) ?> - <?php echo sanitize_text_field(date('H:i:s', strtotime($log->occurred_at))); ?>
					</td>
					<td>
						<form action=""></form>
					</td>
				</tr>

			<?php endforeach ?>
		<?php else: ?>
			<tr>
				<td colspan="6">No results found.</td>
			</tr>
		<?php endif ?>

	</tbody>
</table>

<?php if ($meta->current_page): ?>
	<div style="text-align: center; padding: 20px">
	
		<button <?php echo ( $meta->current_page == 1 ? 'disabled' : ''); ?> class="js-logtivity-pagination button-primary" data-page="<?php echo sanitize_text_field($meta->current_page - 1) ?>">Previous</button>
		
		<button <?php echo ( $meta->current_page >= $meta->last_page ? 'disabled' : ''); ?> class="js-logtivity-pagination button-primary" data-page="<?php echo sanitize_text_field($meta->current_page + 1) ?>">Next</button>

	</div>
<?php endif ?>