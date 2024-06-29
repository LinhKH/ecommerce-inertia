import React from 'react';
import { usePage, Link } from '@inertiajs/react';
import { baseUrl } from '../Components/Baseurl'
import ProductRating from '../Components/ProductRating';

function RatedBlog() {
    const { rating } = usePage().props;
    const products = rating.filter(product => product.rating > 4);
    return (
        products.length > 0 ? <div className="col-md-4 col-sm-6">
            <div className="section-heading">
                <h2 className="title">Top Rated</h2>
            </div>
            {products.map(value => (
                <div className="blog-grid d-flex flex-row" key={value.id}>
                    <Link href={baseUrl + "/product/" + value.slug} className="blog-img">
                        <img src={baseUrl+'/public/products/'+ value.thumbnail_img} alt={value.product_name} />
                    </Link>
                    <div className="blog-content d-flex flex-column">
                        <h4><Link href={baseUrl + "/product/" + value.slug}>{value.product_name}</Link></h4>
                        <ProductRating rating_col={value.rating_col} rating_sum={value.rating_sum} />
                        <div className="price">
                            ${value.unit_price}
                        </div>
                    </div>
                </div>
            ))}
        </div>   : <></>   
    )
}
export default RatedBlog