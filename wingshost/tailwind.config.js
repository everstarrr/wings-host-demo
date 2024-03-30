/*
 * Copyright (c) WINGS, 2023.
 * Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
 * There Will Be Consequences. Think about it.
 */

import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";
import plugin from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    darkMode: "class",

    theme: {
        extend: {
            fontFamily: {
                sans: ["OpenSans-Medium", ...defaultTheme.fontFamily.sans],
            },

            screens: {
                xs: "500px",
                wqhd: "2000px",
            },

            colors: {
                // ...
                wings: {
                    DEFAULT: "#7c00db",
                    light: "#BD66FF",
                },
            },

            backgroundSize: {
                "size-200": "200% 200%",
            },
            backgroundPosition: {
                "pos-0": "0% 0%",
                "pos-90": "90% 90%",
            },

            margin: {
                "7px": "7px",
            },

            backgroundImage: {
                explode: "url('@pngs/whExplodeBG.png')",
                wingsFade: "url('@pngs/whMainBg.png')",
                explosion: "url('@pngs/explosion.png')",
                minecraft: "url('@pngs/minecraft.png')",
                hub: "url('@pngs/hub.png')",
                coding: "url('@pngs/coding.png')",
                vanilla: "url('@pngs/vanilla_c.jpg')",
                bedrock: "url('@pngs/bedrock_c.jpg')",
                roleplay: "url('@pngs/rp_c.jpg')",
                friends: "url('@pngs/friends_c.jpg')",
                mods_preset: "url('@pngs/mods_preset.png')",

                create_minecraft: "url('@pngs/services/create/minecraft.png')",
                create_minecraft_dark: "url('@pngs/services/create/minecraft_dark.png')",

                create_python: "url('@pngs/services/create/python.png')",
                create_python_dark: "url('@pngs/services/create/python_dark.png')",

                create_nodejs: "url('@pngs/services/create/nodejs.png')",
                create_nodejs_dark: "url('@pngs/services/create/nodejs_dark.png')",

                create_csgo: "url('@pngs/services/create/csgo.png')",
                create_csgo_dark: "url('@pngs/services/create/csgo_dark.png')",

                create_database: "url('@pngs/services/create/database.png')",
                create_database_dark: "url('@pngs/services/create/database_dark.png')",

                create_rust: "url('@pngs/services/create/rust.png')",
                create_rust_dark: "url('@pngs/services/create/rust_dark.png')",

                create_samp: "url('@pngs/services/create/samp.png')",
                create_samp_dark: "url('@pngs/services/create/samp_dark.png')",

                create_gmod: "url('@pngs/services/create/gmod.png')",
                create_gmod_dark: "url('@pngs/services/create/gmod_dark.png')",

                create_gtav: "url('@pngs/services/create/gtav.png')",
                create_gtav_dark: "url('@pngs/services/create/gtav_dark.png')",

                create_arma3: "url('@pngs/services/create/arma3.png')",
                create_arma3_dark: "url('@pngs/services/create/arma3_dark.png')",

                create_tf: "url('@pngs/services/create/tf.png')",
                create_tf_dark: "url('@pngs/services/create/tf_dark.png')",

                create_unturned: "url('@pngs/services/create/unturned.png')",
                create_unturned_dark: "url('@pngs/services/create/unturned_dark.png')",

                minecraftbg_dark: "url('@pngs/backgrounds/minecraft.jpeg')",

                purplelight: "url('@pngs/purplelight.png')",
                purplelight_right: "url('@pngs/purplelight_right.png')",

                mods_article: "url('@pngs/mods.png')",
                username_error_article: "url('@pngs/username_error.png')",
                proxy_article: "url('@pngs/proxy.jpg')",
            },
            minHeight: {
                150: "150vh",
            },

            width: {
                90: "360px",
                screen65: "65vw",
                screen70: "70vw",
                screen80: "80vw",
                screen90: "90vw",
            },
        },
    },

    plugins: [
        plugin(function ({ addVariant }) {
            addVariant("current", "&.active");
        }),
        forms,
        typography,
    ],
};
