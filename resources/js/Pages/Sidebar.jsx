import React, { useState, useEffect } from "react";
import { usePage, Link } from "@inertiajs/react";
import ChildCategory from "../Components/ChildCategory";
import { baseUrl } from "../Components/Baseurl";

function Sidebar({ filterData, filterSetData, handleFilter }) {
    const { all_category, cat_detail, brands } = usePage().props;

    const handleChange = (e) => {
        if (e.target.name == "brand") {
            // debugger;
            let { value, checked } = e.target;
            if (checked) {
                filterData[e.target.name].push(value);
            } else {
                let index = filterData[e.target.name].indexOf(value);
                if (index > -1) {
                    filterData[e.target.name].splice(index, 1);
                }
            }
            handleFilter();

        } else {
            filterSetData({ ...filterData, [e.target.name]: e.target.value });
        }
    };

    return (
        <div className="accordion" id="accordionExample1">
            <div className="accordion-item">
                <h2 className="accordion-header" id="headingOne">
                    <button
                        className="accordion-button"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseOne"
                        aria-expanded="true"
                        aria-controls="collapseOne"
                    >
                        Product categories
                    </button>
                </h2>
                <div
                    id="collapseOne"
                    className="accordion-collapse collapse show"
                    aria-labelledby="headingOne"
                    // data-bs-parent="#accordionExample"
                >
                    <div className="accordion-body" style={{border: "1px solid", boxShadow: "3px 3px #059473 , 0em 0 .4em olive"}}>
                        <ChildCategory key={1} />
                    </div>
                </div>
            </div>
            <div className="accordion-item">
                <h2 className="accordion-header" id="headingTwo">
                    <button
                        className="accordion-button"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo"
                        aria-expanded="false"
                        aria-controls="collapseTwo"
                    >
                        Filter By Price
                    </button>
                </h2>
                <div
                    id="collapseTwo"
                    className="accordion-collapse collapse show"
                    aria-labelledby="headingTwo"
                    // data-bs-parent="#accordionExample"
                >
                    <div className="accordion-body" style={{border: "1px solid", boxShadow: "3px 3px #059473 , 0em 0 .4em olive"}}>
                        <div className="row">
                            <div className="col-md-6">
                                <span className="d-block">Min</span>
                                <input
                                    type="number"
                                    name="min_price"
                                    min="0"
                                    max="1000000"
                                    className="price-range-field"
                                    onChange={handleChange}
                                    value={filterData.min_price}
                                />
                            </div>
                            <div className="col-md-6">
                                <span className="d-block">Max</span>
                                <input
                                    type="number"
                                    name="max_price"
                                    min="0"
                                    max="1000000"
                                    className="price-range-field"
                                    onChange={handleChange}
                                    value={filterData.max_price}
                                />
                            </div>
                            <div className="col-md-12">
                                <button
                                    type="button"
                                    className="btn btn-primary btn-sm mt-2"
                                    onClick={handleFilter}
                                >
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {brands && brands.length > 0 && (
                <div className="accordion-item">
                    <h2 className="accordion-header" id="headingThree">
                        <button
                            className="accordion-button"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseThree"
                            aria-expanded="true"
                            aria-controls="collapseThree"
                        >
                            Brands
                        </button>
                    </h2>
                    <div
                        id="collapseThree"
                        className="accordion-collapse collapse show"
                        aria-labelledby="headingThree"
                        // data-bs-parent="#accordionExample"
                    >
                        <div className="accordion-body" style={{border: "1px solid", boxShadow: "3px 3px #059473 , 0em 0 .4em olive"}}>
                            {brands.map((brand_item) => (
                                <div
                                    className="radio-button"
                                    key={brand_item.id}
                                >
                                    <input
                                        id={brand_item.id}
                                        type="checkbox"
                                        className="brand"
                                        name="brand"
                                        onChange={handleChange}
                                        value={brand_item.id}
                                    />
                                    <label
                                        htmlFor={brand_item.id}
                                        className="ml-2"
                                    >
                                        {" " + brand_item.brand_name}
                                    </label>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}

export default Sidebar;
