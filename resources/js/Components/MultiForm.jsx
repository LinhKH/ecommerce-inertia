import { useState } from "react";
import { usePage, useForm, Link } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";
import Preloader from "./Preloader";

const MultiForm = () => {
    const totalStep = 3;
    const [activeStep, setActiveStep] = useState(1);
    const handleStep = (step) => {
        setActiveStep(step);
    };

    const {
        userSession,
        generalSettings,
        user,
        products,
        attributes,
        attrvalues,
        colors,
        payment_method,
        countries,
        states,
        cities,
        flash,
        razorkey,
    } = usePage().props;

    const [cityList, setCityList] = useState(
        user.state != null
            ? cities.filter((city) => city.state == user.state)
            : []
    );

    const handleStateChange = (val) => {
        setCityList(cities.filter((city) => city.state == val));
    };

    const handleChange = (e) => {
        setData({ ...data, [e.target.name]: e.target.value });
    };

    const [charges, setCharges] = useState(
        user.city != null
            ? cities.filter((city) => city.id == user.city)[0].cost_city
            : null
    );

    const calculateTotal = () => {
        let t = 0;
        products.map((item) =>
            item.shipping_charges != "free"
                ? (t +=
                      parseInt(item.taxable_price) * item.qty + parseInt(charges))
                : (t += parseInt(item.taxable_price) * item.qty)
        );
        return t;
    };

    const { data, setData, processing, get } = useForm({
        pay_method: "",
        amount: calculateTotal(),
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        if (data.pay_method == "razorpay") {
            const razorpay = window.Razorpay({
                key: razorkey,
                amount: data.amount * 100,
                name: generalSettings.site_name,
                order_id: "",
                handler: async (transaction) => {
                    const tr = transaction.razorpay_payment_id;
                    get(
                        baseUrl +
                            `/pay-with-razorpay/${data.amount}/${tr}?` +
                            data
                    );
                },
            });

            razorpay.open();
        } else if (data.pay_method == "paypal") {
            const urlParams = new URLSearchParams(
                window.location.href.split("?")[1]
            ).toString();
            window.location.href =
                baseUrl +
                "/pay-with-paypal/" +
                data.amount +
                "?" +
                urlParams +
                "&" +
                new URLSearchParams(data).toString();
        } else if (data.pay_method == "cod") {
            const urlParams = new URLSearchParams(
                window.location.href.split("?")[1]
            ).toString();
            window.location.href =
                baseUrl +
                "/pay-with-cod/" +
                data.amount +
                "?" +
                urlParams +
                "&" +
                new URLSearchParams(data).toString();
        }
    };

    return (
        <form onSubmit={handleSubmit} method="POST">
            {processing && <Preloader />}
            <ul className="d-flex justify-content-around">
                <li>
                    <button
                        type="button"
                        className="btn btn-primary"
                        onClick={() => {
                            handleStep(1);
                        }}
                        disabled={activeStep == 1 ? false : true}
                    >
                        Step 1
                    </button>
                </li>
                <li>
                    <button
                        type="button"
                        className="btn btn-primary"
                        onClick={() => {
                            handleStep(2);
                        }}
                        disabled={activeStep == 2 ? false : true}
                    >
                        Step 2
                    </button>
                </li>
                <li>
                    <button
                        type="button"
                        className="btn btn-primary"
                        onClick={() => {
                            handleStep(3);
                        }}
                        disabled={activeStep == 3 ? false : true}
                    >
                        Step 3
                    </button>
                </li>
            </ul>
            <div className="multi-content">
                {activeStep == 1 && (
                    <div id="Step1" className="row py-3">
                        <table className="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Delivery Details</th>
                                    <th>
                                        <Link
                                            href={baseUrl + "/my-profile"}
                                            className="btn btn-primary"
                                        >
                                            Change
                                        </Link>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Name :</th>
                                    <td>{user.name}</td>
                                </tr>
                                <tr>
                                    <th>Phone Number :</th>
                                    <td>{user.phone}</td>
                                </tr>
                                <tr>
                                    <th>Address :</th>
                                    <td>
                                        {user.address} - {user.pin_code},{" "}
                                        {user.city_name}, {user.state_name},{" "}
                                        {user.country_name}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                )}
                {activeStep == 2 && (
                    <div id="Step1" className="py-3">
                        <table className="table table-bordered">
                            <thead>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </thead>
                            <tbody>
                                {products.map((product) => (
                                    <tr key={product.id}>
                                        <td className="d-flex flex-row">
                                            <img
                                                className="pic-1"
                                                src={
                                                    baseUrl +
                                                    "/public/products/" +
                                                    product.thumbnail_img
                                                }
                                                alt={product.product_name}
                                                width="100px"
                                            />
                                            <div className="ml-2">
                                                {product.product_name}
                                                {product.color_code && (
                                                    <div className="d-flex">
                                                        <b>Color : </b>
                                                        <label
                                                            style={{
                                                                backgroundColor: product.color_code,
                                                                marginLeft:"10px",
                                                                borderRadius:"50%",
                                                                cursor: "auto",
                                                                height: "20px",
                                                                width: "20px",
                                                                display:
                                                                    "inline-block",
                                                            }}
                                                        ></label>
                                                    </div>
                                                )}
                                                <ul>
                                                    {attributes.map((row) => {
                                                        if (product.attrvalues) {
                                                            let values = product.attrvalues.split(",");

                                                            return values.map((item) => {
                                                                let arrItem = item.split(":");
                                                                var attri = arrItem[0];
                                                                var attriValue = attrvalues.find(item => item.id == arrItem[1])['value'] ?? null;
                                                                
                                                                if (attri) {
                                                                    if (row.id == attri) {
                                                                        return (
                                                                            <li key={row.id}>
                                                                                <b>{row.title} : </b> {attriValue}
                                                                            </li>
                                                                        );
                                                                    }

                                                                    return null;
                                                                }
                                                            });

                                                        }
                                                    })}
                                                </ul>
                                                {product.shipping_charges ==
                                                "free" ? (
                                                    <span>Free Delivery</span>
                                                ) : (
                                                    <span>
                                                        Delivery Charges :
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
                                            {product.qty}
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
                                                          product.taxable_price
                                                      ) * product.qty
                                                    : parseInt(
                                                          product.taxable_price
                                                      ) *
                                                          product.qty +
                                                      parseInt(charges)}
                                            </span>
                                        </td>
                                    </tr>
                                ))}
                                <tr>
                                    <td colSpan="3" align="right">
                                        <b>Total Amount</b>
                                    </td>
                                    <td className="">
                                        {generalSettings.currency}
                                        <span>{calculateTotal()}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                )}
                {activeStep == 3 && (
                    <div id="Step1" className="py-3">
                        <div className="card">
                            <div className="card-header"> Payment </div>
                            <div className="card-body">
                                <ul className="list-group">
                                    {payment_method.map((payButton) => (
                                        <li
                                            className="list-group-item"
                                            key={payButton.id}
                                        >
                                            {payButton.payment_name ==
                                                "Paypal" &&
                                                payButton.payment_status ==
                                                    "1" && (
                                                    <>
                                                        <input
                                                            type="radio"
                                                            name="pay_method"
                                                            value="paypal"
                                                            onChange={
                                                                handleChange
                                                            }
                                                            required
                                                        />
                                                        <img
                                                            src={
                                                                baseUrl +
                                                                "/public/images/paypal.png"
                                                            }
                                                            alt=""
                                                            height="20px"
                                                        />
                                                    </>
                                                )}
                                            {payButton.payment_name ==
                                                "COD" &&
                                                payButton.payment_status ==
                                                    "1" && (
                                                    <>
                                                        <input
                                                            type="radio"
                                                            name="pay_method"
                                                            value="cod"
                                                            onChange={
                                                                handleChange
                                                            }
                                                            required
                                                        />
                                                        <img
                                                            src={
                                                                baseUrl +
                                                                "/public/images/cod.png"
                                                            }
                                                            alt=""
                                                            height="20px"
                                                        />
                                                        <input
                                                            type="text"
                                                            hidden
                                                            name="razor_key"
                                                        />
                                                    </>
                                                )}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </div>
                    </div>
                )}
            </div>
            <div className="multi-footer">
                <ul className="d-flex justify-content-end">
                    {activeStep > 1 && (
                        <li className="ml-2">
                            <button
                                type="button"
                                className="btn btn-primary"
                                onClick={() => {
                                    handleStep(activeStep - 1);
                                }}
                            >
                                Prev
                            </button>
                        </li>
                    )}
                    {activeStep > 0 && activeStep < 3 && (
                        <li className="ml-2">
                            <button
                                type="button"
                                className="btn btn-primary"
                                onClick={() => {
                                    handleStep(activeStep + 1);
                                }}
                            >
                                Next
                            </button>
                        </li>
                    )}
                    {activeStep > 2 && (
                        <li className="ml-2">
                            <input
                                type="submit"
                                className="btn btn-primary"
                                value="Complete Order"
                            />
                        </li>
                    )}
                </ul>
            </div>
        </form>
    );
};

export default MultiForm;
