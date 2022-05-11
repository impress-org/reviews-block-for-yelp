/**
 * External Dependencies
 */
const path = require( 'path' );

/**
 * WordPress Dependencies
 */
const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = {
    ...defaultConfig,
    entry: {
        ...defaultConfig.entry,
        "yelp-widget-admin": path.resolve( process.cwd(), 'assets/js', 'admin-main.js' ),
        "yelp-widget-admin-styles": path.resolve( process.cwd(), 'assets/css', 'admin-main.scss' ),
        "yelp-widget-public-styles": path.resolve( process.cwd(), 'assets/css', 'public-main.scss' ),
        "yelp-block": path.resolve( process.cwd(), 'src/block', 'index.js' ),
    },
}
