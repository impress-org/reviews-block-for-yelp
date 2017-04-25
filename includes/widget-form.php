<?php
/*
 *  @description: Widget form options in WP-Admin
 *  @since 1.2.0
 *  @created: 04/10/13
 */
?>

<!-- Title -->
<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Widget Title', 'ywp' ); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
</p>

<!-- Listing Options -->
<p class="widget-api-option">
	<label for="<?php echo $this->get_field_id( 'display_option' ); ?>"><?php _e( 'Yelp API Request Method:', 'ywp' ); ?></label><br />
    <span class="yelp-method-span search-api-option-wrap">
    <input type="radio" name="<?php echo $this->get_field_name( 'display_option' ); ?>" class="<?php echo $this->get_field_id( 'display_option' ); ?> search-api-option" value="0" <?php checked( '0', $displayOption ); ?>><span class="yelp-method-label"><?php _e( 'Search Method', 'ywp' ); ?></span><img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Yelp\'s Search API allows you to display results of a specific search term. For more information <a href=\'http://wordimpress.com/docs/yelp-widget-pro/#search-method\' target=\'_blank\'>Click Here</a>', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /><br />
    </span>
    <span class="yelp-method-span business-api-option-wrap">
    <input type="radio" name="<?php echo $this->get_field_name( 'display_option' ); ?>" class="<?php echo $this->get_field_id( 'display_option' ); ?> business-api-option" value="1" <?php checked( '1', $displayOption ); ?>><span class="yelp-method-label"><?php _e( 'Business Method', 'ywp' ); ?></span><img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Yelp’s Business API allows business owners to display their Yelp reviews (up to 3), address, Google Map location, and more (premium version). For more information <a href=\'http://wordimpress.com/docs/yelp-widget-pro/#business-method\' target=\'_blank\'>Click Here</a>', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" />
    </span>
</p>


<div class="toggle-api-option-1 toggle-item <?php if ( $displayOption == "0" ) {
	echo 'toggled';
} ?>">
	<!-- Search Term -->
	<p>
		<label for="<?php echo $this->get_field_id( 'term' ); ?>"><?php _e( 'Search Term:', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'The term you would like to display results for, ie: \'Bars\', \'Daycare\', \'Restaurants\'.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'term' ); ?>" name="<?php echo $this->get_field_name( 'term' ); ?>" type="text" value="<?php echo $term; ?>" />
	</p>


	<!-- Location -->
	<p>
		<label for="<?php echo $this->get_field_id( 'location' ); ?>"><?php _e( 'Location:', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'The city name you would like to to search, ie \'San Diego\', \'New York\', \'Miami\'.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'location' ); ?>" name="<?php echo $this->get_field_name( 'location' ); ?>" type="text" value="<?php echo $location; ?>" />
	</p>

	<!-- Limit -->
	<p>
		<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Number of Items:', 'ywp' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'limit' ); ?>" id="<?php echo $this->get_field_id( 'limit' ); ?>" class="widefat">
			<?php
			$options = array( '1', '2', '3', '4', '5', '6', '7', '8', '9', '10' );
			foreach ( $options as $option ) {
				?>

				<option value="<?php echo $option; ?>" id="<?php echo $option; ?>" <?php if ( $limit == $option || empty( $limit ) && $option == '4' ) {
					echo 'selected="selected"';
				} ?>><?php echo $option; ?></option>

			<?php } ?>
		</select>
	</p>

	<!-- Sort -->
	<p>
		<label for="<?php echo $this->get_field_id( 'sort' ); ?>"><?php _e( 'Sorting:', 'ywp' ); ?></label>

		<select name="<?php echo $this->get_field_name( 'sort' ); ?>" id="<?php echo $this->get_field_id( 'sort' ); ?>" class="widefat">
			<?php
			$options = array( __( 'Best Match', 'ywp' ), __( 'Distance', 'ywp' ), __( 'Highest Rated', 'ywp' ) );
			//Counter for Option Values
			$counter = 0;

			foreach ( $options as $option ) {
				echo '<option value="' . $counter . '" id="' . $option . '"', $sort == $counter ? ' selected="selected"' : '', '>', $option, '</option>';
				$counter ++;
			}
			?>
		</select>
	</p>

</div><!-- /.toggle-api-option-1 -->


<div class="toggle-api-option-2 toggle-item  <?php if ( $displayOption == "1" ) {
	echo 'toggled';
} ?>">
	<!-- Business ID -->
	<p>
		<label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php _e( 'Business ID:', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'The Business ID is the portion of the Yelp url after the \'http://www.yelp.com/biz/\' portion. For example, the following business\'s URL on Yelp is \'http://www.yelp.com/biz/the-barbeque-pit-seattle-2\' and the Business ID is \'the-barbeque-pit-seattle-2\'. For more information <a href=\'http://wordimpress.com/docs/yelp-widget-pro/#search-method\' target=\'_blank\'>Click Here</a>', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>" type="text" value="<?php echo $id; ?>" />
	</p>
</div>


<h4 class="yelp-toggler"><?php _e( 'Display Options:', 'ywp' ); ?><span></span></h4>

<div class="display-options toggle-item">

	<!-- Profile Image Size -->
	<p>
		<label for="<?php echo $this->get_field_id( 'profile_img_size' ); ?>"><?php _e( 'Profile Image Size:', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Customize the width and height of the business Yelp profile image.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<select name="<?php echo $this->get_field_name( 'profile_img_size' ); ?>" id="<?php echo $this->get_field_id( 'profile_img_size' ); ?>" class="widefat">
			<?php
			$options = array( '40x40', '60x60', '80x80', '100x100' );
			foreach ( $options as $option ) {
				?>

				<option value="<?php echo $option; ?>" id="<?php echo $option; ?>" <?php if ( $profileImgSize == $option || empty( $profileImgSize ) && $option == '60x60' ) {
					echo 'selected="selected"';
				} ?>><?php echo $option; ?></option>

			<?php } ?>
		</select>
	</p>

	<!-- Disable title output checkbox -->
	<p>
		<input id="<?php echo $this->get_field_id( 'display_address' ); ?>" name="<?php echo $this->get_field_name( 'display_address' ); ?>" type="checkbox" value="1" <?php checked( '1', $address ); ?>/>
		<label for="<?php echo $this->get_field_id( 'display_address' ); ?>"><?php _e( 'Display Business Address', 'ywp' ); ?></label>
	</p>
	<!-- Display address -->

	<p>
		<input id="<?php echo $this->get_field_id( 'display_phone' ); ?>" name="<?php echo $this->get_field_name( 'display_phone' ); ?>" type="checkbox" value="1" <?php checked( '1', $phone ); ?>/>
		<label for="<?php echo $this->get_field_id( 'display_phone' ); ?>"><?php _e( 'Display Business Phone Number', 'ywp' ); ?></label>
	</p>
	<!-- Display phone -->

</div>


<h4 class="yelp-toggler"><?php _e('Advanced Options', 'ywp'); ?>: <span></span></h4>

<div class="advanced-options toggle-item">

	<!-- Disable title output checkbox -->
	<p>
		<input id="<?php echo $this->get_field_id( 'disable_title_output' ); ?>" name="<?php echo $this->get_field_name( 'disable_title_output' ); ?>" type="checkbox" value="1" <?php checked( '1', $titleOutput ); ?>/>
		<label for="<?php echo $this->get_field_id( 'disable_title_output' ); ?>"><?php _e( 'Disable Title Output', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'The title output is content within the \'Widget Title\' field above. Disabling the title output may be useful for some themes.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
	</p>

	<!-- Open Links in New Window -->
	<p>
		<input id="<?php echo $this->get_field_id( 'target_blank' ); ?>" name="<?php echo $this->get_field_name( 'target_blank' ); ?>" type="checkbox" value="1" <?php checked( '1', $targetBlank ); ?>/>
		<label for="<?php echo $this->get_field_id( 'target_blank' ); ?>"><?php _e( 'Open Links in New Window', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This option will add target=\'_blank\' to the widget\'s links. This is useful to keep users on your website.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
	</p>
	<!-- No Follow Links -->
	<p>
		<input id="<?php echo $this->get_field_id( 'no_follow' ); ?>" name="<?php echo $this->get_field_name( 'no_follow' ); ?>" type="checkbox" value="1" <?php checked( '1', $noFollow ); ?>/>
		<label for="<?php echo $this->get_field_id( 'no_follow' ); ?>"><?php _e( 'No Follow Links', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'This option will add rel=\'nofollow\' to the widget\'s outgoing links. This option may be useful for SEO.', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
	</p>

	<!-- Transient / Cache -->
	<p>
		<label for="<?php echo $this->get_field_id( 'cache' ); ?>"><?php _e( 'Cache Data:', 'ywp' ); ?>
			<img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/help.png' ?>" title="<?php _e( 'Caching data will save Yelp data to your database in order to speed up response times and conserve API requests. The suggested settings is 1 Day. ', 'ywp' ); ?>" class="tooltip-info" width="16" height="16" /></label>
		<select name="<?php echo $this->get_field_name( 'cache' ); ?>" id="<?php echo $this->get_field_id( 'cache' ); ?>" class="widefat">
			<?php
			$options = array( __( 'None', 'ywp' ), __( '1 Hour', 'ywp' ), __( '3 Hours', 'ywp' ), __( '6 Hours', 'ywp' ), __( '12 Hours', 'ywp' ), __( '1 Day', 'ywp' ), __( '2 Days', 'ywp' ), __( '1 Week', 'ywp' ) );

			foreach ( $options as $option ) {
				?>
				<option value="<?php echo $option; ?>" id="<?php echo $option; ?>" <?php if ( $cache == $option || empty( $cache ) && $option == '1 Day' ) {
					echo ' selected="selected" ';
				} ?>>
					<?php echo $option; ?>
				</option>
				<?php $counter ++;
			}  ?>
		</select>
	</p>


</div>

<div class="pro-option">
	<p>Upgrade to the <a href="http://wordimpress.com/plugins/yelp-widget-pro/" target="_blank" class="new-window" title="<?php _e( 'Get immediate access after purchase to additional features, priority support and auto updates.', 'ywp' ); ?>"><?php _e( 'Premium Version', 'ywp' ); ?></a>
	</p>
</div>