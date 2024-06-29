import React from 'react'
import ProductGrid from './ProductGrid'
import Preloader from '../Components/Preloader'
import {Link,usePage} from '@inertiajs/react'
import {baseUrl} from '../Components/Baseurl'

function WishList() {
    const {userSession,products,flash} = usePage().props;
    const ProductAvailable = products.length > 0;
    return (
        <div id="site-content">
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>Wishlist</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item"><Link href={baseUrl}>Home</Link></li>
                            <li className="breadcrumb-item active">Wishlist</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div className="container-xl container-fluid">
                <div className="row wishlist-data">
                    {ProductAvailable ? (
                        products.map((product) => (
                            <div key={product.id} className="col-lg-3 col-md-6 mb-5">
                                <ProductGrid key={product.id} product={product} />
                            </div>
                        ))
                    ) : (
                        <div className="col-md-12 text-center">
                            <h4 className="mb-2">Your Wishlist is Empty</h4>
                            <Link href={baseUrl} className="btn btn-primary">Add Items to Wishlist</Link>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}

export default WishList;
