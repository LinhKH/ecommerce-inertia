import React from "react";
import { baseUrl } from "../Components/Baseurl";
import ProductGrid from "./ProductGrid";
import Pagination from "../Components/Pagination";
import { Link } from "@inertiajs/react";

const AllTodayDeals = (props) => {
    const { today_deal_products } = props;
    return (
        <>
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>Today Deals</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item">
                                <Link href={baseUrl}>Home</Link>
                            </li>
                            <li className="breadcrumb-item active">
                                Today Deals
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <section id="product_box" className="py-3">
                <div className="container-xl container-fluid">
                    <div className="row">
                        {today_deal_products.data.map((item) => (
                            <div
                                className="col-lg-3 col-md-4 col-sm-6"
                                key={item.id}
                            >
                                <ProductGrid product={item} />
                            </div>
                        ))}
                    </div>
                    {today_deal_products.from !=
                        today_deal_products.last_page && (
                        <div className="row">
                            <div className="col-12 mb-5">
                                <Pagination links={today_deal_products.links} />
                            </div>
                        </div>
                    )}
                </div>
            </section>
        </>
    );
};

export default AllTodayDeals;
