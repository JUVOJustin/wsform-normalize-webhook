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

1. [Create a "Run WordPress Hook" action](https://wsform.com/knowledgebase/run-wordpress-hook/) for your form and select any hook tag you want e.g. `wsf_normalize_webhook`
2. Go to `/wp-admin/options-general.php?page=wsform-nomalize-webhook` of your WordPress instance
   3. Select the hook to want to set the url for
   4. Enter the url you want to send the form data to

**That´s it!**