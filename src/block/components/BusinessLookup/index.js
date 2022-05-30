import { __ } from '@wordpress/i18n';
import {
    TextControl,
    Button,
    Spinner,
} from '@wordpress/components';
import { useState } from '@wordpress/element'
import { dispatch } from "@wordpress/data";
import apiFetch from '@wordpress/api-fetch';
import BusinessResultsModal from "../BusinessResultsModal";

const BusinessLookup = ( { setAttributes } ) => {

    const [businessName, setBusinessName] = useState( '' );
    const [businessLocation, setBusinessLocation] = useState( '' );
    const [searchResults, setSearchResults] = useState( '' );
    const [resultsModalOpen, setResultsModalOpen] = useState( false );

    const handleSubmit = async (e) => {
        e.preventDefault();

        apiFetch( { path: `/yelp-block/v1/profile?term=${businessName}&location=${businessLocation}` } )
            .then( ( response ) => {
                setSearchResults( response );
                setResultsModalOpen( true );
            } )
            .catch( ( error ) => {
                // 🔑 👎 Key is bad.
                const errorMessage = `${__( '🙈️ Yelp API Error:', 'blocks-for-github' )} ${error.message} ${__( 'Error Code:', 'blocks-for-github' )} ${error.code}`;
                dispatch( 'core/notices' ).createErrorNotice( errorMessage, {
                    isDismissible: true,
                    type: 'snackbar',
                } );
            } );
    }

    return (
        <div className={'rby-admin-search-fields-wrap'}>
            <form onSubmit={handleSubmit}>
                <TextControl
                    className={'rby-admin-field rby-admin-field--business-name'}
                    placeholder={__( 'Business Name', 'yelp-widget-pro' )}
                    value={businessName}
                    help={__( 'Enter the name of the business as it appears on Yelp. You can also use search terms like "Shoes, Mexican Restaurants, etc".', 'yelp-widget-pro' )}
                    required
                    onChange={( newBusinessName ) => {
                        setBusinessName( newBusinessName );
                    }}
                />
                <TextControl
                    className={'rby-admin-field rby-admin-field--business-location'}
                    placeholder={__( 'Business Location', 'yelp-widget-pro' )}
                    value={businessLocation}
                    help={__( 'Enter the location of the business. You can use City, State, and Country to help find businesses.', 'yelp-widget-pro' )}
                    required
                    onChange={( newBusinessLocation ) => {
                        setBusinessLocation( newBusinessLocation );
                    }}
                />
                <Button
                    className={'rby-admin-button'}
                    isPrimary
                    type={'submit'}
                >
                    {__( 'Lookup Business', 'yelp-widget-pro' )}
                </Button>
            </form>
            {resultsModalOpen && (
                <BusinessResultsModal
                    setAttributes={setAttributes}
                    onRequestClose={() => setResultsModalOpen( false )}
                    businessResults={JSON.parse( searchResults )}
                />
            )}
        </div>
    );
}

export default BusinessLookup;
