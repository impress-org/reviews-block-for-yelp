import { Modal, Button, Icon } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useRef, useState } from '@wordpress/element';


export default function BusinessResultsModal( { onRequestClose, businessResults } ) {
	const inputRef = useRef();
	const [error, setError] = useState( false );

	console.log( typeof businessResults );

	const handleSubmit = ( event ) => {
		event.preventDefault();

		const input = inputRef.current.value.toLowerCase();

		if ( input !== 'disconnect' ) {
			setError( __( 'Please enter "DISCONNECT" to confirm.', 'donation-block-for-stripe' ) );
			inputRef.current.focus();
			return;
		}

	};

	return (
		<Modal
			onRequestClose={onRequestClose}
			title={__( 'Yelp Business Search Results', 'donation-form-block' )}
			className="dfb-stripe-disconnect-modal"
		>
			<form onSubmit={handleSubmit} className="dfb-stripe-disconnect-modal__form">
				{error && <p className="dfb-stripe-disconnect-modal__error">{error}</p>}

				{businessResults.map( ( business, index ) => {

					return (
						<div key={index}>
							<h3>{business.name}</h3>
							<p>{business.phone}</p>
							<p>{business.url}</p>
						</div>
					);

				} )


				}

				<Button variant="primary" onClick={onRequestClose}>
					Cancel
				</Button>
			</form>
		</Modal>
	);
}
