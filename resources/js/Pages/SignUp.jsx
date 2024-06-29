import React from "react";
import Preloader from "../Components/Preloader";
import { Link, usePage, useForm } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";
import swal from "@sweetalert/with-react";

function SignUp() {
    const { flash } = usePage().props;

    const { data, setData, post, processing, errors } = useForm({
        name: "",
        email: "",
        phone: "",
        password: "",
        con_password: "",
    });

    function handleSubmit(e) {
        e.preventDefault();
        if (data.password == data.con_password) {
            if (data != "") {
                post(baseUrl + "/signup");
            } else {
                window.location.href = "/";
            }
        } else {
            swal({
                title: "Enter Correct Confirm Password",
                icon: "warning",
            });
        }
    }
    return (
        <div id="site-content" className="py-5">
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>Signup</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item">
                                <Link href={baseUrl}>Home</Link>
                            </li>
                            <li className="breadcrumb-item active">Signup</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div className="container">
                <div className="row">
                    <div className="offset-lg-3 col-lg-6 col-md-8 offset-md-2">
                        <div className="signup-form">
                            {processing && <Preloader />}
                            <form
                                className="form-horizontal mb-3"
                                onSubmit={handleSubmit}
                                method="post"
                                autoComplete="off"
                            >
                                <h4 className="user-heading">Sign Up</h4>
                                <input
                                    type="hidden"
                                    className="url"
                                    value="{{url('/signup')}}"
                                />
                                <input
                                    type="hidden"
                                    className="url-login"
                                    value="{{url('/user_login')}}"
                                />
                                <div className="row">
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <input
                                                type="text"
                                                name="name"
                                                className="form-control"
                                                placeholder="Name"
                                                value={data.name}
                                                onChange={(e) =>
                                                    setData(
                                                        "name",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                            {errors.name && (
                                                <div
                                                    className="alert alert-danger mt-2"
                                                    role="alert"
                                                >
                                                    {errors.name}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <input
                                                type="email"
                                                name="email"
                                                className="form-control"
                                                placeholder="Email"
                                                value={data.email}
                                                onChange={(e) =>
                                                    setData(
                                                        "email",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                            {errors.email && (
                                                <div
                                                    className="alert alert-danger mt-2"
                                                    role="alert"
                                                >
                                                    {errors.email}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-md-12">
                                        <div className="form-group">
                                            <input
                                                type="number"
                                                name="phone"
                                                className="form-control"
                                                pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                                placeholder="Phone"
                                                value={data.phone}
                                                onChange={(e) =>
                                                    setData(
                                                        "phone",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                            {errors.phone && (
                                                <div
                                                    className="alert alert-danger mt-2"
                                                    role="alert"
                                                >
                                                    {errors.phone}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <input
                                                type="password"
                                                name="password"
                                                id="password"
                                                className="form-control"
                                                placeholder="Password"
                                                value={data.password}
                                                onChange={(e) =>
                                                    setData(
                                                        "password",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                            {errors.password && (
                                                <div
                                                    className="alert alert-danger mt-2"
                                                    role="alert"
                                                >
                                                    {errors.password}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                    <div className="col-md-6">
                                        <div className="form-group">
                                            <input
                                                type="password"
                                                name="con_password"
                                                className="form-control"
                                                placeholder="Confirm Password"
                                                value={data.con_password}
                                                onChange={(e) =>
                                                    setData(
                                                        "con_password",
                                                        e.target.value
                                                    )
                                                }
                                            />
                                            {errors.con_password && (
                                                <div
                                                    className="alert alert-danger mt-2"
                                                    role="alert"
                                                >
                                                    {errors.con_password}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>
                                <input
                                    type="submit"
                                    disabled={processing}
                                    name="save"
                                    className="btn btn-primary"
                                    value="Signup"
                                    required
                                />
                                {/* Display error message if exists */}
                                {flash.error && (
                                    <div
                                        className="alert alert-danger mt-2"
                                        role="alert"
                                    >
                                        {" "}
                                        {flash.error}{" "}
                                    </div>
                                )}
                                {/* Display success message if exists */}
                                {flash.success && (
                                    <div
                                        className="alert alert-success mt-2"
                                        role="alert"
                                    >
                                        {" "}
                                        {flash.success}{" "}
                                    </div>
                                )}
                            </form>
                            <span className="login-link">
                                {" "}
                                Already have an account{" "}
                                <Link href="user_login">Login</Link>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
export default SignUp;
