<h1>Logtivity.io</h1>

<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>?action=logtivity_update_settings" method="post">

	<?php wp_nonce_field( 'logtivity_update_settings', 'logtivity_update_settings' ) ?>

	<table class="form-table">
		<tbody>
			<tr class="user-user-login-wrap">
				<th><label for="logtivity_site_api_key">Site API Key</label></th>
				<td>
					<input type="text" name="logtivity_site_api_key" id="logtivity_site_api_key" value="<?php echo sanitize_text_field($options['logtivity_site_api_key']); ?>" class="regular-text">
				</td>
				<td>
					<span class="description">You can find this value by logging into your account and navigating to/creating this site settings page.</span>
				</td>
			</tr>
			<tr class="user-user-login-wrap">
				<th><label for="logtivity_should_log_profile_link">Store Users Profile Link</label></th>
				<td>
					<input type="hidden" name="logtivity_should_log_profile_link" id="logtivity_should_log_profile_link" value="0">

					<input type="checkbox" name="logtivity_should_log_profile_link" id="logtivity_should_log_profile_link" value="1" class="regular-checkbox" <?php echo ( absint($options['logtivity_should_log_profile_link']) ? 'checked' : ''); ?>>
				</td>
				<td>
					<span class="description">If you check this box, when logging an action, we will include the users profile link in the logged action.</span>
				</td>
			</tr>
			<tr class="user-user-login-wrap">
				<th><label for="logtivity_should_log_username">Store Users Username</label></th>
				<td>
					<input type="hidden" name="logtivity_should_log_username" id="logtivity_should_log_username" value="0">

					<input type="checkbox" name="logtivity_should_log_username" id="logtivity_should_log_username" value="1" class="regular-checkbox" <?php echo ( absint($options['logtivity_should_log_username']) ? 'checked' : ''); ?>>
				</td>
				<td>
					<span class="description">If you check this box, when logging an action, we will include the users username in the logged action.</span>
				</td>
			</tr>
			<tr class="user-user-login-wrap">
				<th><label for="logtivity_should_store_ip">Store Users IP Address</label></th>
				<td>
					<input type="hidden" name="logtivity_should_store_ip" id="logtivity_should_store_ip" value="0">

					<input type="checkbox" name="logtivity_should_store_ip" id="logtivity_should_store_ip" value="1" class="regular-checkbox" <?php echo ( absint($options['logtivity_should_store_ip']) ? 'checked' : ''); ?>>
				</td>
				<td>
					<span class="description">If you check this box, when logging an action, we will include the users IP address in the logged action.</span>
				</td>
			</tr>
			<tr class="user-user-login-wrap">
				<th><label for="logtivity_should_post_asynchronously">Store Logs Aynchronously (recommended)</label></th>
				<td>
					<input type="hidden" name="logtivity_should_post_asynchronously" id="logtivity_should_post_asynchronously" value="0">

					<input type="checkbox" name="logtivity_should_post_asynchronously" id="logtivity_should_post_asynchronously" value="1" class="regular-checkbox" <?php echo ( absint($options['logtivity_should_post_asynchronously']) ? 'checked' : ''); ?>>
				</td>
				<td>
					<span class="description">
						We recommend enabling this for optimisation. However this can be useful to disable for debugging. Async may not work in certain dev/production environments. This can be because the hosts server doesn't resolve the domain in their hosts file. If this is the case you will need them to update the servers hosts file point the local IP address to your domain.
						<br>
						This setting might look like this.
						<code>127.0.0.1 <?php echo sanitize_text_field($_SERVER['SERVER_NAME']); ?></code>
						<br>
						More information on this can be <a href="https://wordpress.org/support/topic/wp_remote_post-test-back-to-this-server-failed-response-was-curl-error-6/" target="_blank" rel="nofollow">found here</a>.
					</span>
				</td>
			</tr>
			<tr class="user-user-login-wrap">
				<th><label for="logtivity_should_log_latest_response">Log latest response from the Logtivity API</label></th>
				<td>
					<input type="hidden" name="logtivity_should_log_latest_response" id="logtivity_should_log_latest_response" value="0">

					<input type="checkbox" name="logtivity_should_log_latest_response" id="logtivity_should_log_latest_response" value="1" class="regular-checkbox" <?php echo ( absint($options['logtivity_should_log_latest_response']) ? 'checked' : ''); ?>>
				</td>
				<td>
					<span class="description">This can be useful for debugging the result from an API call when storing a log.</span>
				</td>
			</tr>
		</tbody>
	</table>

	<p class="submit">
		<input type="submit" name="submit" id="submit" class="button button-primary" value="Update Settings">
	</p>

</form>

<?php if (absint( $options['logtivity_should_log_latest_response'] )): ?>

	<h3>Latest Response</h3>

	<?php if ($latest_response = $options['logtivity_latest_response']): ?>

		<h4>Date: <?php echo sanitize_text_field($latest_response['date']); ?></h4>

		<code style="display: block; padding: 20px;">
				
			<?php echo sanitize_text_field($latest_response['response']); ?>

		</code>			
			
	<?php else:  ?>

		<p>The latest logging response will appear here after an event has been logged.</p>

	<?php endif ?>
	
<?php endif ?>