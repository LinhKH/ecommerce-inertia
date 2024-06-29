import React from "react";
import he from "he"; // Importing the 'he' library for decoding HTML entities
import { usePage, Link } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";

function Single() {
    const { page } = usePage().props;

    const decodeLabel = (description) => {
        // Decoding the HTML entities using he.decode()
        return he.decode(description);
    };
    return (
        <>
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>{page.page_title}</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item">
                                <Link href={baseUrl}>Home</Link>
                            </li>
                            <li className="breadcrumb-item active">
                                {page.page_title}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div className="product-box">
                <div className="message"></div>
                <div className="container">
                    <div className="row">
                        <div className="col-md-12">
                            {/* Use dangerouslySetInnerHTML to render the HTML content */}
                            <div
                                dangerouslySetInnerHTML={{
                                    __html: decodeLabel(page.description),
                                }}
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

export default Single;
