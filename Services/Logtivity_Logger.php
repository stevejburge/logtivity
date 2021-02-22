<?php

class Logtivity_Logger extends Logtivity_Log_API
{
	/**
	 * Can this instance log something
	 * 
	 * @var bool
	 */
	public $active = true;

	/**
	 * Logtivity_Wp_User
	 * 
	 * @var object
	 */
	public $user;

	/**
	 * The action for the given log
	 * 
	 * @var string
	 */
	public $action;

	/**	
	 * Extra info to pass to the log
	 * 
	 * @var array
	 */
	public $meta = [];

	/**	
	 * Extra user meta to pass to the log
	 * 
	 * @var array
	 */
	public $userMeta = [];

	/**	
	 * Allow the overriding of async posting on a per log basis. Gives us the ability 
	 * to turn it off for certain logs that don't support async. eg. logout
	 * 
	 * @var boolean
	 */
	public $canAsync = true;

	/**	
	 * Set the user and call the parent constructor
	 */
	public function __construct($user_id = null)
	{
		$this->user = new Logtivity_Wp_User($user_id);

		parent::__construct();
	}

	/**
	 * Way into class. 
	 * 
	 * @param  string $action
	 * @param  string $meta
	 * @param  string $user_id
	 * @return Logtivity_Logger::send()
	 */
	public static function log($action = null, $meta = null, $user_id = null)
	{
		$Logtivity_logger = new Logtivity_Logger($user_id);

		if(func_num_args() == 0) {

			return new $Logtivity_logger;

		}

		$Logtivity_logger->setAction($action);

		if ($meta) {
			$Logtivity_logger->addMeta($meta['key'], $meta['value']);
		}

		return $Logtivity_logger->send();
	}

	/**
	 * Set the action string beore sending
	 * 
	 * @param string
	 */
	public function setAction($action)
	{
		$this->action = $action;

		return $this;
	}

	/**
	 * Add to an array any additional information you would like to pass to this log.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function addMeta($key, $value)
	{
		$this->meta[$key] = $value;

		return $this;
	}

	/**
	 * Add to an array of user meta you would like to pass to this log.
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @return $this
	 */
	public function addUserMeta($key, $value)
	{
		$this->userMeta[$key] = $value;

		return $this;
	}

	/**
	 * Set whether this log is going to be posted asynchronously or not
	 * 
	 * @param  boolean $value
	 * @return $this
	 */
	public function async($value = true)
	{
		$this->canAsync = $value;

		return $this;
	}

	/**
	 * Stop this instance of Logtivity_Logger from logging
	 * 
	 * @return $this
	 */
	public function stop()
	{
		$this->active = false;

		return $this;
	}

	/**
	 * Send the logged data to Logtivity
	 * 
	 * @return void
	 */
	public function send()
	{
		$this->maybeAddProfileLink();

		do_action('wp_logtivity_instance', $this);

		if (!$this->active) {
			return;
		}

		if ($this->options->shouldPostAsynchronously() && $this->canAsync) {
			
			$store_log = Store_Log::getInstance();

			return $store_log->data($this->getData('action_name'))->dispatch();

		}

		return $this->makeRequest($this->getStoreUrl(), $this->getData());
	}

	/**	
	 * Build the data array for storing the log
	 *
	 * Allow to override the action array key as 
	 * if using Asynchronous posting, the 
	 * $_POST['action'] is reserved.
	 *
	 * @param  $action Action array key
	 * @return array
	 */
	protected function getData($action = 'action')
	{
		return [
			$action => $this->action, 
			'meta' => $this->getMeta(),
			'user_id' => $this->getUserID(),
			'username' => $this->maybeGetUsersUsername(),
			'user_meta' => $this->getUserMeta(),
			'ip_address' => $this->maybeGetUsersIp(),
		];
	}

	/**
	 * Protected function to get the User ID if the user is logged in
	 * 
	 * @return mixed string|integer
	 */
	protected function getUserID()
	{
		if (!$this->options->shouldStoreUserId()) {
			return;
		}

		if ($this->user->isLoggedIn()) {
			return $this->user->id();
		}

		return;
	}

	/**
	 * Maybe get the users IP address
	 * 
	 * @return string|false
	 */
	protected function maybeGetUsersIp()
	{
		if (!$this->options->shouldStoreIp()) {
			return;
		}

		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			
			//check ip from share internet
			return $_SERVER['HTTP_CLIENT_IP'];

		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		
			//to check ip is pass from proxy
			return $_SERVER['HTTP_X_FORWARDED_FOR'];

		} else {
		
			return $_SERVER['REMOTE_ADDR'];

		}
	}

	/**
	 * Build the user meta array
	 *
	 * @return array
	 */
	public function getUserMeta()
	{
		return apply_filters('wp_logtivity_get_user_meta', $this->userMeta);
	}

	/**
	 * Build the meta array
	 *
	 * @return array
	 */
	public function getMeta()
	{
		return apply_filters('wp_logtivity_get_meta', $this->meta);
	}

	/**
	 * Maybe get the users profile link
	 * 
	 * @return string|false
	 */
	protected function maybeAddProfileLink()
	{
		if (!$this->options->shouldStoreProfileLink()) {
			return;
		}

		$profileLink = $this->user->profileLink();

		if ($profileLink == '') {
			return null;
		}

		return $this->addUserMeta('Profile Link', $profileLink);
	}

	/**
	 * Maybe get the users username
	 * 
	 * @return string|false
	 */
	protected function maybeGetUsersUsername()
	{
		if (!$this->options->shouldStoreUsername()) {
			return null;
		}

		return $this->user->userLogin();
	}

}
