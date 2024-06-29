import React, { useState } from "react";
import { usePage, Link } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";

function OrderProducts({ order_detail, order_products, reviews }) {
    const { generalSettings, color } = usePage().props;

    const calculateDate = (date, days) => {
        const newDate = new Date(date);
        newDate.setDate(newDate.getDate() + parseInt(days));
        return newDate.toDateString();
    };
    return (
        <>
            <table className="table table-bordered">
                <tr>
                    <td>Order No : ODR00{order_detail.order.id}</td>
                </tr>
            </table>
            {order_detail.order_products.map((value) => (
                <div className="d-flex flex-row mb-3">
                    <img
                        src={
                            baseUrl +
                            "/public/products/" +
                            value.thumbnail_img.split(",")[0]
                        }
                        class="mr-2"
                        width="90px"
                    />
                    <div>
                        <h6>{value.product_name}</h6>
                        <ul>
                            <li>Qty : {value.product_qty}</li>
                            <li>
                                {color.map((item) => (
                                    <>
                                        {value.colors == item.id
                                            ? item.color_name
                                            : ""}
                                    </>
                                ))}
                            </li>
                            <li></li>
                            <li>
                                Amount : {generalSettings.currency}
                                {value.product_amount}
                            </li>
                            <li>
                                {value.product_delivery == "0" ? (
                                    <>
                                        <li>Delivery : Pending </li>
                                        <li>
                                            Expected Delivery :{" "}
                                            {calculateDate(
                                                order_detail.order.created_at,
                                                value.shipping_days
                                            )}
                                        </li>
                                    </>
                                ) : (
                                    <>
                                        <li>Delivery : Delivered</li>
                                        <li>
                                            Delivered On :{" "}
                                            {new Date(
                                                order_detail.order.updated_at
                                            ).toLocaleDateString()}
                                        </li>
                                        {!reviews.includes(value.id) && (
                                            <li>
                                                <Link
                                                    href={
                                                        baseUrl +
                                                        "/review/create/" +
                                                        value.product_id
                                                    }
                                                    class="btn btn-primary btn-sm"
                                                >
                                                    Write a product review
                                                </Link>
                                            </li>
                                        )}
                                    </>
                                )}
                            </li>
                        </ul>
                    </div>
                </div>
            ))}
            <table className="table table-bordered">
                <tr>
                    <td>Total Products</td>
                    <td>{order_detail.order.qty}</td>
                </tr>
                <tr>
                    <td>Total Amount</td>
                    <th>
                        {generalSettings.currency}
                        {order_detail.order.amount}
                    </th>
                </tr>
            </table>
        </>
    );
}
export default OrderProducts;
