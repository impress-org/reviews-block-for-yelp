import { Modal, Button, Icon } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useRef, useState } from '@wordpress/element';
import { dispatch } from "@wordpress/data";
import styles from './styles.module.scss';

export default function BusinessResultsModal( { setAttributes, onRequestClose, businessResults } ) {
	const [error, setError] = useState( false );


	const handleSubmit = ( business ) => {


		// Update the businessId attribute
		setAttributes({ businessId: business.id });

		// Get business details from Yelp via WP REST API.


		// Show status update
		// wait for the API to return
		// if the API returns an error, set the error state to true


		// Close modal
		onRequestClose();

	};

	return (
		<Modal
			onRequestClose={onRequestClose}
			title={__( 'Yelp Business Search Results', 'donation-form-block' )}
			className={'yelp-business-results-modal'}
		>
			<div className={styles.modalWrap}>
				{businessResults.map( ( business, index ) => {

					return (
						<div key={index}>
							<div className="">
								<h3>
									<a href={business.url} title={business.name} target={'_blank'}>{business.name}</a>
								</h3>
								<div className="business-address">
									<p>
										<span>{business.rating}</span>
										<span>{business.review_count}</span>
									</p>
								</div>

								{business.transactions.map( ( transaction, index ) => {

									return (
										<div key={index} className="business-transactions">
											<p>{transaction}</p>
										</div>
									)

								} )}

								<p>{business.location.display_address}</p>
								<p>{business.phone}</p>
								<img src={business.image_url} alt={business.name}/>
								<p>{business.phone}</p>
								<p><a href={business.url} title={__( 'Visit the Website', 'yelp-widget-pro' )}
									  target={'_blank'}>{business.name}</a></p>
							</div>
							<Button
								isSecondary
								onClick={() => handleSubmit( business )}
							>
								{__( 'Select', 'yelp-widget-pro' )}
							</Button>
						</div>
					);

				} )}
			</div>

			<Button variant="primary" onClick={onRequestClose}>
				{__( 'Cancel', 'yelp-widget-pro' )}
			</Button>
		</Modal>
	);
}
