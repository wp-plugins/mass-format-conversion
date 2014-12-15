=== Mass Format Conversion ===
Contributors: sillybean
Donate Link: http://sillybean.net/code/wordpress/mass-format-conversion-for-wordpress/
Tags: categories
Requires at least: 2.3
Tested up to: 4.1
Stable tag: 1.2.1


Applies all content filters to posts and comments and saves them back to the database. 

== Description ==

This plugin applies all content filters to posts and comments and saves them back to the database. This is useful if you have been using Textile or Markdown (for example) and you want to switch to plain HTML.

== Installation ==

Installation is pretty standard:

e.g.

1. Upload the files to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Notes ==

USE THIS PLUGIN WITH CAUTION. If the results are not what you want, the damage will be irreparable unless you take the following precautions.

You should back up your database first -- and be sure that you know how to restore from the backup!

If you are have plugins that allow you to use short codes -- e.g. [thing] -- you should deactivate them before running this conversion. Otherwise, that markup too will be converted to its HTML equivalent. (The built-in WordPress [gallery] tag should be fine.) 

Once your conversion is complete, you should deactivate this plugin and reactivate your short code plugins.

== Changelog ==
= 1.2.1 =
* Typo fix
= 1.2 = 
* get rid of deprecated notices
* localized strings
= 1.1 =
* Added user capability check (August 3, 2009)  
= 1.0 =  
* First release (February 1, 2009)