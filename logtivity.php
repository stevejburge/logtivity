<?php

/**
 * Plugin Name: Logtivity
 * Plugin URI:  https://logtivity.io
 * Description: Dedicated Event Monitoring for WordPress using Logtivity.io.
 * Version:     1.6.0
 * Author:      Logtivity
 */

class Logtivity
{
	/**
	 * List all classes here with their file paths. Keep class names the same as filenames.
	 * 
	 * @var array
	 */
	private $dependancies = [
		'Helpers/Helpers',
		'Helpers/Logtivity_Wp_User',
		'Admin/Logtivity_Options',
		'Admin/Logtivity_Admin',
		'Services/Logtivity_Log_API',
		'Services/Logtivity_Logger',
		'Helpers/Logtivity_Log_Global_Function',
		'Logs/Logtivity_Abstract_Logger',
	];

	/**
	 *
	 *	Log classes
	 * 
	 */
	private $logClasses = [
		'Logs/Core/Logtivity_Post',
		'Logs/Core/Logtivity_User',
		'Logs/Core/Logtivity_Core',
		'Logs/Core/Logtivity_Theme',
		'Logs/Core/Logtivity_Plugin',
		'Logs/Core/Logtivity_Comment',
	];

	/**
	 * List all integration dependancies
	 * 
	 * @var array
	 */
	private $integrationDependancies = [
		'WP_DLM' => [
			'Logs/Download_Monitor/Logtivity_Download_Monitor',
		],
		'MeprCtrlFactory' => [
			'Logs/Memberpress/Logtivity_Memberpress',
		],
		'Easy_Digital_Downloads' => [
			'Logs/Easy_Digital_Downloads/Logtivity_Easy_Digital_Downloads'
		]
	];

	public function __construct()
	{
		$this->loadDependancies();

		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [$this, 'addSettingsLinkFromPluginsPage'] );
	}

	public static function log($action = null, $meta = null, $user_id = null)
	{
		return Logtivity_Logger::log($action, $meta, $user_id);
	}

	public function loadDependancies()
	{
		foreach ($this->dependancies as $filePath) 
		{
			$this->loadFile($filePath);
		}

		$this->maybeLoadLogClasses();

		$this->loadIntegrationDependancies();
	}

	public function maybeLoadLogClasses()
	{
		if ($this->defaultLoggingDisabled()) {
			return;
		}

		foreach ($this->logClasses as $filePath) 
		{
			$this->loadFile($filePath);
		}
	}

	/**
	 * Is the default Event logging from within the plugin enabled
	 * 
	 * @return bool
	 */
	public function defaultLoggingDisabled()
	{
		return (new Logtivity_Options)->getOption('logtivity_disable_default_logging');
	}

	public function loadIntegrationDependancies()
	{
		add_action('plugins_loaded', function() {
			foreach ($this->integrationDependancies as $key => $value) 
			{
				if (class_exists($key)) {
					foreach ($value as $filePath) 
					{
						$this->loadFile($filePath);
					}
				}
			}
		});
	}

	public function loadFile($filePath)
	{
		require_once plugin_dir_path( __FILE__ ) . $filePath .'.php';
	}

	public function addSettingsLinkFromPluginsPage($links) 
	{
		$settings_links = array(
			'<a href="' . admin_url( 'tools.php?page=logtivity' ) . '">Settings</a>',
		);
		
		return array_merge($settings_links, $links);
	}
}

$logtivity = new Logtivity;