=== Image Credits nofollow ===
Contributors: apasionados, netconsulting
Donate link: http://apasionados.es/
Tags: image, media, credit, credits, image credits, image credit, licence, licences, license, licenses, Author credits, Image credits, Photo credits
Requires at least: 3.0.1
Tested up to: 4.0.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds credits to the media uploads: Source and source URL. URLs are nofollow by default.

== Description ==

This plugin adds credits to the media uploads: Source and source URL. URLs are nofollow by default, but you have the option to follow them. With a shortcode and various options to display image credits in the posts.

The three display options of the credits are:
- Shortcode: [image-credit] with optional attributes are `sep`, `before` and `after`.
- Template Tag: `the_image_credits()` with optional parameters are `sep`, `before` and `after`.
- Display the credits automatically before or after the content.

There is a setting page in SETTINGS / IMAGE CREDITS that allows to setup the automatic credit display, including the HTML definitions.

This plugin is a fork of the [Better Image Credits plugin](https://wordpress.org/plugins/better-image-credits/) by [Claude Vedovini](https://profiles.wordpress.org/cvedovini/), which is a fork of the [Image Credits plugin](http://wordpress.org/plugins/image-credits/)
by [Adam Capriola](http://profiles.wordpress.org/adamcapriola/). This plugin is 100% compatible with both of them, so if you are already using the Image Credit plugin or the Better Image Credit plugin, just replace them
with this one and it will work about the same way, including the nofollow of the Source URL of the images.

The main difference with the "Image Credits" plugin is that you can choose that the plugin automatically adds the credits before or at the end of a post.
The main difeerence with the "Better Image Credits" plugin is that the Source URL links are NOFOLLOW by default and you can choose to make it DOFOLLOW if you want. We also removed the OVERLAY display option, which included additional Javascript and we think is not usefull because of the compatibility problems with the different themes.

= What can I do with this plugin? =
This plugin adds the Source Name, Source URL and the option to follow the Source URL link to the media uploaded in WordPress.

= How do I configure the plugin? =
Please navigate to SETTINGS / IMAGE CREDITS and choose options or fill in the data. Everything is self-explaining, incluiding help texts.

= IMAGE CREDITS NOFOLLOW in your Language! =
This first release is avaliable in English and Spanish. In the languages folder we have included the necessary files to translate this plugin.

If you would like the plugin in your language and you're good at translating, please drop us a line at [Contact us](http://apasionados.es/contacto/index.php?desde=wordpress-org-imgecreditsnofollow-home).

= Further Reading =
You can access the description of the plugin in Spanish at: [Image Credits Nofollow en castellano](http://apasionados.es/blog/).


== Installation ==

1. Upload the `image-credits-nofollow` folder to the `/wp-content/plugins/` directory (or to the directory where your WordPress plugins are located)
1. Activate the IMAGE CREDITS NOFOLLOW plugin through the 'Plugins' menu in WordPress.
1. Plugin doesn't need any configuration.

Please use with WordPress MultiSite at your own risk, as it has not been tested.


== Frequently Asked Questions ==

= What is IMAGE CREDITS NOFOLLOW good for? =
This plugin exports all entries of SeoCrawlytics and gives you the option to delete the entries that are older than 1 month.

= Does IMAGE CREDITS NOFOLLOW make changes to the database? =
The plugin doesn't make any changes to the database, but it adds new META-DATA for the MEDIA you edit. Every time you add a Source Name, Source URL and the option to follow the Source URL, this is added as metadata to the database.

= How can I check out if the plugin works for me? =
Install and activate. Go to the MEDIA library and edit any image. There should be new fields to introduce: Source Name, Source URL and the option to follow the Source URL link.

= How can I remove IMAGE CREDITS NOFOLLOW? =
You can simply activate, deactivate or delete it in your plugin management section.

= Are there any known incompatibilities? =
Please use it with WordPress MultiSite at your own risk, as it has not been tested.

= Do you make use of IMAGE CREDITS NOFOLLOW yourself? = 
Of course we do. ;-)

== Screenshots ==

1. Image Credits Nofollow example of nofollow credit image
2. Image Credits Nofollow example of dofollow credit image
3. Image Credits Nofollow configuration

== Changelog ==

= 1.0 =
* First stable release.

= 0.5 =
* Beta release.

== Upgrade Notice ==

= 1.0 =
First stable release.

== Contact ==

For further information please send us an [email](http://apasionados.es/contacto/index.php?desde=wordpress-org-imgecreditsnofollow-contact).
