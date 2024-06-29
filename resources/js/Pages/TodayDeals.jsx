import React from "react";
import ProductGrid from "./ProductGrid";
import OwlCarousel from "react-owl-carousel";
import "owl.carousel/dist/assets/owl.carousel.css";
import "owl.carousel/dist/assets/owl.theme.default.css";
import { usePage, Link } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";

function TodayDeals() {
    const { today_deal_products } = usePage().props;

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
    return today_deal_products.length > 0 ? (
        <section id="product_box" className="py-3">
            <div className="container">
                <div className="row">
                    <div className="col-12">
                        <div className="section-heading">
                            <h2 className="title">Today Deals</h2>
                            <Link
                                className="btn btn-primary text-white"
                                href={baseUrl + "/today-deals"}
                            >
                                Show All
                            </Link>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="col-12">
                        <OwlCarousel
                            className="owl-theme product-carousel"
                            {...options}
                        >
                            {today_deal_products.map((today_deal) => {
                                return (
                                    <div key={today_deal.id}>
                                        <ProductGrid
                                            key={today_deal.id}
                                            product={today_deal}
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
        <></>
    );
}

export default TodayDeals;
