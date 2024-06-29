import React, { useState, useEffect } from "react";
import he from "he"; // Importing the 'he' library for decoding HTML entities
import RelatedProducts from "./RelatedProducts";
import { usePage, useForm, Link } from "@inertiajs/react";
import swal from "@sweetalert/with-react";
import { baseUrl } from "../Components/Baseurl";
import SimpleImageSlider from "react-simple-image-slider";
import ProductRating from "../Components/ProductRating";
import { Swiper, SwiperSlide } from "swiper/react";
// Import Swiper styles
import "swiper/css";

function Product() {
    const {
        generalSettings,
        userSession,
        product,
        colors,
        attributes,
        attrvalues,
        cities,
        reviews,
        cart,
        flash,
    } = usePage().props;
    const [charges, setCharges] = useState(null);
    const [userCity, setUserCity] = useState(
        userSession != null ? userSession.user_city : null
    );

    const product_colors = product.colors ? product.colors.split(",") : '';

    const show_shipping_charges = (shipping, user_city) => {
        if (shipping != "free") {
            if (user_city != null) {
                let city = cities.filter((city) => city.id == user_city);
                if (city[0].cost_city == "0") {
                    setCharges("free");
                } else {
                    setCharges(generalSettings.currency + city[0].cost_city);
                }
            }
        } else {
            setCharges("free");
        }
    };

    const handleCityChange = (e) => {
        setUserCity(e.target.value);
        show_shipping_charges(product.shipping_charges, e.target.value);
    };

    const [selectedColor, setSelectedColor] = useState(product_colors[0] || "");
    const [cart_list, setCartlist] = useState(cart);

    const decodeLabel = (description) => {
        // Decoding the HTML entities using he.decode()
        return he.decode(description);
    };

    const { data, setData, post, get, processing, errors } = useForm({
        product_id: product.id,
        color: selectedColor,
        location: userCity,
    });

    const handleChange = (e) => {
        setData({ ...data, [e.target.name]: e.target.value });
        if (e.target.name == "color" && e.target.checked) {
            setSelectedColor(e.target.value);
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        if (userCity == null) {
            swal({
                title: "Select Location First",
                icon: "warning",
            });
        } else {
            post(baseUrl + "/save_cart", {
                preserveScroll: true,
                preserveState: true,
                onSuccess: (response) => {
                    setCartlist((current) => [...current, product.id]);
                    swal({
                        title: "Added Successfully.",
                        icon: "success",
                        showConfirmButton: false,
                    });
                },
            });
        }
    };

    const handleBuyNow = (e) => {
        e.preventDefault();
        if (userCity == null) {
            swal({
                title: "Select Location First",
                icon: "warning",
            });
        } else {
            get(baseUrl + "/checkout");
        }
    };

    useEffect(() => {
        show_shipping_charges(product.shipping_charges, userCity);
        attributes.map((item) =>
            setData({
                ...data,
                [item.title.toLowerCase()]: item.attrvalues.split(",")[0],
            })
        );
    }, []);

    // // slider images
    // const sliderImages = () => {
    //     const images = product.gallery_img.split(",");
    //     const img_array = [];
    //     images.map((i) =>
    //         img_array.push({ url: baseUrl + "/public/products/" + i })
    //     );
    //     return img_array;
    // };
    return (
        <section id="site-content" className="py-3">
            <div className="container">
                <div className="row">
                    <div className="col-md-6">
                        <div className="content-box single-product">
                            <Swiper>
                                {product.gallery_img &&
                                    product.gallery_img
                                        .split(",")
                                        .map((item, index) => (
                                            <SwiperSlide key={index}>
                                                <img
                                                    className="w-100"
                                                    src={
                                                        baseUrl +
                                                        "/public/products/" +
                                                        item
                                                    }
                                                />
                                            </SwiperSlide>
                                        ))}
                            </Swiper>
                        </div>
                    </div>
                    <div className="col-md-6">
                        <form method="GET" onSubmit={handleSubmit} noValidate>
                            <div className="product-info">
                                <span className="brand-name">
                                    {product.brand_name}
                                </span>
                                <p className="product-name">
                                    {product.product_name}
                                </p>
                                {product.discount != "0" ? (
                                    <div className="product-price">
                                        <span className="special-price">
                                            {product.taxable_price -
                                                product.discount}
                                        </span>
                                        <span className="old-price">
                                            {product.taxable_price}
                                        </span>
                                        <span className="discount-price">
                                            {product.discount_percent} off
                                        </span>
                                    </div>
                                ) : (
                                    <div className="product-price">
                                        <span className="special-price">
                                            {generalSettings.currency}
                                            {product.taxable_price}
                                        </span>
                                    </div>
                                )}
                                <ProductRating
                                    rating_col={product.rating_col}
                                    rating_sum={product.rating_sum}
                                />
                                {product.colors &&
                                    product.colors.length > 0 && (
                                        <div className="product-color">
                                            <label>Color:</label>
                                            <ul className="option-list">
                                                {colors.map((item1) => {
                                                    let c_check = false;
                                                    if (
                                                        product.colors.includes(
                                                            item1.id
                                                        )
                                                    ) {
                                                        c_check =
                                                            item1.id ==
                                                            data.color
                                                                ? true
                                                                : false;
                                                        return (
                                                            <li
                                                                className="radio-button"
                                                                key={item1.id}
                                                            >
                                                                <input
                                                                    type="radio"
                                                                    name="color"
                                                                    id={
                                                                        "color" +
                                                                        item1.id
                                                                    }
                                                                    value={
                                                                        item1.id
                                                                    }
                                                                    onChange={
                                                                        handleChange
                                                                    }
                                                                    checked={
                                                                        c_check
                                                                    }
                                                                />
                                                                <label
                                                                    htmlFor={
                                                                        "color" +
                                                                        item1.id
                                                                    }
                                                                    style={{
                                                                        backgroundColor:
                                                                            item1.color_code,
                                                                    }}
                                                                ></label>
                                                            </li>
                                                        );
                                                    }
                                                })}
                                            </ul>
                                        </div>
                                    )}
                                {attributes.map((row) => {
                                    let c_attr = false;
                                    const value = row.attrvalues
                                        .split(",")
                                        .filter((val) => val !== "");
                                    return (
                                        <div
                                            key={row.id}
                                            className="product-attributes"
                                        >
                                            <span>{row.title}:</span>
                                            {attrvalues.map((item1) => {
                                                if (
                                                    value.includes(
                                                        item1.id.toString()
                                                    )
                                                ) {
                                                    c_attr =
                                                        item1.id ==
                                                        data[
                                                            row.title.toLowerCase()
                                                        ]
                                                            ? true
                                                            : false;
                                                    return (
                                                        <React.Fragment
                                                            key={item1.id}
                                                        >
                                                            <input
                                                                type="hidden"
                                                                name="product_attrvalues"
                                                                value={item1.id}
                                                            />
                                                            <input
                                                                type="radio"
                                                                className="attrvalue"
                                                                id={
                                                                    "attrvalue" +
                                                                    item1.id
                                                                }
                                                                name={row.title.toLowerCase()}
                                                                onChange={
                                                                    handleChange
                                                                }
                                                                value={item1.id}
                                                                checked={c_attr}
                                                            />
                                                            <label
                                                                htmlFor={
                                                                    "attrvalue" +
                                                                    item1.id
                                                                }
                                                            >
                                                                {item1.value}
                                                            </label>
                                                        </React.Fragment>
                                                    );
                                                }
                                                return null;
                                            })}
                                        </div>
                                    );
                                })}
                                <div className="product-shipping">
                                    <span className="shipping-head">
                                        Delivery:{" "}
                                    </span>
                                    <select
                                        className="form-control shipping"
                                        value={userCity}
                                        name="shipping"
                                        onChange={handleCityChange}
                                        required
                                    >
                                        <option value="" disabled>
                                            Select Location
                                        </option>
                                        {cities.map((city) => {
                                            return (
                                                <option
                                                    key={city.id}
                                                    value={city.id}
                                                    data-p-ship={
                                                        product.shipping_charges
                                                    }
                                                    data-shipping={
                                                        city.cost_city
                                                    }
                                                >
                                                    {`${city.city_name} (${city.state_name})`}
                                                </option>
                                            );
                                        })}
                                    </select>
                                </div>
                                {charges != null && (
                                    <div className="shipping-charges mb-2">
                                        Shipping Charges : {charges}
                                    </div>
                                )}
                                <div className="product-btn">
                                    {userSession != null ? (
                                        cart_list.includes(product.id) ? (
                                            <>
                                                <Link
                                                    href={baseUrl + "/cart"}
                                                    className="btn btn-primary"
                                                >
                                                    Go to cart{" "}
                                                </Link>
                                            </>
                                        ) : (
                                            <>
                                                <input
                                                    type="submit"
                                                    className="btn btn-primary mr-2"
                                                    name="save_cart"
                                                    value="Add to Cart"
                                                />
                                                {/* <input type="submit" className="btn btn-primary" name="by_now" value="Buy Now" /> */}
                                                <button
                                                    type="button"
                                                    onClick={handleBuyNow}
                                                    className="btn btn-primary mr-2"
                                                >
                                                    Buy Now
                                                </button>
                                            </>
                                        )
                                    ) : (
                                        <>
                                            <Link
                                                href={baseUrl + "/user_login"}
                                                className="btn btn-primary me-2"
                                            >
                                                Add to cart{" "}
                                            </Link>
                                            <a
                                                href={baseUrl + "/user_login"}
                                                className="btn btn-primary"
                                            >
                                                Buy Now
                                            </a>
                                        </>
                                    )}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div className="row">
                    <div className="col-12">
                        <div className="accordion" id="accordionExample">
                            <div className="accordion-item">
                                <h2
                                    className="accordion-header"
                                    id="headingOne"
                                >
                                    <button
                                        className="accordion-button"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne"
                                        aria-expanded="true"
                                        aria-controls="collapseOne"
                                    >
                                        Description
                                    </button>
                                </h2>
                                <div
                                    id="collapseOne"
                                    className="accordion-collapse collapse show"
                                    aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample"
                                >
                                    <div className="accordion-body">
                                        {/* Use dangerouslySetInnerHTML to render the HTML content */}
                                        <p
                                            dangerouslySetInnerHTML={{
                                                __html: decodeLabel(
                                                    product.description
                                                ),
                                            }}
                                        ></p>
                                    </div>
                                </div>
                            </div>
                            <div className="accordion-item">
                                <h2
                                    className="accordion-header"
                                    id="headingTwo"
                                >
                                    <button
                                        className="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseTwo"
                                        aria-expanded="false"
                                        aria-controls="collapseTwo"
                                    >
                                        Additional Information
                                    </button>
                                </h2>
                                <div
                                    id="collapseTwo"
                                    className="accordion-collapse collapse"
                                    aria-labelledby="headingTwo"
                                    data-bs-parent="#accordionExample"
                                >
                                    <div className="accordion-body">
                                        <table className="table">
                                            <tbody>
                                                {attributes.map((row) => {
                                                    const value = row.attrvalues
                                                        .split(",")
                                                        .filter(
                                                            (val) => val !== ""
                                                        );
                                                    let j = 0;
                                                    return (
                                                        <tr
                                                            key={row.id}
                                                            className="table-active product-attributes"
                                                        >
                                                            <th>
                                                                {row.title}:
                                                            </th>
                                                            {attrvalues.map(
                                                                (item1) => {
                                                                    if (
                                                                        value.includes(
                                                                            item1.id.toString()
                                                                        )
                                                                    ) {
                                                                        const attr_check =
                                                                            j ==
                                                                            0
                                                                                ? "checked"
                                                                                : "";
                                                                        return (
                                                                            <React.Fragment
                                                                                key={
                                                                                    item1.id
                                                                                }
                                                                            >
                                                                                <td>
                                                                                    {" "}
                                                                                    {
                                                                                        item1.value
                                                                                    }

                                                                                    ,
                                                                                </td>
                                                                            </React.Fragment>
                                                                        );
                                                                        j++;
                                                                    }
                                                                    return null;
                                                                }
                                                            )}
                                                        </tr>
                                                    );
                                                })}
                                                {attributes.length == 0 && (
                                                    <tr>
                                                        <td className="col-md-6">
                                                            <span>
                                                                No Additional
                                                                Information.
                                                            </span>
                                                        </td>
                                                    </tr>
                                                )}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div className="accordion-item">
                                <h2
                                    className="accordion-header"
                                    id="headingThree"
                                >
                                    <button
                                        className="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#collapseThree"
                                        aria-expanded="false"
                                        aria-controls="collapseThree"
                                    >
                                        Reviews
                                    </button>
                                </h2>
                                <div
                                    id="collapseThree"
                                    className="accordion-collapse collapse"
                                    aria-labelledby="headingThree"
                                    data-bs-parent="#accordionExample"
                                >
                                    <div className="accordion-body">
                                        <div className="row">
                                            {reviews.length > 0 && (
                                                <div className="col-md-6">
                                                    <div className="product-reviews">
                                                        {reviews.map(
                                                            (review) => (
                                                                <div
                                                                    className="review-item"
                                                                    key={
                                                                        review.id
                                                                    }
                                                                >
                                                                    <h6>
                                                                        <span className="bg-success">
                                                                            <i className="fa fa-star"></i>{" "}
                                                                            {
                                                                                review.rating
                                                                            }
                                                                        </span>
                                                                        {
                                                                            review.title
                                                                        }
                                                                    </h6>
                                                                    <p>
                                                                        {
                                                                            review.desc
                                                                        }
                                                                    </p>
                                                                    <span className="user">
                                                                        {
                                                                            review.name
                                                                        }
                                                                    </span>
                                                                </div>
                                                            )
                                                        )}
                                                    </div>
                                                </div>
                                            )}
                                            {product.video_link !== "" && (
                                                <div className="col-md-6"></div>
                                            )}
                                        </div>
                                        {reviews.length == 0 && (
                                            <div className="col-md-6">
                                                <span>
                                                    No Reviews Available.
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="col-12">
                        <RelatedProducts />
                    </div>
                </div>
            </div>
        </section>
    );
}
export default Product;
