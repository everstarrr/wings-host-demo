/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

import "./bootstrap";
import "../css/app.css";

import "../css/fonts.css";

import "../css/svg.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "ziggy";
// import { Ziggy } from './ziggy';
import { i18nVue } from "laravel-vue-i18n";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import VLazyImage from "v-lazy-image";

//import * as Sentry from "@sentry/vue";
//import { BrowserTracing, browserTracingIntegration } from "@sentry/vue";

/*axios.defaults.withCredentials = false;

// TODO: make it global and use in every api calls
const api_v1 = axios.create({
    baseURL: "http://localhost:8000/api/v1",
    withCredentials: false,
    headers: {
        //"Accept": 'application/json',
        "Content-type": "application/json",
    },
});*/

const appName = window.document.getElementsByTagName("title")[0]?.innerText || "WINGS";

const app = createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue")),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18nVue, {
                resolve: async (lang) => {
                    const langs = import.meta.glob("../../lang/*.json");
                    if (lang.includes("php_")) {
                        return await langs[`../../lang/${lang}.json`]();
                    }
                },
            })
            .use(ZiggyVue)
            .component("font-awesome-icon", FontAwesomeIcon)
            .component("v-lazy-image", VLazyImage)
            .mount(el);
    },
    progress: {
        // The delay after which the progress bar will appear, in milliseconds...
        delay: 250,

        // The color of the progress bar...
        color: "#7c00db",

        // Whether to include the default NProgress styles...
        includeCSS: true,

        // Whether the NProgress spinner will be shown...
        showSpinner: true,
    },
});
