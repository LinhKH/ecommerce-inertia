import React, { useState } from "react";
import { useForm, router, Link, usePage } from "@inertiajs/react";
import Preloader from "../Components/Preloader";
import { baseUrl } from "../Components/Baseurl";

function Cart() {
    const {
        generalSettings,
        userSession,
        products,
        city,
        attributes,
        attrvalues,
        cart,
        token,
        flash,
    } = usePage().props;
    const [charges, setCharges] = useState(
        userSession.user_city != null
            ? city.filter((city) => city.id == userSession.user_city)[0]
                .cost_city
            : null
    );
    const [cart_list, setCartlist] = useState(cart);

    const { get, processing, errors } = useForm({});

    let total = 0;
    const calculateTotal = (amount) => {
        total = total + amount;
    };

    const handleChangeQty = (e) => {
        let val = e.target.value;
        let id = e.target.id.replace("cart", "");
        router.post(
            baseUrl + "/update_cart_qty",
            { id: id, qty: val },
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: (res) => {
                    calculateTotal(
                        products.filter((product) => product.cart_id == id)[0][
                        "taxable_price"
                        ] * val
                    );
                },
            }
        );
    };

    function handleRemoveCart(cart_id) {
        router.post(
            baseUrl + "/remove_cart",
            { id: cart_id },
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: (response) => {
                    setCartlist((current) => [...current, cart_id]);
                },
            }
        );
    }

    return (
        <div id="site-content">
            {/* Display error message if exists */}
            {flash.error && (
                <div className="alert alert-danger mt-2" role="alert">
                    {" "}
                    {flash.error}{" "}
                </div>
            )}
            {/* Display success message if exists */}
            {flash.success && (
                <div className="alert alert-success mt-2" role="alert">
                    {" "}
                    {flash.success}{" "}
                </div>
            )}
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>My Cart</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item">
                                <Link href={baseUrl}>Home</Link>
                            </li>
                            <li className="breadcrumb-item active">My Cart</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div className="container-xl container-fluid">
                <div className="row">
                    <div className="col-md-12">
                        {products.length > 0 ? (
                            <form action={baseUrl + "/checkout"}>
                                {processing && <Preloader />}
                                <input
                                    type="hidden"
                                    name="_token"
                                    value={token}
                                />
                                <table className="table table-bordered">
                                    <thead>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th>Remove</th>
                                    </thead>
                                    <tbody>
                                        {products.map((product) => (
                                            <tr key={product.id}>
                                                {product.shipping_charges !=
                                                    "free"
                                                    ? calculateTotal(
                                                        parseInt(
                                                            product.taxable_price
                                                        ) *
                                                        product.qty +
                                                        parseInt(charges)
                                                    )
                                                    : calculateTotal(
                                                        parseInt(
                                                            product.taxable_price
                                                        ) * product.qty
                                                    )}
                                                <input
                                                    type="hidden"
                                                    name="product_id[]"
                                                    value={product.id}
                                                />
                                                <input
                                                    type="hidden"
                                                    name="product_attr[{{$product->id}}]"
                                                    value={product.attrvalues}
                                                />
                                                <input
                                                    type="hidden"
                                                    name="product_color[{{$product->id}}]"
                                                    value={product.color}
                                                />
                                                <td className="d-flex flex-row">
                                                    <img
                                                        className="pic-1"
                                                        src={
                                                            baseUrl +
                                                            "/public/products/" +
                                                            product.thumbnail_img
                                                        }
                                                        alt={
                                                            product.product_name
                                                        }
                                                        width="70px"
                                                    />
                                                    <div className="ml-2">
                                                        {product.product_name}
                                                        {product.color_code && (
                                                            <span class="d-block">
                                                                <b>Color : </b>
                                                                <label
                                                                    className="border"
                                                                    style={{
                                                                        backgroundColor:
                                                                            product.color_code,
                                                                        cursor: "auto",
                                                                        height: "25px",
                                                                        width: "25px",
                                                                    }}
                                                                ></label>
                                                            </span>
                                                        )}
                                                        <ul>
                                                            <li></li>
                                                            <li></li>
                                                        </ul>
                                                        {product.shipping_charges ==
                                                            "free" ? (
                                                            <span>
                                                                Free Delivery
                                                            </span>
                                                        ) : (
                                                            <span>
                                                                Delivery Charges
                                                                :
                                                                {
                                                                    generalSettings.currency
                                                                }
                                                                {charges}
                                                            </span>
                                                        )}
                                                    </div>
                                                </td>
                                                <td>
                                                    {generalSettings.currency}
                                                    {product.taxable_price}
                                                </td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        className="form-control"
                                                        name={`qty[${product.id}]`}
                                                        min="1"
                                                        style={{
                                                            width: "80px",
                                                        }}
                                                        defaultValue={
                                                            product.qty
                                                        }
                                                        id={
                                                            "cart" +
                                                            product.cart_id
                                                        }
                                                        onChange={
                                                            handleChangeQty
                                                        }
                                                    />
                                                    <input
                                                        type="number"
                                                        className="product-price"
                                                        name={`price[${product.id}]`}
                                                        defaultValue={
                                                            product.taxable_price
                                                        }
                                                        hidden
                                                    />
                                                    <input
                                                        type="number"
                                                        className="product-shipping"
                                                        defaultValue={charges}
                                                        hidden
                                                    />
                                                </td>
                                                <td>
                                                    {generalSettings.currency}
                                                    <span className="product-total">
                                                        {product.shipping_charges ==
                                                            "free"
                                                            ? parseInt(
                                                                product.taxable_price *
                                                                product.qty
                                                            )
                                                            : parseInt(
                                                                product.taxable_price *
                                                                product.qty
                                                            ) +
                                                            parseInt(charges)}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button
                                                        type="button"
                                                        className="btn btn-danger"
                                                        onClick={() =>
                                                            handleRemoveCart(
                                                                product.id
                                                            )
                                                        }
                                                    >
                                                        <i className="fas fa-trash"></i>
                                                    </button>
                                                    {/* <button type="button" className="btn btn-danger remove-cart" data-id={product.cart_id}><i className="fas fa-trash"></i></button> */}
                                                </td>
                                            </tr>
                                        ))}
                                        <tr>
                                            <td colSpan="3" align="right">
                                                <b>Total Amount</b>
                                            </td>
                                            <td className="">
                                                {generalSettings.currency}
                                                <span>{total}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <Link
                                    className="btn btn-primary"
                                    href={baseUrl + "/"}
                                >
                                    Continue Shopping
                                </Link>
                                <Link
                                    className="btn btn-primary float-right"
                                    href={baseUrl + "/checkout"}
                                >
                                    Proceed to Checkout
                                </Link>
                                {/* <input type="submit" className="btn btn-success float-right" name="checkout" value="Proceed to Checkout" onClick={() => handleSubmit()}/> */}
                            </form>
                        ) : (
                            <div className="content-box text-center">
                                <p className="">No Products Found</p>
                                <Link
                                    href={baseUrl}
                                    className="btn btn-primary"
                                >
                                    Shop Now
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Cart;
