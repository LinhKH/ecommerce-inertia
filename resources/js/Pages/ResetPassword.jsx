import React, {useState} from 'react';
import Preloader from '../Components/Preloader';
import {usePage,useForm,Link} from '@inertiajs/react';
import {baseUrl} from '../Components/Baseurl';

function ResetPassword() {
    const {email,token,flash,userSession} = usePage().props;

    const {data,setData,post,processing,errors} = useForm({
        email: email.email,
        token: token, // Use token from state
        password: '',
        password_confirmation: '',
    })

    function handleSubmit(e) {
        e.preventDefault()
        post(baseUrl+'/reset-password');
    }
    return(
        <div id="site-content" className="py-5"> 
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>Reset Password</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item"><Link href={baseUrl}>Home</Link></li>
                            <li className="breadcrumb-item active">Reset Password</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div className="container">
                <div className="row">
                    <div className="offset-md-4 col-md-4">
                        <div className="signup-form">
                            {processing && <Preloader />}
                            <form className="form-horizontal" onSubmit={handleSubmit} autoComplete="off">
                                <h4 className="user-heading">Reset Password</h4>
                                <div className="form-group">
                                    <input type="email" name="email" className="form-control" placeholder="Email Address" readOnly
                                    value={data.email} onChange={e => setData('email', e.target.value)}/>
                                    {errors.email && <div className="alert alert-danger mt-2" role="alert">{errors.email}</div>}
                                </div>
                                <div className="form-group">
                                    <input type="password" name="password" className="form-control" placeholder="Password" 
                                    value={data.password} onChange={e => setData('password', e.target.value)} />
                                    <input type="text" hidden name="token" value={data.token} onChange={e => setData('token', e.target.value)} />
                                   {errors.password && <div className="alert alert-danger mt-2" role="alert">{errors.password}</div>}
                                </div>
                                <div className="form-group">
                                    <input type="password" name="password_confirmation" className="form-control" placeholder="Confirm Password" 
                                    value={data.password_confirmation} onChange={e => setData('password_confirmation', e.target.value)}/>
                                    {errors.password_confirmation && <div className="alert alert-danger mt-2" role="alert">{errors.password_confirmation}</div>}
                                </div>
                                <input type="submit" disabled={processing}  name="save" className="btn btn-primary" value="Reset" required />
                                {/* Display error message if exists */}
                                {flash.error && (<div className="alert alert-danger mt-2" role="alert"> {flash.error} </div> )}
                                {/* Display success message if exists */}
                                {flash.success && (<div className="alert alert-success mt-2" role="alert"> {flash.success} </div> )}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

export default ResetPassword