<?php

class Logtivity_User extends Logtivity_Abstract_Logger
{
	public function registerHooks()
	{
		add_action('wp_login', [$this, 'userLoggedIn'], 10, 2);
		add_action('wp_logout', [$this, 'userLoggedOut']);
		add_action( 'user_register', [$this, 'userCreated'], 10, 1 );
		add_action( 'delete_user', [$this, 'userDeleted'] );
		add_action( 'profile_update', [$this, 'profileUpdated'], 10, 2 );
	}

	public function userLoggedIn( $user_login, $user ) 
	{
	    return Logtivity_Logger::log('User Logged In', null, $user->ID);
	}

	public function userLoggedOut()
	{
		$current_user = wp_get_current_user(); 

		return (new Logtivity_Logger($current_user->ID))
						->async(false)
						->setAction('User Logged Out')
						->send();
	}

	public function userCreated($user_id)
	{
		$user = new Logtivity_WP_User($user_id);

		return Logtivity_Logger::log('User Created. Role: ' . $user->getRole(), [
			'key' => 'Username',
			'value' => $user->userLogin()
		]);
	}

	public function userDeleted($user_id)
	{
		$user = new Logtivity_WP_User($user_id);

		return Logtivity_Logger::log('User Deleted. Role: ' . $user->getRole(), [
			'key' => 'Username',
			'value' => $user->userLogin()
		]);
	}

	public function profileUpdated($user_id, $old_user_data)
	{
		$user = new Logtivity_WP_User($user_id);

		return Logtivity_Logger::log('Profile Updated. Role: ' . $user->getRole(), [
			'key' => 'Username',
			'value' => $user->userLogin()
		]);
	}
}

$Logtivity_User = new Logtivity_User;