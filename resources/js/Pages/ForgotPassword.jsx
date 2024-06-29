import React from "react"
import Preloader from '../Components/Preloader'
import {Link,usePage,useForm} from '@inertiajs/react'
import { baseUrl } from '../Components/Baseurl'
function ForgotPassword() {
    const {flash} = usePage().props;
    const {data, setData, post,processing,errors } = useForm({
        email: '',
    });

    function handleSubmit(e) {
        e.preventDefault()
        post(baseUrl+'/forgot-password');
    }

    return(
        <div id="site-content" className="py-5">
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>Forgot Password</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item"><Link href={baseUrl}>Home</Link></li>
                            <li className="breadcrumb-item active">Forgot Password</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div className="container">
                <div className="row">
                    <div className="offset-md-3 col-md-6">
                        <div className="signup-form">
                            {processing && <Preloader />}
                            <form className="form-horizontal mb-3" onSubmit={handleSubmit} autoComplete="off">
                                <h4 className="user-heading mb-4">Forgot Password</h4>
                                <input type="hidden" className="url" value="/" />
                                <div className="form-group mb-4">
                                    <input type="email" name="email" className="form-control" placeholder="Email Address"
                                    value={data.email} onChange={(e) => setData('email',e.target.value)}/>
                                     {errors.email && <div className="alert alert-danger mt-2" role="alert">{errors.email}</div>}
                                </div>
                                <input type="submit" disabled={processing} name="save" className="btn btn-primary" value="Send Password Reset Link" required/>
                                {/* Display error message if exists */}
                                {flash.error && (<div className="alert alert-danger mt-2" role="alert"> {flash.error} </div> )}
                                {/* Display success message if exists */}
                                {flash.success && (<div className="alert alert-success mt-2" role="alert"> {flash.success} </div> )}
                            </form>
                            <span className="login-link">
                                <Link href={baseUrl+"/user_login"}>Back to Login</Link>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
      </div>
    )
}
export default ForgotPassword;