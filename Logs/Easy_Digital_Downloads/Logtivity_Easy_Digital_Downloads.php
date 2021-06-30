<?php

class Logtivity_Easy_Digital_Downloads extends Logtivity_Abstract_Logger
{
	public function __construct()
	{
		add_action('edd_post_add_to_cart',  [$this, 'itemAddedToCart'], 10, 3);
	}

	public function itemAddedToCart($download_id, $options, $items)
	{
		$log = Logtivity_Logger::log()
			->setAction('Download Added to Cart')
			->setContext(get_the_title($download_id));

		foreach ($items as $item) {
			$log->addMeta('Item', $item);
		}

		$log->send();
	}
}

$Logtivity_Easy_Digital_Downloads = new Logtivity_Easy_Digital_Downloads;
