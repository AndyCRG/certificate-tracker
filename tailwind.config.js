import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                // KSG Brand Colors
                ksgBrown: {
                    DEFAULT: 'rgb(127, 98, 44)', // Brown
                    light: '#a67c37',
                    dark: '#5a3c12',
                },
                ksgLime: {
                    DEFAULT: 'rgb(203, 211, 0)', // Lime Green
                    light: '#d8e400',
                    dark: '#a5b700',
                },
            },
            fontFamily: {
                sans: ['"Figtree"', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
