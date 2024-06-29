import React from "react";
import { Link, usePage, useForm } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";
import Preloader from "../Components/Preloader";

function Reviews() {
    const { product, generalSettings, user } = usePage().props;

    const { data, setData, post, processing, errors } = useForm({
        user: user,
        product: product.id,
        star: "",
        title: "",
        review: "",
    });

    function handleChange(e) {
        const key = e.target.name;
        const value = e.target.value;
        setData((prevdata) => ({ ...prevdata, [key]: value }));
    }

    function handleSubmit(e) {
        e.preventDefault();
        post(baseUrl + "/review/store", {
            preserveScroll: true,
        });
    }

    return (
        <div id="site-content">
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>My Reviews</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item">
                                <Link href={baseUrl}>Home</Link>
                            </li>
                            <li className="breadcrumb-item active">
                                My Reviews
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div className="message"></div>
            <div className="container-xl container-fluid">
                <div className="row">
                    <div className="col-md-6">
                        <div className="card">
                            <div className="card-body">
                                <form onSubmit={handleSubmit} method="POST">
                                    {processing && <Preloader />}
                                    <div className="form-group">
                                        <label for="">Add a Headline</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            name="title"
                                            value={data.title}
                                            onChange={handleChange}
                                        />
                                        {errors.title && (
                                            <div
                                                className="alert alert-danger mt-2"
                                                role="alert"
                                            >
                                                {errors.title}
                                            </div>
                                        )}
                                    </div>
                                    <div className="form-group">
                                        <label for="">Write your review</label>
                                        <textarea
                                            name="review"
                                            value={data.review}
                                            onChange={handleChange}
                                            className="form-control"
                                        ></textarea>
                                        {errors.review && (
                                            <div
                                                className="alert alert-danger mt-2"
                                                role="alert"
                                            >
                                                {errors.review}
                                            </div>
                                        )}
                                    </div>
                                    <div className="form-group">
                                        <label for="">Overall Raing</label>
                                        <ul className="review-rating">
                                            <li>
                                                <input
                                                    className="star star-1"
                                                    value="1"
                                                    id="star-1"
                                                    type="radio"
                                                    name="star"
                                                    onChange={handleChange}
                                                />
                                                <label
                                                    className="star star-1"
                                                    for="star-1"
                                                ></label>
                                            </li>
                                            <li>
                                                <input
                                                    className="star star-2"
                                                    value="2"
                                                    id="star-2"
                                                    type="radio"
                                                    name="star"
                                                    onChange={handleChange}
                                                />
                                                <label
                                                    className="star star-2"
                                                    for="star-2"
                                                ></label>
                                            </li>
                                            <li>
                                                <input
                                                    className="star star-3"
                                                    value="3"
                                                    id="star-3"
                                                    type="radio"
                                                    name="star"
                                                    onChange={handleChange}
                                                />
                                                <label
                                                    className="star star-3"
                                                    for="star-3"
                                                ></label>
                                            </li>
                                            <li>
                                                <input
                                                    className="star star-4"
                                                    value="4"
                                                    id="star-4"
                                                    type="radio"
                                                    name="star"
                                                    onChange={handleChange}
                                                />
                                                <label
                                                    className="star star-4"
                                                    for="star-4"
                                                ></label>
                                            </li>
                                            <li>
                                                <input
                                                    className="star star-5"
                                                    value="5"
                                                    id="star-5"
                                                    type="radio"
                                                    name="star"
                                                    onChange={handleChange}
                                                />
                                                <label
                                                    className="star star-5"
                                                    for="star-5"
                                                ></label>
                                            </li>
                                        </ul>
                                    </div>
                                    <input
                                        type="submit"
                                        disabled={processing}
                                        className="btn btn-sm btn-primary"
                                        name="submit-review"
                                        value="Submit"
                                    />
                                </form>
                            </div>
                        </div>
                    </div>
                    <div className="col-md-6">
                        <div className="d-flex flex-row">
                            <img
                                src={
                                    baseUrl +
                                    "/public/products/" +
                                    product.thumbnail_img
                                }
                                className="img-thumbnail"
                                alt="{{$product->product_name}}"
                                width="250px"
                            />
                            <div className="text-left px-3">
                                <h6>{product.product_name}</h6>
                                <span>
                                    {generalSettings.currency}
                                    {product.taxable_price}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Reviews;