<?php

class Logtivity_Memberpress extends Logtivity_Abstract_Logger
{
	public function __construct()
	{
		add_action('mepr-txn-status-complete', [$this, 'transactionCompleted']);
	}

	public function transactionCompleted($txn) 
	{
		return Logtivity_Logger::log()
					->setAction('Memberpress Transation Complete.')
					->send();

		$user = new Logtivity_Wp_User;

		// if (!$user->isLoggedIn()) {
		// 	return;
		// }

		error_log('Logtivity User');
		error_log(print_r($user, true));

		//It's possible this could be a recurring transaction for a product the user is already subscribed to so probably use a user meta field described below
		$memberpressUser = new MeprUser($txn->user_id); //A MeprUser object
		$membership = new MeprProduct($txn->product_id); //A MeprProduct object
		$users_memberships = $user->active_product_subscriptions('ids'); //An array of membership CPT ID's
		
		error_log('Memberpress User');
		error_log(print_r($memberpressUser, true));
		error_log('Memberpress Membership');
		error_log(print_r($membership, true));
		error_log('Users Memberships');
		error_log(print_r($users_memberships, true));

		// return Logtivity_Logger::log()
		// 			->setAction('Memberpress Transation Complete. [' . $download->get_title() . ']')
		// 			// ->addMeta('Download Title', $download->get_title())
		// 			// ->addMeta('Download ID', $download->get_id())
		// 			// ->addMeta('Download Count', $download->get_download_count())
		// 			->send();
	}
}

$Logtivity_Memberpress = new Logtivity_Memberpress;