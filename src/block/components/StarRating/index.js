import { __ } from "@wordpress/i18n";

export default function StarRating({overallRating, totalRatings, size = "small"}) {

    return (
        <div className={`rby-business-stars-wrap rby-business-stars-wrap-${size}`}>
            <span
                aria-label={`${overallRating} out of 5 stars`}
                className={`rby-business-stars rby-business-stars--${overallRating.toString().replace( '.', '-' )}  rby-business-stars-${size}`}></span>
            <span
                className={'rby-business-stars-reviews'}>{totalRatings} {__( 'reviews', 'yelp-widget-pro' )}</span>
        </div>
    );
}
