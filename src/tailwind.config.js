/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");

export default {
    content: [
        "./resources/**/*.blade.php",
        "./app/Filament/**/*.php",
        "./resources/views/filament/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
    ],
    darkMode: "class",
    theme: {
        extend: {
            fontFamily: {
                display: ["'ClashDisplay-Semibold'", "'Fraunces'", "Georgia", "serif"],
                body: ["'Inter'", ...defaultTheme.fontFamily.sans],
                clash: ["'ClashDisplay-Bold'", "'Fraunces'", "Georgia", "serif"],
            },
            colors: {
                ink: '#000000',
                cream: '#F5F0E8',
                accent: '#E8FF00',
                'accent-hover': '#cce600',
                ember: '#FF5733',
                'purple-soft': '#B0A0F8',
                coral: '#F26B9E',
                'surface-1': '#1A1A1A',
                'surface-2': '#141414',
                'surface-3': '#0D0D0D',
                'border-dark': '#2C2C2A',
                'border-light': '#E0DDD6',
                muted: '#9E9E9E',
                'mid-gray': '#5F5E5A',
                'text-subtle': '#B4B2A9',
                'zone-vip': '#E8FF00',
                'zone-festival': '#F26B9E',
                'zone-tribune': '#B0A0F8',
                'zone-regular': '#FF5733',
                'zone-disabled': '#9E9E9E',
                success: '#5DCAA5',
                error: '#E24B4A',
                warning: '#F09595',
            },
            borderRadius: {
                pill: '999px',
                card: '16px',
                'card-lg': '20px',
            },
            keyframes: {
                slideIn: {
                    from: { opacity: '0', transform: 'translateX(20px)' },
                    to: { opacity: '1', transform: 'translateX(0)' },
                },
                ticker: {
                    from: { transform: 'translateX(0)' },
                    to: { transform: 'translateX(-50%)' },
                },
                blink: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.15' },
                },
            },
            animation: {
                'slide-in': 'slideIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards',
                'ticker': 'ticker 30s linear infinite',
                'blink': 'blink 2s infinite',
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
