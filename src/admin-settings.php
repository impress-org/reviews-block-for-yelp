<?php

/**
 * Yelp Options Page.
 */
function yelp_widget_add_options_page() {
	// Add the menu option under Settings, shows up as "Yelp API Settings" (second param)
	add_submenu_page(
			'options-general.php', // The parent page of this menu
			__( 'Reviews Block for Yelp Settings', 'yelp-widget-pro' ), // The Page Title
			__( 'Yelp Reviews', 'yelp-widget-pro' ), // The Menu Title
			'manage_options', // The capability required for access to this item
			'yelp_widget', // the slug to use for the page in the URL.
			'yelp_widget_options_form' // The function to call to render the page.
	);
}

add_action( 'admin_menu', 'yelp_widget_add_options_page' );

/**
 * Enqueue scripts.
 *
 * Add Yelp Widget Pro option scripts to admin head only to the appropriate pages.
 *
 * @param $hook
 */
function yelp_admin_scripts( $hook ) {

	if ( 'widgets.php' === $hook || 'settings_page_yelp_widget' === $hook ) {
		wp_register_script( 'yelp_widget_admin_scripts', YELP_WIDGET_PRO_URL . '/build/yelp-widget-admin.js' );
		wp_enqueue_script( 'yelp_widget_admin_scripts' );

		wp_register_style( 'yelp_widget_admin_css', YELP_WIDGET_PRO_URL . '/build/yelp-widget-admin-styles.css' );
		wp_enqueue_style( 'yelp_widget_admin_css' );
	}

}

add_action( 'admin_enqueue_scripts', 'yelp_admin_scripts', 10, 1 );

/**
 * Outputs the yelp_widget option setting value.
 *
 * @param $setting
 * @param $options
 *
 * @return mixed|string
 */
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
 * @param string|array $value Value to be sanitized.
 *
 * @return string|array Array of clean values or single clean value.
 * @since 1.5.0
 *
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
			<h2><?php _e( 'Reviews Block for Yelp Settings', 'yelp-widget-pro' ); ?> </h2>
			<a href="https://wpbusinessreviews.com/" class="wpbr-option-page-upsell"
			   title="Upgrade to WP Business Reviews"
			   target="_blank" rel="noopener noreferrer" class="update-link new-window">
				<svg class="wpbr-star-icon wpbr-banner-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
					<rect x="0" fill="none" width="20"
						  height="20"/>
					<g>
						<path d="M10 1l3 6 6 .75-4.12 4.62L16 19l-6-3-6 3 1.13-6.63L1 7.75 7 7z"/>
					</g>
				</svg><?php _e( 'Upgrade to WP Business Reviews', 'yelp-widget-pro' ); ?></a>
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
							<h3 class="hndle">
								<span><?php _e( 'Reviews Block for Yelp Introduction', 'yelp-widget-pro' ); ?></span>
							</h3>

							<div class="inside">
								<p>
									<strong><?php _e( 'Thanks for choosing this plugin to display your business reviews. To get started, follow the steps below:', 'yelp-widget-pro' ); ?></strong>
								</p>

								<ol class="yelp-widget-api-key-steps">
									<li><?php _e( 'First, <a href="https://www.yelp.com/developers/v3/manage_app" target="_blank" rel="noopener noreferrer">create your own Yelp app</a>. The app is required to access Yelp listings.', 'yelp-widget-pro' ); ?></li>
									<li><?php _e( 'Once you\'ve created the app, copy the API Key from the <a href="https://www.yelp.com/developers/v3/manage_app" target="_blank" rel="noopener noreferrer">My App</a> page. Save it in the Yelp API Key field below.', 'yelp-widget-pro' ); ?></li>
									<li><?php _e( 'Head over to your posts, pages, CPTs, or <a href="' . esc_url( admin_url( 'widgets.php' ) ) . '">widgets screen</a> to integrate your Yelp listings via the block!', 'yelp-widget-pro' ); ?></li>
								</ol>
							</div>
							<!-- /.inside -->
						</div>
						<!-- /#yelp-widget-intro -->

						<div class="postbox" id="yelp-widget-options">

							<h3 class="hndle"><span><?php esc_html_e('Yelp Block Settings', 'yelp-widget-pro'); ?></span></h3>

							<div class="inside">
								<div class="control-group">
									<div class="control-label yelp-widget-api-key-label">
										<label for="yelp_widget_fusion_api"><?php esc_html_e('Yelp API Key:', 'yelp-widget-pro'); ?><img
													src="<?php echo YELP_WIDGET_PRO_URL . '/assets/images/help.png'; ?>"
													title="<?php
													_e( 'This is necessary to get reviews from Yelp.', 'yelp-widget-pro' ); ?>"
													class="tooltip-info" width="16" height="16"/></label>
									</div>
									<div class="controls">
										<?php $ywpFusionAPI = empty( $options['yelp_widget_fusion_api'] ) ? '' : $options['yelp_widget_fusion_api']; ?>
										<input type="password"
											   id="yelp_widget_fusion_api"
											   name="yelp_widget_settings[yelp_widget_fusion_api]"
											   value="<?php esc_attr_e($ywpFusionAPI); ?>"
											   size="45"/>
										<p class="ywp-help-text">
											<a href="https://www.yelp.com/developers/v3/manage_app"
											   target="_blank"
											   rel="noopener noreferrer"><?php _e( 'Get a Yelp API Key by creating your own Yelp App.', 'yelp-widget-pro' ); ?></a> <?php _e( 'Don\'t worry, the process is quick and easy.', 'yelp-widget-pro' ); ?>
										</p>
									</div>
								</div>
							</div>
							<!-- /.inside -->
						</div>
						<!-- /#yelp-widget-options -->

						<div class="control-group">
							<div class="controls">
								<input class="button-primary" type="submit" name="submit-button"
									   value="<?php _e( 'Update', 'yelp-widget-pro' ); ?>"/>
							</div>
						</div>
					</div>
					<!-- /#main-sortables -->
				</div>
				<!-- /.postbox-container -->
				<div class="alignright" style="width:24%">
					<div id="sidebar-sortables" class="meta-box-sortables ui-sortable">

						<div id="wpbr-upsell" class="postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
							<h3 class="hndle"><span><?php _e( 'WP Business Reviews', 'yelp-widget-pro' ); ?></span></h3>

							<div class="inside">
								<p><?php _e( '<a href="https://wpbusinessreviews.com">WP Business Reviews</a> is a significant upgrade to the Yelp Block that adds features such as review mashups, the ability to add Yelp reviews manually, carousel formats, and additional review platforms such as Facebook, Google, and more!', 'yelp-widget-pro' ); ?></p>
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
