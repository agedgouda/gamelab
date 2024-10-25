import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
      './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
      './storage/framework/views/*.php',
      './resources/views/**/*.blade.php',
      './resources/views/**/*.php',
      './resources/js/**/*.vue', // If you're using Vue.js
      './resources/js/**/*.js',  // If you're using JS files
      './resources/**/*.blade.php', // Make sure this covers all Blade templates
    ],
    theme: {
      extend: {
        fontFamily: {
          sans: ['Figtree', ...defaultTheme.fontFamily.sans],
    data70: ['Data70', ...defaultTheme.fontFamily.sans],
        },
      },
    },
    plugins: [forms],
  };
