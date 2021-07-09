<?php

class Logtivity_Easy_Digital_Downloads extends Logtivity_Abstract_Logger
{
	public function __construct()
	{
		add_action('edd_post_add_to_cart',  [$this, 'itemAddedToCart'], 10, 3);
		add_action('edd_post_remove_from_cart',  [$this, 'itemRemovedFromCart'], 10, 3);
		add_action('edd_complete_purchase', [$this, 'purchasedCompleted'], 10, 3);
		add_action('edd_update_payment_status', [$this, 'paymentStatusUpdated'], 10, 3);
		add_action('edd_post_refund_payment', [$this, 'paymentRefunded'], 10, 1);
	}

	public function itemAddedToCart($download_id, $options, $items)
	{
		$log = Logtivity_Logger::log()
			->setAction('Download Added to Cart')
			->setContext(get_the_title($download_id));

		$prices = edd_get_variable_prices($download_id);

		foreach ($items as $item) {
			if (isset($prices[$item['options']['price_id']])) {
				$log->addMeta('Variable Item', $prices[$item['options']['price_id']]['name']);
			}

			if ($item['quantity']) {
				$log->addMeta('Quantity', $item['quantity']);
			}
		}

		$log->send();
	}

	public function itemRemovedFromCart($key, $item_id)
	{
		$log = Logtivity_Logger::log()
			->setAction('Download Removed from Cart')
			->setContext(get_the_title($item_id));

		$prices = edd_get_variable_prices($item_id);

		if ($prices && count($prices)) {
			if (isset($prices[$key]['name'])) {
				$log->addMeta('Variable Item', $prices[$key]['name']);
			}
		}

		$log->send();
	}

	public function purchasedCompleted($payment_id, $payment, $customer)
	{
		$log = Logtivity_Logger::log()
			->setAction('Purchase Complete')
			->setContext($this->getPaymentKey($payment));

		foreach ($payment->cart_details as $item) {
			$log->addMeta('Cart Item', $item['name']);
		}

		$log->addMeta('Total', $payment->total);
		$log->addMeta('Currency', $payment->currency);
		$log->addMeta('Gateway', $payment->gateway);

		$log->addMeta('Customer ID', $customer->id);
		$log->addMeta('Customer Email', $customer->email);

		$log->send();
	}

	public function paymentStatusUpdated($payment_id, $status, $old_status)
	{
		if ($status == 'failed') {
			return $this->paymentFailed($payment_id);
		}

		if ($status == 'refunded') {
			return;
		}
	}

	public function paymentFailed($payment_id)
	{
		$payment = new EDD_Payment($payment_id);

		Logtivity_Logger::log()
			->setAction('Payment Failed')
			->setContext($this->getPaymentKey($payment))
			->send();
	}

	public function paymentRefunded($EDD_Payment)
	{
		Logtivity_Logger::log()
			->setAction('Payment Refunded')
			->setContext($this->getPaymentKey($EDD_Payment))
			->addMeta('Amount', $EDD_Payment->get_meta('_edd_payment_total'))
			->send();
	}

	public function getPaymentKey($payment)
	{
		$meta = $payment->get_meta();

		if (isset($meta['key'])) {
			return $meta['key'];
		}
	}
}

$Logtivity_Easy_Digital_Downloads = new Logtivity_Easy_Digital_Downloads;

