<?php

class Logtivity_Memberpress extends Logtivity_Abstract_Logger
{
	public function __construct()
	{
		add_action('mepr-event-member-signup-completed', [$this, 'freeSubscriptionCreated']);
		add_action('mepr-event-subscription-created', [$this, 'subscriptionCreated']);
		add_action('mepr-event-subscription-paused', [$this, 'subscriptionPaused']);
		add_action('mepr-event-subscription-resumed', [$this, 'subscriptionResumed']);
		add_action('mepr-event-subscription-stopped', [$this, 'subscriptionStopped']);
	}

	public function freeSubscriptionCreated($event)
	{
		$user = $event->get_data();
		$subscription = $this->getSubscription($user);

		if (!$subscription) {
			return;
		}

		if ($subscription->gateway != 'free') {
			return;			
		}
		
		$product = $subscription->product();

		return (new Logtivity_Logger($user->ID))
			->setAction('Free Subscription Created')
			->setContext($product->post_title)
			->send();
	}

	protected function getSubscription($user)
	{
		foreach ($user->subscriptions() as $subscription) {
			return $subscription;
		}

		return null;
	}	

	public function subscriptionCreated($event) 
	{
		$subscription = $event->get_data();
		$user = $subscription->user();
		$product = $subscription->product();
		$paymentMethod = $subscription->payment_method();

		return (new Logtivity_Logger($user->ID))
			->setAction('Subscription Created')
			->setContext($product->post_title)
			->addMeta('Transaction Total', $subscription->total)
			->addMeta('Payment Method', $paymentMethod->name)
			->send();
	}

	public function subscriptionPaused($event) 
	{
		$subscription = $event->get_data();
		$product = $subscription->product();

		return Logtivity_Logger::log()
			->setAction('Subscription Paused')
			->setContext($product->post_title)
			->send();
	}

	public function subscriptionResumed($event) 
	{
		$subscription = $event->get_data();
		$product = $subscription->product();
	
		return Logtivity_Logger::log()
			->setAction('Subscription Resumed')
			->setContext($product->post_title)
			->send();
	}

	public function subscriptionStopped($event) 
	{
		$subscription = $event->get_data();
		$product = $subscription->product();

		return Logtivity_Logger::log()
			->setAction('Subscription Stopped')
			->setContext($product->post_title)
			->send();
	}
}

$Logtivity_Memberpress = new Logtivity_Memberpress;