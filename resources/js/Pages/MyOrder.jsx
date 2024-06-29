import React, { useState } from "react";
import { Link, usePage, router } from "@inertiajs/react";
import OrderProducts from "../Components/OrderProducts";
import { baseUrl } from "../Components/Baseurl";

function MyOrder() {
    const { my_orders, order_detail, order_products, reviews } = usePage().props;

    const [orderDetail, setOrderDetail] = useState(
        order_detail.length > 0
            ? { order: order_detail, order_products: order_products }
            : null
    );

    const handleShowDetails = (id) => {
        router.post(
            baseUrl + "/my_orders",
            { id: id },
            {
                preserveScroll: true,
                // preserveState: true,
                // replace: true,
                // only : ['order_detail','order_products'],
                onSuccess: (response) => {
                    setOrderDetail({
                        order: response.props.order_detail,
                        order_products: response.props.order_products,
                    });
                },
            }
        );
    };
    return (
        <div id="site-content">
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>My Orders</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item">
                                <Link href={baseUrl}>Home</Link>
                            </li>
                            <li className="breadcrumb-item active">
                                My Orders
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div className="message"></div>
            <div className="container-xl container-fluid">
                <div className="row">
                    <div className={"col-md-" + (orderDetail ? 8 : 12)}>
                        {!my_orders.isEmpty ? (
                            <table className="table table-bordered table-striped">
                                <thead>
                                    <th>Order No</th>
                                    <th>Products</th>
                                    <th>Order Placed</th>
                                    <th>View</th>
                                </thead>
                                <tbody className="cart-data">
                                    {my_orders.map((order) => (
                                        <tr className="active" key={order.id}>
                                            <td>
                                                <Link className="show-product">
                                                    {" "}
                                                    ODR00{order.id}{" "}
                                                </Link>
                                            </td>
                                            <td>
                                                <ul>
                                                    {order.names
                                                        .split("|||")
                                                        .map(function (names) {
                                                            return (
                                                                <li className="mb-2">
                                                                    {names}
                                                                </li>
                                                            );
                                                        })}
                                                </ul>
                                            </td>
                                            <td>
                                                {new Date(
                                                    order.created_at
                                                ).toLocaleDateString()}
                                            </td>
                                            <td>
                                                <button
                                                    type="button"
                                                    className="btn btn-primary"
                                                    onClick={() => {
                                                        handleShowDetails(
                                                            order.id
                                                        );
                                                    }}
                                                >
                                                    <i className="fa fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        ) : (
                            <div className="content-box text-center">
                                <p className="m-0">No Orders Found</p>
                            </div>
                        )}
                    </div>
                    <div className="col-md-4 show-product-content">
                        {orderDetail != null && (
                            <OrderProducts
                                order_detail={orderDetail}
                                reviews={reviews}
                                key={orderDetail.order.id}
                            />
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}

export default MyOrder;
