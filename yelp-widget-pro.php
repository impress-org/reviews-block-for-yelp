<?php
/**
 * Plugin Name: Yelp Block
 * Plugin URI: http://wordpress.org/extend/plugins/yelp-widget-pro/
 * Description: Easily display Yelp business reviews and ratings with a simple and intuitive WordPress block.
 * Version: 3.0.0
 * Author: WP Business Reviews
 * Author URI: https://wpbusinessreviews.com/
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

// Yelp Block
require_once YELP_WIDGET_PRO_PATH . '/src/block/serverside.php';


/**
 * Register the settings.
 *
 * @return void
 */
function yelp_block_plugin_settings() {
	register_setting(
		'yelp_widget_settings',
		'yelp_widget_settings',
		[
			'default'      => '',
			'show_in_rest' => [
				'schema' => [
					'type'       => 'object',
					'properties' => [
						'yelp_widget_fusion_api' => [
							'type' => 'string',
						],
					]
				],
			]
		]
	);
}

add_action( 'init', 'yelp_block_plugin_settings' );

/**
 * Migrate the old widget settings to the new block settings.
 */
function yelp_block_activate() {

	// Migrate old settings to new settings (non-serialized).
	$newSettings = get_option( 'yelp_block_api_key' );
	$oldSettings = get_option( 'yelp_widget_settings' );
	$migrated    = get_option( 'yelp_block_api_key_migrated' );

	if (
		( isset( $oldSettings['yelp_widget_fusion_api'] ) && ! empty( $oldSettings['yelp_widget_fusion_api'] ) )
		&& empty( $newSettings )
		&& ! $migrated
	) {
		update_option( 'yelp_block_api_key', $oldSettings['yelp_widget_fusion_api'] );
		add_option( 'yelp_block_api_key_migrated', true );
	}

}

//register_activation_hook( __FILE__, 'yelp_block_activate' );

/**
 * Delete options when uninstalled.
 */
function yelp_widget_uninstall() {
	delete_option( 'yelp_widget_settings' );
}

register_uninstall_hook( YELP_PLUGIN_FILE, 'yelp_widget_uninstall' );

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets, so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
 */
function create_yelp_block_init() {
	register_block_type( __DIR__, [
			'render_callback' => 'yelp_block_render_profile_block',
		]
	);
}

add_action( 'init', 'create_yelp_block_init' );


/**
 * Localize the Plugin for Other Languages
 */
load_plugin_textdomain( 'yelp-widget-pro', false, dirname( plugin_basename( YELP_PLUGIN_FILE ) ) . '/languages/' );


/**
 * Adds Yelp Widget Pro Options Page
 */
if ( is_admin() ) {
	require_once YELP_WIDGET_PRO_PATH . '/src/admin-settings.php';
	require_once YELP_WIDGET_PRO_PATH . '/src/plugin-listing-page.php';
}

/**
 * Get the widget if on WP 5.7.X or lower.
 */
if (
	! class_exists( 'Yelp_Widget' )
	&& ! version_compare( $GLOBALS['wp_version'], '5.8', '>=' )
) {
	require_once YELP_WIDGET_PRO_PATH . '/src/class-yelp-widget.php';
}

