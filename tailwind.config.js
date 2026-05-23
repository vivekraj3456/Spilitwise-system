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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                splitwise: {
                    DEFAULT: '#5BC5A7',
                    dark: '#4AA68A',
                    light: '#E6F8F3',
                },
                danger: {
                    DEFAULT: '#FF652F',
                    light: '#FFEBE5',
                },
                slate: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    800: '#1e293b',
                    900: '#0f172a',
                },
            },
        },
    },

    plugins: [forms],
};
