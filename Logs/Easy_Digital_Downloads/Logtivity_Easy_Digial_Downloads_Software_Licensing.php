<?php

class Logtivity_Easy_Digial_Downloads_Software_Licensing
{
	protected $deactivatingLicenseArgs;

	public function __construct()
	{
		add_action('edd_sl_store_license', [$this, 'licenseCreated'], 10, 4);
		add_filter('edd_sl_post_activate_license_result', [$this, 'licenseActivated'], 10, 2);
		add_filter('edd_sl_pre_deactivate_license_args', [$this, 'deactivatingLicense']);
		add_action('edd_sl_deactivate_license', [$this, 'licenseDeactivated'], 10, 2);
		 
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
				$log->addMeta('Site', $args['url']);
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

		Logtivity_Logger::log()
			->setAction('License Deactivated')
			->setContext($this->deactivatingLicenseArgs['key'])
			->setUser($license->user_id ?? null)
			->addMeta('Site', $this->deactivatingLicenseArgs['url'] ?? $domain ?? null)
			->send();
	}
}

$Logtivity_Easy_Digial_Downloads_Software_Licensing = new Logtivity_Easy_Digial_Downloads_Software_Licensing;