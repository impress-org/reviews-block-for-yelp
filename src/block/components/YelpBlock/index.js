import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { dispatch } from '@wordpress/data';
import apiFetch from '@wordpress/api-fetch';
import StarRating from '../StarRating';
import Address from '../Address';
import OpenHours from '../OpenHours';
import IconClaimed from '../../images/check-circle.svg';
import IconStarOutline from '../../images/star-outline.svg';
import IconYelp from '../../images/yelp-icon.svg';
import YelpLogo from '../../images/yelp_logo.svg';

import Review from '../Review';
import { Icon, Spinner } from '@wordpress/components';

const YelpBlock = ( props ) => {
    const [isLoading, setIsLoading] = useState( true );
    const [businessData, setBusinessData] = useState( null );

    useEffect( () => {
        if ( props.attributes.businessId ) {
            // ⚙️ Fetch REST API to get Yelp data.
            apiFetch( { path: `/yelp-block/v1/profile?businessId=${props.attributes.businessId}` } )
                .then( ( response ) => {
                    setBusinessData( response );
                    setIsLoading( false );
                } )
                .catch( ( error ) => {
                    console.log( error );
                    const errorMessage = `${__( '🙈️ Yelp API Error:', 'yelp-widget-pro' )} ${error.message} ${__(
                        'Error Code:',
                        'yelp-widget-pro',
                    )} ${error.code}`;
                    dispatch( 'core/notices' ).createErrorNotice( errorMessage, {
                        isDismissible: true,
                        type: 'snackbar',
                    } );
                } );
        }
    }, [props.attributes.businessId] );

    if ( isLoading ) {
        return (
            <div id={`rby-wrap`} className={`rby-wrap`}>
                <div className={'rby-loading-content'}>
                    <img src={YelpLogo} alt={__( 'Loading Yelp Business Data', 'yelp-widget-pro' )} />
                    <div className={'rby-loading-text'}>
                        <Spinner />
                        {__( 'Loading Yelp Business Data', 'yelp-widget-pro' )}
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div id={`rby-wrap`} className={`rby-wrap`}>
            {props.attributes.showHeader && (
                <>
                    <div className={'rby-yelp-icon-header'}>
                        <img src={IconYelp} alt={__( 'Yelp', 'yelp-widget-pro' )} />
                    </div>
                    {props.attributes.mediaUrl && (
                        <div className={`rby-image-header rby-image-header__custom`}
                             style={{ backgroundImage: `url(${props.attributes.mediaUrl})` }} />
                    )}
                    {!props.attributes.mediaUrl && (
                        <div className={`rby-image-header`}>
                            {businessData.photos && (
                                <>
                                    <img src={businessData.photos[0]} alt='Yelp Business' />
                                    <img src={businessData.photos[1]} alt='Yelp Business' />
                                    <img src={businessData.photos[2]} alt='Yelp Business' />
                                </>
                            )}
                        </div>
                    )}

                    <div className={`rby-title-header`}>
                        <div className={`rby-business-name-wrap`}>
                            <h3 className={`rby-business-name`}>{businessData.name}</h3>
                        </div>

                        <div className={'rby-business-meta-wrap'}>
                            {props.attributes.showBusinessRating && (
                                <StarRating
                                    overallRating={businessData.rating}
                                    totalRatings={businessData.total}
                                />
                            )}
                            {props.attributes.showBusinessMeta && (
                                <div className={'rby-business-status-meta-wrap'}>
                                    <div className={'rby-business-status-meta-wrap__inner'}>
                                        {businessData.is_claimed && (
                                            <div className={'rby-business-claimed'}>
                                                <img
                                                    src={IconClaimed}
                                                    alt={__( 'Claimed', 'yelp-widget-pro' )}
                                                    className={'rby-business-claimed__icon'}
                                                />
                                                <span className={'rby-business-claimed__text'}>
                                                            {__( 'Claimed', 'yelp-widget-pro' )}
                                                        </span>
                                            </div>
                                        )}
                                        <span className={'rby-business-price'}>{businessData.price}</span>

                                        {businessData.hours[0].is_open_now && (
                                            <span
                                                className={
                                                    'rby-business-open-status rby-business-open-status__open'
                                                }
                                            >
                                                        {__( 'Open', 'yelp-widget-pro' )}
                                                    </span>
                                        )}
                                        {!businessData.hours[0].is_open_now && (
                                            <span
                                                className={
                                                    'rby-business-open-status rby-business-open-status__closed'
                                                }
                                            >
                                                        {__( 'Closed', 'yelp-widget-pro' )}
                                                    </span>
                                        )}
                                    </div>
                                </div>
                            )}
                            {props.attributes.showReviewButton && (
                                <div className={`rby-button-wrap`}>
                                    <a
                                        href={`https://www.yelp.com/writeareview/biz/${businessData.id}`}
                                        target={'_blank'}
                                        className={`rby-button rby-button--red rby-button--link`}
                                    >
                                        <img
                                            src={IconStarOutline}
                                            alt={__( 'Write a Review', 'yelp-widget-pro' )}
                                            className={'rby-button__icon'}
                                        />
                                        {__( 'Write a Review', 'yelp-widget-pro' )}
                                    </a>
                                </div>
                            )}
                        </div>
                    </div>
                </>
            )}
            {props.attributes.showBusinessInfo && (
                <div className={'rby-additional-info-wrap'}>
                    {props.attributes.showPhone && (
                        <div className={'rby-additional-info-wrap__inner'}>
                            <h4 className={'rby-heading'}>{__( 'Phone and More', 'yelp-widget-pro' )}</h4>
                            <div className={'rby-business-phone-wrap'}>
                                <Icon icon={'phone'} size={16} />
                                <a href={`tel:${businessData.phone}`} title={`Call ${businessData.name}`}>
                                    {businessData.display_phone}
                                </a>
                            </div>
                            <div className={'rby-business-badges-wrap'}>
                                {businessData.categories &&
                                    businessData.categories.map( ( category, index ) => (
                                        <span key={index} className={'rby-badge'}>
                                                    {category.title}
                                                </span>
                                    ) )}
                                {businessData.transactions &&
                                    businessData.transactions.map( ( category, index ) => (
                                        <span key={index} className={'rby-badge'}>
                                                    {category}
                                                </span>
                                    ) )}
                            </div>
                        </div>
                    )}
                    {props.attributes.showHours && (
                        <div className={'rby-additional-info-wrap__inner'}>
                            <h4 className={'rby-heading'}>{__( 'Hours', 'yelp-widget-pro' )}</h4>
                            <OpenHours hours={businessData.hours[0].open} />
                        </div>
                    )}
                    {props.attributes.showLocation && (
                        <div className={'rby-additional-info-wrap__inner'}>
                            <h4 className={'rby-heading'}>{__( 'Location', 'yelp-widget-pro' )}</h4>

                            <Address
                                displayAddress={businessData.location.display_address}
                                alias={businessData.alias}
                            />
                            <div className={'rby-directions-link-wrap'}>
                                <a
                                    href={`https://www.yelp.com/map/${businessData.alias}`}
                                    target={'_blank'}
                                    className={`rby-button rby-button--white rby-button--link`}
                                >
                                    {__( 'Get Directions', 'yelp-widget-pro' )}
                                </a>
                            </div>
                        </div>
                    )}
                </div>
            )}
            {props.attributes.showReviews && (
                <div className={'rby-business-reviews-wrap'}>
                    <h3 className={'rby-heading'}>{__( 'Highlighted Reviews', 'yelp-widget-pro' )}</h3>
                    {businessData.reviews.map( ( review, index ) => {
                        return <Review key={index} review={review} />;
                    } )}
                </div>
            )}
            <div className={'rby-powered-by-wrap'}>
                <div className={'rby-powered-by'}>
                    <span>{__( 'Powered by', 'yelp-widget-pro' )}</span>
                    <img src={YelpLogo} alt={__( 'Powered by Yelp', 'yelp-widget-pro' )} />
                </div>
            </div>
        </div>
    );
};

YelpBlock.defaultProps = {
    attributes: [],
};
export default YelpBlock;
