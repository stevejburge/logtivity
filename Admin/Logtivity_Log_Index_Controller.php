<?php

class Logtivity_Log_Index_Controller
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
			(new Logtivity_Api)->get('/logs', [
				'page' => $_GET['page'] ?? null,
				'action' => $_GET['search_action'] ?? null,
				'context' => $_GET['search_context'] ?? null,
				'action_user' => $_GET['action_user'] ?? null,
			])
		);

		return wp_send_json([
			'view' => logtivity_view('_logs-loop', [
				'logs' => $response->data,
				'meta' => $response->meta,
				'hasNextPage' => $response->links->next,
			])
		]);
	}
}

new Logtivity_Log_Index_Controller;