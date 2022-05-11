import { useEffect, useState } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import { dispatch } from "@wordpress/data";
import apiFetch from '@wordpress/api-fetch';
import styles from './styles.module.scss';

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
                    const errorMessage = `${__( '🙈️ Yelp API Error:', 'blocks-for-github' )} ${error.message} ${__( 'Error Code:', 'blocks-for-github' )} ${error.code}`;
                    dispatch( 'core/notices' ).createErrorNotice( errorMessage, {
                        isDismissible: true,
                        type: 'snackbar',
                    } );
                } );


        }
    }, [props.attributes.businessId] );

    return (
        <div className={`reviews-block-yelp ${styles.yelpBlockWrap}`}>
            {isLoading && (
                <div>Loading...</div>
            )}
            {!isLoading && (
                <>
                    <h3>{businessData.name}</h3>
                    {props.attributes.showBusinessRating && (
                        <p>{businessData.rating}</p>
                    )}
                </>
            )}


        </div>
    );

}


YelpBlock.defaultProps = {
    attributes: [],
};
export default YelpBlock;
