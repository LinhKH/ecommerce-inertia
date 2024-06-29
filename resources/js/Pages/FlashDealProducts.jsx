import ProductGrid from "./ProductGrid";
import { baseUrl } from "../Components/Baseurl";
import { Link } from "@inertiajs/react";

function AllFlashProducts(props) {
    const { flash_deal, flash_products } = props;
    return (
        <>
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>{flash_deal.flash_title}</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item">
                                <Link href={baseUrl}>Home</Link>
                            </li>
                            <li className="breadcrumb-item">
                                <Link href={baseUrl + "/flash-deals"}>
                                    Flash Deals
                                </Link>
                            </li>
                            <li className="breadcrumb-item active">
                                {flash_deal.flash_title}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <section id="flash-sale" className="py-4">
                <div className="container">
                    <div className="row">
                        {flash_products.data.map((flash_product) => {
                            const datetime = flash_product.flash_date_range.split("-");
                            const currentDatetime = new Date();

                            let startDatetime = "";
                            let endDatetime = "";

                            if (flash_product.flash_date_range !== "") {
                                startDatetime = new Date(datetime[0]);
                                endDatetime = new Date(datetime[1]);
                            }

                            if (
                                currentDatetime >= startDatetime &&
                                currentDatetime <= endDatetime
                            ) {
                                return (
                                    <div
                                        className="col-lg-3 col-md-4 col-sm-6"
                                        key={flash_product.id}
                                    >
                                        <ProductGrid
                                            key={flash_product.id}
                                            product={flash_product}
                                        />
                                    </div>
                                );
                            }
                            return null;
                        })}
                    </div>
                    {flash_products.from != flash_products.last_page && (
                        <div className="row">
                            <div className="col-12 mb-5">
                                <Pagination links={flash_products.links} />
                            </div>
                        </div>
                    )}
                </div>
            </section>
        </>
    );
}

export default AllFlashProducts;
