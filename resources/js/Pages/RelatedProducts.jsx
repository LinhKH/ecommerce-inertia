import React from 'react';
import {usePage} from '@inertiajs/react';
import ProductGrid from './ProductGrid';
import OwlCarousel from 'react-owl-carousel';
import 'owl.carousel/dist/assets/owl.carousel.css';
import 'owl.carousel/dist/assets/owl.theme.default.css';

function RelatedProducts() {
    const { related } = usePage().props;

    const options = {
        // Specify the options for Owl Carousel here
        loop: false,
        margin: 15,
        nav: false,
        responsive: {
        0: { items: 1},
        600: { items: 2 },
        1000: { items: 4 }
        }
    };
    return (
        <section id="product_box" className="py-3">
            <div className="row">
                <div className="col-12">
                    <div className="section-heading">
                        <h2 className="title">Related Product</h2>
                    </div>
                </div>
            </div>
            <div className="row">
                <div className='col-12'>
                    <OwlCarousel className="owl-theme product-carousel" {...options}>
                        {related.map((value) => {
                            return(
                            <div key={value.id}>
                                <ProductGrid key={value.id} product={value} />
                            </div>
                            )
                        })}
                    </OwlCarousel>
                </div>
            </div>
        </section>
    )
}

export default RelatedProducts;
    