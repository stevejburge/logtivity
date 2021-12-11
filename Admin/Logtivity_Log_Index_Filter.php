<?php

class Logtivity_Log_Index_Filter
{
	public function __construct()
	{
		add_action("wp_ajax_nopriv_logtivity_log_index_filter", [$this, 'search']);
		add_action("wp_ajax_logtivity_log_index_filter", [$this, 'search']);
	}

	public function search()
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$response = json_decode(
			(new Logtivity_Api)->get('/logs', [])
		);

		return wp_send_json([
			'view' => logtivity_view('_logs-loop', [
				'logs' => $response->data,
				'links' => $response->links,
			])
		]);
	}
}

new Logtivity_Log_Index_Filter;