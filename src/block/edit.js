import { __ } from '@wordpress/i18n';
import {
    PanelBody,
    PanelRow,
    TextControl,
    Button,
    Spinner,
    CheckboxControl,
    ResponsiveWrapper, ToggleControl, ExternalLink,
} from '@wordpress/components';
import { Fragment, useState, useEffect } from '@wordpress/element';
import { InspectorControls, MediaUpload, useBlockProps, MediaUploadCheck } from '@wordpress/block-editor';
import { dispatch, useSelect } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

import './editor.scss';
import BusinessLookup from './components/BusinessLookup';
import YelpBlock from './components/YelpBlock';
import runLottieAnimation from './helperFunctions/runLottieAnimation';

import YelpLogo from './images/yelp_logo.svg';

/**
 * Edit function.
 *
 *  @see https://developer.wordpress.org/block-editor/developers/block-api/block-edit-save/#edit
 *
 * @return {WPElement} Element to render.
 */
export default function Edit( { attributes, setAttributes } ) {

    const {
        businessId,
        showBusinessRating,
        preview,
    } = attributes;

    const [yelpApiKey, setYelpApiKey] = useState( false );
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

            if ( yelp_widget_settings.yelp_widget_fusion_api ) {
                setYelpApiKey( true );
                setYelpConnected( true );
            }
        }
    }, [siteSettings] );

    const userIsAdmin = useSelect( ( select ) => {
        return select( 'core' ).canUser( 'create', 'users' );
    }, [] );

    const testApiKey = ( apiKey ) => {

        // Fetch REST API to test key.
        apiFetch( { path: `/yelp-block/v1/profile?apiKey=${apiKey}&keyValidation=true` } )
            .then( ( response ) => {
                console.log( response );

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
            } );
    };

    // Run Lotties.
    useEffect( () => {
        // business search screen
        if ( yelpConnected && !businessId ) {
            runLottieAnimation( 'search', 'yelp-block-admin-lottie-search' );
        }
        if ( !yelpConnected && !businessId ) {
            runLottieAnimation( 'twinkle-stars', 'yelp-block-admin-lottie-api' );
        }
    }, [yelpConnected] )

    return (
        <Fragment>
            <Fragment>
                <InspectorControls>
                    {userIsAdmin && (
                        <Fragment>
                            {yelpConnected && businessId && (
                                <PanelBody title={__( 'Appearance Settings', 'yelp-block' )}>
                                    <>
                                        <PanelRow>
                                            <ToggleControl
                                                label={__( 'Display Business Rating', 'donation-form-block' )}
                                                help={
                                                    <>
                                                        {__(
                                                            'This option allows donors to give using their saved payment methods with Stripe Link. Currently, this option is only available for US donors.',
                                                            'donation-form-block'
                                                        )}
                                                        <ExternalLink
                                                            href={'https://support.stripe.com/questions/link-faq'}>
                                                            {__( 'Link FAQ', 'donation-form-block' )}
                                                        </ExternalLink>
                                                    </>
                                                }
                                                className={'dfb-stripe-link-toggle'}
                                                checked={showBusinessRating}
                                                onChange={( value ) => {
                                                    setAttributes( { showBusinessRating: value } );
                                                }}
                                            />
                                        </PanelRow>
                                        <PanelRow>
                                            <Button
                                                isSecondary
                                                onClick={() => setAttributes( { businessId: '' } )}
                                            >
                                                {__( 'Reset Business', 'yelp-block' )}
                                            </Button>
                                        </PanelRow>
                                    </>

                                </PanelBody>
                            )}
                            <PanelBody title={__( 'Yelp Connection', 'yelp-block' )} initialOpen={false}>
                                {!yelpConnected ? (
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
                                                onClick={() => {
                                                    setYelpApiKey( null )
                                                    setYelpConnected( null )
                                                }}
                                            >
                                                {__( 'Reset API Key', 'yelp-block' )}
                                            </Button>
                                        </PanelRow>
                                    </>
                                )}
                            </PanelBody>
                        </Fragment>
                    )}
                </InspectorControls>
            </Fragment>
            <Fragment>
                <div {...useBlockProps()}>
                    {!yelpConnected && !businessId && (
                        <div id={'rby-admin-welcome-wrap'}>
                            <div className="rby-admin-welcome-content-wrap">
                                <img className={'rby-admin-yelp-logo'} src={YelpLogo} alt={'Yelp Logo'}/>
                                <div id={'yelp-block-admin-lottie-api'}></div>
                                <h2 className={'rby-admin-yelp-welcome-heading'}>{__( 'Welcome to the Review Block for Yelp! Let’s get started.', 'yelp-widget-pro' )}</h2>
                                <p className={'rby-admin-yelp-welcome-text'}>{__( 'This plugin requires a Yelp Fusion API key to get started. Don’t worry! It’s easy to get one. All you need is a Yelp account and to request one.', 'yelp-widget-pro' )}</p>
                                <TextControl
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
                                <Button
                                    className={'rby-admin-button'}
                                    isPrimary
                                    onClick={() => testApiKey( yelpApiKey )}
                                >
                                    {__( 'Save API Key', 'yelp-block' )}
                                </Button>
                            </div>
                        </div>
                    )}
                    {yelpConnected && !businessId && (
                        <div id={'rby-admin-business-lookup-wrap'}>
                            <div className="rby-admin-business-lookup">
                                <div className={'rby-admin-business-lookup-content-wrap'}>
                                    <img className={'rby-admin-yelp-logo'} src={YelpLogo} alt={'Yelp Logo'}/>
                                    <div id={'yelp-block-admin-lottie-search'}></div>
                                    <h2 className={'rby-admin-yelp-welcome-heading'}>{__( 'Let’s find the business you’re looking for on Yelp!', 'yelp-widget-pro' )}</h2>
                                    <p className={'rby-admin-yelp-welcome-text'}>{__( 'Use the fields below to lookup the business you\'d like to display on Yelp.', 'yelp-widget-pro' )}</p>
                                </div>
                                <BusinessLookup
                                    setAttributes={setAttributes}
                                    businessId={businessId}
                                />
                            </div>
                        </div>
                    )}
                    {yelpConnected && businessId && (
                        <YelpBlock
                            attributes={attributes}
                        />
                    )}
                </div>
            </Fragment>
        </Fragment>
    );

}
