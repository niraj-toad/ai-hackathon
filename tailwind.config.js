const colors = require('tailwindcss/colors');
const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                'serif': [...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            accent: {
                800: colors.green['800'],
                900: colors.green['900'],
            },
            primary: {
                0: colors.white,
                50: colors.gray['50'],
                100: colors.gray['100'],
                200: colors.gray['200'],
                300: colors.gray['300'],
                400: colors.gray['400'],
                800: colors.gray['800'],
            },
            secondary: {
                50: colors.zinc['50'],
                300: colors.zinc['300'],
                100: colors.zinc['100'],
                200: colors.zinc['200'],
            },
            red: {
                400: colors.red['400'],
                600: colors.red['600'],
            },
            transparent: colors.transparent,
            white: colors.white,
        },
        textColor: {
            primary: {
                100: colors.zinc['100'],
                500: colors.zinc['500'],
                900: colors.zinc['900'],
            },
            accent: {
                800: colors.green['800'],
            },
            green: {
                600: colors.green['600'],
            },
            red: {
                600: colors.red['600'],
            },
            transparent: colors.transparent,
        },
    },
    plugins: [
        require('@headlessui/tailwindcss'),
    ],
};
