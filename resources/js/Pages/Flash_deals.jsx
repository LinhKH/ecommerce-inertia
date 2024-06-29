import React from "react";
import { usePage, Link } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";

function Flash_deals() {
    const { flash_deals } = usePage().props;

    const deals = flash_deals.filter((deal) => {
        const datetime = deal.flash_date_range.split("-");
        const currentDatetime = new Date();

        let startDatetime = "";
        let endDatetime = "";

        if (deal.flash_date_range !== "") {
            startDatetime = new Date(datetime[0]);
            endDatetime = new Date(datetime[1]);
        }

        if (
            currentDatetime >= startDatetime &&
            currentDatetime <= endDatetime
        ) {
            return deal;
        }
        return null;

    });

    return (
        <>
            {deals.length > 0 && (
                <section id="flash-deals" className="py-4">
                    <div className="container-xl container-fluid">
                        <div className="row">
                            <div className="col-12">
                                <div className="section-heading">
                                    <h2 className="title">Flash Deals</h2>
                                    <Link
                                        href={baseUrl + "/flash-deals"}
                                        className="btn btn-primary"
                                    >
                                        Show All
                                    </Link>
                                </div>
                            </div>
                        </div>
                        <div className="row">
                            {deals.map((flash_deal) => {
                                return (
                                    <div
                                        className="col-md-4 flash-deal-box"
                                        key={flash_deal.id}
                                    >
                                        <div className="banner-inner">
                                            <Link
                                                href={
                                                    baseUrl +
                                                    "/public/flash-products/" +
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
                                                    alt="{flash_deal.flash_slug}"
                                                />
                                            </Link>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </section>
            )}
        </>
    );
}

export default Flash_deals;
