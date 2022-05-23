import { __ } from "@wordpress/i18n";
import StarRating from "../StarRating";
import BlankAvatar from "../../images/blank-avatar.png";
import { Icon } from "@wordpress/components";
import IconYelp from "../../images/yelp-icon.svg";

export default function Review( { index, review = [] } ) {

    return (
        <div className={'rby-business-review'} key={index}>
            <img src={IconYelp} className={'rby-business-review-yelp-icon'} />
            <div className={'rby-business-review-user'}>
                <div className={'rby-business-review-user-image'}>
                    {review.user.image_url &&(
                        <img src={review.user.image_url} alt={review.user.name}/>
                    )}
                    {!review.user.image_url &&(
                        <img src={BlankAvatar} alt={review.user.name}/>
                    )}

                </div>
                <div className={'rby-business-review-user-name'}>
                    {review.user.name}
                </div>
            </div>
            <div className={'rby-business-review-content'}>
                <div className={'rby-business-review-content-rating'}>
                    <StarRating
                        overallRating={review.rating}
                        date={review.time_created}
                    />
                </div>
                <div className={'rby-business-review-content-text'}>
                    <p>{review.text}</p>
                    <div className={'rby-business-review-content-readmore-wrap'}>
                        <a href={review.url} target={'_blank'} className={'rby-business-review-content-readmore'}>{__('Read more', 'yelp-widget-pro')} <Icon icon={'external'} size={'100'}/></a>

                    </div>
                </div>
            </div>
        </div>
    );

}
