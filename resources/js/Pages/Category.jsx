import React from "react";
import OwlCarousel from "react-owl-carousel";
import "owl.carousel/dist/assets/owl.carousel.css";
import "owl.carousel/dist/assets/owl.theme.default.css";
import { usePage, Link } from "@inertiajs/react";

function Category() {
    const { url } = usePage();
    const { all_category } = usePage().props;
    const options = {
        // Specify the options for Owl Carousel here
        loop: false,
        margin: 15,
        nav: false,
        responsive: {
            0: { items: 3 },
            600: { items: 5 },
            1000: { items: 9 },
        },
    };
    return (
        <section id="category" className="py-5">
            <div className="container">
                <div className="row">
                    <div className="col-12">
                        <OwlCarousel
                            className="owl-theme category-carousel"
                            {...options}
                        >
                            {all_category.map((category) => (
                                <div className="item" key={category.id}>
                                    <div className="category-grid text-center">
                                        <h4>
                                            <Link
                                                href={
                                                    url +
                                                    "c/" +
                                                    category.category_slug
                                                }
                                            >
                                                {category.category_name}
                                            </Link>
                                        </h4>
                                    </div>
                                </div>
                            ))}
                        </OwlCarousel>
                    </div>
                </div>
            </div>
        </section>
    );
}

export default Category;
