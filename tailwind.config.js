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
                sans:  ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif', ...defaultTheme.fontFamily.sans],
                serif: ['"DM Serif Display"', 'Georgia', 'serif'],
            },
            colors: {
                teal: {
                    50:  '#eafaf9',
                    100: '#d5f3f1',
                    200: '#b0e7e4',
                    300: '#86d9d5',
                    400: '#5dcbc6',
                    500: '#3cbab4',
                    600: '#32a29d',
                    700: '#2a8c87',
                    800: '#1e6e6a',
                    900: '#135150',
                    950: '#0d3836',
                },
            },
        },
    },

    plugins: [forms],
};
