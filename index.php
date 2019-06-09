<?php

/**
 * Plugin Name: WP Logtivity
 * Plugin URI:  https://local.nettbiglog.vm
 * Description: A logging integration with Logtivity.io
 * Version:     0.1
 * Author:      Ralph Morris
 * Author URI:  https://logtivity.io
 * License:     GPLv2+
 */

class Logtivity_Log_Plugin
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
		'Vendor/Background_Processing/Logtivity_Async_Request',
		'Jobs/Store_Log',
		'Services/Logtivity_Log_API',
		'Services/Logtivity_Log_API',
		'Services/Logtivity_Logger',
		'Helpers/Logtivity_Log_Global_Function',
		/**
		 *
		 *	Log classes
		 * 
		 */
		'Logs/Logtivity_Abstract_Logger',
		'Logs/Core/Logtivity_Post',
		'Logs/Core/Logtivity_User',
		'Logs/Core/Logtivity_Core',
		'Logs/Core/Logtivity_Theme',
		'Logs/Core/Logtivity_Plugin',
	];

	public function __construct()
	{
		$this->loadDependancies();

		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [$this, 'addSettingsLinkFromPluginsPage'] );
	}

	public function loadDependancies()
	{
		foreach ($this->dependancies as $filePath) 
		{
			require_once plugin_dir_path( __FILE__ ) . $filePath .'.php';
		}
	}

	public function addSettingsLinkFromPluginsPage($links) 
	{
		$settings_links = array(
			'<a href="' . admin_url( 'tools.php?page=logtivity' ) . '">Settings</a>',
		);
		
		return array_merge($settings_links, $links);
	}
}

$Logtivity_Log_Plugin = new Logtivity_Log_Plugin;
