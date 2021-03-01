<?php

class Logtivity_Admin
{
	protected $options;
	
	public function __construct()
	{
		add_action( 'admin_menu', [$this, 'registerOptionsPage'] );

		add_action( 'wp_ajax_logtivity_update_settings', [$this, 'update']);
		add_action( 'wp_ajax_nopriv_logtivity_update_settings', [$this, 'update']);

		$this->options = new Logtivity_Options;
	}

	/**
	 * Register the settings page
	 */
	public function registerOptionsPage() 
	{
		add_management_page( 'Logtivity', 'Logtivity', 'manage_options', 'logtivity', [$this, 'content'] );
	}

	/**	
	 * Show the admin settings template
	 * 
	 * @return void
	 */
	public function content() 
	{
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$options = $this->options->getOptions();

		echo logtivity_view('admin', compact('options'));
	}

	/**
	 * Update the settings
	 * 
	 * @return WP_Redirect
	 */
	public function update()
	{
		if (!wp_verify_nonce( $_POST['logtivity_update_settings'], 'logtivity_update_settings' )) 
		{
		    wp_safe_redirect( $this->settingsPageUrl() );
			exit;
			return;
		}

		$user = new Logtivity_Wp_User;

		if (!$user->hasRole('administrator')) {
		    wp_safe_redirect( $this->settingsPageUrl() );
			exit;
			return;
		}

		$this->options->update();
		
	    wp_safe_redirect( $this->settingsPageUrl() );
	    exit;
	}

	/**
	 * Get the url to the settings page
	 * 
	 * @return string
	 */
	public function settingsPageUrl()
	{
		return admin_url('tools.php?page=logtivity');
	}

}

$Logtivity_Admin = new Logtivity_Admin;

