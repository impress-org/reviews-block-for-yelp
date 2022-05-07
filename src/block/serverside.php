<?php
/**
 * Custom endpoint to grab Yelp data securely.
 *
 * @return void
 */
function yelp_api_rest_endpoint() {
	register_rest_route( 'yelp-block/v1', 'profile/',
		[
			'methods'             => 'GET',
			'callback'            => 'yelp_api_rest_callback',
			'permission_callback' => '__return_true',
		]
	);
}

add_action( 'rest_api_init', 'yelp_api_rest_endpoint' );


/**
 * Custom REST endpoint callback to output Yelp API data.
 *
 * @param WP_REST_Request $request
 *
 * @return WP_Error|WP_REST_Response
 */
function yelp_api_rest_callback( WP_REST_Request $request ) {

	// Get parameters from request
	$params = $request->get_params();

	if ( ! isset( $params['apiKey'] ) && isset( $params['keyValidation'] ) ) {
		return new WP_Error( 'yelp_api_error', esc_html__( 'Request must include a valid Yelp Fusion API key.', 'yelp-widget-pro' ), [ 'status' => 400 ] );
	}

	$apiKey = get_option('yelp_widget_settings');

	$apiKey = $apiKey['yelp_widget_fusion_api'];

	// Get the GitHub User info.
	$args = [
		'headers' =>
			[
				'user-agent'    => '',
				'authorization' => 'Bearer ' . $apiKey,
			],
	];

	// Get Repo data.
	$requestUrl = add_query_arg( [
		'term' => $params['term'] ? $params['term'] : '',
		'location' => $params['location'] ? $params['location'] : 'NYC',
	], 'https://api.yelp.com/v3/businesses/search' );

	$requestRequest = wp_remote_get( $requestUrl, $args );
	$requestBody    = json_decode( wp_remote_retrieve_body( $requestRequest) );

	if ( $requestBody->error ) {
		return new WP_Error( 'yelp_api_error', $requestBody->error->description, [ 'status' => 400 ] );
	}

	// Create the response object
	return new WP_REST_Response( wp_json_encode($requestBody->businesses), 200 );
}


/**
 * Render Yelp block serverside.
 *
 * @param $attr
 * @param $content
 *
 * @return false|string
 */
function yelp_block_render_profile_block( $attr, $content ) {

	$apiKey = get_option( 'yelp_block_api_key', $attr['apiKeyState'] );

	if ( ! $apiKey ) :
		ob_start(); ?>
		<div id="yelp-block-welcome-wrap" class="yelp-reviews-wrap">
			<div class="yelp-block-welcome-wrap-inner">
				<span class="yelp-block-welcome-wave">👋</span>
				<h2>Welcome to the Yelp Block!</h2>
				<p>To begin, please enter your Yelp API key in the block's setting panel to the right.
					Don't worry, you'll only have to do this one time.</p>
			</div>
		</div>
		<?php
		return ob_get_clean();
	endif;

	ob_start(); ?>

	<p class="yelp-block-wrap">Hello</p>

	<?php
	// Return output
	return ob_get_clean();
}
