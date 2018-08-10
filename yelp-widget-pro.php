<?php
/**
 * Plugin Name: Yelp Widget Pro
 * Plugin URI: http://wordpress.org/extend/plugins/yelp-widget-pro/
 * Description: Easily display Yelp business ratings with a simple and intuitive WordPress widget.
 * Version: 2.0.0
 * Author: WP Business Reviews
 * Author URI: http://wpbusinessreviews.com/
 * License: GPLv2
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( ! defined( 'YELP_PLUGIN_FILE' ) ) {
	define( 'YELP_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'YELP_PLUGIN_NAME_PLUGIN' ) ) {
	define( 'YELP_PLUGIN_NAME_PLUGIN', plugin_basename( YELP_PLUGIN_FILE ) );
}
if ( ! defined( 'YELP_WIDGET_PRO_PATH' ) ) {
	define( 'YELP_WIDGET_PRO_PATH', untrailingslashit( plugin_dir_path( YELP_PLUGIN_FILE ) ) );
}
if ( ! defined( 'YELP_WIDGET_PRO_URL' ) ) {
	define( 'YELP_WIDGET_PRO_URL', plugins_url( basename( plugin_dir_path( YELP_PLUGIN_FILE ) ), basename( YELP_PLUGIN_FILE ) ) );
}


/**
 * Localize the Plugin for Other Languages
 */
load_plugin_textdomain( 'yelp-widget-pro', false, dirname( plugin_basename( YELP_PLUGIN_FILE ) ) . '/languages/' );


/**
 * Delete options when uninstalled.
 */
function yelp_widget_uninstall() {
	delete_option( 'yelp_widget_settings' );
}

register_uninstall_hook( YELP_PLUGIN_FILE, 'yelp_widget_uninstall' );

/**
 * Adds Yelp Widget Pro Options Page
 */
if ( is_admin() && file_exists( YELP_WIDGET_PRO_PATH . '/includes/admin-settings.php' ) ) {
	require_once YELP_WIDGET_PRO_PATH . '/includes/admin-settings.php';
}

/**
 * Get the Widget
 */
if ( ! class_exists( 'Yelp_Widget' ) && file_exists( YELP_WIDGET_PRO_PATH . '/includes/class-yelp-widget.php' ) ) {
	require_once YELP_WIDGET_PRO_PATH . '/includes/class-yelp-widget.php';
}

if ( is_admin() && file_exists( YELP_WIDGET_PRO_PATH . '/includes/plugin-listing-page.php' ) ) {
	include YELP_WIDGET_PRO_PATH . '/includes/plugin-listing-page.php';
}


