<?php
/**
 * Plugin Name: Yelp Widget Pro
 * Plugin URI: http://wordpress.org/extend/plugins/yelp-widget-pro/
 * Description: Easily display Yelp business ratings with a simple and intuitive WordPress widget.
 * Version: 1.5.1
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

define( 'YELP_PLUGIN_NAME', 'yelp-widget-pro' );

if ( ! defined( 'YELP_PLUGIN_NAME_PLUGIN' ) ) {
	define( 'YELP_PLUGIN_NAME_PLUGIN', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'YELP_WIDGET_PRO_PATH' ) ) {
	define( 'YELP_WIDGET_PRO_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}
if ( ! defined( 'YELP_WIDGET_PRO_URL' ) ) {
	define( 'YELP_WIDGET_PRO_URL', plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) );
}

/**
 * Adds Yelp Widget Pro Options Page
 */
require_once dirname( __FILE__ ) . '/includes/options.php';


/**
 * Localize the Plugin for Other Languages
 */
load_plugin_textdomain( 'yelp-widget-pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/**
 * Adds Yelp Widget Pro Stylesheets
 */
function add_yelp_widget_css() {

	$cssOption = get_option( 'yelp_widget_settings' );

	if ( ! $cssOption || ! array_key_exists( 'yelp_widget_disable_css', $cssOption ) ) {

		wp_register_style( 'yelp-widget', YELP_WIDGET_PRO_URL . '/assets/style/yelp.css' );
		wp_enqueue_style( 'yelp-widget' );

	}

}

add_action( 'wp_print_styles', 'add_yelp_widget_css' );

/**
 * Get the Widget
 */
if ( ! class_exists( 'Yelp_Widget' ) && file_exists( YELP_WIDGET_PRO_PATH . '/includes/class-yelp-widget.php' ) ) {
	require_once YELP_WIDGET_PRO_PATH . '/includes/class-yelp-widget.php';
}

if ( is_admin() && file_exists( YELP_WIDGET_PRO_PATH . '/includes/admin/admin.php' ) ) {
	include YELP_WIDGET_PRO_PATH . '/includes/admin/admin.php';
}
