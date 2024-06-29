import React, { useState } from "react";
import { usePage, Link, router, useForm } from "@inertiajs/react";
import Preloader from "../Components/Preloader";
import swal from "@sweetalert/with-react";
import { baseUrl } from "../Components/Baseurl";
import ProductRating from "../Components/ProductRating";

function Product({ product }) {
    const { userSession, generalSettings, component, flash, wishlist } =
        usePage().props;
    const [wish_list, setWishlist] = useState(wishlist);

    const productName =
        product.product_name.length > 25
            ? product.product_name.substring(0, 25) + "..."
            : product.product_name;

    const { processing } = useForm({});

    const handleAddWishlist = (product_id) => {
        swal({
            title: "Item Added to Wishlist",
            showConfirmButton: false,
            timer: 1000,
            icon: "success",
            showCancelButton: true,
        }).then((result) => {
            router.post(
                baseUrl + "/add-wishlist",
                { id: product_id },
                {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: (response) => {
                        setWishlist((current) => [...current, product_id]);
                    },
                }
            );
        });
    };

    function handleRemoveFromWishlist(product_id) {
        router.post(
            baseUrl + "/remove-wishlist",
            { id: product_id },
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: (response) => {
                    setWishlist((current) => [...current, product_id]);
                    if (response.props.flash.success) {
                        swal({
                            title: "Removed Successfully.",
                            icon: "success",
                            showConfirmButton: false,
                            timer: 1000,
                        });
                    }
                },
            }
        );
    }

    return (
        <>
            {processing && <Preloader />}
            <div className="product-grid" key={product.id}>
                <div className="product-image">
                    <Link
                        href={baseUrl + "/product/" + product.slug}
                        className="image"
                    >
                        <img
                            className="pic-1"
                            src={
                                baseUrl +
                                "/public/products/" +
                                product.thumbnail_img.split(",")[0]
                            }
                            alt={product.product_name}
                        />
                    </Link>
                    {product.discount != "0" && (
                        <span className="product-discount-label">
                            {product.discount_percent} off
                        </span>
                    )}
                    {/* <span className="product-sale-label">sale</span> */}
                    <Link
                        className="quick-view"
                        href={baseUrl + "/product/" + product.slug}
                    >
                        {" "}
                        quick view{" "}
                    </Link>
                </div>
                <div className="product-content">
                    <span className="category">
                        <span>{product.brand_name}</span>
                    </span>
                    <h3 className="title">
                        <Link href={baseUrl + "/product/" + product.slug}>
                            {productName}
                        </Link>
                    </h3>
                    <ProductRating
                        rating_col={product.rating_col}
                        rating_sum={product.rating_sum}
                    />
                    {product.discount != "0" ? (
                        <>
                            <span className="old-price">
                                {generalSettings.currency}
                                {product.taxable_price}
                            </span>
                            <span className="price">
                                {generalSettings.currency}
                                {product.taxable_price - product.discount}
                            </span>
                        </>
                    ) : (
                        <span className="price">
                            {generalSettings.currency}
                            {product.taxable_price}
                        </span>
                    )}
                    <ul className="product-links">
                        <li>
                            <Link
                                href={baseUrl + "/product/" + product.slug}
                                data-id={product.id}
                            >
                                Add to cart
                            </Link>
                        </li>
                        {userSession ? (
                            component != "wishlist" && (
                                <li>
                                    <Link
                                        href="#"
                                        data-tip="Add To Wishlist"
                                        data-id={product.id}
                                        onClick={() =>
                                            handleAddWishlist(product.id)
                                        }
                                    >
                                        <i className="far fa-heart"></i>
                                    </Link>
                                </li>
                            )
                        ) : (
                            <>
                                <li>
                                    <Link
                                        href={baseUrl + "/user_login"}
                                        data-tip="Add To Wishlist"
                                        data-id={product.id}
                                    >
                                        <i className="far fa-heart"></i>
                                    </Link>
                                </li>
                            </>
                        )}
                    </ul>
                    {component == "wishlist" && (
                        <button
                            type="button"
                            className="btn btn-danger btn-sm mt-2"
                            data-id={product.id}
                            onClick={() => handleRemoveFromWishlist(product.id)}
                        >
                            Remove from wishlist
                        </button>
                    )}
                </div>
            </div>
        </>
    );
}

export default Product;
