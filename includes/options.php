<?php
/**
 * Admin options page. Creates a page to set your OAuth settings for the Yelp API v2.
 */

register_activation_hook( __FILE__, 'yelp_widget_activate' );
register_uninstall_hook( __FILE__, 'yelp_widget_uninstall' );
add_action( 'admin_init', 'yelp_widget_init' );
add_action( 'admin_menu', 'yelp_widget_add_options_page' );


// Delete options when uninstalled
function yelp_widget_uninstall() {
	delete_option( 'yelp_widget_settings' );
	delete_option( 'yelp_widget_consumer_key' );
	delete_option( 'yelp_widget_consumer_secret' );
	delete_option( 'yelp_widget_token' );
	delete_option( 'yelp_widget_token_secret' );
}

// Run function when plugin is activated
function yelp_widget_activate() {

	$options = get_option( 'yelp_widget_settings' );

}

// Yelp Options Page
function yelp_widget_add_options_page() {
	// Add the menu option under Settings, shows up as "Yelp API Settings" (second param)
	$page = add_submenu_page(
		'options-general.php', // The parent page of this menu
		__( 'Yelp Widget Pro Settings', 'yelp-widget-pro' ), // The Page Title
		__( 'Yelp Reviews', 'yelp-widget-pro' ), // The Menu Title
		'manage_options', // The capability required for access to this item
		'yelp_widget', // the slug to use for the page in the URL
		'yelp_widget_options_form'
	); // The function to call to render the page

	/* Using registered $page handle to hook script load */
	add_action( 'admin_print_scripts-' . $page, 'yelp_options_scripts' );

}

/**
 * Add Yelp Widget Pro option scripts to admin head - will only be loaded on plugin options page
 */
function yelp_options_scripts() {

	// register admin JS
	wp_register_script( 'yelp_widget_options_js', plugins_url( 'assets/js/options.js', dirname( __FILE__ ) ) );
	wp_enqueue_script( 'yelp_widget_options_js' );

	// register our stylesheet
	wp_register_style( 'yelp_widget_options_css', plugins_url( 'includes/style/options.css', dirname( __FILE__ ) ) );
	// It will be called only on plugin admin page, enqueue our stylesheet here
	wp_enqueue_style( 'yelp_widget_options_css' );
}

/**
 * Load Widget JS Script ONLY on Widget page
 */
function yelp_widget_scripts( $hook ) {
	if ( $hook == 'widgets.php' ) {
		wp_register_script( 'yelp_widget_admin_scripts', plugins_url( 'assets/js/admin-widget.js', dirname( __FILE__ ) ) );
		wp_enqueue_script( 'yelp_widget_admin_scripts' );

		wp_register_style( 'yelp_widget_admin_css', plugins_url( 'includes/style/admin-widget.css', dirname( __FILE__ ) ) );
		wp_enqueue_style( 'yelp_widget_admin_css' );
	}
}

add_action( 'admin_enqueue_scripts', 'yelp_widget_scripts' );


/**
 * Initiate the Yelp Widget
 */
function yelp_widget_init( $file ) {
	// Register the yelp_widget settings as a group
	register_setting( 'yelp_widget_settings', 'yelp_widget_settings', array( 'sanitize_callback' => 'yelp_widget_clean' ) );

	// call register settings function
	add_action( 'admin_init', 'yelp_widget_options_css' );
	add_action( 'admin_init', 'yelp_widget_options_scripts' );

}



// Output the yelp_widget option setting value
function yelp_widget_option( $setting, $options ) {
	$value = '';
	// If the old setting is set, output that
	if ( get_option( $setting ) != '' ) {
		$value = get_option( $setting );
	} elseif ( is_array( $options ) ) {
		$value = $options[ $setting ];
	}

	return $value;

}

/**
 * Recursively sanitizes a given value.
 *
 * @since 1.5.0
 *
 * @param string|array $value Value to be sanitized.
 *
 * @return string|array Array of clean values or single clean value.
 */
function yelp_widget_clean( $value ) {
	if ( is_array( $value ) ) {
		return array_map( 'yelp_widget_clean', $value );
	} else {
		return is_scalar( $value ) ? sanitize_text_field( $value ) : '';
	}
}


/**
 * Admin Options.
 */
function yelp_widget_options_form() { ?>

	<div class="wrap" xmlns="http://www.w3.org/1999/html">

		<!-- Plugin Title -->
		<div id="ywp-title-wrap">
			<div id="icon-yelp" class=""></div>
			<h2><?php _e( 'Yelp Widget Pro Settings', 'yelp-widget-pro' ); ?> </h2>
			<label class="label basic-label">Basic Version</label>
			<a href="https://wpbusinessreviews.com/" title="Upgrade to Yelp Widget Premium"
			   target="_blank" rel="noopener noreferrer" class="update-link new-window">Upgrade to WP Business Reviews</a>
		</div>
		<form id="yelp-settings" method="post" action="options.php">

			<?php
			/**
			 * Tells WordPress that the options we registered are being handled by this form.
			 */
			settings_fields( 'yelp_widget_settings' );

			// Retrieve stored options, if any...
			$options = get_option( 'yelp_widget_settings' );
			?>

			<div class="metabox-holder">

				<div class="postbox-container" style="width:75%">


					<div id="main-sortables" class="meta-box-sortables ui-sortable">
						<div class="postbox" id="yelp-widget-intro">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle"><span><?php _e( 'Yelp Widget Pro Introduction', 'yelp-widget-pro' ); ?></span></h3>

							<div class="inside">
								<h3><?php _e( 'Thanks for choosing Yelp Widget Pro!', 'yelp-widget-pro' ); ?></h3>
								<p>
									<strong><?php _e( 'To get started, follow the steps below:', 'yelp-widget-pro' ); ?></strong>
								</p>

								<ol>
									<li><?php _e( 'First, <a href="https://www.yelp.com/developers/v3/manage_app" target="_blank" rel="noopener noreferrer">create your own Yelp app</a>. The app is required to access Yelp listings.', 'yelp-widget-pro' ); ?></li>
									<li><?php _e( 'Once you\'ve created the app, copy the API Key from the <a href="https://www.yelp.com/developers/v3/manage_app" target="_blank" rel="noopener noreferrer">My App</a> page. Save it in the Yelp API Key field below.', 'yelp-widget-pro' ); ?></li>
									<li><?php _e( 'Head over to your <a href="' . esc_url( admin_url( 'widgets.php' ) ) . '">Widgets screen</a> to integrate your Yelp listing now.', 'yelp-widget-pro' ); ?></li>
								</ol>
								<div class="social-items-wrap">

									<iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2Fpages%2FWordImpress%2F353658958080509&amp;send=false&amp;layout=button_count&amp;width=100&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=220596284639969"
									        scrolling="no" frameborder="0"
									        style="border:none; overflow:hidden; width:100px; height:21px;"
									        allowTransparency="true"></iframe>

									<a href="https://twitter.com/wordimpress" class="twitter-follow-button"
									   data-show-count="false">Follow @wordimpress</a>
									<script>
																			! function( d, s, id ) {
																				var js, fjs = d.getElementsByTagName( s )[ 0 ],
																					p = /^http:/.test( d.location ) ? 'http' : 'https';
																				if ( ! d.getElementById( id ) ) {
																					js = d.createElement( s );
																					js.id = id;
																					js.src = p + '://platform.twitter.com/widgets.js';
																					fjs.parentNode.insertBefore( js, fjs );
																				}
																			}( document, 'script', 'twitter-wjs' );
									</script>

								</div>
								<!--/.social-items-wrap -->

							</div>
							<!-- /.inside -->
						</div>
						<!-- /#yelp-widget-intro -->

						<div class="postbox" id="yelp-widget-options">

							<h3 class="hndle"><span>Yelp Widget Pro Settings</span></h3>

							<div class="inside">
								<div class="control-group">
									<div class="control-label">
										<label for="yelp_widget_fusion_api">Yelp API Key:<img src="<?php echo YELP_WIDGET_PRO_URL . '/assets/images/help.png'; ?>" title="<?php
											_e( 'This is necessary to get reviews from Yelp.', 'yelp-widget-pro' ); ?>" class="tooltip-info" width="16" height="16" /></label>
									</div>
									<div class="controls">
										<?php $ywpFusionAPI = empty( $options['yelp_widget_fusion_api'] ) ? '' : $options['yelp_widget_fusion_api']; ?>
										<p><input type="text" id="yelp_widget_fusion_api" name="yelp_widget_settings[yelp_widget_fusion_api]" value="<?php echo $ywpFusionAPI; ?>"
										          size="45" /><br />
											<small><a href="https://www.yelp.com/developers/v3/manage_app" target="_blank"
											          rel="noopener noreferrer"><?php _e( 'Get a Yelp API Key by creating your own Yelp App', 'yelp-widget-pro' ); ?></a></small>
										</p>
									</div>
								</div>
								<div class="control-group">
									<div class="control-label">
										<label for="yelp_widget_disable_css">Disable Plugin CSS Output:<img
													src="<?php echo YELP_WIDGET_PRO_URL . '/assets/images/help.png'; ?>"
													title="<?php _e( 'Disabling the widget\'s CSS output is useful for more complete control over customizing the widget styles. Helpful for integration into custom theme designs.', 'yelp-widget-pro' ); ?>"
													class="tooltip-info" width="16" height="16" /></label>
									</div>
									<div class="controls">
										<input type="checkbox" id="yelp_widget_disable_css"
										       name="yelp_widget_settings[yelp_widget_disable_css]" value="1"
											<?php
											$cssOption = empty( $options['yelp_widget_disable_css'] ) ? '' : $options['yelp_widget_disable_css'];
											checked( 1, $cssOption );
											?>
										/>
									</div>
								</div>
								<!--/.control-group -->

							</div>
							<!-- /.inside -->
						</div>
						<!-- /#yelp-widget-options -->

						<div class="control-group">
							<div class="controls">
								<input class="button-primary" type="submit" name="submit-button"
								       value="<?php _e( 'Update', 'yelp-widget-pro' ); ?>" />
							</div>
						</div>
					</div>
					<!-- /#main-sortables -->
				</div>
				<!-- /.postbox-container -->
				<div class="alignright" style="width:24%">
					<div id="sidebar-sortables" class="meta-box-sortables ui-sortable">

						<div id="yelp-widget-pro-premium" class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle"><span><?php _e( 'WP Business Reviews', 'yelp-widget-pro' ); ?></span></h3>

							<div class="inside">

								<p><?php _e( '<a href="https://wpbusinessreviews.com">WP Business Reviews</a> is a significant upgrade to Yelp Widget Pro that adds features such as review mashups, the ability to add Yelp reviews manually, carousel formats, and additional review platforms such as Facebook, Google, and more!', 'yelp-widget-pro' ); ?></p>

								<p><?php _e( 'Also included is Priority Support, updates, and well-documented shortcodes to display your Yelp reviews on any page or post.', 'yelp-widget-pro' ); ?></p>
							</div>
						</div>
						<!-- /.premium-metabox -->

					</div>
					<!-- /.sidebar-sortables -->

				</div>
				<!-- /.alignright -->
			</div>
			<!-- /.metabox-holder -->
		</form>


	</div><!-- /#wrap -->

	<?php
} //end yelp_widget_options_form
?>
