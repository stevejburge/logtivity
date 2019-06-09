<?php

/**
 *
 * Dump and Die variable or whatever
 *
 * @param $dump
 *
 */
function logtivity_dd($dump) {
	echo "<pre>";
	var_export($dump);
	echo "</pre>";
	die();
}

/**
 * Load a view and pass variables into it
 *
 * To ouput a view you would want to echo it
 * 
 * @param  string $fileName excluding file extension
 * @param  array  $vars
 * @return string
 */
function logtivity_view($fileName, $vars = array()) {

    foreach ($vars as $key => $value) {
        
        ${$key} = $value;

    }

    ob_start();

    include( dirname(__FILE__) . '/../views/' . $fileName . '.php');

    return ob_get_clean();

}

/**
 * Get Site API Key
 * 
 * @return string
 */
function logtivity_get_api_key() {

    $api_key = sanitize_text_field(get_option('logtivity_site_api_key'));

    if ($api_key == '') 
    {
        return null;
    }

    return $api_key;

}