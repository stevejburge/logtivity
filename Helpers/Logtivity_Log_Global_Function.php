<?php

/**
 * Global function for easy use in themes/plugins for logging custom actions
 * 
 * @param  string $action
 * @param  string $meta
 * @param  string $user_id
 * @return Logtivity_Logger::send()
 */
function logtivity_log($action = null, $meta = null, $user_id = null)
{
	$Logtivity_logger = new Logtivity_Logger;

	if(func_num_args() == 0) {

		return new $Logtivity_logger;

	}

	return Logtivity_Logger::log($action, $meta, $user_id);
}