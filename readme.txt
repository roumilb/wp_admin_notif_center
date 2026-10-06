=== Hide admin notices - Admin Notification Center ===
Contributors: roumi
Tags: notification, notice, notices, notifications, admin
Requires at least: 5.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 4.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Keep your dashboard clean by grouping all the WordPress notices in a notification center.

== Description ==

Tired of having the top of your screen full of notifications coming from all the plugins you've installed?

Here is what you will be able to do:

* Group all your notifications in a notification center located at the right of your page.
* Use the button in the admin bar to display / hide this notification center.
* Be informed when a new notification is present thanks to a badge displayed in the admin bar.
* Select the notification types (success/info/warning/error) you want to move in the notification center, and the ones you want to keep at the top of your screen.
* Display or not the notifications depending on user roles.
* Hide notifications containing spam words.
* Keep notifications containing white listed words at their usual place.

== Installation ==

Install the plugin from the plugin installer in your WordPress admin, then go to Settings > Hide admin notices.

== Screenshots ==

1. The "Installed plugins" page without the plugin
2. The "Installed plugins" page with the plugin
3. The plugin settings

== Changelog ==

= 4.0.0 =
* Code cleanup: removal of the unused notice history feature and of its database table
* Settings can only be saved by administrators
* Roles created after the settings were saved now appear in the settings page
* Fix every notice being considered as spam when the spam words list ended with a comma
* Assets are now cached by the browser between page loads
* Requires PHP 7.4
* Tested up to WordPress 7.1

= 3.4.0 =
* Tested up to WordPress 6.9

= 3.3.0 =
* Move the settings page under the Settings menu
* Tested up to WordPress 6.7

= 3.2.2 =
* Fix spam word displaying notices

= 3.2.0 =
* Fix notification number showing 0

= 3.0.0 =
* Store notice displayed in the admin
* Listing of all notices stored
* Change admin menu icon
