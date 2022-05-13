import { Modal, Button, Icon } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useRef, useState } from '@wordpress/element';
import StarRating from "../StarRating";
import Address from "../Address";

export default function BusinessResultsModal( { setAttributes, onRequestClose, businessResults } ) {
    const [error, setError] = useState( false );

    const handleSubmit = ( business ) => {
        // Update the businessId attribute
        setAttributes( { businessId: business.id } );
        // Close modal
        onRequestClose();
    };

    return (
        <Modal
            onRequestClose={onRequestClose}
            title={__( 'Yelp Business Search Results', 'donation-form-block' )}
            className={'rby-business-results-modal'}
        >
            <div id={'yelp-block-business-results'} className={'rby-business-results-inner'}>
                {businessResults.map( ( business, index ) => {

                    return (
                        <div className={'rby-business-result'} key={index}>
                            <div className={'rby-business-result-image'}>
                                <img src={business.image_url} alt={business.name}/>
                            </div>
                            <div className={'rby-business-result-content'}>
                                <h3>
                                    <a href={business.url} title={business.name} target={'_blank'}>{business.name}</a>
                                </h3>
                                <div className={'rby-business-result-meta'}>
                                    <StarRating
                                        overallRating={business.rating}
                                        totalRatings={business.review_count}
                                    />
                                    <div className={'rby-business-result-address'}>
                                        <Address
                                            displayAddress={business.location.display_address}
                                            alias={business.alias}
                                        />
                                    </div>
                                </div>
                            </div>
                            <div className={'rby-business-result-button'}>
                                <Button
                                    isSecondary
                                    onClick={() => handleSubmit( business )}
                                >
                                    {__( 'Select', 'yelp-widget-pro' )}
                                </Button>
                            </div>
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
