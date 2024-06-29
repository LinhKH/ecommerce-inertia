import React from "react";
import { usePage, Link } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";
import ProductRating from "../Components/ProductRating";

function SellingBlog() {
    const { orderProducts } = usePage().props;
    return orderProducts.length > 0 ? (
        <div className="col-md-4 col-sm-6">
            <div className="section-heading">
                <h2 className="title">Best Selling</h2>
            </div>
            {orderProducts.map((value, index) => {
                return (
                    <div className="blog-grid d-flex flex-row" key={index}>
                        <Link
                            href={baseUrl + "/product/" + value.slug}
                            className="blog-img"
                        >
                            <img
                                src={
                                    baseUrl +
                                    "/public/products/" +
                                    value.thumbnail_img
                                }
                                alt={value.product_name}
                            />
                        </Link>
                        <div className="blog-content d-flex flex-column">
                            <h4>
                                <Link href={baseUrl + "/product/" + value.slug}>
                                    {value.product_name}
                                </Link>
                            </h4>
                            <ProductRating
                                rating_col={value.rating_col}
                                rating_sum={value.rating_sum}
                            />
                            <div className="price">${value.unit_price}</div>
                        </div>
                    </div>
                );
            })}
        </div>
    ) : (
        <></>
    );
}
export default SellingBlog;
