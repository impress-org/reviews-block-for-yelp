import { __ } from '@wordpress/i18n';
import {
    PanelBody,
    PanelRow,
    TextControl,
    Button,
    Spinner,
    ToggleControl,
    CheckboxControl,
    Icon,
    ResponsiveWrapper,
} from '@wordpress/components';
import { Fragment, useState, useEffect } from '@wordpress/element';
import { InspectorControls, MediaUploadCheck, useBlockProps, MediaUpload } from '@wordpress/block-editor';
import { dispatch, useSelect } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';

import './editor.scss';
import BusinessLookup from './components/BusinessLookup';
import YelpBlock from './components/YelpBlock';
import runLottieAnimation from './helperFunctions/runLottieAnimation';

import YelpLogo from './images/yelp_logo.svg';
import PreviewImage from './images/block-preview.png';

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
        mediaId,
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

    const [yelpApiKey, setYelpApiKey] = useState( '' );
    const [apiKeyLoading, setApiKeyLoading] = useState( false );
    const [yelpConnected, setYelpConnected] = useState( null );

    // Preview image when an admin hovers over the block in block insert panel.
    if ( preview ) {
        return (
            <Fragment>
                <img src={PreviewImage} alt={__( 'Reviews Block for Yelp', 'yelp-widget-pro' )}
                     style={{ maxWidth: '100%' }} />
            </Fragment>
        );
    }

    const removeMedia = () => {
        setAttributes( {
            mediaId: 0,
            mediaUrl: '',
        } );
    };

    const onSelectMedia = ( media ) => {
        setAttributes( {
            mediaId: media.id,
            mediaUrl: media.url,
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
            const { yelp_widget_settings } = siteSettings;

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
                // 🔑 👍 Key is good. Save it.
                dispatch( 'core' )
                    .saveEntityRecord( 'root', 'site', {
                        yelp_widget_settings: {
                            yelp_widget_fusion_api: apiKey,
                        },
                    } )
                    .then( () => {
                        dispatch( 'core/notices' ).createErrorNotice(
                            __( '🎉 Success! You have connected to the Yelp API.', 'yelp-widget-pro' ),
                            {
                                isDismissible: true,
                                type: 'snackbar',
                            },
                        );
                        // setAttributes( { apiKey: apiKey } );
                        setYelpConnected( true );
                    } );
            } )
            .catch( ( error ) => {
                // 🔑 👎 Key is bad.
                const errorMessage = `${__( '🙈️ Yelp API Error:', 'yelp-widget-pro' )} ${error.message} ${__(
                    'Error Code:',
                    'yelp-widget-pro',
                )} ${error.code}`;
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
    }, [yelpConnected, businessId] );

    return (
        <Fragment>
            <Fragment>
                <InspectorControls>
                    {userIsAdmin && (
                        <Fragment>
                            {yelpConnected && businessId && (
                                <PanelBody title={__( 'Appearance Settings', 'yelp-widget-pro' )}>
                                    <>
                                        <PanelRow>
                                            <ToggleControl
                                                label={__( 'Display Header', 'donation-form-block' )}
                                                help={__(
                                                    'Do you want to display the business name, overall rating, images, price point, more?',
                                                    'donation-form-block',
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
                                                    <div className='rbg-media-uploader'>
                                                        <p className={'rbg-label'}>
                                                            <label>{__( 'Header Image', 'blocks-for-github' )}</label>
                                                        </p>
                                                        <MediaUploadCheck>
                                                            <MediaUpload
                                                                onSelect={onSelectMedia}
                                                                value={attributes.mediaId}
                                                                allowedTypes={['image']}
                                                                render={( { open } ) => (
                                                                    <Button
                                                                        className={attributes.mediaId === 0 ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview'}
                                                                        onClick={open}
                                                                    >
                                                                        {attributes.mediaId === 0 && __( 'Choose an image', 'blocks-for-github' )}
                                                                        {media !== undefined &&
                                                                            <ResponsiveWrapper
                                                                                naturalWidth={media.media_details.width}
                                                                                naturalHeight={media.media_details.height}
                                                                            >
                                                                                <img src={media.source_url} />
                                                                            </ResponsiveWrapper>
                                                                        }
                                                                    </Button>
                                                                )}
                                                            />
                                                        </MediaUploadCheck>
                                                        <div className='rbg-media-btns'>
                                                            {attributes.mediaId !== 0 &&
                                                                <MediaUploadCheck>
                                                                    <MediaUpload
                                                                        title={__( 'Replace Image', 'blocks-for-github' )}
                                                                        value={attributes.mediaId}
                                                                        onSelect={onSelectMedia}
                                                                        allowedTypes={['image']}
                                                                        render={( { open } ) => (
                                                                            <Button onClick={open} isSmall
                                                                                    variant='secondary'
                                                                                    className={'rbg-replace-image-btn'}>{__( 'Replace Image', 'blocks-for-github' )}</Button>
                                                                        )}
                                                                    />
                                                                </MediaUploadCheck>
                                                            }
                                                            {attributes.mediaId !== 0 &&
                                                                <MediaUploadCheck>
                                                                    <Button onClick={removeMedia} isSmall
                                                                            variant='secondary'>{__( 'Remove Image', 'blocks-for-github' )}</Button>
                                                                </MediaUploadCheck>
                                                            }
                                                        </div>
                                                        <p className={'rbg-Zhelp-text'}>{__( 'Upload or select an image for the header background.', 'blocks-for-github' )}</p>
                                                    </div>
                                                </PanelRow>
                                                <PanelRow>
                                                    <CheckboxControl
                                                        label={__( 'Display Business Rating', 'donation-form-block' )}
                                                        help={__(
                                                            'Check to display the overall business rating.',
                                                            'donation-form-block',
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
                                                            'donation-form-block',
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
                                                            'donation-form-block',
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
                                                    'donation-form-block',
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
                                                            'donation-form-block',
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
                                                            'donation-form-block',
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
                                                            'donation-form-block',
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
                                                    'donation-form-block',
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
                            <PanelBody title={__( 'Yelp Connection', 'yelp-widget-pro' )} initialOpen={false}>
                                {!yelpConnected ? (
                                    <>
                                        <PanelRow>
                                            <TextControl
                                                label={__( 'Yelp Fusion API Key', 'yelp-widget-pro' )}
                                                value={yelpApiKey}
                                                type={'password'}
                                                help={
                                                    <>
                                                        {__(
                                                            'Please enter your API key to use this block. To create an API key please',
                                                            'yelp-widget-pro',
                                                        )}{' '}
                                                        <a
                                                            href='https://www.yelp.com/developers/v3/manage_app'
                                                            target='_blank'
                                                            rel='noopener noreferrer'
                                                        >
                                                            {__( 'click here', 'yelp-widget-pro' )}
                                                        </a>
                                                        {'.'}
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
                                                {__( 'Save API Key', 'yelp-widget-pro' )}
                                            </Button>
                                            <div className='jw-text-center'>{apiKeyLoading && <Spinner />}</div>
                                        </PanelRow>
                                    </>
                                ) : (
                                    <>
                                        {businessId && (
                                            <PanelRow>
                                                <div className={'rby-change-business'}>
                                                    <div className={'rby-label-wrap'}>
                                                        <label>
                                                            <Icon icon={'warning'} />{' '}
                                                            {__( 'Change Business', 'donation-form-block' )}
                                                        </label>
                                                    </div>
                                                    <p className={'rby-field-description'}>
                                                        {__(
                                                            'Do you want to update the business displayed for this block?',
                                                            'yelp-widget-pro',
                                                        )}
                                                    </p>
                                                    <Button
                                                        isSecondary
                                                        onClick={() => setAttributes( { businessId: '' } )}
                                                    >
                                                        {__( 'Reset Business', 'yelp-widget-pro' )}
                                                    </Button>
                                                </div>
                                            </PanelRow>
                                        )}
                                        <PanelRow>
                                            <div className={'rby-change-business'}>
                                                <div className={'rby-label-wrap'}>
                                                    <label>
                                                        <Icon
                                                            icon={'warning'} /> {__( 'API Key', 'donation-form-block' )}
                                                    </label>
                                                </div>
                                                <p className={'rby-field-description'}>
                                                    {__(
                                                        'Do you want to update the API key used for all Review blocks?',
                                                        'yelp-widget-pro',
                                                    )}
                                                </p>
                                                <Button
                                                    isSecondary
                                                    onClick={() => {
                                                        setYelpApiKey( null );
                                                        setYelpConnected( null );
                                                    }}
                                                >
                                                    {__( 'Reset API Key', 'yelp-widget-pro' )}
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
                            <div className='rby-admin-welcome-content-wrap'>
                                <img className={'rby-admin-yelp-logo'} src={YelpLogo} alt={'Yelp Logo'} />
                                <div id={'yelp-block-admin-lottie-api'}></div>
                                <h2 className={'rby-admin-yelp-welcome-heading'}>
                                    {__( 'Welcome to the Reviews Block for Yelp! Let’s get started.', 'yelp-widget-pro' )}
                                </h2>
                                <p className={'rby-admin-yelp-welcome-text'}>
                                    {__(
                                        'This plugin requires a Yelp Fusion API key to get started. Don’t worry! It’s easy to get one. All you need is a Yelp account and to request one.',
                                        'yelp-widget-pro',
                                    )}
                                </p>
                                <TextControl
                                    value={yelpApiKey}
                                    type={'password'}
                                    help={
                                        <>
                                            {__(
                                                'Please enter your API key to use this block. To create an API key please',
                                                'yelp-widget-pro',
                                            )}{' '}
                                            <a
                                                href='https://www.yelp.com/developers/v3/manage_app'
                                                target='_blank'
                                                rel='noopener noreferrer'
                                            >
                                                {__( 'click here', 'yelp-widget-pro' )}
                                            </a>
                                            {'.'}
                                        </>
                                    }
                                    onChange={( newApiKey ) => {
                                        setYelpApiKey( newApiKey );
                                    }}
                                />
                                <Button className={'rby-admin-button'} isPrimary
                                        onClick={() => testApiKey( yelpApiKey )}>
                                    {__( 'Save API Key', 'yelp-widget-pro' )}
                                </Button>
                            </div>
                        </div>
                    )}
                    {yelpConnected && !businessId && (
                        <div id={'rby-admin-business-lookup-wrap'}>
                            <div className='rby-admin-business-lookup'>
                                <div className={'rby-admin-business-lookup-content-wrap'}>
                                    <img className={'rby-admin-yelp-logo'} src={YelpLogo} alt={'Yelp Logo'} />
                                    <div id={'yelp-block-admin-lottie-search'}></div>
                                    <h2 className={'rby-admin-yelp-welcome-heading'}>
                                        {__( 'Let’s find the business you’re looking for on Yelp!', 'yelp-widget-pro' )}
                                    </h2>
                                    <p className={'rby-admin-yelp-welcome-text'}>
                                        {__(
                                            'Use the fields below to lookup the business you\'d like to display on Yelp.',
                                            'yelp-widget-pro',
                                        )}
                                    </p>
                                </div>
                                <BusinessLookup setAttributes={setAttributes} businessId={businessId} />
                            </div>
                        </div>
                    )}
                    {yelpConnected && businessId && <YelpBlock attributes={attributes} />}
                </div>
            </Fragment>
        </Fragment>
    );
}
