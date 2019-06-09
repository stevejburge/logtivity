<?php

class Logtivity_Core extends Logtivity_Abstract_Logger
{
	public function registerHooks()
	{
		add_action( 'upgrader_process_complete', [$this, 'upgradeProcessComplete'], 10, 2);
	}

	public function upgradeProcessComplete( $upgrader_object, $options ) 
	{
	    if ( $options['type'] != 'core' ) {
	    	return;
	    }

		if ($options['action'] == 'update') {
			
			return $this->coreUpdated($upgrader_object, $options);

		}

		if ($options['action'] == 'install') {

			return $this->coreInstalled($upgrader_object, $options);

		}
	}

	public function coreUpdated($upgrader_object, $options)
	{
		return Logtivity_Logger::log('WP Core Updated');
	}

	public function coreInstalled($upgrader_object, $options)
	{
		return Logtivity_Logger::log('WP Core Installed');
	}

}

$Logtivity_Core = new Logtivity_Core;