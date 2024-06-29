import React from "react";

const ProductRating = ({ rating_col, rating_sum }) => {
    let rating = 0;
    if (rating_col > 0 && rating_sum != null) {
        rating = rating_sum / rating_col;
    }
    let star = "";
    for (let i = 0; i < 5; i++) {
        if (i < rating) {
            star += '<li class="fa fa-star"></li>';
        } else {
            star += '<li class="far fa-star disable"></li>';
        }
    }
    star = star + " (" + rating_col + " reviews)";
    return (
        <ul className="rating" dangerouslySetInnerHTML={{ __html: star }}></ul>
    );
};

export default ProductRating;
