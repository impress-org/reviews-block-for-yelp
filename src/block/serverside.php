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

    if ( array_key_exists( 'term', $params ) && array_key_exists( 'location', $params ) || isset( $params['keyValidation'] ) ) {
        return yelp_retrieve_business_search_results( $params );
    } else {
        return yelp_retrieve_business_details( $params );
    }


}

/**
 * @param $params
 *
 * @return WP_Error|WP_REST_Response
 */
function yelp_retrieve_business_search_results( $params ) {
    $apiKey = get_option( 'yelp_widget_settings' );
    $apiKey = $params['apiKey'] ?? $apiKey['yelp_widget_fusion_api'];

    if ( empty( $params['apiKey'] ) && isset( $params['keyValidation'] ) ) {
        return new WP_Error( 'yelp_api_error', esc_html__( 'Request must include a valid Yelp Fusion API key.', 'yelp-widget-pro' ), [ 'status' => 400 ] );
    }

    // Ready to Query the Yelp API.
    $args = [
        'headers' =>
            [
                'user-agent'    => '',
                'authorization' => 'Bearer ' . $apiKey,
            ],
    ];

    // Get business search data.
    $requestUrl = add_query_arg( [
        'term'     => rawurlencode( $params['term'] ?? '' ),
        'location' => rawurlencode( $params['location'] ?? 'NYC' ),
    ], 'https://api.yelp.com/v3/businesses/search' );

    $requestRequest = wp_safe_remote_get( $requestUrl, $args );
    $requestBody    = json_decode( wp_remote_retrieve_body( $requestRequest ) );

    if ( $requestBody->error ) {
        return new WP_Error( 'yelp_api_error', $requestBody->error->description, [ 'status' => 400 ] );
    }

    // Create the response object
    return new WP_REST_Response( wp_json_encode( $requestBody->businesses ), 200 );
}

/**
 * @param $params
 *
 * @return WP_Error|WP_REST_Response
 */
function yelp_retrieve_business_details( $params ) {

    // Check if transient exists.
    $business_details = get_transient( $params['businessId'] );
    if ( $business_details ) {
        return $business_details;
    }

    $apiKey = get_option( 'yelp_widget_settings' );
    $apiKey = $apiKey['yelp_widget_fusion_api'];

    // Ready to Query the Yelp API.
    $args = [
        'headers' =>
            [
                'user-agent'    => '',
                'authorization' => 'Bearer ' . $apiKey,
            ],
    ];

    $businessDetailsReq = add_query_arg( [
        'locale' => rawurlencode( $params['locale'] ?? 'en_US' ),
    ], "https://api.yelp.com/v3/businesses/{$params['businessId']}" );

    // 1️⃣ Get business details.
    $response        = wp_safe_remote_get( $businessDetailsReq, $args );
    $businessDetails = json_decode( wp_remote_retrieve_body( $response ) );

    if ( $businessDetails->error ) {
        return new WP_Error( 'yelp_api_error', $businessDetails->error->description, [ 'status' => 400 ] );
    }

    // 2️⃣ Get business reviews.
    $response        = wp_safe_remote_get( "https://api.yelp.com/v3/businesses/{$params['businessId']}/reviews", $args );
    $businessReviews = json_decode( wp_remote_retrieve_body( $response ) );

    if ( $businessReviews->error ) {
        return new WP_Error( 'yelp_api_error', $businessDetails->error->description, [ 'status' => 400 ] );
    }

    // Save transient for later use.
    set_transient( $params['businessId'], (object) array_merge( (array) $businessDetails, (array) $businessReviews ), HOUR_IN_SECONDS );

    // 3️⃣ Combine objects and pass to REST response.
    return new WP_REST_Response( (object) array_merge( (array) $businessDetails, (array) $businessReviews ), 200 );;

}


/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets, so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
 */
function create_yelp_block_init() {

    wp_register_script(
        'reviews-block-yelp-script',
        plugins_url( 'build/yelp-block.js', YELP_PLUGIN_FILE ),
        YELP_PLUGIN_SCRIPT_ASSET['dependencies'],
        YELP_PLUGIN_SCRIPT_ASSET['version']
    );

    wp_register_style( 'reviews-block-yelp-style', plugins_url( 'build/yelp-block.css', YELP_PLUGIN_FILE ), [], YELP_PLUGIN_SCRIPT_ASSET['version'] );

    register_block_type( YELP_WIDGET_PRO_PATH, [
            'render_callback' => 'yelp_block_render_profile_block',
        ]
    );
}

add_action( 'init', 'create_yelp_block_init' );

/**
 * Render Yelp block serverside.
 *
 * @param $attr
 * @param $content
 *
 * @return false|string
 */
function yelp_block_render_profile_block( $attributes, $content ) {

    if ( ! is_admin() ) {
        wp_enqueue_script( 'reviews-block-yelp-script' );
        wp_enqueue_style( 'reviews-block-yelp-style' );
        wp_set_script_translations( 'reviews-block-yelp-script', 'yelp-widget-pro' );
    }

    ob_start();

    ?>
    <div id="reviews-block-yelp-<?php echo esc_html( $attributes['businessId'] ); ?>" class="root-yelp-block"
        <?php
        // 🔁 Loop through and set attributes per block.
        foreach ( $attributes as $key => $value ) :
            // Arrays need to be stringified.
            if ( is_array( $value ) ) {
                $value = implode( ', ', $value );
            } ?>
            data-<?php
            // output as hyphen-case so that it's changed to camelCase in JS.
            esc_attr_e( preg_replace( '/([A-Z])/', '-$1', $key ) ); ?>="<?php
            esc_attr_e( $value ); ?>"
        <?php
        endforeach; ?>></div>
    <?php
    // Return clean buffer
    return ob_get_clean();

}
