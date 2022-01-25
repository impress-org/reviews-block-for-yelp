const mix = require( 'laravel-mix' );
const wpPot = require( 'wp-pot' );
const { default: ImageminPlugin } = require( 'imagemin-webpack-plugin' );
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );
const path = require( 'path' );



mix
	.setPublicPath( 'assets/dist' )
	.sourceMaps( false )
	// Admin
	.js( 'assets/src/js/admin-main.js', 'assets/dist/js/admin-main.js' )
	.js( 'assets/src/js/admin-settings.js', 'assets/dist/js/admin-settings.js' )
	.js( 'assets/src/js/admin-widget.js', 'assets/dist/js/admin-widget.js' )
	// .js( 'assets/src/js/yelp-block.js', 'assets/dist/js/yelp-block.js' )
	.sass( 'assets/src/css/admin-main.scss', 'assets/dist/css/admin-main.css' )
	// Public
	// .js( 'assets/src/js/public-main.js', 'assets/dist/public-main.js' )
	.sass( 'assets/src/css/public-main.scss', 'assets/dist/css/public-main.css' )

	// Images
	.copy('assets/src/images', 'assets/dist/images')
	.options({
		processCssUrls: false
	});

mix.webpackConfig({
	externals: {
		$: 'jQuery',
		jquery: 'jQuery',
	},
	plugins: [
		new CleanWebpackPlugin(),
		new ImageminPlugin({
			test: /\.(jpe?g|png|gif|svg)$/i,
			disable: ! mix.inProduction()
		})
	],
	resolve: {
		alias: {
			'@ywp/js': path.resolve(__dirname, 'assets/src/js/'),
			'@ywp/blocks': path.resolve(__dirname, 'includes/blocks/'),
			'@ywp/components': path.resolve(__dirname, 'includes/blocks/components/'),
		},
	},
});


if ( mix.inProduction() ) {
	wpPot({
		package: 'Yelp Widget Pro',
		domain: 'yelp-widget-pro',
		destFile: 'languages/yelp-widget-pro.pot',
		relativeTo: './',
		bugReport: 'https://wordpress.org/support/plugin/yelp-widget-pro',
		team: 'WPBR Support <support@wpbusinessreviews.com>'
	});
}
