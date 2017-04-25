<?php
/**
 * @DESC: Licensing Metabox
 */
global $options;
?>

<div id="yelp-widget-pro-premium" class="postbox">
	<div class="handlediv" title="Click to toggle"><br></div>
	<h3 class="hndle"><span><?php _e( 'Yelp Widget Premium', 'ywp' ); ?></span></h3>

	<div class="inside">
		<?php

		/**
		 *  Premium License Logic - No Obfuscation Here
		 *  Stealing isn't nice... please respect our work and purchase a license rather than hacking :)
		 */

		//License logic
		$licensing = new Plugin_Licensing();
		$options = get_option( 'yelp_widget_settings' );
		$response = $licensing->license_status( $options );


		//Current License Status
		$licenseStatus = $options["yelp_widget_premium_license_status"];

		//Activated
		$status = $response["activated"];
		$code = $response["code"]; ?>

		<form id="yelp-license" method="post" action="options.php">

			<?php //Display appropriate notifications to the user
			echo $licensing->license_response( $response );  ?>

			<div class="control-group">
				<p><?php _e( 'If you have purchased a license for Yelp Widget Premium you may enter it in below to enable premium features:', 'ywp' ); ?></p>

				<div class="control-label">
					<label for="yelp_widget_premium_email"><?php _e( 'License Email', 'ywp' ); ?>
						<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This is the address you purchased the license key with and received email confirmation.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
				</div>

				<div class="controls">
					<input type="text" id="yelp_widget_premium_email" name="yelp_widget_settings[yelp_widget_premium_email]" placeholder="your.email@email.com" value="<?php echo yelp_widget_option( 'yelp_widget_premium_email', $options ); ?>" />
					<!-- hidden license status field -->
					<input type="hidden" id="yelp_widget_premium_license_status" name="yelp_widget_settings[yelp_widget_premium_license_status]" value="<?php echo $licenseStatus; ?>" />
				</div>
			</div>
			<!--/.control-group -->
			<div class="control-group">
				<div class="control-label">
					<label for="yelp_widget_premium_license"><?php _e( 'License Key', 'ywp' ); ?>
						<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'The license key can be found in your confirmation email. If you lost your license you can <a href=\'http://wordimpress.com/lost-licence/\'>request it sent by email</a>.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
				</div>

				<div class="controls">
					<input type="text" id="yelp_widget_premium_license" name="yelp_widget_settings[yelp_widget_premium_license]" placeholder="VALID LICENSE KEY" value="<?php echo yelp_widget_option( 'yelp_widget_premium_license', $options ); ?>" />
				</div>

			</div>
			<!--/.control-group -->


			<div class="control-group">
				<div class="controls">
					<?php
					//Output appropriate Submit Button
					if ( $licenseStatus == 1 ) {
						?>

						<input class="button" id="deactivate" type="submit" name="submit-button" value="<?php _e( 'Deactivate', 'ywp' ); ?>" />

					<?php } else { ?>

						<input class="button" id="activate" type="submit" name="submit-button" value="<?php _e( 'Activate', 'ywp' ); ?>" />

					<?php } ?>

				</div>
			</div>


	</div>
	<!-- /.inside -->
</div><!-- /.yelp-widget-pro-support -->