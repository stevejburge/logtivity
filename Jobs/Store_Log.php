<?php

class Store_Log extends Logtivity_Async_Request {

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
			'action' => $_POST['action_name'],
			'meta' => $_POST['meta'],
			'user_id' => $_POST['user_id'],
			'username' => $_POST['username'],
			'user_meta' => $_POST['user_meta'],
			'ip_address' => $_POST['ip_address'],
		]);
	}

}

add_action('init', function() {

	$store_log = Store_Log::getInstance();

});
