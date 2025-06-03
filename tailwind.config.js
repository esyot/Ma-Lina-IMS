import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                suez: ['"Suez One"', "serif"],
                Oswald: ['"Oswald"', "sans-serif"],
            },
            keyframes: {
                SlideToRight: {
                    "0%": {
                        transform: "translateX(-100%)",
                        opacity: "0",
                    },
                    "100%": {
                        transform: "translateX(0)",
                        opacity: "1",
                    },
                },
                SlideToTop: {
                    "0%": {
                        transform: "translateY(100%)",
                        opacity: "0",
                    },
                    "100%": {
                        transform: "translateY(0)",
                        opacity: "1",
                    },
                },
                SlideToLeft: {
                    "0%": {
                        transform: "translateX(100%)",
                        opacity: "0",
                    },
                    "100%": {
                        transform: "translateX(0)",
                        opacity: "1",
                    },
                },
            },
            animation: {
                SlideToRight: "SlideToRight 0.6s ease-out forwards",
                SlideToTop: "SlideToTop 0.6s ease-out forwards",
                SlideToLeft: "SlideToLeft 0.6s ease-out forwards",
            },
        },
    },
    plugins: [],
};
