import React from "react";
import { usePage } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";
import { Link } from "@inertiajs/react";

function AllFlashDeals() {
    const { flash_deals } = usePage().props;
    const currentDate = new Date();

    return (
        <>
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>Flash Deals</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item">
                                <Link href={baseUrl}>Home</Link>
                            </li>
                            <li className="breadcrumb-item active">
                                Flash Deals
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            {flash_deals.data.length > 0 && (
                <section id="flash-deals" className="py-4">
                    <div className="container-xl container-fluid">
                        <div className="row">
                            {flash_deals.data.map((flash_deal) => {
                                const datetime =
                                    flash_deal.flash_date_range.split("-");
                                const currentDatetime = new Date();

                                let startDatetime = "";
                                let endDatetime = "";

                                if (flash_deal.flash_date_range !== "") {
                                    startDatetime = new Date(datetime[0]);
                                    endDatetime = new Date(datetime[1]);
                                }

                                if (
                                    flash_deal.status == "1" &&
                                    currentDatetime >= startDatetime &&
                                    currentDatetime <= endDatetime
                                ) {
                                    return (
                                        <div
                                            className="col-md-4 flash-deal-box"
                                            key={flash_deal.id}
                                        >
                                            <div className="banner-inner">
                                                <Link
                                                    href={
                                                        baseUrl +
                                                        "/flash-products/" +
                                                        flash_deal.flash_slug
                                                    }
                                                >
                                                    <img width={225} height={225}
                                                        src={
                                                            baseUrl +
                                                            "/public/flash-deals/" +
                                                            flash_deal.flash_image.split(
                                                                ","
                                                            )[0]
                                                        }
                                                        alt=""
                                                    />
                                                </Link>
                                            </div>
                                        </div>
                                    );
                                }
                                return null;
                            })}
                        </div>
                    </div>
                </section>
            )}
        </>
    );
}

export default AllFlashDeals;
