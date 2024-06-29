import React from "react"
import {usePage,useForm, Link} from '@inertiajs/react'
import {baseUrl} from '../Components/Baseurl'

function ChangePassword() {
    const {flash} = usePage().props;

    const { data, setData, post, processing,errors } = useForm({
        password: '',
        new_pass: '',
        re_pass: '',
    })

    function handleSubmit(e) {
        e.preventDefault()
        post(baseUrl+'/changepassword');
    }

    return(
        <div id="user-content">
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>Change Password</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item"><Link href={baseUrl}>Home</Link></li>
                            <li className="breadcrumb-item active">Change Password</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div className="container">
                <div className="row">
                    <div className="offset-md-3 col-md-6">
                        <div className="signup-form">
                            <form className="form-horizontal" onSubmit={handleSubmit} autocomplete="off">
                                <div className="form-group">
                                    <label>Old Password</label>
                                    <input type="password" name="password" className="form-control" placeholder="Old Password" 
                                    value={data.password} onChange={e => setData('password', e.target.value)}/>
                                    {errors.password && <div className="alert alert-danger mt-2" role="alert">{errors.password}</div>}
                                </div>
                                <div className="form-group">
                                    <label>New Password</label>
                                    <input type="password" name="new_pass" className="form-control" id="new-pass" placeholder="New Password" 
                                    value={data.new_pass} onChange={e => setData('new_pass', e.target.value)} />
                                    {errors.new_pass && <div className="alert alert-danger mt-2" role="alert">{errors.new_pass}</div>}
                                </div>
                                <div className="form-group">
                                    <label>Re-enter New Password</label>
                                    <input type="password" name="re_pass" className="form-control" placeholder="Re-enter New Password" 
                                    value={data.re_pass} onChange={e => setData('re_pass', e.target.value)} />
                                     {errors.re_pass && <div className="alert alert-danger mt-2" role="alert">{errors.re_pass}</div>}
                                </div>
                                <input type="submit" disabled={processing} name="save" className="btn btn-primary" value="Update" required />
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

export default ChangePassword;