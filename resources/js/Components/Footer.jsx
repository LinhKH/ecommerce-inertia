import React from "react";
import { usePage, Link } from "@inertiajs/react";
import { baseUrl } from "./Baseurl";

function Footer() {
    const { generalSettings, sitePages, all_category } = usePage().props;
    return (
        <div className="footer-widget">
            <div className="container-xl container-fluid">
                <div className="row">
                    <div className="col-lg-3 col-md-6 mb-5">
                        <div className="site-info-widget">
                            <div className="footer-logo">
                                <a href={baseUrl}>
                                    <h6>{generalSettings.site_name}</h6>
                                </a>
                            </div>
                            {/* Use dangerouslySetInnerHTML to render the HTML content */}
                            <p
                                dangerouslySetInnerHTML={{
                                    __html: generalSettings.description,
                                }}
                            ></p>
                        </div>
                    </div>
                    <div className="col-lg-3 col-md-6 mb-5">
                        <div className="widget-box">
                            <h6 className="widget-title">Categories</h6>
                            <ul className="widget-list">
                                {all_category &&
                                    all_category.map(
                                        (cat_menu) =>
                                            cat_menu.parent_category == "0" && (
                                                <li key={cat_menu.id}>
                                                    <Link
                                                        href={
                                                            baseUrl +
                                                            "/c/" +
                                                            cat_menu.category_slug
                                                        }
                                                    >
                                                        <i
                                                            className="fa fa-angle-right"
                                                            aria-hidden="true"
                                                        ></i>{" "}
                                                        {cat_menu.category_name}
                                                    </Link>
                                                </li>
                                            )
                                    )}
                            </ul>
                        </div>
                    </div>
                    <div className="col-lg-3 col-md-6 mb-5">
                        <div className="widget-box">
                            <h6 className="widget-title">Links</h6>
                            <ul className="widget-list">
                                {sitePages.map(
                                    (page) =>
                                        page.show_in_footer == "1" && (
                                            <li key={page.page_id}>
                                                <Link
                                                    href={
                                                        baseUrl +
                                                        "/" +
                                                        page.page_slug
                                                    }
                                                    key={page.page_id}
                                                >
                                                    <i
                                                        className="fa fa-angle-right"
                                                        aria-hidden="true"
                                                    ></i>{" "}
                                                    {page.page_title}
                                                </Link>
                                            </li>
                                        )
                                )}
                            </ul>
                        </div>
                    </div>
                    <div className="col-lg-3 col-md-6 d-flex justify-content-left justify-content-lg-center">
                        <div className="contact-widget">
                            <h6 className="widget-title">Contact Us</h6>
                            <ul className="contact-list">
                                {generalSettings.address && (
                                    <li>
                                        <span className="icon">
                                            <i className="fas fa-map-marker-alt"></i>
                                        </span>
                                        <span>
                                            <b>Address: </b>
                                            {generalSettings.address}
                                        </span>
                                    </li>
                                )}
                                {generalSettings.email && (
                                    <li>
                                        <span className="icon">
                                            <i className="fas fa-envelope"></i>
                                        </span>
                                        <span>
                                            <b>Email: </b>
                                            {generalSettings.email}
                                        </span>
                                    </li>
                                )}
                                {generalSettings.phone && (
                                    <li>
                                        <span className="icon">
                                            <i className="fas fa-phone-alt"></i>
                                        </span>
                                        <span>
                                            <b>Contact Us: </b>
                                            {generalSettings.phone}
                                        </span>
                                    </li>
                                )}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div className="footer-bottom py-3">
                <div className="container">
                    <div className="row">
                        <div className="col-md-6 col-12 align-self-center">
                            <input
                                type="hidden"
                                className="demo"
                                value={baseUrl}
                            ></input>
                            <span>
                                {" "}
                                <strong>
                                    {generalSettings.copyright} by{" "}
                                    <a href="https://news-portal.shop/">
                                        Linh Dev
                                    </a>
                                    .
                                </strong>{" "}
                                All rights reserved
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
export default Footer;
