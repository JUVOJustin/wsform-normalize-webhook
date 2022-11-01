=== Plugin Name ===
Contributors: juvojustin
Donate link: https://juvo-design.de
Tags: wsform
Requires at least: 6.0
Tested up to: 6.0.3
Stable tag: 1.0.1
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

= 1.0 =
* A change since the previous version.
* Another change.

= 0.5 =
* List versions from most recent at top to oldest at bottom.

== Upgrade Notice ==

= 1.0 =
Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.

= 0.5 =
This version fixes a security related bug.  Upgrade immediately.

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.

== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`