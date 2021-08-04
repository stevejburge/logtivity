=== Logtivity: WordPress Activity Log With Charts and Notifications ===

Contributors: logtivity
Tags: logtivity, activity log, logging, event monitoring, download monitor, memberpress, user activity, easy digital downloads, edd
Requires at least: 4.7
Tested up to: 5.7
Stable tag: 1.6.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Logtivity logs the user activity on your site. You can use activity data for charts and notifications. Plus, you can easily search and export the data.

== Description ==

[Logtivity](https://logtivity.io) allow you to track the user activity on your site. Then you can turn that activity data into beautiful charts. You can also use the data to send notifications to your email or Slack. Plus, you can easily search and export the information.

If you have customers on your WordPress site, you’ll find Logtivity to be invaluable. Because Logtivity records all the important activity, you can see real customer journeys across your site. This can be incredibly helpful for customer support: you can see exactly what a user has done on your site.

To get started, simply install the Logtivity plugin and then connect your site to [Logtivity](https://logtivity.io). You’ll immediately start to see the results. You will see everything this user has done from installing plugins and updating posts, to changing blocks and plugin settings.

Yes, Logtivity will keep a record of the activity on your website. However, that’s just where the magic begins. You can then use your data in Logtivity and do three additional things:

1. Create beautiful charts
2. Send alert notifications
3. Export reports to CSV

Here’s an introduction to those three options, starting with charts:

= #1. Create beautiful charts from your activity log =

This image on the top of this page shows what's possible with Logtivity. First, I searched my site’s data for file downloads and then clicked the “Convert to Report” button. Logtivity did the rest. With a couple of mouse clicks, I’ve created a chart showing all the daily file downloads.

Logtivity customers can build a whole dashboard full of charts, so you can quickly see the number of logins, downloads, payments and anything else that's important to you.

[Click here to see more about activity log charts](https://logtivity.io/docs/reports/).

= #2. Send notifications for user activity =

Once your site is connected to Logtivity, you can set up unlimited alerts for any activity you want to know about as soon as it happens. You can send the alerts to your inbox or Slack channel.

One Logtivity customer employs various writers, so they’ve set up a series of Slack alerts that show when the writers log in. You can use this as a security alert, letting you know every time someone in the Administrator role logs in.

Another customer sends themselves an email every time a plugin or theme is updated. They only run updates on Monday, but realized that some plugins will run their own auto-updates. Logtivity allows them to be constantly aware of all site changes. A third customer sends themselves an alert every time there have been no plugin updates in the last week! They want to make sure they don’t forget to run updates.

eCommerce sites can use Logtivity for convenient notifications and set up alerts for all new, changed, and canceled subscriptions. Click here to see more about alerts.

[Click here to see more about activity log notifications](https://logtivity.io/docs/notifications/).

= #3. Export user activity logs to CSV =

Logtivity was started because one of our clients had reporting problems due to the amount of data collected on their site. The client was using a plugin that stored data in the WordPress database. Whenever we tried to export large amounts of logs, the site would show 502 errors. We needed to have the information stored separately. Being a dedicated service, Logtivity can optimize for things such as exporting 100,000 logs.

[Click here to see more about activity log exports](https://logtivity.io/docs/export-data/).

= What user activities does Logtivity record? =

Below is a list of all core WordPress actions that Logtivity records for your site. In addition to support for the WordPress core, Logtivity records events for many plugins and themes.

* WordPress Core Updated	
* WordPress Core Installed	
* Post Updated	
* Post Published	
* Post Trashed	
* Post Restored from Trash	
* Post Permanently Deleted	
* Attachment Uploaded	
* Attachment Meta Updated	
* Theme Switched	
* Theme Deleted	
* Theme Updated	
* Theme Installed	
* Theme File Edited	
* Theme Customizer Updated	
* User Logged In	
* User Logged Out	
* User Created
* User Deleted
* Profile Updated
* Plugin Activated 
* Plugin Deactivated
* Plugin Deleted
* Plugin Updated 
* Plugin Installed 
* Plugin File Edited

Logtivity provides a flexible API to log and store custom events. [Click here to see more about custom activity logs](https://logtivity.io/docs/custom-events/).

You can control what personal data is stored by Logtivity. Click here to see more about personal data in [Logtivity](https://logtivity.io/docs/personal-data/).

== Screenshots ==

1. Configurable Site Dashboard
2. Site Logs with powerful filtering
3. WordPress Settings Page
4. Set up unlimited alerts for any activity you want to know about as soon as it happens. Straight to your mailbox or slack channel.

== Installation ==
#### From your WordPress dashboard
Visit 'Plugins > Add New'
Search for 'logtivity’
Activate Logtivity from your Plugins page.

#### From WordPress.org
Download logtivity.
Upload the 'logtivity' directory to your '/wp-content/plugins/' directory, either through the UI (Plugins > Add new) or via SFTP or example.
Activate Logtivity from your Plugins page.

#### Once Activated
Visit 'Tools > Logtivity' to view the settings page.
Enter your Logtivity API key, configure your options and your good to go!

== Frequently Asked Questions ==

= Can I log custom events? =

Yes, the plugin provides a flexible API to log and store custom events with Logtivity. An example of logging a custom event is below.

`
Logtivity::log()
	->setAction('My Custom Action')
	->addMeta('Meta Label', $meta)
	->addMeta('Another Meta label', $someOtherMeta)
	->addUserMeta('Stripe Customer ID', $stripeCustomerId)
	->send();
`

= Can I disable user information being sent with the logs =

Yes. You can choose to only log a profile link, user ID, username, IP address, or nothing at all.

= Can I disable all default logging and only store custom logs. =

Yes! You can easily disable all default logging that this plugin provides so that you can only store the logs that matter to you manually.

You can also disable buit in logs on an individual basis via the filter example below:

`
add_action('wp_logtivity_instance', function($Logtivity_Logger) {

	if (strpos($Logtivity_Logger->action, 'Page was updated') !== false) {
		$Logtivity_Logger->stop();
	}

});
`

== Changelog ==

= 1.6.1 =

_Release Date – Wednesday 4th August 2021_

* Begin adding support for the Easy Digital Downloads Software Licensing Addon.
* Log License Created events.
* Log License Activated events.
* Log License Activation Failed events.
* Log License Deactivated events.
* Don't log new comments when they are marked as spam.

= 1.6.0 =
* Added initial Easy Digital Downloads core integration.
* Track when Core Settings are updated.
* Track when Permalinks are updated.
* Track when Memberpress Transactions are Created/Updated.
* Track when Memberpress Emails are sent.
* Track when a Memberpress User Profile is updated.
* Track when Memberpress Settings are Updated.
* Track WordPress comments CRUD.

= 1.5.0 =
* Renamed Download Monitor Action name to File Downloaded.
* Added Request URL as log meta.
* Added Request Type as log meta.

= 1.4.0 =
* Removed deprecated async method from Logtivity_Logger class.
* Added API key verification when updating Logtivity settings.

= 1.3.1 =
* Fix user info not always being picked up on User login action.
* Fix 0 being logged for username when not logged in.
* Fixed duplicate logs being recorded when Updating a post in Gutenberg.

= 1.3.0 =
* Added revision link to Post Updated logs.
* Added Role to Content parameter for User Logged In and User Logged Out.

= 1.2.0 =
* Added context parameter to API calls to separate out Actions from Titles.

= 1.1.0 =
* Add logging when updating menus.
* Add logging when updating widgets.
* Fix spelling mistake in postPermanentlyDeleted method.

= 1.0 =
* Fix php warning when tracking a logout event.
