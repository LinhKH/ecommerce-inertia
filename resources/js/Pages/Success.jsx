import { baseUrl } from '../Components/Baseurl';
import {Link} from '@inertiajs/react'

function Success(){
    return(
        <div className="successfull-page py-5 text-center">
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>Payment Successful</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item"><Link href={baseUrl}>Home</Link></li>
                            <li className="breadcrumb-item active">Success</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div className="row m-0">
                <div className="offset-md-4 col-md-4">
                    <div className="success-message">
                        <div className="icon h2">
                            <i className="fas fa-check-circle"></i>
                        </div>
                        <h4 className="mb-2">Your Order is Confirmed!</h4>
                    </div>
                </div>
                <div className="col-md-4"></div>
                <div className="col-md-12">
                    <div className="booking-btn text-center">
                        <a href={baseUrl+'/my_orders'} className="btn btn-primary text-center">My Orders</a>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Success;