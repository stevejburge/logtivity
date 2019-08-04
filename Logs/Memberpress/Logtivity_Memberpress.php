<?php

class Logtivity_Memberpress extends Logtivity_Abstract_Logger
{
	public function __construct()
	{
		add_action('mepr-event-subscription-created', [$this, 'subscriptionCreated']);
		add_action('mepr-event-subscription-paused', [$this, 'subscriptionPaused']);
		add_action('mepr-event-subscription-resumed', [$this, 'subscriptionResumed']);
		add_action('mepr-event-subscription-stopped', [$this, 'subscriptionStopped']);
	}

	public function subscriptionCreated($event) 
	{
		$subscription = $event->get_data();
		$user = $subscription->user();
		$product = $subscription->product();
		$paymentMethod = $subscription->payment_method();

		return (new Logtivity_Logger($user->ID))
						->setAction('Memberpress. Subscription Created')
						->addMeta('Transaction Total', $subscription->total)
						->addMeta('Membership Product', $product->post_title)
						->addMeta('Payment Method', $paymentMethod->name)
						->send();
	}

	public function subscriptionPaused($event) 
	{
		$subscription = $event->get_data();
		$product = $subscription->product();

		return Logtivity_Logger::log()
						->setAction('Memberpress. Subscription Paused')
						->addMeta('Membership Product', $product->post_title)
						->send();
	}

	public function subscriptionResumed($event) 
	{
		$subscription = $event->get_data();
		$product = $subscription->product();
	
		return Logtivity_Logger::log()
						->setAction('Memberpress. Subscription Resumed')
						->addMeta('Membership Product', $product->post_title)
						->send();
	}

	public function subscriptionStopped($event) 
	{
		$subscription = $event->get_data();
		$product = $subscription->product();

		return Logtivity_Logger::log()
						->setAction('Memberpress. Subscription Stopped')
						->addMeta('Membership Product', $product->post_title)
						->send();
	}
}

$Logtivity_Memberpress = new Logtivity_Memberpress;