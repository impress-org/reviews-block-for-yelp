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
		preview,
	} = attributes;

	// Preview image when an admin hovers over the block.
	if ( preview ) {
		return (
			<Fragment>
				<img src={bfgPreviews.profile_preview}/>
			</Fragment>
		);
	}

	const [apiKeyLoading, setApiKeyLoading] = useState( false );

	const removeMedia = () => {
		setAttributes( {
			mediaId: 0,
			mediaUrl: ''
		} );
	};

	const onSelectMedia = ( media ) => {
		setAttributes( {
			mediaId: media.id,
			mediaUrl: media.url
		} );
	};

	const media = useSelect( ( select ) => {
		return select( 'core' ).getMedia( mediaId );
	}, [onSelectMedia] );

	const siteSettings = useSelect( ( select ) => {
		return select( 'core' ).getEntityRecord( 'root', 'site' );
	}, [] );

	useEffect( () => {
		if ( siteSettings ) {
			const {
				blocks_for_github_plugin_personal_token: apiKeyState,
			} = siteSettings;
			setAttributes( { apiKeyState: apiKeyState } );
		}
	}, [siteSettings] );

	const testApiKey = () => {
		setApiKeyLoading( true );

		// Check the API key entered.
		axios.get( 'https://api.github.com/user', {
			headers: {
				Authorization: 'Bearer ' + apiKeyState
			}
		} ).then( ( response ) => {
			// Admin entered a good token 👍.
			// Save it and show a notice.
			dispatch( 'core' ).saveEntityRecord( 'root', 'site', {
				blocks_for_github_plugin_personal_token: apiKeyState,
			} )
				.then(
					( { blocks_for_github_plugin_personal_token: apiKeyState } ) => {
						dispatch( 'core/notices' ).createErrorNotice( __( '🎉 Success! You have connected to the GitHub API.', 'yelp-block' ), {
							isDismissible: true,
							type: 'snackbar',
						} );
						setAttributes( { apiKeyState: apiKeyState } );
						setApiKeyLoading( false );
					}
				)
				.catch( ( error ) => {
					dispatch( 'core/notices' ).createErrorNotice( error.message, {
						isDismissible: true,
						type: 'snackbar',
					} );
					setAttributes( { apiKeyState: null } );
					setApiKeyLoading( false );
				} );

		} )
			.catch( ( error ) => {
				// Nice error message format (very explanatory 📢).
				const errorMessage = `${__( '🙈️ GitHub API Error:', 'yelp-block' )} ${error.message} ${__( 'Error Code:', 'yelp-block' )} ${error.code}`;
				// Delete entered API key. 🙅
				dispatch( 'core' ).saveEntityRecord( 'root', 'site', {
					blocks_for_github_plugin_personal_token: null,
				} );
				// Show nice little toast notice if error occurs. 🥂
				dispatch( 'core/notices' ).createErrorNotice( errorMessage, {
					isDismissible: true,
					type: 'snackbar',
				} );
				setApiKeyLoading( false );
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
										{__( 'Please enter your Yelp API Key to use this block. To access or create a Yelp API key', 'yelp-block'
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
