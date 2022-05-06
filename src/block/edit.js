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
import ServerSideRender from '@wordpress/server-side-render';
import { dispatch, useSelect } from '@wordpress/data';
import axios from 'axios';
import apiFetch from '@wordpress/api-fetch';

import './editor.scss';

/**
 * Main edit component.
 *
 * @param attributes
 * @param setAttributes
 * @returns {JSX.Element}
 * @constructor
 */
export default function Edit( { attributes, setAttributes } ) {

	const {
		apiKeyState,
		apiKeyValid,
		preview,
	} = attributes;

	const [apiKeyLoading, setApiKeyLoading] = useState( false );

	const siteSettings = useSelect( ( select ) => {
		return select( 'core' ).getEntityRecord( 'root', 'site' );
	}, [] );

	useEffect( () => {
		if ( siteSettings ) {
			const {
				yelp_block_api_key: apiKeyState,
			} = siteSettings;
			setAttributes( { apiKeyState: apiKeyState } );
		}
	}, [siteSettings] );

	const testApiKey = () => {
		setApiKeyLoading( true );

		// Save entered key.
		dispatch( 'core' ).saveEntityRecord( 'root', 'site', {
			yelp_block_api_key: apiKeyState,
		} ).then( () => {

			// Fetch REST API to test key.
			apiFetch( { path: `/yelp-block/v1/profile` } )
				.then( ( response ) => {
					dispatch( 'core/notices' ).createErrorNotice( __( '🎉 Success! You have connected to the Yelp API.', 'yelp-block' ), {
						isDismissible: true,
						type: 'snackbar',
					} );
					setAttributes( { apiKeyState: apiKeyState, apiKeyValid: true } );
					setApiKeyLoading( false );
				} )
				.catch( ( error ) => {
					const errorMessage = `${__( '🙈️ Yelp API Error:', 'blocks-for-github' )} ${error.message} ${__( 'Error Code:', 'blocks-for-github' )} ${error.code}`;
					dispatch( 'core/notices' ).createErrorNotice( errorMessage, {
						isDismissible: true,
						type: 'snackbar',
					} );
					setAttributes( { apiKeyState: '', apiKeyValid: false } );
					setApiKeyLoading( false );
				} );
		} );
	};

	return (
		<Fragment>
			<Fragment>
				<InspectorControls>
					<PanelBody title={__( 'Yelp API Setting', 'yelp-block' )} initialOpen={false}>
						<PanelRow>
							<TextControl
								label={__( 'API Key', 'yelp-block' )}
								value={apiKeyState}
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
									setAttributes( { apiKeyState: newApiKey } );
								}}
							/>
						</PanelRow>
						<PanelRow className={'yelp-block-button-row'}>
							<Button
								isSecondary
								isBusy={apiKeyLoading}
								onClick={() => testApiKey( apiKeyState )}
							>
								{__( 'Save API Key', 'yelp-block' )}
							</Button>
							<div className="jw-text-center">
								{apiKeyLoading && <Spinner/>}
							</div>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
			</Fragment>
			<Fragment>
				<div {...useBlockProps()}>
					<ServerSideRender
						block="yelp-block/profile"
						attributes={{
							apiKeyState: attributes.apiKeyState,
						}}
					/>
				</div>
			</Fragment>
		</Fragment>
	);

}
