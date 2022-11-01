=== Plugin Name ===
Contributors: juvojustin
Donate link: https://juvo-design.de
Tags: wsform
Requires at least: 6.0
Tested up to: 6.0.3
Stable tag: 1.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin has the sole intention of providing a better webhook format than WSForm does by itself.

== Description ==

This plugin has the sole intention of providing a better webhook format than WSForm does by itself.

## Main benefits:
* Additional information for each field such as:
    * Label
    * Sectionname
    * Groupname
    * Fieldtype
* Grouping of repeatable sections to get information on a per-row basis

== Installation ==

To use this plugin [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/pro/) needs to be installed. It provides the option page and input fields to map
hooks with the webhook url.

1. _**[Download latest plugin version](https://github.com/JUVOJustin/wsform-normalize-webhook/releases/latest/download/wsform-normalize-webhook.zip)**_
2. [Create a "Run WordPress Hook" action](https://wsform.com/knowledgebase/run-wordpress-hook/) for your form and select any hook tag you want e.g. `wsf_normalize_webhook`
3. Go to `/wp-admin/options-general.php?page=wsform-nomalize-webhook` of your WordPress instance
   4. Select the hook to want to set the url for
   5. Enter the url you want to send the form data to

== Changelog ==

= 1.0.4 =
Remove duplicate "goup" and "section" data for fields in repeatable sections

= 1.0.3 =
Improve pipeline

= 1.0.2 =
Update plugin header

= 1.0.1 =
* Pipeline improves
* Autoupdater

= 1.0 =
* Initial Release