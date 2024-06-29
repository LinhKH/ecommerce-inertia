import React from 'react';

function Preloader() {
    return (
        <div className="loader-container">
            <div className="loader">
                <span className="loader-inner box-1"></span>
                <span className="loader-inner box-2"></span>
                <span className="loader-inner box-3"></span>
                <span className="loader-inner box-4"></span>
            </div>
        </div>
    )
}
export default Preloader