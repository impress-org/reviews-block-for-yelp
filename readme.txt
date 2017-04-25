=== Yelp Widget Pro ===
Contributors: wordimpress, dlocc, webdevmattcrom
Donate link: http://wordimpress.com/
Tags: yelp, yelp widget, yelp shortcode, yelp api, yelp business listings, yelp reviews, yelp widget pro
Requires at least: 3.6
Tested up to: 4.2.2
Stable tag: 1.4.3

Yelp Widget Pro makes it easy for you to add business listings to your website or blog via an easy-to-use and intuitive widget.

== Description ==

= Yelp Widget Premium =
[Upgrade to Yelp Widget Premium](http://wordimpress.com/plugins/yelp-widget-pro/ "Upgrade to Yelp Widget Premium")

[youtube http://www.youtube.com/watch?v=47ARrKb7rL8]

[View the Online Demo](http://yelpwidgetpro.wordimpress.com/ "View the Online Demo of Yelp Widget Pro")

*Yelp Widget Premium* is a **significant upgrade** to *Yelp Widget Pro* that adds many features that will allow you to further customize your widgets with Google Maps, Yelp review snippets, additional graphics and display options plus so much more! Also included is priority support, auto updates, and well documented shortcode to display Yelp in any page or post.

= Yelp Widget Pro =
Yelp Widget Pro allows you to easily display Yelp profiles for any business on your website or blog using an intuitive and easily configurable widget. Yelp Widget Pro users are able to display business names, ratings, review counts and profile images in any WordPress sidebar. Customize the widget to display one or many listings from Yelp based on location.

This widget supports for Yelp v2.0's Search and Business API methods. Yelp Widget Pro allows for multiple widgets within the same or separate sidebars. No coding knowledge required.

Yelp Widget Pro is actively supported and developed. The open source version is available for free to the WordPress community. For additional options and priority support please consider [upgrading to Yelp Widget Premium](http://wordimpress.com/plugins/yelp-widget-pro/). If you like this plugin please [rate it on WordPress.org](http://wordpress.org/support/view/plugin-reviews/yelp-widget-pro/).

= Features =

1. Display Content by Yelp Business ID or Search Term.
2. Option to Cache Data to Save API Requests
3. Option to disable widget output title
4. Option to disable plugin CSS
5. Clean and easy-to-configure user interface
6. Actively developed and improved
7. Option to open links in new window
8. Option to no-follow links for all the SEOs

= Other Business Reviews Plugins =

Why limit your reviews to just Yelp Reviews? Check out our other free business reviews plugins to add to your site as well:

* [Google Places Review](https://wordpress.org/plugins/google-places-reviews "Google Places Reviews")
* [Yellow Pages Reviews](https://wordpress.org/plugins/yellow-pages-reviews/ "Yellow Pages Reviews")
* Get all three of our Premium Business Reviews plugins for one low price. [Premium Business Reviews Bundle](https://wordimpress.com/plugins/business-reviews-bundle/?utm_source=WordPress.org&utm_medium=readme&utm_campaign=Yelp%20Reviews%20Repo "Premium Business Reviews Bundle")

== Installation ==

1. Upload the `yelp-widget-pro` folder and it's contents to the `/wp-content/plugins/` directory or install via the WP plugins panel in your WordPress admin dashboard
2. Activate the plugin through the 'Plugins' menu in WordPress
3. That's it! You should now be able to access the Plugin's options via your settings panel.  You will need to enter your Yelp API information prior to using the plugin.

Note: If you have Wordpress 2.7 or above you can simply go to 'Plugins' &gt; 'Add New' in the WordPress admin and search for "Yelp Widget Pro" and install it from there.

== Frequently Asked Questions ==

= How do I upgrade to the Premium version? =

You can update to the premium version of Yelp Widget Pro by [clicking here](http://wordimpress.com/plugins/yelp-widget-pro/). Once you upgrade you will be emailed a license key which will allow you to enable auto updates.

= I've purchased the Premium version. How do I use it? =

First you should remove the free version of the plugin by deactivating and deleting the plugin. Don't worry, none of your data will be lost. Next, download the premium version zip file from your [WordImpress purchase history page](http://wordimpress.com/checkout/purchase-history/) or from the successful purchase email which includes your license key and download link. After that, goto the plugin settings and input your license key to activate.

= Why should I use this plugin? =

Use this plugin if you have a website that would benefit in displaying search results for a Yelp search term.  For example, a travel website selling reservations could display the top resorts for a given destination.  A business website could also display their yelp business listing in their sidebar by using the Business ID search option

= How do I display only my business? =

If you would like to display only your certain business then you must enter in your "Yelp Business ID" in the widget's ID input.  The ID of the business is the last part of the Yelp URL of its Yelp page. Ex: `http://www.yelp.com/biz/togos-sandwiches-san-diego-3`, the id is `togos-sandwiches-san-diego-3`.  This is the *only* parameter you need to set to use this method.

= How do I disable the CSS =

If you would like to theme the widget yourself you should disable the plugin's CSS output.  To do that please visit the options page (Settings > Yelp Widget Pro) and check the appropriate option.

= The plugin looks funny in my sidebar, what's the deal? =

Some themes may have very small sidebars and CSS styles that conflict or alter the styles within Yelp Widget Pro.  To correct any styling errors you can either disable the plugin's CSS all together or override the CSS selectors in use to make the widget appear how you'd like.  CSS-related issues are not actively supported as there's too many variations between the thousands of WordPress themes available.

== Screenshots ==

1. A view of the Yelp Widget Pro Settings page displaying the metabox to enter in your Yelp API v2.0 Information

2. Many plugins don't have the option to disable CSS - this one does.  If you want to style the plugin to suit your needs then enable this option.

3. Yelp Widget Pro expanded displaying all available options as of version 1.1

4. How the widget looks in a website sidebar

== Changelog ==

= 1.4.3 =
* Updated text in activation banner to reflect free version of plugin better
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
