<?php

class Logtivity_Easy_Digial_Downloads_Software_Licensing
{
	public function __construct()
	{
		add_action( 'edd_sl_store_license', [$this, 'licenseCreated'], 10, 4);
		add_filter( 'edd_sl_post_activate_license_result', [$this, 'licenseActivated'], 10, 2);
	}

	public function licenseCreated($license_id, $purchased_download_id, $payment_id, $type)
	{
		$license = edd_software_licensing()->get_license($license_id);

		Logtivity_Logger::log()
			->setAction('License Created')
			->setContext($license->key)
			->setUser($license->user_id ?? null)
			->addMeta('Payment ID', $payment_id)
			->addMeta('Type', $type)
			->send();
	}

	public function licenseActivated($result, $args)
	{
		$license = edd_software_licensing()->get_license($args['key'], true);

		$log = Logtivity_Logger::log()
			->setContext($args['key']);

		if ( false !== $license ) {
			$log->setUser($license->user_id ?? null);
		}

		if ($result['success']) {
			$log->setAction('License Activated');

			if (isset($args['url'])) {
				$log->addMeta('URL', $args['url']);
			}

			if (isset($result['site_count'])) {
				$log->addMeta('Activation Count', $result['site_count']);
			}

			if (isset($result['license_limit'])) {
				$log->addMeta('Activation Limit', $result['license_limit']);
			}

			if (isset($result['expires'])) {
				$log->addMeta('Expiration Date', $result['expires']);
			}

			$log->send();

			return $result;
		}
	
		$log->setAction('License Activation Failed')
			->addMeta('Reason', $result['error'] ?? '' )
			->send();

		return $result;
	}
}

$Logtivity_Easy_Digial_Downloads_Software_Licensing = new Logtivity_Easy_Digial_Downloads_Software_Licensing;