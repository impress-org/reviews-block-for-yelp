<?php

/**
 * Adds Yelp Widget Pro Widget
 */
class Yelp_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'yelp_widget', // Base ID
			'Yelp Widget Pro', // Name
			array( 'description' => __( 'Display Yelp business ratings and reviews on your Website.', 'ywp' ), ) // Args
		);
	}


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {

		extract( $args );

		/* Thanks Again to the Yelp It plugin for the following code! */
		$options = get_option( 'yelp_widget_settings' ); // Retrieve settings array, if it exists

		// Base unsigned URL
		$unsigned_url = "http://api.yelp.com/v2/";

		// Token object built using the OAuth library
		$yelp_widget_token        = $options['yelp_widget_token'];
		$yelp_widget_token_secret = $options['yelp_widget_token_secret'];

		$token = new OAuthToken( $yelp_widget_token, $yelp_widget_token_secret );

		// Consumer object built using the OAuth library
		$yelp_widget_consumer_key    = $options['yelp_widget_consumer_key'];
		$yelp_widget_consumer_secret = $options['yelp_widget_consumer_secret'];

		$consumer = new OAuthConsumer( $yelp_widget_consumer_key, $yelp_widget_consumer_secret );

		// Yelp uses HMAC SHA1 encoding
		$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

		//Yelp Widget Options
		$title          = apply_filters( 'widget_title', $instance['title'] );
		$displayOption  = $instance['display_option'];
		$term           = $instance['term'];
		$id             = $instance['id'];
		$location       = $instance['location'];
		$address        = $instance['display_address'];
		$phone          = $instance['display_phone'];
		$limit          = $instance['limit'];
		$profileImgSize = $instance['profile_img_size'];
		$sort           = $instance['sort'];
		$align          = $instance['alignment'];
		$titleOutput    = $instance['disable_title_output'];
		$targetBlank    = $instance['target_blank'];
		$noFollow       = $instance['no_follow'];
		$cache          = $instance['cache'];

		//Build URL Parameters
		$urlparams = array(
			'term'     => $term,
			'id'       => $id,
			'location' => $location,
			'limit'    => $limit,
			'sort'     => $sort
		);

		// If ID param is set, use business method and delete any other parameters
		if ( $urlparams['id'] != '' ) {
			$urlparams['method'] = 'business/' . $urlparams['id'];
			unset( $urlparams['term'], $urlparams['location'], $urlparams['id'], $urlparams['sort'] );
		} else {
			$urlparams['method'] = 'search';
			unset( $urlparams['id'] );
		}

		// Set method
		$unsigned_url = $unsigned_url . $urlparams['method'];

		unset( $urlparams['method'] );

		// Build OAuth Request using the OAuth PHP library. Uses the consumer and
		// token object created above.
		$oauthrequest = OAuthRequest::from_consumer_and_token( $consumer, $token, 'GET', $unsigned_url, $urlparams );

		// Sign the request
		$oauthrequest->sign_request( $signature_method, $consumer, $token );

		// Get the signed URL
		$signed_url = $oauthrequest->to_url();

		// Cache: cache option is enabled
		if ( $cache !== 'None' ) {

			$transient = $displayOption . $term . $id . $location . $limit . $sort . $profileImgSize;

			// Check for an existing copy of our cached/transient data
			if ( ( $response = get_transient( $transient ) ) == false ) {

				//Get Time to Cache Data
				$expiration = $cache;

				//Assign Time to appropriate Math
				switch ( $expiration ) {

					case "1 Hour":
						$expiration = 3600;
						break;
					case "3 Hours":
						$expiration = 3600 * 3;
						break;
					case "6 Hours":
						$expiration = 3600 * 6;
						break;
					case "12 Hours":
						$expiration = 60 * 60 * 12;
						break;
					case "1 Day":
						$expiration = 60 * 60 * 24;
						break;
					case "2 Days":
						$expiration = 60 * 60 * 48;
						break;
					case "1 Week":
						$expiration = 60 * 60 * 168;
						break;


				}

				// Cache data wasn't there, so regenerate the data and save the transient
				$response = yelp_widget_curl( $signed_url );
				set_transient( $transient, $response, $expiration );

			}


		} else {

			//No Cache option enabled
			$response = yelp_widget_curl( $signed_url );

		}


		/*
		 * Output Yelp Widget Pro
		 */

		//Widget Output
		echo $before_widget;

		// if the title is set & the user hasn't disabled title output
		if ( $title && $titleOutput != 1 ) {
			echo $before_title . $title . $after_title;
		}

		if ( isset( $response->businesses ) ) {
			$businesses = $response->businesses;
		} else {
			$businesses = array( $response );
		}

		// Instantiate output var
		$output = '';

		//Check Yelp API response for an error
		if ( isset( $response->error ) ) {
			$output = '<div class="yelp-error">';
			if ( $response->error->id == 'EXCEEDED_REQS' ) {
				$output .= __( 'Yelp is exhausted (Contact Yelp to increase your API call limit)', 'ywp' );
			} else {
				$output .= $response->error->text;
			}

			$output .= '</div>';
		} //Verify results have been returned
		else {
			if ( ! isset( $businesses[0] ) ) {
				$output = '<div class="yelp-error">No results</div>';
			} /**
			 * Response from Yelp is Valid - Output Widget
			 */
			else {


				// Open link in new window if set
				if ( $targetBlank == 1 ) {
					$targetBlank = 'target="_blank" ';
				} else {
					$targetBlank = '';
				}
				// Add nofollow relation if set
				if ( $noFollow == 1 ) {
					$noFollow = 'rel="nofollow" ';
				} else {
					$noFollow = '';
				}

				//Begin Setting Output Variable by Looping Data from Yelp
				for ( $x = 0; $x < count( $businesses ); $x ++ ) {
					?>

					<div class="yelp yelp-business <?php echo $align;

					//Set profile image size
					switch ( $profileImgSize ) {

						case '40x40':
							echo "ywp-size-40";
							break;
						case '60x60':
							echo "ywp-size-60";
							break;
						case '80x80':
							echo "ywp-size-80";
							break;
						case '100x100':
							echo "ywp-size-100";
							break;
						default:
							echo "ywp-size-60";
					}


					?>">
						<div class="biz-img-wrap">
							<img class="picture" src="<?php if ( ! empty( $businesses[ $x ]->image_url ) ) {
								echo esc_attr( $businesses[ $x ]->image_url );
							} else {
								echo YELP_WIDGET_PRO_URL . '/includes/images/blank-biz.png';
							}; ?>"
								<?php
								//Set profile image size
								switch ( $profileImgSize ) {

									case '40x40':
										echo "width='40' height='40'";
										break;
									case '60x60':
										echo "width='60' height='60'";
										break;
									case '80x80':
										echo "width='80' height='80'";
										break;
									case '100x100':
										echo "width='100' height='100'";
										break;
									default:
										echo "width='60' height='60'";
								} ?> /></div>
						<div class="info">
							<a class="name" <?php echo $targetBlank; ?>  <?php echo $noFollow; ?> href="<?php echo esc_attr( $businesses[ $x ]->url ); ?>" title="<?php echo esc_attr( $businesses[ $x ]->name ); ?> <?php _e( 'on Yelp', 'ywp' ); ?>"><?php echo $businesses[ $x ]->name; ?></a>
							<img class="rating" src="<?php echo esc_attr( $businesses[ $x ]->rating_img_url ); ?>" alt="<?php echo esc_attr( $businesses[ $x ]->name ); ?> <?php _e( 'Yelp Rating', 'ywp' ); ?>" title="<?php echo esc_attr( $businesses[ $x ]->name ); ?> <?php _e( 'Yelp Rating', 'ywp' ); ?>" />
							<span class="review-count"><?php echo esc_attr( $businesses[ $x ]->review_count ) . ' reviews'; ?></span>
							<a class="yelp-branding" href="<?php echo esc_attr( $businesses[ $x ]->url ); ?>" <?php echo $targetBlank; ?> <?php echo $noFollow; ?>><img src="<?php echo YELP_WIDGET_PRO_URL . '/includes/images/yelp.png'; ?>" alt="Powered by Yelp" /></a>
						</div>

						<?php
						//Does the User want to display Address?
						if ( $address == 1 ) {
							?>
							<div class="yelp-address-wrap">
								<address>
									<?php
									//Iterate through Address Array
									foreach ( $businesses[ $x ]->location->display_address as $addressItem ) {

										echo $addressItem . "<br/>";
									} ?>
									<address>
							</div>

						<?php
						} //endif address

						//Phone
						if ( $phone == 1 ) {
							?>

							<p class="ywp-phone"><?php
								//echo pretty display_phone (only avail in biz API)
								if ( ! empty( $businesses[ $x ]->display_phone ) ) {
									echo $businesses[ $x ]->display_phone;
								} else {
									echo $businesses[ $x ]->phone;
								}  ?></p>


						<?php } //endif phone	?>

					</div><!--/.yelp-->

				<?php

				} //end for
			}
		} //Output Widget Contents

		echo $output;

		echo $after_widget;

	}


	/**
	 * @DESC: Saves the widget options
	 * @SEE WP_Widget::update
	 */
	function update( $new_instance, $old_instance ) {
		$instance                         = $old_instance;
		$instance['title']                = strip_tags( $new_instance['title'] );
		$instance['display_option']       = strip_tags( $new_instance['display_option'] );
		$instance['term']                 = strip_tags( $new_instance['term'] );
		$instance['id']                   = strip_tags( $new_instance['id'] );
		$instance['location']             = strip_tags( $new_instance['location'] );
		$instance['display_address']      = strip_tags( $new_instance['display_address'] );
		$instance['display_phone']        = strip_tags( $new_instance['display_phone'] );
		$instance['limit']                = strip_tags( $new_instance['limit'] );
		$instance['profile_img_size']     = strip_tags( $new_instance['profile_img_size'] );
		$instance['sort']                 = strip_tags( $new_instance['sort'] );
		$instance['alignment']            = strip_tags( $new_instance['alignment'] );
		$instance['disable_title_output'] = strip_tags( $new_instance['disable_title_output'] );
		$instance['target_blank']         = strip_tags( $new_instance['target_blank'] );
		$instance['no_follow']            = strip_tags( $new_instance['no_follow'] );
		$instance['cache']                = strip_tags( $new_instance['cache'] );

		return $instance;
	}


	/**
	 * Back-end widget form.
	 * @see WP_Widget::form()
	 */
	function form( $instance ) {

		$title          = ! isset( $instance['title'] ) ? '' : esc_attr( $instance['title'] );
		$displayOption  = ! isset( $instance['display_option'] ) ? '' : esc_attr( $instance['display_option'] );
		$term           = ! isset( $instance['term'] ) ? '' : esc_attr( $instance['term'] );
		$id             = ! isset( $instance['id'] ) ? '' : esc_attr( $instance['id'] );
		$location       = ! isset( $instance['location'] ) ? '' : esc_attr( $instance['location'] );
		$address        = ! isset( $instance['display_address'] ) ? '' : esc_attr( $instance['display_address'] );
		$phone          = ! isset( $instance['display_phone'] ) ? '' : esc_attr( $instance['display_phone'] );
		$limit          = ! isset( $instance['limit'] ) ? '' : esc_attr( $instance['limit'] );
		$profileImgSize = ! isset( $instance['profile_img_size'] ) ? '' : esc_attr( $instance['profile_img_size'] );
		$sort           = ! isset( $instance['sort'] ) ? '' : esc_attr( $instance['sort'] );
		$align          = ! isset( $instance['alignment'] ) ? '' : esc_attr( $instance['alignment'] );
		$titleOutput    = ! isset( $instance['disable_title_output'] ) ? '' : esc_attr( $instance['disable_title_output'] );
		$targetBlank    = ! isset( $instance['target_blank'] ) ? '' : esc_attr( $instance['target_blank'] );
		$noFollow       = ! isset( $instance['no_follow'] ) ? '' : esc_attr( $instance['no_follow'] );
		$cache          = ! isset( $instance['cache'] ) ? '' : esc_attr( $instance['cache'] );


		$apiOptions = get_option( 'yelp_widget_settings' );

		//Verify that the API values have been inputed prior to output
		if ( empty( $apiOptions["yelp_widget_consumer_key"] ) || empty( $apiOptions["yelp_widget_consumer_secret"] ) || empty( $apiOptions["yelp_widget_token"] ) || empty( $apiOptions["yelp_widget_token_secret"] ) ) {
			//the user has not properly configured plugin so diplay a warning
			?>
			<div class="alert alert-red"><?php _e( 'Please input your Yelp API information in the <a href="options-general.php?page=yelp_widget">plugin settings</a> page prior to enabling Yelp Widget Pro.', 'ywp' ); ?></div>
		<?php
		} //The user has properly inputted Yelp API info so output widget form so output the widget contents
		else {

			include( 'includes/widget-form.php' );

		} //endif check for Yelp API key inputs

	} //end form function

}

/*
 * @DESC: Register Yelp Widget Pro widget
 */
add_action( 'widgets_init', create_function( '', 'register_widget( "Yelp_Widget" );' ) );


/**
 * @DESC: CURLs the Yelp API with our url parameters and returns JSON response
 */
function yelp_widget_curl( $signed_url ) {

	// Send Yelp API Call using WP's HTTP API
	$data = wp_remote_get( $signed_url );

	//Use curl only if necessary
	if ( empty( $data['body'] ) ) {

		$ch = curl_init( $signed_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		$data = curl_exec( $ch ); // Yelp response
		curl_close( $ch );
		$data     = yelp_update_http_for_ssl( $data );
		$response = json_decode( $data );

	} else {
		$data     = yelp_update_http_for_ssl( $data );
		$response = json_decode( $data['body'] );
	}

	// Handle Yelp response data
	return $response;

}

/**
 * Function update http for SSL
 *
 */
function yelp_update_http_for_ssl( $data ) {

	if ( ! empty( $data['body'] ) && is_ssl() ) {
		$data['body'] = str_replace( 'http:', 'https:', $data['body'] );
	} elseif ( is_ssl() ) {
		$data = str_replace( 'http:', 'https:', $data );
	}
	$data = str_replace( 'http:', 'https:', $data );

	return $data;

}