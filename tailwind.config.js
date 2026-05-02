/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Be Vietnam Pro"', 'Inter', 'system-ui', 'sans-serif'],
                serif: ['Merriweather', 'Lora', 'serif'],
                reading: ['Merriweather', 'serif'],
            },
            fontSize: {
                'reading-sm': ['1.125rem', { lineHeight: '1.8' }], // 18px
                'reading-base': ['1.25rem', { lineHeight: '1.9' }], // 20px
                'reading-lg': ['1.375rem', { lineHeight: '2' }], // 22px
            },
            colors: {
                primary: {
                    50: '#f0f4ff',
                    100: '#e0e7ff',
                    200: '#c7d2fe',
                    300: '#a5b4fc',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5', // Indigo-600 (Primary from DESIGN.md)
                    700: '#4338ca',
                    800: '#3730a3',
                    900: '#312e81',
                },
                secondary: {
                    50: '#fdf2f8',
                    100: '#fce7f3',
                    200: '#fbcfe8',
                    300: '#f9a8d4',
                    400: '#f472b6',
                    500: '#ec4899',
                    600: '#db2777',
                    700: '#be185d',
                    800: '#9d174d',
                    900: '#831843',
                },
                dark: {
                    bg: '#16161a',
                    surface: '#1e1e2e',
                    'surface-strong': '#262637',
                    border: '#2e2e3f',
                }
            },
            boxShadow: {
                'premium': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'premium-hover': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
            }
        },
    },
    plugins: [],
};
