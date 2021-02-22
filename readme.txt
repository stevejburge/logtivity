=== WP Logtivity ===
Contributors: logtivity
Tags: logtivity, activity log, logging, event monitoring, download monitor, memberpress, user activity
Requires at least: 4.7
Tested up to: 5.6.1
Stable tag: 1.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Track activity/events on your website. Connect your site to Logtivity to send logs to the third party service for metrics and security purposes.

== Description ==

Logtivity is a Dedicated Event Monitoring Solution for WordPress. This plugin will capture events/actions on a WP site and send them to Logtivity for logging, reporting and checking for alerts. Some actions this plugin will track include installing/updating plugins, creating, updating and deleting users, page/post/custom post type CRUD actions and more.

== Frequently Asked Questions ==

= Can I log custom events? =

Yes, the plugin provides a flexible API to log and store custom events with Logtivity. An example of logging a custom event is below.

```
Logtivity_Logger::log()
	->setAction('My Custom Action')
	->addMeta('Meta Label', $meta)
	->addMeta('Another Meta label', $someOtherMeta)
	->addUserMeta('Stripe Customer ID', $stripeCustomerId);
	->send();
```

= Can I disable user information being sent with the logs =

Yes. You can choose to only log a profile link, user ID, username, IP address, or nothing at all.

= Can I disable all default logging and only store custom logs. =

Yes! You can easily disable all default logging that this plugin provides so that you can only store the logs that matter to you manually.

You can also disable buit in logs on an individual basis via the filter example below:

```
add_action('wp_logtivity_instance', function($Logtivity_Logger) {

	if (strpos($Logtivity_Logger->action, 'Page was updated') !== false) {
		$Logtivity_Logger->stop();
	}

});
```

== Changelog ==

= 1.0 =
* Fix php warning when tracking a logout event.
