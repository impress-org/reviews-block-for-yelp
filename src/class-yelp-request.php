<?php
/**
 * Defines the Yelp_Request class
 *
 * @link https://wpbusinessreviews.com
 *
 * @package WP_Business_Reviews\Includes\Request
 * @since 0.1.0
 */

/**
 * Retrieves data from Yelp API.
 *
 * @since 0.1.0
 */
class Yelp_Request {
	/**
	 * Platform ID.
	 *
	 * @since 0.1.0
	 * @var string $platform
	 */
	protected $platform = 'yelp';

	/**
	 * Yelp API key.
	 *
	 * @since 0.1.0
	 * @var string $key
	 */
	private $key;

	/**
	 * Instantiates the Yelp_Request object.
	 *
	 * @param string $key Yelp API key.
	 *
	 * @since 0.1.0
	 *
	 */
	public function __construct( $key ) {
		$this->key = $key;
	}

	/**
	 * Retrieves the platform status based on a test request.
	 *
	 * @return string The platform status.
	 * @since 1.0.1
	 *
	 */
	public function get_platform_status() {
		$response = $this->search_review_source( 'PNC Park', 'Pittsburgh' );

		if ( is_wp_error( $response ) ) {
			return 'disconnected';
		}

		return 'connected';
	}

	/**
	 * Searches review sources based on search terms and location.
	 *
	 * @param string $terms The search terms, usually a business name.
	 * @param string $location The location within which to search.
	 *
	 * @return array|object Associative array containing response or WP_Error
	 *                        if response structure is invalid.
	 * @since 0.1.0
	 *
	 */
	public function search_review_source( $terms, $location ) {
		$url = add_query_arg(
			array(
				'term'     => $terms,
				'location' => $location,
				'limit'    => 10,
			),
			'https://api.yelp.com/v3/businesses/search'
		);

		$args = array(
			'user-agent' => '',
			'headers'    => array(
				'authorization' => 'Bearer ' . $this->key,
			),
		);

		$response = $this->get( $url, $args );

		if ( ! isset( $response['businesses'] ) || empty( $response['businesses'] ) ) {
			return new \WP_Error( 'wpbr_no_review_sources', __( 'No results found. For best results, enter the entire business name, city, and state as they appear on the platform.', 'wp-business-reviews' ) );
		}

		return $response['businesses'];
	}

	/**
	 * Retrieves review source details based on Yelp business ID.
	 *
	 * @param string $id The Yelp business ID.
	 *
	 * @return array|object Associative array containing response or WP_Error
	 *                        if response structure is invalid.
	 * @since 0.1.0
	 *
	 */
	public function get_review_source( $id ) {
		$url = 'https://api.yelp.com/v3/businesses/' . $id;

		$args = array(
			'user-agent' => '',
			'headers'    => array(
				'authorization' => 'Bearer ' . $this->key,
			),
		);

		return $this->get( $url, $args );

	}

	/**
	 * Retrieves reviews based on Yelp business ID.
	 *
	 * @param string $id The Yelp business ID.
	 *
	 * @return array|object Associative array containing response or WP_Error if response structure is invalid.
	 * @since 1.5.0 Return reviews from the various languages supported. Issue #324.
	 * @since 1.2.0 Return reviews in reverse chronological order.
	 * @since 0.1.0
	 *
	 */
	public function get_reviews( $id ) {
		$url = 'https://api.yelp.com/v3/businesses/' . $id . '/reviews';

		$args = array(
			'user-agent' => '',
			'headers'    => array(
				'authorization' => 'Bearer ' . $this->key,
			),
		);

		$response = $this->get( $url, $args );

		if ( ! isset( $response['reviews'] ) ) {
			return new \WP_Error( 'wpbr_no_reviews', __( 'No reviews found. Although reviews may exist on the platform, none were returned from the platform API.', 'wp-business-reviews' ) );
		}

		$reviews = $response['reviews'];

		// Check for reviews in additional locales and pull those in.
		if ( isset( $response['possible_languages'] ) ) {

			foreach ( $response['possible_languages'] as $locale ) {

				// Skip English because that's what we pull in by default.
				if ( 'en' === $locale ) {
					continue;
				}

				$locale_query_url = $this->get_locale_query_url( $locale, $url );

				// If no proper URL is returned.
				if ( ! $locale_query_url ) {
					continue;
				}

				$locale_response = $this->get( $locale_query_url, $args );

				// If other reviews then merge with English reviews.
				if ( isset( $locale_response['reviews'] ) ) {
					$reviews = array_merge( $reviews, $locale_response['reviews'] );
				}

			}

		}

		usort( $reviews, array( $this, 'compare_timestamps' ) );

		return $reviews;

	}

	/**
	 * Compares the timestamps of two reviews.
	 *
	 * @param array $review1 Array of review data with a timestamp.
	 * @param array $review2 Array of review data with a timestamp.
	 *
	 * @return int Difference between two timestamps.
	 * @since 1.2.0
	 */
	protected function compare_timestamps( $review1, $review2 ) {
		$timestamp1 = strtotime( $review1['time_created'] );
		$timestamp2 = strtotime( $review2['time_created'] );

		return $timestamp2 - $timestamp1;
	}

	/**
	 * Get the list of locales supported by Yelp.
	 *
	 * @param $locale string
	 * @param $url string
	 *
	 * @return string
	 * @since 1.5.0
	 *
	 */
	protected function get_locale_query_url( $locale, $url ) {

		$locale_query_url = false;

		$yelp_locales = apply_filters( 'wpbr_yelp_supported_locales', [
			'cs'  => 'cs_CZ',
			'de'  => 'de_DE',
			'es'  => 'es_ES',
			'fi'  => 'fi_FI',
			'fil' => 'fil_PH',
			'fr'  => 'fr_FR',
			'it'  => 'it_IT',
			'ms'  => 'ms_MY',
			'nb'  => 'nb_NO',
			'nl'  => 'nl_NL',
			'pl'  => 'pl_PL',
			'pt'  => 'pt_PT',
			'sv'  => 'sv_SE',
			'tr'  => 'tr_TR',
			'zh'  => 'zh_TW',
		] );

		if ( in_array( $locale, array_keys( $yelp_locales ) ) ) {
			$locale_query_url = $url . '?locale=' . $yelp_locales[ $locale ];
		}

		return $locale_query_url;

	}
}
