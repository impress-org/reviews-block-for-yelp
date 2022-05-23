/**
 * Yelp Widget Pro Backend JavaScripts
 */


/**
 * Initialize the API Request Method widget radio input toggles
 */
jQuery(
	function () {
		yelpWidgetToggles();
		yelpWidgetTooltips();
	}
);

/**
 * Function to Refresh jQuery toggles for Yelp Widget Pro upon saving specific widget
 */
jQuery( document ).ajaxSuccess(
	function (e, xhr, settings) {
		yelpWidgetToggles();
		yelpWidgetTooltips();
	}
);

/**
 * Toggling Widget Options.
 */
function yelpWidgetToggles() {

	// API Method Toggle
	jQuery( '#widgets-right .widget-api-option .yelp-method-span:not("clickable")' ).each(
		function () {

			jQuery( this ).addClass( 'clickable' ).unbind( 'click' ).click(
				function () {
					jQuery( this ).parent().parent().find( '.toggled' ).slideUp().removeClass( 'toggled' );
					jQuery( this ).find( 'input' ).attr( 'checked', 'checked' );
					if (jQuery( this ).hasClass( 'search-api-option-wrap' )) {
						jQuery( this ).parent().next( '.toggle-api-option-1' ).slideToggle().toggleClass( 'toggled' );
					} else {
						jQuery( this ).parent().next().next( '.toggle-api-option-2' ).slideToggle().toggleClass( 'toggled' );
					}

				}
			);
		}
	);

	// Advanced Options Toggle (Bottom-gray panels)
	jQuery( '#widgets-right .yelp-toggler:not("clickable")' ).each(
		function () {

			jQuery( this ).addClass( 'clickable' ).unbind( 'click' ).click(
				function () {
					jQuery( this ).toggleClass( 'toggled' );
					jQuery( this ).next().slideToggle();
				}
			);

		}
	);

}

/**
 * Yelp Widget Tooltips.
 */
function yelpWidgetTooltips() {
	// Tooltips for admins
	jQuery( '.tooltip-info' ).tipsy(
		{
			fade: false,
			html: true,
			gravity: 's',
			delayOut: 0,
			delayIn: 0
		}
	);
}
