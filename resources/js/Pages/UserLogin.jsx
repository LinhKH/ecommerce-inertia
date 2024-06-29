import React from "react";
import Preloader from "../Components/Preloader";
import { Link, usePage, useForm } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";
import swal from "@sweetalert/with-react";

function UserLogin() {
    const { userSession, flash } = usePage().props;

    const { data, setData, post, processing, errors } = useForm({
        username: "",
        password: "",
    });

    function handleSubmit(e) {
        e.preventDefault();

        post(baseUrl + "/user_login", {
            preserveScroll: true,
            preserveState: true,
            onSuccess: (response) => {
                if (response.props.flash.success) {
                    swal({
                        title: "Loggedin Successfully.",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500,
                    });
                    setTimeout(() => {
                        window.location.href = baseUrl;
                    }, 1000);
                }
            },
        });
    }

    return (
        <div id="site-content" className="py-5">
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>Login</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item">
                                <Link href={baseUrl}>Home</Link>
                            </li>
                            <li className="breadcrumb-item active">Login</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div className="container">
                <div className="row">
                    <div className="col-lg-4 offset-lg-4 col-md-6 offset-md-3">
                        <div className="signup-form">
                            {processing && <Preloader />}
                            <form
                                className="form-horizontal mb-3"
                                onSubmit={handleSubmit}
                                autoComplete="off"
                            >
                                <h4 className="user-heading">Login</h4>
                                <input
                                    type="hidden"
                                    className="url"
                                    value="{{url('/')}}"
                                />
                                <div className="form-group">
                                    <input
                                        type="email"
                                        name="username"
                                        className="form-control"
                                        placeholder="Email Address"
                                        value={data.username}
                                        onChange={(e) =>
                                            setData("username", e.target.value)
                                        }
                                    />
                                    {errors.username && (
                                        <div
                                            className="alert alert-danger mt-2"
                                            role="alert"
                                        >
                                            {errors.username}
                                        </div>
                                    )}
                                </div>
                                <div className="form-group">
                                    <input
                                        type="password"
                                        name="password"
                                        className="form-control"
                                        placeholder="Password"
                                        value={data.password}
                                        onChange={(e) =>
                                            setData("password", e.target.value)
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

                                <div className="d-flex flex-row justify-content-between">
                                    <input
                                        type="submit"
                                        disabled={processing}
                                        name="save"
                                        className="btn btn-primary login-btn"
                                        value="Login"
                                        required
                                    />
                                    <Link
                                        href={baseUrl + "/forgot-password"}
                                        className="forgot-password align-self-center"
                                    >
                                        forgot password
                                    </Link>
                                </div>
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
                            <span className="signup-link">
                                <Link href={baseUrl + "/signup"}>
                                    Create Account
                                </Link>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default UserLogin;
