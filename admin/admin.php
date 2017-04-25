<?php

/* Display a notice that can be dismissed */

add_action('admin_notices', 'ywp_activation_admin_notice');

function ywp_activation_admin_notice() {
	global $current_user ;
	$user_id = $current_user->ID;
	/* Check that the user hasn't already clicked to ignore the message */
	global $pagenow;
	if ( $pagenow == 'plugins.php' ) {
		if (!get_user_meta($user_id, 'ywp_activation_ignore_notice')) { ?>
			<style>
				div.updated.ywp,
				div.updated.ywp header,
				div.updated.ywp header img,
				div.updated.ywp header h3,
				div.updated.ywp .dismiss,
				.ywp-actions,
				.ywp-action,
				.ywp-action #mc_embed_signup,
				div.updated.ywp .ywp-action span.dashicons:before {
					-webkit-box-sizing: border-box;
					/* Safari/Chrome, other WebKit */
					-moz-box-sizing: border-box;
					/* Firefox, other Gecko */
					box-sizing: border-box;
					/* Opera/IE 8+ */
					width: 100%;
					position: relative;
					padding: 0;
					margin: 0;
					overflow: hidden;
					float: none;
					display: block;
					text-align: left;
				}
				.ywp-action a,
				.ywp-action a:hover,
				div.updated.ywp .ywp-action.mailchimp:hover,
				div.updated.ywp .ywp-action.mailchimp span {
					-webkit-transition: all 500ms ease-in-out;
					-moz-transition: all 500ms ease-in-out;
					-ms-transition: all 500ms ease-in-out;
					-o-transition: all 500ms ease-in-out;
					transition: all 500ms ease-in-out;
				}
				div.updated.ywp {
					margin: 1rem 0 2rem 0;
				}
				div.updated.ywp header h3 {
					line-height: 1.4;
				}
				@media screen and (min-width: 280px) {
					div.updated.ywp {
						border: 0px;
						background: transparent;
						-webkit-box-shadow: 0 1px 1px 1px rgba(0, 0, 0, 0.1);
						box-shadow: 0 1px 1px 1px rgba(0, 0, 0, 0.1);
					}
					div.updated.ywp header {
						background: #bf3026;
						color: white;
						position: relative;
						height: 5rem;
					}
					div.updated.ywp header img {
						display: none;
						max-width: 98px;
						margin: 1rem;
						float: left;
					}
					div.updated.ywp header h3 {
						float: left;
						max-width: 60%;
						margin: 1rem;
						display: inline-block;
						color: white;
					}
					div.updated.ywp a.dismiss {
						display: block;
						position: absolute;
						left: auto;
						top: 0;
						bottom: 0;
						right: 0;
						width: 6rem;
						background: rgba(255, 255, 255, .15);
						color: white;
						text-align: center;
					}
					.ywp a.dismiss:before {
						font-family: 'Dashicons';
						content: "\f153";
						display: inline-block;
						position: absolute;
						top: 50%;

						transform: translate(-50%);
						right: 40%;
						margin: auto;
						line-height: 0;
					}
					div.updated.ywp a.dismiss:hover {
						color: #777;
						background: rgba(255, 255, 255, .5)
					}

					/* END ACTIVATION HEADER
					 * START ACTIONS
					 */
					div.updated.ywp .ywp-action {
						display: table;
					}
					.ywp-action a,
					.ywp-action #mc_embed_signup {
						background: rgba(0,0,0,.1);
						color: rgba(51, 51, 51, 1);
						padding: 0 1rem 0 6rem;
						height: 4rem;
						display: table-cell;
						vertical-align: middle;
					}
					.ywp-action.mailchimp {
						margin-bottom: -1.5rem;
						top: -.5rem;
					}
					.ywp-action.mailchimp p {
						margin: 9px 0 0 0;
					}

					.ywp-action #mc_embed_signup form {
						display: inline-block;
					}

					div.updated.ywp .ywp-action span {
						display: block;
						position: absolute;
						left: 0;
						top: 0;
						bottom: 0;
						height: 100%;
						width: auto;
					}
					div.updated.ywp .ywp-action span.dashicons:before {
						padding: 2rem 1rem;
						color: #bf3026;
						line-height: 0;
						top: 50%;
						transform: translateY(-50%);
						background: rgba(163, 163, 163, .25);
					}
					div.updated.ywp .ywp-action a:hover,
					div.updated.ywp .ywp-action.mailchimp:hover {
						background: rgba(0,0,0,.2);
					}
					div.updated.ywp .ywp-action a {
						text-decoration: none;
					}

					div.updated.ywp .ywp-action a,
					div.updated.ywp .ywp-action #mc_embed_signup {
						position: relative;
						overflow: visible;
					}
					.ywp-action #mc_embed_signup form,
					.ywp-action #mc_embed_signup form input#mce-EMAIL {
						width: 100%;
					}
					div.updated.ywp .mailchimp form input#mce-EMAIL + input.submit-button {
						display: block;
						position: relative;
						top: -1.75rem;
						float: right;
						right: 4px;
						border: 0;
						background: #cccccc;
						border-radius: 2px;
						font-size: 10px;
						color: white;
						cursor: pointer;
					}

					div.updated.ywp .mailchimp form input#mce-EMAIL:focus + input.submit-button {
						background: #bf3026;
					}

					.ywp-action #mc_embed_signup form input#mce-EMAIL div#placeholder,
					input#mce-EMAIL:-webkit-input-placeholder {opacity: 0;}
				}
				@media screen and (min-width: 780px) {
					div.updated.ywp header h3 {line-height: 3;}

					div.updated.ywp .mailchimp form input#mce-EMAIL + input.submit-button {
						top: -1.55rem;
					}
					div.updated.ywp header img {
						display: inline-block;
					}
					div.updated.ywp header h3 {
						max-width: 50%;
					}
					.ywp-action {
						width: 30%;
						float: left;
					}
					div.updated.ywp .ywp-action a {
						
					}
					.ywp-action a,
					.ywp-action #mc_embed_signup {
						padding: 0 1rem 0 4rem;
					}
					div.updated.ywp .ywp-action span.dashicons:before {

					}
					div.updated.ywp .ywp-action.mailchimp {
						width: 40%;
					}
				}
			</style>
			<div class="updated ywp">
				<header>
					<img src="<?php echo YELP_WIDGET_PRO_URL; ?>/includes/images/yelp-logo-transparent-icon.png"  class="yelp-logo"/>
					<h3><?php _e('Thanks for installing Yelp Widget Pro (Free Version)!','ywp'); ?></h3>
					<?php printf(__('<a href="%1$s" class="dismiss"></a>', 'ywp'), '?ywp_nag_ignore=0'); ?>
				</header>
				<div class="ywp-actions">
					<div class="ywp-action">
						<a href="<?php echo admin_url(); ?>options-general.php?page=yelp_widget">
							<span class="dashicons dashicons-admin-settings"></span><?php _e('Go to Settings','ywp'); ?>
						</a>
					</div>

					<div class="ywp-action">
						<a href="https://wordimpress.com/plugins/yelp-widget-pro/" target="_blank">
							<span class="dashicons dashicons-download"></span>
							<?php _e('Upgrade to Premium Version','ywp'); ?>
						</a>
					</div>

					<div class="ywp-action mailchimp">
						<script>
							jQuery(function ($) {
								var mcemail = $('#mce-EMAIL').val();
							}
						</script>
						<div id="mc_embed_signup">
							<span class="dashicons dashicons-edit"></span>
							<form action="//wordimpress.us3.list-manage.com/subscribe/post?u=3ccb75d68bda4381e2f45794c&amp;id=cf1af2563c" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
								<div class="mc-field-group">
									<p><small><?php _e('Get notified of plugin updates:','ywp'); ?></small></p>
									<input type="text" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="my.email@wordpress.com">
									<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="submit-button">
									<input type="hidden" value="2" name="group[13857]" id="mce-group[13857]-13857-3" checked="checked">
								</div>
								<div id="mce-responses" class="clear">
									<div class="response" id="mce-error-response" style="display:none"></div>
									<div class="response" id="mce-success-response" style="display:none"></div>
								</div>
								<div style="position: absolute; left: -5000px;">
									<input type="text" name="b_3ccb75d68bda4381e2f45794c_83609e2883" value="">
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
	}
}

add_action('admin_init', 'ywp_nag_ignore');

function ywp_nag_ignore() {
	global $current_user;
	$user_id = $current_user->ID;
	/* If user clicks to ignore the notice, add that to their user meta */
	if ( isset($_GET['ywp_nag_ignore']) && '0' == $_GET['ywp_nag_ignore'] ) {
		add_user_meta($user_id, 'ywp_activation_ignore_notice', 'true', true);
	}
}