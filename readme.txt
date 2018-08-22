=== Yelp Widget Pro ===
Contributors: wordimpress, dlocc, webdevmattcrom
Donate link: https://impress.org/
Tags: yelp, yelp widget, yelp shortcode, yelp api, yelp business listings, yelp reviews, yelp reviews widget, yelp business reviews, yelp widget pro, business reviews, google reviews, google places reviews, facebook reviews, facebook page reviews
Requires at least: 4.2
Tested up to: 4.9
Stable tag: 2.0.0

Yelp Widget Pro makes it easy for you to display your Yelp reviews and business information on your website via an easy-to-use and intuitive widget.

== Description ==

= Yelp Widget Pro =
Yelp Widget Pro allows you to easily display Yelp reviews and profiles for any business on your website easily configurable widget. Yelp Widget Pro users are able to display business names, ratings, review counts and profile images in any WordPress sidebar. Customize the widget to display one or many listings from Yelp based on location.

This widget supports for Yelp's Search and Business API methods. Yelp Widget Pro allows for multiple widgets within the same or separate sidebars. No coding knowledge is required.

Yelp Widget Pro is actively supported and developed. The open source version is available for free to the WordPress community. For additional options and priority support please consider [upgrading to WP Business Reviews](https://wpbusinessreviews.com). If you like this plugin please [rate it on WordPress.org](http://wordpress.org/support/view/plugin-reviews/yelp-widget-pro/).

= WP Business Reviews =

*WP Business Reviews* is a **significant upgrade** to *Yelp Widget Pro* that adds many features that will allow you to further customize how you display reviews on your WordPress website.

[Upgrade to WP Business Reviews](https://wpbusinessreviews.com "Upgrade to WP Business Reviews")

[youtube https://www.youtube.com/watch?v=3xNJX5cjdQ0]

== Installation ==

1. Upload the `yelp-widget-pro` folder and it's contents to the `/wp-content/plugins/` directory or install via the WP plugins panel in your WordPress admin dashboard
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it! You should now be able to access the Plugin's options via your settings panel.  You will need to enter your Yelp API information prior to using the plugin.

Note: If you have Wordpress 2.7 or above you can simply go to 'Plugins' &gt; 'Add New' in the WordPress admin and search for "Yelp Widget Pro" and install it from there.

== Frequently Asked Questions ==

= Why should I use this plugin? =

A business website can use this plugin to display their yelp business listing and reviews in their sidebar by using the Business ID search option. You can also use this plugin if you have a website that would benefit in displaying search results for a Yelp search term.  For example, a travel website selling reservations could display the top resorts for a given destination.

= How do I display only my business? =

If you would like to display only your certain business then you must enter in your "Yelp Business ID" in the widget's ID input.  The ID of the business is the last part of the Yelp URL of its Yelp page. Ex: `http://www.yelp.com/biz/togos-sandwiches-san-diego-3`, the id is `togos-sandwiches-san-diego-3`.  This is the *only* parameter you need to set to use this method.

= How do I get a Yelp API key? =

In order for the plugin to access your Yelp reviews, you must have a valid Yelp API key. To get the key please review [how to create a Yelp API key](https://wpbusinessreviews.com/documentation/platforms/yelp/).

= How do I disable the CSS =

If you would like to theme the widget yourself you should disable the plugin's CSS output.  To do that please visit the options page (Settings > Yelp Widget Pro) and check the appropriate option.

= The plugin looks funny in my sidebar, what's the deal? =

Some themes may have very small sidebars and CSS styles that conflict or alter the styles within Yelp Widget Pro.  To correct any styling errors you can either disable the plugin's CSS all together or override the CSS selectors in use to make the widget appear how you'd like.

== Screenshots ==

1. A view of the Yelp Widget Pro Settings page displaying the metabox to enter in your Yelp API key.

2. Yelp Widget Pro expanded displaying all available options.

3. How the widget looks in a website sidebar.

== Changelog ==

= 2.0.0 =
* We have recently launched a new premium plugin for reviews called [WP Business Reviews](https://wpbusinessreviews.com/?plugin=yelp-widget-pro). You can use this new WordPress review plugin to display your best reviews from platforms like Google, Yelp, and Facebook right on your website.
* New: You can now display up to 3 yelp reviews using the widget's Yelp API "Business" request method.
* New: Modernized the styles for a more clean display with improved cross-theme compatibility.
* Tweak: Modified upsells for new WP Business Reviews plugin.

= 1.5.0 =
* Update: Replace Yelp API v2 with Yelp API v3 (Fusion) for remote requests.
* Update: Add responsive star images so ratings display sharper on high-resolution displays.
* Update: Add responsive Yelp logo so logo dislpays sharper on high-resolution displays.
* Fix: Widget would get stuck in Business mode after toggling back to Search mode. Toggling now works as expected.

= 1.4.3 =
* Fix: Support for businesses with strange characters with accents and such like `café-poland-columbia`
* Tweak: No more API keys necessary to wor
* Tweak: Cleaned up enqueing of scripts and styles.
* Tweak: Updated text in activation banner to reflect free version of plugin better
* Minor typo fixes and text updates

= 1.4.2 =
* Added Activation Banner
* Updated Readme.txt
* Fully I18n (internationalize) ready.

= 1.4.1 =
* Update: Renamed "Yelp Widget Pro Premium" to just "Yelp Widget Premium" - It makes more sense and sounds better
* Update: CSS update for Yelp Settings page so metaboxes do not have toggle icons or move hovers (the metaboxes cannot be moved)
* Fix: PHP Warning for array_key_exists check when get_option returns false

= 1.4 =
* New: Added WordImpress logo image to options page
* Updated: Improved widget's UI for new WP 3.8 style
* Updated: Reformatted all code to WP Coding Standards
* Updated: Swapped Yelp icon URL for biz url rather than just yelp.com
* Updated: Removed old license metabox in place for new license activation explanation metabox
* Updated: Compatible with up to WP 3.8
* Updated: readme.txt file with additional information
* Fixed: Broken link to premium upgrade page
* Fixed: WP_DEBUG notices

= 1.3.8.2 =
* Removed unnecessary plugin updater class

= 1.3.8.1 =
* Fixed: Issue with unavailable automatic updates and new licensing server

= 1.3.8 =
* Updated: New Updates handeling for premium plugin purchasers
* Fixed: Very minor CSS issue in admin options UI issue with G+ displaying block

= 1.3.7 =
* Updated: Improved logic to communicate between Software API and plugin

= 1.3.6 =
* FIXED: Issue with Sorting feature not working properly
* NEW: Social media buttons in settings panel (like us if you get a chance!)
* UPDATED: Support links to forum updated

= 1.3.5.2 =
* UPDATED: Licensing logic for plugin
* Code cleanup and optimization

= 1.3.5.1 =
* UPDATED: Reverted license update method back to curl from wp_remote_get due to some issues with various hosts
* Minor UI Updates
* Code cleanup and improvements

= 1.3.5 =
* NEW: Added tooltips with information and links to screencast tutorials on the widgets and on widget settings pages
* NEW: Added default image for businesses without profile images
* NEW: Added Profile image size select; now you can easily modify the size of your Yelp profile image
* NEW: Added links to Options page, Rate the Plugin and Premium Upgrade on the Plugins page
* UPDATED: Changed default number of items in Search Method to 4 rather than 1
* UPDATED: Changed default cache value to 1 Day to encourage caching results
* UPDATED: Widget image output alt and title tags for better SEO optimization
* UPDATED: Readme.txt file with additional information and updated description
* UPDATED: Premium plugin update classes to latest version from GitHub
* FIXED: Fixed Premium licensing metabox inputs min-width issue
* Added link to Premium Version on widget
* Code cleanup and organization

= 1.3 =
* Release of plugin w/ licensing logic
* UI and code cleanup

= 1.2 =
* Added localization support for future translations.  If you are interested in helping with translation please contact me!
* Additional UI updates and tweaks
* Updated yelp_widget_curl() function to use WordPress' HTTP API first and backup as cURL
* Integrating premium licencing logic; GPL compatible plugin
* Fixed UI bug with widget API selection radio
* Updated Facebook Like box to new WordImpress (no-ed) page
* Grammatical fixes
* Improved widget frontend CSS

= 1.1 =
* Improved frontend widget display CSS with percentage-based element widths
* Added business address display option to widget functionality
* Cleaned up Widget UI: Added toggle option panels
* Cleaned up options panel UI: Added metaboxes to hold content; fixed typo in introduction; added like box for WordImpressed
* Improved how scripts are loaded in the WordPress admin panel by only loaded them on the pages needed
* Coming soon: Premium Add-ons! Themes, New Features and More.

= 1.0 =
* Initial plugin release - Special thanks to the Yelp It plugin for kickstarting this widget
