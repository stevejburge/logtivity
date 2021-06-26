<?php

class Logtivity_Log_API
{
	public $logtivityApiUrl = 'https://api.logtivity.io';
	
	/**
	 * Option class to access the plugin settings
	 * 
	 * @var object
	 */
	protected $options;

	/**
	 * Should we wait to return the response from the API?
	 * 
	 * @var boolean
	 */
	public $waitForResponse = false;

	public function __construct()
	{
		$this->options = new Logtivity_Options;
	}

	/**
	 * Get the API URL for the Logtivity endpoint
	 * 
	 * @return string
	 */
	public function getEndpoint($endpoint)
	{
		if (defined('LOGTIVITY_API_URL')) {
		    return LOGTIVITY_API_URL . $endpoint;
		}
		
		return $this->logtivityApiUrl . $endpoint;
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

		$shouldLogLatestResponse = $this->waitForResponse || $this->options->shouldLogLatestResponse();

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