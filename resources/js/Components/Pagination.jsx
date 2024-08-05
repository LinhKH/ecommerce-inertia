import React from "react";
import he from "he"; // Importing the 'he' library for decoding HTML entities
import { usePage, Link } from "@inertiajs/react";

function Pagination({ links }) {
    // const {links} = usePage().props.products;
    const decodeLabel = (label) => {
        // Decoding the HTML entities using he.decode()
        return he.decode(label);
    };
    return (
        <>
            <ul className="pagination">
                {links.map((link, index) =>
                    link.url ? (
                        <li
                            key={index}
                            className={`page-item ${
                                link.active ? "active" : ""
                            }`}
                        >
                            <Link preserveScroll className="page-link" href={link.url}>
                                {decodeLabel(
                                    link.label
                                        .replace(" Previous", "")
                                        .replace("Next ", "")
                                )}{" "}
                                {/* Decoding the label here */}
                            </Link>
                        </li>
                    ) : (
                        <li
                            key={index}
                            className={`page-item ${
                                link.active ? "active disabled" : "disabled"
                            }`}
                        >
                            <Link preserveScroll className="page-link" href={link.url}>
                                {decodeLabel(
                                    link.label
                                        .replace(" Previous", "")
                                        .replace("Next ", "")
                                )}{" "}
                                {/* Decoding the label here */}
                            </Link>
                        </li>
                    )
                )}
            </ul>
        </>
    );
}
export default Pagination;
