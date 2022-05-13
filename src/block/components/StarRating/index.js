import { __ } from "@wordpress/i18n";

export default function StarRating( { overallRating, totalRatings = '', date = '', size = "small" } ) {

    const dateFormatted = (date) => {
        const dateObj = new Date(date);
        const month = dateObj.toLocaleString(undefined, { month: 'long' });
        const day = dateObj.getDate();
        const year = dateObj.getFullYear();
        return `${month} ${day}, ${year}`;
    }


    return (
        <div className={`rby-business-stars-wrap rby-business-stars-wrap-${size}`}>
            <span
                aria-label={`${overallRating} out of 5 stars`}
                className={`rby-business-stars rby-business-stars--${overallRating.toString().replace( '.', '-' )}  rby-business-stars-${size}`}></span>
            {totalRatings && (
                <span
                    className={'rby-business-stars-reviews'}>{totalRatings} {__( 'reviews', 'yelp-widget-pro' )}</span>
            )}
            {date && (
                <span className={'rby-business-stars-date'}>{dateFormatted(date)}</span>
            )}

        </div>
    );
}
