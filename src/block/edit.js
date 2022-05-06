import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	PanelRow,
	TextControl,
	Button,
	Spinner,
	CheckboxControl,
	ResponsiveWrapper,
} from '@wordpress/components';
import { Fragment, useState, useEffect } from '@wordpress/element';
import { InspectorControls, MediaUpload, useBlockProps, MediaUploadCheck } from '@wordpress/block-editor';
import { dispatch, useSelect } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

import './editor.scss';

/**
 * Edit function.
 *
 *  @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {

	const {
		apiKey,
		preview,
	} = attributes;

	const [yelpApiKey, setYelpApiKey] = useState( attributes.apiKey );
	const [apiKeyLoading, setApiKeyLoading] = useState( false );
	const [yelpConnected, setYelpConnected] = useState( null );

	const siteSettings = useSelect( ( select ) => {
		return select( 'core' ).getEntityRecord( 'root', 'site' );
	}, [] );

	useEffect( () => {
		if ( siteSettings ) {
			const {
				yelp_widget_settings,
			} = siteSettings;

			setAttributes( { apiKey: yelp_widget_settings.yelp_widget_fusion_api } );
			setYelpConnected( true );
		}
	}, [siteSettings] );

	const userIsAdmin = useSelect( ( select ) => {
		return select( 'core' ).canUser( 'create', 'users' );
	}, [] );

	const testApiKey = ( apiKey ) => {
		// setApiKeyLoading( true );

		// Fetch REST API to test key.
		apiFetch( { path: `/yelp-block/v1/profile?apiKey=${apiKey}` } )
			.then( ( response ) => {
				// 🔑 👍 Key is good. Save it.
				dispatch( 'core' ).saveEntityRecord( 'root', 'site', {
					yelp_widget_settings: {
						yelp_widget_fusion_api: apiKey,
					},
				} ).then( () => {
					dispatch( 'core/notices' ).createErrorNotice( __( '🎉 Success! You have connected to the Yelp API.', 'yelp-block' ), {
						isDismissible: true,
						type: 'snackbar',
					} );
					// setAttributes( { apiKey: apiKey } );
					setYelpConnected( true );
					// setApiKeyLoading( false );
				} );
			} )
			.catch( ( error ) => {
				// 🔑 👎 Key is bad.
				const errorMessage = `${__( '🙈️ Yelp API Error:', 'blocks-for-github' )} ${error.message} ${__( 'Error Code:', 'blocks-for-github' )} ${error.code}`;
				dispatch( 'core/notices' ).createErrorNotice( errorMessage, {
					isDismissible: true,
					type: 'snackbar',
				} );
				setYelpApiKey( '' );
				// setApiKeyLoading( false );
			} );
	};

	return (
		<Fragment>
			<Fragment>
				<InspectorControls>
					{userIsAdmin && (
						<PanelBody title={__( 'Yelp Connection', 'yelp-block' )} initialOpen={false}>
							{! yelpConnected ? (
								<>
									<PanelRow>

										<TextControl
											label={__( 'Yelp Fusion API Key', 'yelp-block' )}
											value={yelpApiKey}
											type={'password'}
											help={
												<>
													{__( 'Please enter your API key to use this block. To create an API key please', 'yelp-block'
													)}{' '}
													<a
														href="https://www.yelp.com/developers/v3/manage_app"
														target="_blank"
														rel="noopener noreferrer"
													>
														{__(
															'click here',
															'yelp-block'
														)}
													</a>{'.'}
												</>
											}
											onChange={( newApiKey ) => {
												setYelpApiKey( newApiKey );
											}}
										/>
									</PanelRow>
									<PanelRow className={'yelp-block-button-row'}>
										<Button
											isSecondary
											isBusy={apiKeyLoading}
											onClick={() => testApiKey( yelpApiKey )}
										>
											{__( 'Save API Key', 'yelp-block' )}
										</Button>
										<div className="jw-text-center">
											{apiKeyLoading && <Spinner/>}
										</div>
									</PanelRow>
								</>
							) : (
								<>
									<PanelRow>
										<Button
											isSecondary
											onClick={() => setYelpConnected(false)}
										>
											{__( 'Reset API Key', 'yelp-block' )}
										</Button>
									</PanelRow>
								</>
							)}
						</PanelBody>
					)}
				</InspectorControls>
			</Fragment>
			<Fragment>
				<div {...useBlockProps()}>
					{!yelpConnected && (
						<p>NO API KEY!</p>
					)}
					{yelpConnected && (
						<p>Search for Yelp Business</p>
					)}
				</div>
			</Fragment>
		</Fragment>
	);

}
