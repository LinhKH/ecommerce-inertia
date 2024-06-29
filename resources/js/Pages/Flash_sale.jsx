import React from "react";
import ProductGrid from "./ProductGrid";
import OwlCarousel from "react-owl-carousel";
import "owl.carousel/dist/assets/owl.carousel.css";
import "owl.carousel/dist/assets/owl.theme.default.css";
import { usePage } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";

function Flash_sale() {
    const { flash_products } = usePage().props;

    const products = flash_products.filter((product) => {
        const datetime = product.flash_date_range.split("-");
        const currentDatetime = new Date();

        let startDatetime = "";
        let endDatetime = "";

        if (product.flash_date_range !== "") {
            startDatetime = new Date(datetime[0]);
            endDatetime = new Date(datetime[1]);
        }

        if (
            currentDatetime >= startDatetime &&
            currentDatetime <= endDatetime
        ) {
            return product;
        }
        return null;
    });

    const options = {
        // Specify the options for Owl Carousel here
        loop: false,
        margin: 15,
        nav: false,
        responsive: {
            0: { items: 1 },
            600: { items: 2 },
            1000: { items: 4 },
        },
    };

    return products.length > 0 ? (
        <section id="flash-sale" className="py-4">
            <div className="container-xl container-fluid">
                <div className="row">
                    <div className="col-12">
                        <div className="section-heading">
                            <h2 className="title">Flash Sale</h2>
                            <a
                                href={baseUrl + "/flash-products"}
                                className="btn btn-primary"
                            >
                                Show All
                            </a>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="col-12">
                        <OwlCarousel
                            className="owl-theme product-carousel"
                            {...options}
                        >
                            {products.map((product) => {
                                return (
                                    <div key={product.id}>
                                        <ProductGrid
                                            key={product.id}
                                            product={product}
                                        />
                                    </div>
                                );
                            })}
                        </OwlCarousel>
                    </div>
                </div>
            </div>
        </section>
    ) : (
        ""
    );
}

export default Flash_sale;
