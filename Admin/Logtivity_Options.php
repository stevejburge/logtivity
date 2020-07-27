<?php

class Logtivity_Options
{
	/**
	 * The option keys that we can save to the options table
	 * 
	 * @var array
	 */
	protected $settings = [
		'logtivity_site_api_key',
		'logtivity_disable_default_logging',
		'logtivity_should_store_ip',
		'logtivity_should_post_asynchronously',
		'logtivity_should_log_latest_response',
		'logtivity_should_log_profile_link',
		'logtivity_should_log_username',
		'logtivity_latest_response',
	];

	/**
	 * Get the admin settings or the plugin
	 * 
	 * @return array
	 */
	public function getOptions()
	{
		$options = [];

		foreach ($this->settings as $setting) {
			$options[$setting] = get_option($setting);
		}

		return $options;
	}

	/**
	 * Get an option from the database
	 * 
	 * @param  string $key
	 * @return mixed
	 */
	public function getOption($key)
	{
		if (!in_array($key, $this->settings)) {
			return false;
		}

		return get_option($key);
	}

	/**
	 * Should we store the users IP?
	 * 
	 * @return bool
	 */
	public function shouldStoreIp()
	{
		return $this->getOption('logtivity_should_store_ip');
	}

	/**	
	 * Should we store the users profile link?
	 * 
	 * @return bool
	 */
	public function shouldStoreProfileLink()
	{
		return $this->getOption('logtivity_should_log_profile_link');
	}

	/**	
	 * Should we store the users username?
	 * 
	 * @return bool
	 */
	public function shouldStoreUsername()
	{
		return $this->getOption('logtivity_should_log_username');
	}

	/**
	 * Should we be posting to the Log api asynchronously?
	 * 
	 * @return bool
	 */
	public function shouldPostAsynchronously()
	{
		return $this->getOption('logtivity_should_post_asynchronously');
	}

	/**	
	 * Should we be logging the response from the API
	 * 
	 * @return bool
	 */
	public function shouldLogLatestResponse()
	{
		return $this->getOption('logtivity_should_log_latest_response');
	}

	/**
	 * Update the options for this plugin
	 * 
	 * @param  array $data
	 * @return void[type]       [description]
	 */
	public function update($data)
	{
		foreach ($data as $key => $value) 
		{
			if (in_array($key, $this->settings)) 
			{
				update_option($key, $value);
			}
		}
	}
}