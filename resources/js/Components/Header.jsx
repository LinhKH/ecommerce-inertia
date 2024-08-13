import React from "react";
import { usePage, Link, useForm  } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";
// import { toast, ToastContainer } from 'react-toastify';
// import "react-toastify/dist/ReactToastify.css";

//import {Link} from '@inertiajs/react'
import { router } from '@inertiajs/react'
import swal from "@sweetalert/with-react";

function Header() {
    const {
        generalSettings,
        sitePages,
        userSession,
        userWishlist,
        userCart,
        all_category,
        flash,
    } = usePage().props;

    if (flash.success == "logout") {
        swal({
            title: "Logged out Successfully.",
            icon: "success",
            showConfirmButton: false,
            timer: 1500,
        });
    }

    const { data, setData } = useForm({
        keyword: new URL(window.location.href).searchParams.get("keyword")
            ? new URL(window.location.href).searchParams.get("keyword")
            : "",
        category: new URL(window.location.href).searchParams.get("category")
            ? new URL(window.location.href).searchParams.get("category")
            : "all",
    });

    const handleChange = (e) => {
        setData({ ...data, [e.target.name]: e.target.value });
    };

    function handleSubmit(e) {
        e.preventDefault();
        router.get(baseUrl + "/search", data);
    };

    const childCategoryList = (id) => {
        let children = all_category.filter((cat) => cat.parent_category == id);
        return (
            children.length &&
            children.map((item) => (
                <React.Fragment key={item.id}>
                    <option value={item.category_slug}>
                        {item.category_name}
                    </option>
                    {childCategoryList(item.id)}{" "}
                </React.Fragment>
            ))
        );
    };

    return (
        <div id="wrapper">
            {/* <ToastContainer /> */}
            <header id="header">
                <div className="top-header d-lg-block">
                    <div className="container-xl container-fluid">
                        <div className="row">
                            <div className="col-md-8">
                                <ul className="top-address">
                                    {generalSettings.email != "" && (
                                        <li>
                                            <i className="fa fa-envelope"></i>{" "}
                                            {generalSettings.email}
                                        </li>
                                    )}
                                    {generalSettings.phone != "" && (
                                        <li>
                                            <i className="fa fa-phone"></i>{" "}
                                            {generalSettings.phone}
                                        </li>
                                    )}
                                </ul>
                            </div>
                            <div className="col-md-4">
                                <ul className="top-right-menu text-end">
                                    <li>
                                        <span className="welcome-message">
                                            welcome to our store!
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="container-xl container-fluid">
                    <div className="row my-2">
                        <div className="col-lg-3 col-md-4 col-sm-12 align-self-center">
                            <div className="logo">
                                <Link href={baseUrl}>
                                    <img
                                        src={
                                            baseUrl +
                                            "/public/site/" +
                                            generalSettings.site_logo
                                        }
                                        alt={generalSettings.site_logo}
                                    />
                                </Link>
                            </div>
                        </div>
                        <div className="col-lg-5 col-md-8 col-sm-12">
                            <div className="searchbox position-relative my-3">
                                <form onSubmit={handleSubmit}
                                    method="GET"
                                    className="search-form rounded-0 d-flex"
                                >
                                    <input
                                        type="text"
                                        className="form-control rounded-0"
                                        id="search"
                                        name="keyword"
                                        value={data.keyword}
                                        onChange={handleChange}
                                        placeholder="Search Product Here..."
                                    />
                                    <select
                                        className="form-select search-categories"
                                        value={data.category}
                                        onChange={handleChange}
                                        name="category"
                                        aria-label="Default select example"
                                    >
                                            <option value="all">
                                                All Categories
                                            </option>
                                            {all_category.map((item) => (
                                                <React.Fragment key={item.id}>
                                                    {item.parent_category ==
                                                        "0" && (
                                                        <option
                                                            value={
                                                                item.categpry_slug
                                                            }
                                                        >
                                                            {item.category_name}
                                                        </option>
                                                    )}
                                                    {childCategoryList(item.id)}{" "}
                                                </React.Fragment>
                                            ))}
                                    </select>
                                    <button
                                        type="submit"
                                        className="btn btn-primary rounded-0"
                                    >
                                        <i className="fa fa-search"></i>
                                    </button>
                                </form>
                                <div className="search-content position-absolute"></div>
                            </div>
                        </div>
                        <div className="col-lg-4 col-md-12 col-sm-12">
                            <ul className="header-links ml-auto mr-0 text-lg-right text-center">
                                {userSession ? (
                                    <li>
                                        <div className="dropdown">
                                            <a
                                                href="#"
                                                className="dropdown-toggle"
                                                id="dropdownMenuButton"
                                                data-toggle="dropdown"
                                            >
                                                <i className="far fa-user"></i>{" "}
                                                Hello,{" "}
                                                {userSession.user_name.substring(
                                                    0,
                                                    10
                                                ) + "..."}
                                            </a>
                                            <div
                                                className="dropdown-menu"
                                                aria-labelledby="dropdownMenuButton"
                                            >
                                                <Link
                                                    className="dropdown-item"
                                                    href={
                                                        baseUrl + "/my-profile"
                                                    }
                                                >
                                                    My Profile
                                                </Link>
                                                <Link
                                                    className="dropdown-item"
                                                    href={baseUrl + "/cart"}
                                                >
                                                    My Cart
                                                </Link>
                                                <Link
                                                    className="dropdown-item"
                                                    href={
                                                        baseUrl + "/my_orders"
                                                    }
                                                >
                                                    My Orders
                                                </Link>
                                                <Link
                                                    className="dropdown-item"
                                                    href={
                                                        baseUrl + "/my-reviews"
                                                    }
                                                >
                                                    My Reviews
                                                </Link>
                                                <Link
                                                    className="dropdown-item"
                                                    href={
                                                        baseUrl +
                                                        "/changepassword"
                                                    }
                                                >
                                                    Change Password
                                                </Link>
                                                <a
                                                    className="dropdown-item"
                                                    href={baseUrl + "/logout"}
                                                >
                                                    Log Out
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                ) : (
                                    <>
                                        <li>
                                            <Link
                                                href={baseUrl + "/user_login"}
                                            >
                                                <i className="far fa-user"></i>{" "}
                                                My Account
                                            </Link>
                                        </li>
                                    </>
                                )}
                                <li>
                                    <Link href={baseUrl + "/wishlists"}>
                                        <i className="far fa-heart"></i>{" "}
                                        Wishlist
                                    </Link>
                                    <span className="wishlist-count">
                                        {userWishlist}
                                    </span>
                                </li>
                                <li>
                                    <Link href={baseUrl + "/cart"}>
                                        <i className="fas fa-shopping-cart"></i>{" "}
                                        Cart
                                    </Link>
                                    <span className="cartlist">{userCart}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
            <nav className="navbar navbar-expand-lg">
                <div className="container-xl container-fluid">
                    <div className="navbar-brand" href="#">
                        <div className="nav-item dropdown">
                            <a
                                className="nav-link dropdown-toggle"
                                href="#"
                                id="navbarDropdownMenuLink"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                shopping by Categories
                            </a>
                            <ul
                                className="dropdown-menu"
                                aria-labelledby="navbarDropdownMenuLink"
                            >
                                {all_category &&
                                    all_category.map((cat_menu) => {
                                        if (cat_menu.parent_category == "0") {
                                            return (
                                                <li key={cat_menu.id}>
                                                    <Link
                                                        className="dropdown-item"
                                                        href={
                                                            baseUrl +
                                                            "/c/" +
                                                            cat_menu.category_slug
                                                        }
                                                    >
                                                        {cat_menu.category_name}
                                                    </Link>
                                                    {/* <Link className="dropdown-item" href={'localhost:8000/c/' + cat_menu.category_slug}>{cat_menu.category_name}</Link> */}
                                                </li>
                                            );
                                        }
                                        return null;
                                    })}
                            </ul>
                        </div>
                    </div>
                    <button
                        className="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarNavDropdown"
                        aria-controls="navbarNavDropdown"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        {/* <span className="navbar-toggler-icon"></span> */}
                        <i className="fa-solid fa-bars"></i>
                    </button>
                    <div
                        className="collapse navbar-collapse"
                        id="navbarNavDropdown"
                    >
                        <ul className="navbar-nav">
                            <li className="nav-item">
                                <Link
                                    className="nav-link active"
                                    aria-current="page"
                                    href={baseUrl}
                                >
                                    Home
                                </Link>
                            </li>
                            <li className="nav-item">
                                <Link
                                    className="nav-link active"
                                    aria-current="page"
                                    href={baseUrl + "/all-products"}
                                >
                                    Shop
                                </Link>
                            </li>
                            {sitePages.map(
                                (page) =>
                                    page.show_in_header == "1" && (
                                        <li
                                            className="nav-item"
                                            key={page.page_id}
                                        >
                                            <Link
                                                href={
                                                    baseUrl +
                                                    "/" +
                                                    page.page_slug
                                                }
                                                className="nav-link active"
                                                aria-current="page"
                                            >
                                                {page.page_title}
                                            </Link>
                                        </li>
                                    )
                            )}
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    );
}
export default Header;
