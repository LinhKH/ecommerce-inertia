import React from "react";
import { usePage, Link } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";
import ProductRating from "../Components/ProductRating";

function SaleBlog() {
    const { flash_products, generalSettings } = usePage().props;
    const products = flash_products.filter((product) => {
        const datetime = product.flash_date_range.split("-");
        const currentDatetime = new Date();

        let startDatetime = "";
        let endDatetime = "";

        if (product.flash_date_range !== "") {
            startDatetime = new Date(datetime[0]);
            endDatetime = new Date(datetime[1]);
        }

        if (
            currentDatetime >= startDatetime &&
            currentDatetime <= endDatetime
        ) {
            return product;
        }
        return null;
    });
    return products.length > 0 ? (
        <div className="col-md-4 col-sm-6">
            <div className="section-heading">
                <h2 className="title">On Sale</h2>
            </div>
            {products.map((flash_product) => (
                <div
                    className="blog-grid d-flex flex-row"
                    key={flash_product.id}
                >
                    <Link
                        href={baseUrl + "/product/" + flash_product.slug}
                        className="blog-img"
                    >
                        <img
                            src={
                                baseUrl +
                                "/public/products/" +
                                flash_product.thumbnail_img
                            }
                            alt={flash_product.product_name}
                        />
                    </Link>
                    <div className="blog-content d-flex flex-column">
                        <h4>
                            <Link
                                href={
                                    baseUrl + "/product/" + flash_product.slug
                                }
                            >
                                {flash_product.product_name}
                            </Link>
                        </h4>
                        <ProductRating
                            rating_col={flash_product.rating_col}
                            rating_sum={flash_product.rating_sum}
                        ></ProductRating>
                        {flash_product.discount != "0" ? (
                            <>
                                <span className="old-price">
                                    {generalSettings.currency}
                                    {flash_product.taxable_price}
                                </span>
                                <span className="price">
                                    {generalSettings.currency}
                                    {flash_product.taxable_price -
                                        flash_product.discount}
                                </span>
                            </>
                        ) : (
                            <span className="price">
                                {generalSettings.currency}
                                {flash_product.taxable_price}
                            </span>
                        )}
                    </div>
                </div>
            ))}
        </div>
    ) : (
        <></>
    );
}
export default SaleBlog;
