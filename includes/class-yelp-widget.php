<?php

/**
 * Adds Yelp Widget Pro Widget
 *
 * Class Yelp_Widget
 */
class Yelp_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'yelp_widget', // Base ID
			'Yelp Widget Pro', // Name
			array( 'description' => __( 'Display Yelp business ratings and reviews on your Website.', 'yelp-widget-pro' ) ) // Args
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

		$yelp = new Yelp_Widget();

		extract( $args );

		// Get plugin options.
		$options = get_option( 'yelp_widget_settings' );

		/**
		 * As of v1.5.0, the Yelp API transitioned from v2 to v3. To ensure upgraded plugins continue to function, a backup API key has been included below.
		 * It is still highly recommended that each user set up their own Yelp app and use their own API key.
		 */
		$fusion_api_key = ! empty( $options['yelp_widget_fusion_api'] ) ? $options['yelp_widget_fusion_api'] : 'u6iiKEMVJzF8hpqAaxajY-pf0bWxltr4etYBs6jo6HDpgZHQErXP8JkIGWA2ISKI2HUE9-E-3MBiYK14YXCq3fZmGPKFFjPVouU4HhQONe4AlEIct9MTVf97ZOs5WnYx';

		// Get Widget Options.
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

		// If cache option is enabled, attempt to get response from transient.
		if ( strtolower( $cache ) != 'none' ) {

			$transient = $displayOption . $term . $id . $location . $limit . $sort . $profileImgSize;

			// Check for an existing copy of our cached/transient data
			if ( ( $response = get_transient( $transient ) ) == false ) {

				// Get Time to Cache Data
				$expiration = $cache;

				// Assign Time to appropriate Math
				switch ( $expiration ) {
					case '1 Hour':
						$expiration = 3600;
						break;
					case '3 Hours':
						$expiration = 3600 * 3;
						break;
					case '6 Hours':
						$expiration = 3600 * 6;
						break;
					case '12 Hours':
						$expiration = 60 * 60 * 12;
						break;
					case '1 Day':
						$expiration = 60 * 60 * 24;
						break;
					case '2 Days':
						$expiration = 60 * 60 * 48;
						break;
					case '1 Week':
						$expiration = 60 * 60 * 168;
						break;
				}

				// Cache data wasn't there, so regenerate the data and save the transient
				if ( $displayOption == '1' ) {
					$response = yelp_widget_fusion_get_business( $fusion_api_key, $id, $reviewsOption );
				} else {
					$response = yelp_widget_fusion_search( $fusion_api_key, $term, $location, $limit, $sort );
				}

				set_transient( $transient, $response, $expiration );
			}
		} else {

			// No Cache option enabled
			if ( $displayOption == '1' ) {
				// Widget is in Business mode.
				$response = yelp_widget_fusion_get_business( $fusion_api_key, $id );
			} else {
				// Widget is in Search mode.
				$response = yelp_widget_fusion_search( $fusion_api_key, $term, $location, $limit, $sort );
			}
		}

		/*
		 * Output Yelp Widget Pro
		 */

		// Widget Output
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

		// Check Yelp API response for an error
		if ( isset( $response->error ) ) {
			$output .= $yelp->handle_yelp_api_error( $response );
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

				// Begin Setting Output Variable by Looping Data from Yelp
				for ( $x = 0; $x < count( $businesses ); $x ++ ) {
					?>

					<div class="yelp yelp-business 
					<?php
					echo $align;

					// Set profile image size
					switch ( $profileImgSize ) {

						case '40x40':
							echo 'ywp-size-40';
							break;
						case '60x60':
							echo 'ywp-size-60';
							break;
						case '80x80':
							echo 'ywp-size-80';
							break;
						case '100x100':
							echo 'ywp-size-100';
							break;
						default:
							echo 'ywp-size-60';
					}

					?>
					">
						<div class="biz-img-wrap">
							<img class="picture" src="
							<?php
							if ( ! empty( $businesses[ $x ]->image_url ) ) {
								echo esc_attr( $businesses[ $x ]->image_url );
							} else {
								echo YELP_WIDGET_PRO_URL . '/assets/images/blank-biz.png';
							};
							?>
							"
								<?php
								// Set profile image size
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
								}
								?>
							/></div>
						<div class="info">
							<a class="name" <?php echo $targetBlank . $noFollow; ?> href="<?php echo esc_attr( $businesses[ $x ]->url ); ?>"
							   title="<?php echo esc_attr( $businesses[ $x ]->name ); ?> Yelp page"><?php echo $businesses[ $x ]->name; ?></a>
							<?php yelp_widget_fusion_stars( $businesses[ $x ]->rating ); ?>
							<span class="review-count"><?php echo esc_attr( $businesses[ $x ]->review_count ); ?><?php _e( 'reviews', 'yelp-widget-pro' ); ?></span>
							<a class="yelp-branding"
							   href="<?php echo esc_url( $businesses[ $x ]->url ); ?>" <?php echo $targetBlank . $noFollow; ?>><?php yelp_widget_fusion_logo(); ?></a>
						</div>

						<?php
						// Does the User want to display Address?
						if ( $address == 1 ) {
							?>
							<div class="yelp-address-wrap">
								<address>
									<?php
									// Iterate through Address Array
									foreach ( $businesses[ $x ]->location->display_address as $addressItem ) {

										echo $addressItem . '<br/>';
									}
									?>
									<address>
							</div>

							<?php
						} //endif address

						// Phone
						if ( $phone == 1 ) {
							?>

							<p class="ywp-phone">
								<?php
								// echo pretty display_phone (only avail in biz API)
								if ( ! empty( $businesses[ $x ]->display_phone ) ) {
									echo $businesses[ $x ]->display_phone;
								} else {
									echo $businesses[ $x ]->phone;
								}
								?>
							</p>


						<?php } //endif phone ?>

					</div><!--/.yelp-->

					<?php

				} //end for
			}
		} //Output Widget Contents

		echo $output;

		echo $after_widget;

	}


	/**
	 * Saves the widget options
	 *
	 * @see WP_Widget::update
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 *
	 * @return array
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
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance
	 *
	 * @return string|void
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

		ob_start();
		include YELP_WIDGET_PRO_PATH . '/includes/widget-form.php';
		echo ob_get_clean();

	} //end form function

	/**
	 * Handle Yelp Error Messages
	 *
	 * @param $response
	 */
	public function handle_yelp_api_error( $response ) {

		$output = '<div class="yelp-error">';
		if ( $response->error->code == 'EXCEEDED_REQS' ) {
			$output .= __( 'The default Yelp API has exhausted its daily limit. Please enable your own API Key in your Yelp Widget Pro settings.', 'yelp-widget-pro' );
		} elseif ( $response->error->code == 'BUSINESS_UNAVAILABLE' ) {
			$output .= __( '<strong>Error:</strong> Business information is unavailable. Either you mistyped the Yelp biz ID or the business does not have any reviews.', 'yelp-widget-pro' );
		} elseif ( $response->error->code == 'TOKEN_MISSING' ) {
			$output .= sprintf(
				__( '%1$sSetup Required:%2$s Enter a Yelp Fusion API Key in the %3$splugin settings screen.%4$s', 'yelp-widget-pro' ),
				'<strong>',
				'</strong>',
				'<a href="' . YWP_SETTINGS_URL . '">',
				'</a>'
			);
		} //output standard error
		else {
			if ( ! empty( $response->error->code ) ) {
				$output .= $response->error->code . ': ';
			}
			if ( ! empty( $response->error->description ) ) {
				$output .= $response->error->description;
			}
		}
		$output .= '</div>';

		echo $output;

	}
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

	// Use curl only if necessary
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
 * Retrieves search results based on a search term and location.
 *
 * @since 1.5.0
 *
 * @param string $key      Yelp Fusion API Key.
 * @param string $term     The search term, usually a business name.
 * @param string $location The location within which to search.
 * @param string $limit    Number of businesses to return.
 * @param string $sort_by  Optional. Sort the results by one of the these modes:
 *                         best_match, rating, review_count or distance. Defaults to best_match.
 *
 * @return array Associative array containing the response body.
 */
function yelp_widget_fusion_search( $key, $term, $location, $limit, $sort_by ) {
	switch ( $sort_by ) {
		case '0':
			$sort_by = 'best_match';
			break;
		case '1':
			$sort_by = 'distance';
			break;
		case '2':
			$sort_by = 'rating';
			break;
		default:
			$sort_by = 'best_match';
	}

	$url = add_query_arg(
		array(
			'term'     => $term,
			'location' => $location,
			'limit'    => $limit,
			'sort_by'  => $sort_by,
		),
		'https://api.yelp.com/v3/businesses/search'
	);

	$args = array(
		'user-agent' => '',
		'headers'    => array(
			'authorization' => 'Bearer ' . $key,
		),
	);

	$response = yelp_widget_fusion_get( $url, $args );

	return $response;
}

/**
 * Retrieves business details based on Yelp business ID.
 *
 * @since 1.5.0
 *
 * @param string $key Yelp Fusion API Key.
 * @param string $id  The Yelp business ID.
 *
 * @return array Associative array containing the response body.
 */
function yelp_widget_fusion_get_business( $key, $id ) {
	$url = 'https://api.yelp.com/v3/businesses/' . $id;

	$args = array(
		'user-agent' => '',
		'headers'    => array(
			'authorization' => 'Bearer ' . $key,
		),
	);

	$response = yelp_widget_fusion_get( $url, $args );

	return $response;
}

/**
 * Retrieves a response from a safe HTTP request using the GET method.
 *
 * @since 1.5.0
 *
 * @see   wp_safe_remote_get()
 *
 * @return array Associative array containing the response body.
 */
function yelp_widget_fusion_get( $url, $args = array() ) {
	$response = wp_safe_remote_get( $url, $args );

	if ( is_wp_error( $response ) ) {
		return false;
	}

	$body = json_decode( $response['body'] );

	$response = yelp_update_http_for_ssl( $response );
	$response = json_decode( $response['body'] );

	/**
	 * Filters the Yelp Fusion API response.
	 *
	 * @since 1.5.0
	 */
	return apply_filters( 'yelp_fusion_api_response', $response );
}

/**
 * Generates a star image based on numerical rating.
 *
 * @since 1.5.0
 *
 * @param int|float $rating Numerical rating between 0 and 5 in increments of 0.5.
 *
 * @return string Responsive image element.
 */
function yelp_widget_fusion_stars( $rating = 0 ) {
	$ext          = '.png';
	$floor_rating = floor( $rating );

	if ( $rating != $floor_rating ) {
		$image_name = $floor_rating . '_half';
	} else {
		$image_name = $floor_rating;
	}

	$uri_image_name = YELP_WIDGET_PRO_URL . '/assets/images/stars/regular_' . $image_name;
	$single         = $uri_image_name . $ext;
	$double         = $uri_image_name . '@2x' . $ext;
	$triple         = $uri_image_name . '@3x' . $ext;
	$srcset         = "{$single}, {$double} 2x, {$triple} 3x";
	$decimal_rating = number_format( $rating, 1, '.', '' );

	echo '<img class="rating" srcset="' . esc_attr( $srcset ) . '" src="' . esc_attr( $single ) . '" title="' . $decimal_rating . ' star rating" alt="' . $decimal_rating . ' star rating">';
}

/**
 * Displays responsive Yelp logo.
 *
 * @since 1.5.0
 *
 * @return string Responsive image element.
 */
function yelp_widget_fusion_logo() {
	$image_name     = 'yelp-widget-logo';
	$ext            = '.png';
	$uri_image_name = YELP_WIDGET_PRO_URL . '/assets/images/' . $image_name;
	$single         = $uri_image_name . $ext;
	$double         = $uri_image_name . '@2x' . $ext;
	$srcset         = "{$single}, {$double} 2x";

	echo '<img class="ywp-logo" srcset="' . esc_attr( $srcset ) . '" src="' . esc_attr( $single ) . '" alt="Yelp logo">';
}

/**
 * Function update http for SSL
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
