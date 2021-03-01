<?php

class Logtivity_Log_API
{
	public $logtivityStoreLogEndpoint = 'https://api.logtivity.io/logs/store';
	
	/**
	 * Option class to access the plugin settings
	 * 
	 * @var object
	 */
	protected $options;

	public function __construct()
	{
		$this->options = new Logtivity_Options;
	}

	/**
	 * Get the endpoint we post to with the log data to store
	 * 
	 * @return string
	 */
	public function getStoreUrl()
	{
		if (defined('Logtivity_Store_Endpoint')) {
		    return Logtivity_Store_Endpoint;
		}
		
		return $this->logtivityStoreLogEndpoint;
	}

	/**	
	 * Make a request to the Logtivity API
	 * 
	 * @param  string $url
	 * @param  array $body
	 * @param  string $method
	 * @return mixed $response
	 */
	public function makeRequest($url, $body, $method = 'POST')
	{
		$api_key = logtivity_get_api_key();

		if (!$api_key) 
		{
			return;
		}

		$shouldLogLatestResponse = $this->options->shouldLogLatestResponse();

		$response = wp_remote_post( $url, [
			'method' => $method,
			'timeout'   => ( $shouldLogLatestResponse ? 45 : 0.01),
			'blocking'  => ( $shouldLogLatestResponse ? true : false),
			'redirection' => 5,
			'httpversion' => '1.0',
			'headers' => array(),
			'body' => array_merge(['api_key' => $api_key], $body),
			'cookies' => array()
		]);

		$response = wp_remote_retrieve_body($response);

		if ($shouldLogLatestResponse) {

			$this->options->update([
				'logtivity_latest_response' => [
					'date' => date("Y-m-d H:i:s"),
					'response' => print_r($response, true)
				]
			]);

		}

		return $response;
	}
}