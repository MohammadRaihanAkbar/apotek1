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
                // Colors from user image
                primary: {
                    50: '#F7FBFF',
                    100: '#DEEBF7',
                    200: '#C6DBEF',
                    300: '#9ECAE1',
                    400: '#6BAED6',
                    500: '#4292C6',
                    600: '#2171B5',
                    700: '#084594',
                    800: '#063778', // generated darker shade for hover
                    900: '#04295A', // generated darker shade
                }
            },
        },
    },

    plugins: [forms],
    darkMode: 'media',
};
