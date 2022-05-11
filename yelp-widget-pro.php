<?php
/**
 * Plugin Name: Reviews Block for Yelp
 * Plugin URI: http://wordpress.org/extend/plugins/yelp-widget-pro/
 * Description: Easily display Yelp business reviews and ratings with a simple and intuitive WordPress block.
 * Version: 3.0.0
 * Author: WP Business Reviews
 * Author URI: https://wpbusinessreviews.com/
 * Requires PHP: 7.2
 * Requires WP: 4.7
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: yelp-widget-pro
 */

define( 'YELP_PLUGIN_VERSION', '3.0.0' );
define( 'YELP_PLUGIN_FILE', __FILE__ );
define( 'YELP_PLUGIN_NAME_PLUGIN', plugin_basename( YELP_PLUGIN_FILE ) );
define( 'YELP_WIDGET_PRO_PATH', untrailingslashit( plugin_dir_path( YELP_PLUGIN_FILE ) ) );
define( 'YELP_WIDGET_PRO_URL', plugins_url( basename( plugin_dir_path( YELP_PLUGIN_FILE ) ), basename( YELP_PLUGIN_FILE ) ) );
define( 'YELP_PLUGIN_SCRIPT_ASSET', require( YELP_WIDGET_PRO_PATH . '/build/yelp-block.asset.php' ) );


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
    ( ! class_exists( 'Yelp_Widget' ) && ! version_compare( $GLOBALS['wp_version'], '5.8', '>=' ) )
    || ( in_array( 'classic-widgets/classic-widgets.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )

) {
    require_once YELP_WIDGET_PRO_PATH . '/src/class-yelp-widget.php';
}

