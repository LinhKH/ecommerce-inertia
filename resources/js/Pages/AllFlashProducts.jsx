import ProductGrid from "./ProductGrid";
import { baseUrl } from "../Components/Baseurl";
import { Link } from "@inertiajs/react";

function AllFlashProducts(props) {
    const { flash_products } = props;

    return (
        <>
            <div id="banner" className="d-flex flex-row justify-content-center">
                <div className="align-self-center">
                    <h2>Flash Sale</h2>
                    <nav aria-label="breadcrumb">
                        <ol className="breadcrumb justify-content-center p-0">
                            <li className="breadcrumb-item">
                                <Link href={baseUrl}>Home</Link>
                            </li>
                            <li className="breadcrumb-item active">
                                Flash Sale
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <section id="flash-sale" className="py-4">
                <div className="container-xl container-fluid">
                    <div className="row">
                        {flash_products.data.map((flash_product) => {
                            const datetime =
                                flash_product.flash_date_range.split("-");
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
                </div>
            </section>
        </>
    );
}

export default AllFlashProducts;
