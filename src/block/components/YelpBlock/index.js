import { useEffect, useState } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { dispatch } from "@wordpress/data";
import apiFetch from '@wordpress/api-fetch';
import StarRating from "../StarRating";
import Address from "../Address";
import OpenHours from "../OpenHours";

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
                    const errorMessage = `${__( '🙈️ Yelp API Error:', 'yelp-widget-pro' )} ${error.message} ${__( 'Error Code:', 'yelp-widget-pro' )} ${error.code}`;
                    dispatch( 'core/notices' ).createErrorNotice( errorMessage, {
                        isDismissible: true,
                        type: 'snackbar',
                    } );
                } );


        }
    }, [props.attributes.businessId] );


    return (
        <div id={`rby-wrap`} className={`rby-wrap`}>
            {isLoading && (
                <div>Loading...</div>
            )}
            {!isLoading && (
                <>
                    <div className={`rby-image-header`}>
                        {businessData.photos && (
                            <>
                                <img src={businessData.photos[0]} alt="Yelp Business"/>
                                <img src={businessData.photos[1]} alt="Yelp Business"/>
                                <img src={businessData.photos[2]} alt="Yelp Business"/>
                            </>
                        )}
                    </div>

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
                            <div className={'rby-business-status-meta-wrap'}>
                                <div className={'rby-business-status-meta-wrap__inner'}>
                                    {businessData.is_claimed && (
                                        <span
                                            className={'rby-badge rby-business-claimed'}>{__( 'Claimed', 'yelp-widget-pro' )}</span>
                                    )}
                                    <span className={'rby-business-price'}>{businessData.price}</span>

                                    {!businessData.is_closed && (
                                        <span
                                            className={'rby-business-open-closed'}>{__( 'Open', 'yelp-widget-pro' )}</span>
                                    )}
                                </div>
                            </div>

                            <div className={`rby-button-wrap`}>
                                <a href={`https://www.yelp.com/writeareview/biz/${businessData.id}`}
                                   target={'_blank'}
                                   className={`rby-button rby-button--red rby-button--link`}>{__( 'Write a Review', 'yelp-widget-pro' )}</a>
                            </div>

                        </div>

                    </div>

                    <div className={'rby-additional-info-wrap'}>
                        <div className={'rby-additional-info-wrap__inner'}>
                            <h4>{__( 'Phone and More', 'yelp-widget-pro' )}</h4>
                            <p>{businessData.display_phone}</p>
                        </div>
                        <div className={'rby-additional-info-wrap__inner'}>
                            <h4>{__( 'Hours', 'yelp-widget-pro' )}</h4>
                            <OpenHours
                                hours={businessData.hours[0].open}
                            />
                        </div>
                        <div className={'rby-additional-info-wrap__inner'}>
                            <h4>{__( 'Location', 'yelp-widget-pro' )}</h4>

                            <Address
                                displayAddress={businessData.location.display_address}
                                alias={businessData.alias}
                            />
                            <div className={'rby-directions-link-wrap'}>
                                <a href={`https://www.yelp.com/map/${businessData.alias}`}
                                   target={'_blank'}
                                   className={`rby-button rby-button--white rby-button--link`}>{__( 'Get Directions', 'yelp-widget-pro' )}</a>
                            </div>

                        </div>
                    </div>

                </>
            )}


        </div>
    );

}


YelpBlock.defaultProps = {
    attributes: [],
};
export default YelpBlock;
