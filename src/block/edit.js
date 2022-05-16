import { __ } from '@wordpress/i18n';
import {
    PanelBody,
    PanelRow,
    TextControl,
    Button,
    Spinner,
    ToggleControl,
    ExternalLink,
    CheckboxControl, Icon,
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
        showHeader,
        showBusinessRating,
        showReviewButton,
        showBusinessMeta,
        showBusinessInfo,
        showPhone,
        showHours,
        showLocation,
        showReviews,
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
    }, [yelpConnected, businessId] )

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
                                                label={__( 'Display Header', 'donation-form-block' )}
                                                help={__(
                                                    'Do you want to display the business name, overall rating, images, price point, more?',
                                                    'donation-form-block'
                                                )}
                                                className={'dfb-stripe-link-toggle'}
                                                checked={showHeader}
                                                onChange={( value ) => {
                                                    setAttributes( { showHeader: value } );
                                                }}
                                            />
                                        </PanelRow>
                                        {showHeader && (
                                            <div className={'rby-admin-subfields-wrap'}>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Business Rating', 'donation-form-block' )}
                                                        help={__(
                                                            'Check to display the overall business rating.',
                                                            'donation-form-block'
                                                        )}
                                                        className={'dfb-stripe-link-toggle'}
                                                        checked={showBusinessRating}
                                                        onChange={( value ) => {
                                                            setAttributes( { showBusinessRating: value } );
                                                        }}
                                                    />
                                                </PanelRow>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Review Button', 'donation-form-block' )}
                                                        help={__(
                                                            'Check to display the "Write a Review" button.',
                                                            'donation-form-block'
                                                        )}
                                                        className={'dfb-stripe-link-toggle'}
                                                        checked={showReviewButton}
                                                        onChange={( value ) => {
                                                            setAttributes( { showReviewButton: value } );
                                                        }}
                                                    />
                                                </PanelRow>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Meta', 'donation-form-block' )}
                                                        help={__(
                                                            'Check to display the business meta info like price point, open or closed, and price point.',
                                                            'donation-form-block'
                                                        )}
                                                        className={'dfb-stripe-link-toggle'}
                                                        checked={showBusinessMeta}
                                                        onChange={( value ) => {
                                                            setAttributes( { showBusinessMeta: value } );
                                                        }}
                                                    />
                                                </PanelRow>
                                            </div>
                                        )}
                                        <PanelRow>
                                            <ToggleControl
                                                label={__( 'Display Business Info', 'donation-form-block' )}
                                                help={__(
                                                    'Toggle on to display the business info section containing hours, location, and more.',
                                                    'donation-form-block'
                                                )}
                                                className={'dfb-stripe-link-toggle'}
                                                checked={showBusinessInfo}
                                                onChange={( value ) => {
                                                    setAttributes( { showBusinessInfo: value } );
                                                }}
                                            />
                                        </PanelRow>
                                        {showBusinessInfo && (
                                            <div className={'rby-admin-subfields-wrap'}>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Phone', 'donation-form-block' )}
                                                        help={__(
                                                            'Toggle on to display the business phone number and additional business information.',
                                                            'donation-form-block'
                                                        )}
                                                        className={'dfb-stripe-link-toggle'}
                                                        checked={showPhone}
                                                        onChange={( value ) => {
                                                            setAttributes( { showPhone: value } );
                                                        }}
                                                    />
                                                </PanelRow>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Hours', 'donation-form-block' )}
                                                        help={__(
                                                            'Toggle on to display the business hours.',
                                                            'donation-form-block'
                                                        )}
                                                        className={'dfb-stripe-link-toggle'}
                                                        checked={showHours}
                                                        onChange={( value ) => {
                                                            setAttributes( { showHours: value } );
                                                        }}
                                                    />
                                                </PanelRow>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Location', 'donation-form-block' )}
                                                        help={__(
                                                            'Toggle on to display the business location.',
                                                            'donation-form-block'
                                                        )}
                                                        className={'dfb-stripe-link-toggle'}
                                                        checked={showLocation}
                                                        onChange={( value ) => {
                                                            setAttributes( { showLocation: value } );
                                                        }}
                                                    />
                                                </PanelRow>
                                            </div>
                                        )}
                                        <PanelRow>
                                            <ToggleControl
                                                label={__( 'Display Reviews', 'donation-form-block' )}
                                                help={__(
                                                    'Toggle on to display individual reviews (up to 3 total per locale).',
                                                    'donation-form-block'
                                                )}
                                                className={'dfb-stripe-link-toggle'}
                                                checked={showReviews}
                                                onChange={( value ) => {
                                                    setAttributes( { showReviews: value } );
                                                }}
                                            />
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
                                        {businessId && (
                                            <PanelRow>
                                                <div className={'rby-change-business'}>
                                                    <div className={'rby-label-wrap'}>
                                                        <label><Icon
                                                            icon={'warning'}/> {__( 'Change Business', 'donation-form-block' )}
                                                        </label>
                                                    </div>
                                                    <p className={'rby-field-description'}>{__( 'Do you want to update the business displayed for this block?', 'yelp-widget-pro' )}</p>
                                                    <Button
                                                        isSecondary
                                                        onClick={() => setAttributes( { businessId: '' } )}
                                                    >
                                                        {__( 'Reset Business', 'yelp-block' )}
                                                    </Button>
                                                </div>
                                            </PanelRow>
                                        )}
                                        <PanelRow>
                                            <div className={'rby-change-business'}>
                                                <div className={'rby-label-wrap'}>
                                                    <label><Icon
                                                        icon={'warning'}/> {__( 'API Key', 'donation-form-block' )}
                                                    </label>
                                                </div>
                                                <p className={'rby-field-description'}>{__( 'Do you want to update the API key used for all Review blocks?', 'yelp-widget-pro' )}</p>
                                                <Button
                                                    isSecondary
                                                    onClick={() => {
                                                        setYelpApiKey( null )
                                                        setYelpConnected( null )
                                                    }}
                                                >
                                                    {__( 'Reset API Key', 'yelp-block' )}
                                                </Button>
                                            </div>
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
