import { __ } from '@wordpress/i18n';
import {
	TextControl,
	Button,
	Spinner,
} from '@wordpress/components';
import { Fragment, useState } from '@wordpress/element'
import { dispatch } from "@wordpress/data";
import apiFetch from '@wordpress/api-fetch';
import BusinessResultsModal from "../BusinessResultsModal";
import styles from './styles.module.scss';

const BusinessLookup = ({setAttributes}) => {

	const [businessName, setBusinessName] = useState( '' );
	const [businessLocation, setBusinessLocation] = useState( '' );
	const [searchResults, setSearchResults] = useState('');
	const [resultsModalOpen, setResultsModalOpen] = useState(false);

	const handleSubmit = (businessName, businessLocation) => {

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
		<div className={styles.searchFieldsWrap}>
			<TextControl
				label={__( 'Business Name', 'yelp-block' )}
				value={businessName}
				help={__( 'Enter the name of your business as it appears on Yelp.', 'yelp-block' )}
				onChange={( newBusinessName ) => {
					setBusinessName( newBusinessName );
				}}
			/>
			<TextControl
				label={__( 'Business Location', 'yelp-block' )}
				value={businessLocation}
				help={__( 'Enter the name of your business as it appears on Yelp.', 'yelp-block' )}
				onChange={( newBusinessLocation ) => {
					setBusinessLocation( newBusinessLocation );
				}}
			/>
			<Button
				isPrimary
				onClick={() => handleSubmit( businessName, businessLocation )}
			>
				{__( 'Lookup Business', 'yelp-block' )}
			</Button>

			{resultsModalOpen && (
				<BusinessResultsModal
					setAttributes={setAttributes}
					onRequestClose={() => setResultsModalOpen(false)}
					businessResults={JSON.parse(searchResults)}
				/>
			)}
		</div>
	);
}

export default BusinessLookup;
