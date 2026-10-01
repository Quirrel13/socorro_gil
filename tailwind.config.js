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
                ifb: ['"DM Sans"', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                ifb: {
                    bg: '#07040F',
                    card: '#0D0A1A',
                    secondary: '#160E2C',
                    muted: '#110D22',
                    primary: '#8B31FF',
                    'primary-hover': '#7B21EF',
                    accent: '#B57BFF',
                    text: '#EDE9F8',
                    soft: '#C4B8E8',
                    dim: '#7A6FA0',
                    success: '#22C55E',
                    danger: '#EF4444',
                    warning: '#F59E0B',

                    line: 'rgba(139, 49, 255, 0.15)',
                    'line-strong': 'rgba(139, 49, 255, 0.35)',
                    'primary-soft': 'rgba(139, 49, 255, 0.15)',

                    'success-soft': 'rgba(34, 197, 94, 0.1)',
                    'success-hover': 'rgba(34, 197, 94, 0.2)',
                    'success-line': 'rgba(34, 197, 94, 0.3)',

                    'danger-soft': 'rgba(239, 68, 68, 0.1)',
                    'danger-hover': 'rgba(239, 68, 68, 0.2)',
                    'danger-line': 'rgba(239, 68, 68, 0.3)',

                    'warning-soft': 'rgba(245, 158, 11, 0.1)',
                    'warning-line': 'rgba(245, 158, 11, 0.3)',
                },
            },
        },
    },

    plugins: [forms],
};