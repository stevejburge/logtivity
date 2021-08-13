<?php

abstract class Logtivity_Abstract_Easy_Digital_Downloads
{
	public function getDownloadTitle($licenseOrSubscriptionOrDownloadId, $price_id = null)
	{
		$priceOptionName = false;

		if ($licenseOrSubscriptionOrDownloadId instanceof EDD_Subscription) {
			$download_id = $licenseOrSubscriptionOrDownloadId->product_id;
			$price_id = $licenseOrSubscriptionOrDownloadId->price_id;
		} elseif($licenseOrSubscriptionOrDownloadId instanceof EDD_SL_License) {
			$download_id = $licenseOrSubscriptionOrDownloadId->download_id;
			$price_id = $licenseOrSubscriptionOrDownloadId->price_id;
		} else {
			$download_id = $licenseOrSubscriptionOrDownloadId;
		}
		
		$prices = edd_get_variable_prices($download_id);

		if ($prices && count($prices) && $price_id) {
			if (isset($prices[$price_id])) {
				$priceOptionName = $prices[$price_id]['name'];
			}
		}

		return get_the_title($download_id) . ( $priceOptionName ? ' - ' .$priceOptionName : '');
	}
}