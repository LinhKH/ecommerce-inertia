// import './bootstrap';
// import '../css/app.css';

//import './Layouts/layout'
import Layout from "./Layouts/layout";

import React from "react";
import { createInertiaApp } from "@inertiajs/react";
//import { InertiaProgress } from "@inertiajs/progress";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createRoot } from "react-dom/client";

// createInertiaApp({
//     resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
//     setup({el, App, props}) {
//         const root = createRoot(el)
//         return root.render(<App {...props}/>);
//     }
// });

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.jsx", { eager: true });
        let page = pages[`./Pages/${name}.jsx`];
        page.default.layout =
            page.default.layout || ((page) => <Layout children={page} />);
        return page;
    },
    // ...
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />);
    },
});

//InertiaProgress.init({ color: "#414042" });
