import React, { useCallback } from "react";
import { usePage, Link } from "@inertiajs/react";
import { baseUrl } from "../Components/Baseurl";

function ChildCategory() {
    const { all_category, cat_detail, cat_array } = usePage().props;

    const renderSubcategories = useCallback((subcategories) => {
        return (
            <ul className="subcategory-list pl-3">
                {subcategories.map((subCategory) => (
                    <li key={subCategory.id}>
                        <Link href={baseUrl + "/c/" + subCategory.category_slug}>
                            {cat_detail !== null &&
                                cat_detail.id == subCategory.id && (
                                    <i className="fas fa-angle-right"></i>
                                )}
                            {subCategory.category_name}
                        </Link>
                        {subCategory.sub_category &&
                            renderSubcategories(subCategory.sub_category)}
                    </li>
                ))}
            </ul>
        );
    });
    // return <>{renderSubcategories(all_category)}</>;
    return (
        <ul className="category">
            {cat_detail != null ? (
                <>
                    <li className="category_name">
                        <Link href={baseUrl + "/search?category=all"}>
                            <strong>All Categories</strong>
                        </Link>
                    </li>
                    <li className="category_name">
                        <Link href={baseUrl + "/c/" + cat_array.category_slug}>
                            {cat_detail.id == cat_array.id && (
                                <i className="fas fa-angle-right"></i>
                            )}
                            {cat_array.category_name}
                        </Link>
                        {cat_array.sub_category &&
                            renderSubcategories(cat_array.sub_category)}
                    </li>
                </>
            ) : (
                all_category.map((item) => (
                    <>
                        {item.parent_category == "0" && (
                            <li key={item.id}>
                                <Link
                                    href={baseUrl + "/c/" + item.category_slug}
                                >
                                    {item.category_name}
                                </Link>
                            </li>
                        )}
                    </>
                ))
            )}
        </ul>
    );
}
export default ChildCategory;
