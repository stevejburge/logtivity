<?php

class Logtivity_Easy_Digital_Downloads_Software_Licensing
{
	protected $deactivatingLicenseArgs;

	public function __construct()
	{
		add_action('edd_sl_store_license', [$this, 'licenseCreated'], 10, 4);
		add_filter('edd_sl_post_activate_license_result', [$this, 'licenseActivated'], 10, 2);
		add_filter('edd_sl_pre_deactivate_license_args', [$this, 'deactivatingLicense']);
		add_action('edd_sl_deactivate_license', [$this, 'licenseDeactivated'], 10, 2);
		add_action('edd_sl_license_upgraded', [$this, 'licenseUpgraded'], 10, 2);
		add_action('edd_sl_post_set_status', [$this, 'licenseStatusUpdated'], 10, 2);
		add_action('edd_sl_license_unsubscribed', [$this, 'unsubscribedFromRenewalNotices'], 10, 1);
		add_action('edd_sl_post_license_renewal', [$this, 'licenseRenewed'], 10, 2);
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
			->addMeta('Customer ID', $license->customer_id)
			->send();
	}

	public function licenseActivated($result, $args)
	{
		$license = edd_software_licensing()->get_license($args['key'], true);

		$log = Logtivity_Logger::log()
			->setContext($args['key']);

		if ( false !== $license ) {
			$log->setUser($license->user_id ?? null);
			try {
				if ($license->customer_id) {
					$log->addMeta('Customer ID', $license->customer_id);
				}
			} catch (\Exception $e) {
			}
		}

		if (isset($args['url'])) {
			$log->addMeta('Site', $args['url']);
		}
		
		if ($result['success']) {
			$log->setAction('License Activated');

			if (isset($result['site_count'])) {
				$log->addMeta('Activation Count', $result['site_count']);
			}

			if (isset($result['license_limit'])) {
				$log->addMeta('Activation Limit', $result['license_limit']);
			}

			if (isset($result['expires']) && $result['expires']) {
				try {
					$log->addMeta('Expiration Date', date('M d Y', $result['expires']));
				} catch (\Exception $e) {
				}
			}

			$log->send();

			return $result;
		}
	
		$log->setAction('License Activation Failed')
			->addMeta('Reason', $result['error'] ?? '');
		
		$log->send();

		return $result;
	}

	public function deactivatingLicense($args)
	{
		$this->deactivatingLicenseArgs = $args;

		return $args;
	}

	public function licenseDeactivated($license_id, $download_id)
	{
		$license = edd_software_licensing()->get_license($this->deactivatingLicenseArgs['key'], true);

		if ( empty( $this->deactivatingLicenseArgs['url'] ) && ! edd_software_licensing()->force_increase() ) {

			// Attempt to grab the URL from the user agent if no URL is specified
			$domain = array_map( 'trim', explode( ';', $_SERVER['HTTP_USER_AGENT'] ) );

			if ( ! empty( $domain[1] ) ) {
				$this->deactivatingLicenseArgs['url'] = trim( $domain[1] );
			}
		}

		$log = Logtivity_Logger::log()
			->setAction('License Deactivated')
			->setContext($this->deactivatingLicenseArgs['key'])
			->setUser($license->user_id ?? null);

			try {
				if ($license->customer_id) {
					$log->addMeta('Customer ID', $license->customer_id);
				}
			} catch (\Exception $e) {
			}

		$log->addMeta('Site', $this->deactivatingLicenseArgs['url'] ?? $domain ?? null)
			->send();
	}

	public function licenseUpgraded($license_id, $args)
	{
		$license = edd_software_licensing()->get_license($license_id);

		$log = Logtivity_Logger::log()
			->setAction('License Upgraded')
			->setContext($license->key);

		if (isset($args['payment_id'])) {
			$log->addMeta('Payment ID', $args['payment_id']);
		}

		if (isset($args['old_payment_id'])) {
			$log->addMeta('Old Payment ID', $args['old_payment_id']);
		}

		if (isset($args['download_id'])) {
			$log->addMeta('Download ID', $args['download_id']);
		}

		if (isset($args['old_download_id'])) {
			$log->addMeta('Old Download ID', $args['old_download_id']);
		}

		if (isset($args['old_price_id']) && $args['old_price_id']) {
			$log->addMeta('Old Price ID', $args['old_price_id']);
		}

		if (isset($args['upgrade_id']) && $args['upgrade_id']) {
			$log->addMeta('Upgrade ID', $args['upgrade_id']);
		}

		if (isset($args['upgrade_price_id']) && $args['upgrade_price_id']) {
			$log->addMeta('Upgrade Price ID', $args['upgrade_price_id']);
		}

		$log->addMeta('Customer ID', $license->customer_id)
			->send();
	}

	public function licenseStatusUpdated($license_id, $status)
	{
		$license = edd_software_licensing()->get_license($license_id);

		Logtivity_Logger::log()
			->setAction('License Status Changed to ' . ucfirst($status))
			->setContext($license->key)
			->addMeta('Customer ID', $license->customer_id)
			->send();
	}

	public function unsubscribedFromRenewalNotices($license_id)
	{
		$license = edd_software_licensing()->get_license($license_id);

		Logtivity_Logger::log()
			->setAction('License Renewal Notification Unsubscribed')
			->setContext($license->key)
			->addMeta('Customer ID', $license->customer_id)
			->send();
	}

	public function licenseRenewed($license_id, $new_expiration)
	{
		$license = edd_software_licensing()->get_license($license_id);

		$log = Logtivity_Logger::log()
			->setAction('License Renewed')
			->setContext($license->key)
			->addMeta('Customer ID', $license->customer_id);

		try {
			$log->addMeta('New Expiration Date', date('M d Y', $new_expiration));
		} catch (\Exception $e) {
		}
		
		$log->send();
	}
}

$Logtivity_Easy_Digital_Downloads_Software_Licensing = new Logtivity_Easy_Digital_Downloads_Software_Licensing;