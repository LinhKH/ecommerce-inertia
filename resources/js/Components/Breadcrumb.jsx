import React from "react";

function Breadcrumb() {
    return (
        <div id="banner" className="d-flex flex-row justify-content-center">
            <div className="align-self-center">
                <h1>Page Title Name</h1>
                <nav aria-label="breadcrumb">
                    <ol className="breadcrumb justify-content-center p-0">
                        <li className="breadcrumb-item">
                            <a href="{{url('/')}}">Home</a>
                        </li>
                        <li className="breadcrumb-item">Page Title Name</li>
                    </ol>
                </nav>
            </div>
        </div>
    );
}
export default Breadcrumb;
