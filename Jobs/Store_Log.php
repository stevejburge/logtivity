<?php

class Store_Log extends Logtivity_Async_Request 
{
	/**
	 * @var string
	 */
	protected $action = 'store_log';

    /**
     * The unique instance of the plugin.
     *
     * @var Store_Log
     */
    private static $instance;
 
    /**
     * Gets an instance of our plugin.
     *
     * @return Store_Log
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }

	/**
	 * Handle
	 *
	 * Override this method to perform any actions required
	 * during the async request.
	 */
	protected function handle() 
	{
		$Logtivity_Log_API = new Logtivity_Log_API;

		$Logtivity_Log_API->makeRequest($Logtivity_Log_API->getStoreUrl(), [
			'action' => sanitize_text_field($_POST['action_name']),
			'meta' => ( isset($_POST['meta']) ? (array) $_POST['meta'] : []),
			'user_id' => ( isset($_POST['user_id']) ? intval($_POST['user_id']) : null ),
			'username' => ( isset($_POST['username']) ? sanitize_user($_POST['username']) : null),
			'user_meta' => ( isset($_POST['user_meta']) ? (array) $_POST['user_meta'] : []),
			'ip_address' => ( isset($_POST['ip_address']) ? sanitize_text_field($_POST['ip_address']) : null),
		]);
	}
}

add_action('init', function() {

	$store_log = Store_Log::getInstance();

});
