import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Roboto", ...defaultTheme.fontFamily.sans],
                caveat: ["Caveat", "cursive"],
            },
            colors: {
                pastel: {
                    purple: "#CB9DF0",
                    pink: "#F0C1E1",
                    peach: "#FDDBBB",
                    yellow: "#FFF9BF",
                    "yellow-btn": "#FCE77E",
                },
            },
            height: {
                85: "21.25rem", // 340px
            },
        },
    },

    plugins: [forms, typography],
};
