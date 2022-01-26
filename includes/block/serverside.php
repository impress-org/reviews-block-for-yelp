<?php

if ( function_exists( 'register_block_type' ) ) {
	add_action( 'enqueue_block_editor_assets', 'yelp_block_enqueue_block_assets' );
}

/**
 * Enqueues block assets.
 *
 * @unreleased
 */
function yelp_block_enqueue_block_assets() {
	wp_enqueue_script(
		'wpbr-blocks',
		YELP_WIDGET_PRO_URL . '/assets/dist/js/yelp-block.js',
		[
			'wp-i18n',
			'wp-element',
			'wp-blocks',
			'wp-components',
			'wp-editor',
		]
	);

//	wp_localize_script(
//		'wpbr-blocks',
//		'wpbrData',
//		[
//			'apiRoot'             => esc_url_raw( rest_url( Endpoint::ROUTE_NAMESPACE ) ),
//			'apiNonce'            => wp_create_nonce( 'wp_rest' ),
//			'collectionsAdminUrl' => admin_url( 'edit.php?post_type=wpbr_collection' ),
//			'reviewsAdminUrl'     => admin_url( 'edit.php?post_type=wpbr_review' )
//		]
//	);
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

	$accessToken = get_option( 'blocks_for_github_plugin_personal_token', $attr['apiKeyState'] );

	if ( ! $accessToken ) :
		ob_start(); ?>
		<div id="bfg-welcome-wrap">
			<div class="bfg-welcome-wrap-inner">
				<span class="bfg-welcome-wave">👋</span>
				<h2>Welcome to Blocks for GitHub!</h2>
				<p>To begin, please enter your GitHub personal access token in the block's setting panel to the right.
					Don't worry, you'll only have to do this one time.</p>
			</div>
		</div>
		<?php
		return ob_get_clean();
	endif;


	ob_start(); ?>

	<p>Hello</p>

	<?php
	// Return output
	return ob_get_clean();
}
