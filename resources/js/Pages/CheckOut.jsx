import React, { useState, useEffect } from "react";
import { usePage, useForm } from "@inertiajs/react";
import Preloader from "../Components/Preloader";
// import swal from '@sweetalert/with-react'
import { baseUrl } from "../Components/Baseurl";
// import MultiStep from 'react-multistep'
import MultiForm from "../Components/MultiForm";

function CheckOut() {
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
        rezorkey,
    } = usePage().props;

    const [stateList, setStateList] = useState(
        user.country != null
            ? states.filter((state) => state.country == user.country)
            : []
    );
    const [cityList, setCityList] = useState(
        user.state != null
            ? cities.filter((city) => city.state == user.state)
            : []
    );

    const handleCountryChange = (val) => {
        setStateList(states.filter((state) => state.country == val));
    };

    const handleStateChange = (val) => {
        setCityList(cities.filter((city) => city.state == val));
    };

    const handleChange = (e) => {
        const key = e.target.name;
        const value = e.target.value;
        if (key == "country") {
            handleCountryChange(value);
        } else if (key == "state") {
            handleStateChange(value);
        }
    };

    const [charges, setCharges] = useState(
        userSession.user_city != null
            ? cities.filter((city) => city.id == userSession.user_city)[0].cost_city
            : null
    );

    const { processing } = useForm({});

    const handleSubmit = async () => {
        alert(1);
        const razorpay = window.Razorpay({
            key: rezorkey,
            amount: 100 * 100,
            name: generalSettings.site_name,
            order_id: "",
            handler: async (transaction) => {
                const tr = transaction.razorpay_payment_id;
                var formdata = $("#form-1,#form-2,#form-3").serialize();
                window.location.href =
                    `http://localhost:8000/pay-with-razorpay/100/${tr}?` +
                    formdata;

                if (response.ok) {
                    window.location.href = `http://localhost:8000/pay-with-razorpay/100/{tr}`;
                }
            },
        });

        razorpay.open();
    };

    return (
        <div id="site-content">
            <div className="container-xl container-fluid">
                <div className="row">
                    <div className="col-12">
                        <MultiForm></MultiForm>
                        {processing && <Preloader />}
                    </div>
                </div>
            </div>
        </div>
    );
}
export default CheckOut;

const StepOne = () => {
    return <>fdgdfgdfgdfg</>;
};

const StepTwo = () => {
    return <>fdgdfgdfgdfg</>;
};
