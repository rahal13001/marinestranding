import "./bootstrap";
import "../css/app.css";
import React from "react";
import { createInertiaApp } from "@inertiajs/react";
import { createRoot } from "react-dom/client";
import Layout from "./Layouts/Layout";


interface AppProps {
  page: React.ComponentType<any>;
  default: any; // line to specify the type of children
}

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.tsx', { eager: true })
    let page = pages[`./Pages/${name}.tsx`] as AppProps;

    page.default.layout = page.default.layout || ((page: React.ReactNode) => <Layout>{page}</Layout>);
    return page;
  },
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />)
  },
})