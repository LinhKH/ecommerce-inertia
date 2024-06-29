import React from "react";
import Category from "./Category";
import TodayDeals from "./TodayDeals";
import LastestProduct from "./LastestProduct";
import Flash_deals from "./Flash_deals";
import Flash_sale from "./Flash_sale";
import SellingBlog from "./SellingBlog";
import SaleBlog from "./SaleBlog";
import RatedBlog from "./RatedBlog";
import Banner from "./Banner";

function Index() {
    return (
        <>
            <Banner />
            <Category />
            <TodayDeals />
            <LastestProduct />
            <Flash_deals />
            <Flash_sale />
            <div className="py-4">
                <div className="container">
                    <div className="row">
                        <SellingBlog />
                        <SaleBlog />
                        <RatedBlog />
                    </div>
                </div>
            </div>
        </>
    );
}

export default Index;
