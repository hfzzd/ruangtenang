import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Palet tenang: sage/teal yang lembut — menenangkan, bukan mencolok
                tenang: {
                    50: '#f3f8f5',
                    100: '#e1efe8',
                    200: '#c4ded3',
                    300: '#9dc6b7',
                    400: '#74a995',
                    500: '#548d79',
                    600: '#427063',
                    700: '#375b52',
                    800: '#304a45',
                    900: '#293d39',
                    950: '#152825',
                },
            },
        },
    },

    plugins: [forms, typography],
};
