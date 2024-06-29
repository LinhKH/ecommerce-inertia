import React from 'react'
import {Link, usePage } from '@inertiajs/react'
import {baseUrl} from '../Components/Baseurl'

function MyReviews() {
    const { reviews } = usePage().props;
    return(
        <div id="site-content">
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>My Reviews</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item"><Link href={baseUrl}>Home</Link></li>
                            <li className="breadcrumb-item active">My Reviews</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div className="message"></div>
            <div className="container-xl container-fluid">
                <div className="row">
                    <div className="col-md-12">
                    {reviews.data.map((row) => (
                        <div className="card mb-4" key={row.id}>
                            <h5 className="card-header">{row.product_name}</h5>
                            <div className="card-body">
                                <h5>{row.title}</h5>
                                <p>{row.desc}</p>
                                <ul className="show-review-rating mb-2">
                                    {Array.from({ length: 5 }).map((_, i) => (
                                        <li key={i} className={i < row.rating ? 'fa fa-star' : 'far fa-star'}></li>
                                    ))}
                                </ul>
                                {row.hide_by_admin == '1' && (
                                    <div className="alert alert-danger p-2 py-0 m-0 d-inline-block">
                                        Hidden by Admin
                                    </div>
                                )}
                                {row.approved == '0' && (
                                    <div className="alert alert-danger p-2 py-0 m-0 d-inline-block">
                                        Under Approval Process
                                    </div>
                                )}
                            </div>
                        </div>
                    ))}
                    </div>
                    {reviews.from != reviews.last_page &&
                        <div className="row">
                            <div className="col-12 mb-5">
                                <Pagination links={reviews.links} />
                            </div>
                        </div>}
                </div>
            </div>
        </div>
    )
}

export default MyReviews;