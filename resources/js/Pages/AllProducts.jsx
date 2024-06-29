import React, { useState, useEffect } from "react";
import { usePage, Link, router, useForm } from "@inertiajs/react";
import Pagination from "../Components/Pagination";
import ProductGrid from "./ProductGrid";
import Sidebar from "./Sidebar";
import { baseUrl } from "../Components/Baseurl";

function AllProducts() {
    const {
        slug,
        cat_detail,
        products,
        breadcrumb,
        links,
        keyword,
        url_search,
    } = usePage().props;
    const [change, setChange] = useState(false);

    const { data, setData, get } = useForm({
        sort: "latest",
        brand: [],
        min_price: "0",
        max_price: "100000",
    });

    const handleFilter = (e) => {
        router.get(
            url_search,
            {
                ...data,
            },
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (res) => {
                    setChange(false);
                },
            }
        );
    };

    const handleChange = (e) => {
        setData({ ...data, [e.target.name]: e.target.value });
        setChange(true);
    };

    useEffect(() => {
        if (change) handleFilter();
    }, [change]);

    const ProductAvailable = products.data.length > 0;
    return (
        <>
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    {keyword != null ? (
                        <h2>Search : "{keyword}"</h2>
                    ) : cat_detail != null ? (
                        <h2>{cat_detail.category_name}</h2>
                    ) : (
                        <h2>All Products</h2>
                    )}
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item">
                                <Link href={baseUrl}>Home</Link>
                            </li>
                            {breadcrumb != null ? (
                                breadcrumb.map((value) =>
                                    value.id == cat_detail.id ? (
                                        <li
                                            className="breadcrumb-item active"
                                            key={value.id}
                                        >
                                            {value.category_name}{" "}
                                        </li>
                                    ) : (
                                        <li
                                            className="breadcrumb-item"
                                            key={value.id}
                                        >
                                            <Link
                                                href={
                                                    baseUrl +
                                                    "/c/" +
                                                    value.category_slug
                                                }
                                            >
                                                {value.category_name}
                                            </Link>
                                        </li>
                                    )
                                )
                            ) : (
                                <li className="breadcrumb-item">
                                    All Products
                                </li>
                            )}
                        </ol>
                    </nav>
                </div>
            </div>
            <form onSubmit={handleFilter}>
                <div className="container-xl container-fluid">
                    <div className="row">
                        <div className="col-md-3">
                            <Sidebar
                                filterData={data}
                                filterSetData={setData}
                                handleFilter={handleFilter}
                            />
                        </div>
                        <div className="col-md-9">
                            <div className="row">
                                <div className="col-md-12">
                                    <div className="content-box">
                                        <div className="row">
                                            <div className="col-md-4 d-flex flex-row align-items-center">
                                                <h5 className="title">
                                                    {slug}
                                                </h5>
                                                <p className="result-count">
                                                    Showing {products.from} to{" "}
                                                    {products.to} of{" "}
                                                    {products.total}
                                                </p>
                                            </div>
                                            <div className="col-md-4"></div>
                                            <div className="col-md-4 d-flex flex-row justify-content-between align-items-center">
                                                <label
                                                    htmlFor=""
                                                    className="text-nowrap my-auto mr-2"
                                                >
                                                    Sort By
                                                </label>
                                                <select
                                                    name="sort"
                                                    className="form-control"
                                                    value={data.sort}
                                                    onChange={handleChange}
                                                >
                                                    <option value="latest">
                                                        Latest
                                                    </option>
                                                    <option value="oldest">
                                                        Oldest
                                                    </option>
                                                    <option value="l-h">
                                                        Price:Low to High
                                                    </option>
                                                    <option value="h-l">
                                                        Price:High to Low
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {ProductAvailable ? (
                                    products.data.map((product) => (
                                        <div
                                            key={product.id}
                                            className="col-lg-4 col-md-6 col-sm-6 mb-5"
                                        >
                                            <ProductGrid
                                                key={product.id}
                                                product={product}
                                            />
                                        </div>
                                    ))
                                ) : (
                                    <div className="col-12 mb-5">
                                        <div className="text-center">
                                            No Products Found
                                        </div>
                                    </div>
                                )}
                                {ProductAvailable &&
                                    products.from != products.last_page && (
                                        <div className="col-12 mb-5">
                                            <Pagination
                                                links={products.links}
                                            />
                                        </div>
                                    )}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </>
    );
}

export default AllProducts;
