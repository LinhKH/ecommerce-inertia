import React from 'react';
import ProductGrid from './ProductGrid';
import OwlCarousel from 'react-owl-carousel';
import 'owl.carousel/dist/assets/owl.carousel.css';
import 'owl.carousel/dist/assets/owl.theme.default.css';
import {usePage } from '@inertiajs/react';
import {baseUrl} from '../Components/Baseurl'

function LastestProduct() {
    const { latest_products } = usePage().props;
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
        latest_products.length > 0 ?
        <section id="product_box" className="py-3">
            <div className="container">
                <div className="row">
                    <div className="col-12">
                        <div className="section-heading">
                            <h2 className="title">Lastest Product</h2>
                            <a className="btn btn-primary text-white" href={baseUrl+'/search'}>Show All</a>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className='col-12'>
                        <OwlCarousel className="owl-theme product-carousel" {...options}>
                            {latest_products.map((latest_product) => {
                                return(
                                <div key={latest_product.id}>
                                    <ProductGrid key={latest_product.id}  product={latest_product} />
                                </div>
                                )
                            })}
                        </OwlCarousel>
                    </div>
                </div>
            </div>
        </section> : <></>
    )
}

export default LastestProduct