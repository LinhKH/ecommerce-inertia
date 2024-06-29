import React from "react"
import {baseUrl} from '../Components/Baseurl'

function NewPassword() {
    return(
        <div className="successfull-page text-center my-4">
            <div className="row m-0">
                <div className="offset-md-2 col-md-8">
                    <div class="alert alert-success" role="alert">
                        Your password has been changed! Login with new password
                    </div>
                </div>
                <div className="col-md-12">
                    <a href={baseUrl+'/user_login'} className="btn btn-primary text-center">Login</a>
                </div>
            </div>
        </div>
    )
}

export default NewPassword;
