<?php

class Logtivity_Core extends Logtivity_Abstract_Logger
{
	public function registerHooks()
	{
		add_action( 'upgrader_process_complete', [$this, 'upgradeProcessComplete'], 10, 2);
		add_action( 'wp_update_nav_menu', [$this, 'menuUpdated'], 10, 2 );
		add_filter( 'widget_update_callback', [$this, 'widgetUpdated'], 10, 4);
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

	public function menuUpdated($nav_menu_id, $menuData = [])
	{
		if (isset($menuData['menu-name'])) {
			return Logtivity_Logger::log()
				->setAction('Menu Updated')
				->setContext($menuData['menu-name'])
				->addMeta('Menu ID', $nav_menu_id)
				->send();
		}
	}

	public function widgetUpdated($instance, $new, $old, $obj)
	{
		Logtivity_Logger::log()
			->setAction('Widget Updated')
			->setContext($obj->name)
			->addMeta('New Content', $new)
			->addMeta('Old Content', $old)
			->send();

		return $instance;
	}

}

$Logtivity_Core = new Logtivity_Core;