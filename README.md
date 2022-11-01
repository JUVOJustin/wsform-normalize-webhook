
# WSForm Normalize Webhook
This plugin has the sole intention of providing a better webhook format than WSForm does by itself.

## Main benefits:
* Additional information for each field such as:
    * Label
    * Sectionname
    * Groupname
    * Fieldtype
* Grouping of repeatable sections to get information on a per-row basis

## How to set up
To use this plugin [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/pro/) needs to be installed. It provides the option page and input fields to map
hooks with the webhook url.

 - _**[Download latest plugin version](https://github.com/JUVOJustin/wsform-normalize-webhook/releases/latest/download/wsform-normalize-webhook.zip)**_
 - [Create a "Run WordPress Hook" action](https://wsform.com/knowledgebase/run-wordpress-hook/) for
   your form and select any hook tag you want e.g. `wsf_normalize_webhook`
 - Go to `/wp-admin/options-general.php?page=wsform-nomalize-webhook` of your WordPress instance
	 - Select the hook to want to set the url for
	 - Enter the url you want to send the form data to

**That´s it!**
