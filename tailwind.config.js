/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./public/**/*.html"
    ],
    theme: {
        extend: {
            colors: {
                ocean: {
                    50: '#f0f7fb',
                    100: '#e0eff7',
                    200: '#cbdde9',
                    300: '#94bed9',
                    400: '#559bc4',
                    500: '#2872A1', // Primary Brand Ocean Blue
                    600: '#1e5e86',
                    700: '#184b6c',
                    800: '#163f5b',
                    900: '#16364d',
                    950: '#0b1e2c',
                },
                cloudy: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbdde9', // Secondary Brand Cloudy Sky
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                }
            },
            fontFamily: {
                display: ['Outfit', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                sans: ['"Plus Jakarta Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
            },
            boxShadow: {
                'bezel': '0 20px 50px rgba(40, 114, 161, 0.08), 0 2px 8px rgba(15, 23, 42, 0.03)',
                'bezel-hover': '0 30px 65px rgba(40, 114, 161, 0.16), 0 4px 14px rgba(15, 23, 42, 0.05)',
                'island': '0 12px 35px rgba(40, 114, 161, 0.08), inset 0 1px 1px rgba(255, 255, 255, 1)',
                'glow': '0 10px 25px rgba(40, 114, 161, 0.35)',
                'inner-glass': 'inset 0 1px 1px rgba(255, 255, 255, 0.95)',
            },
            borderRadius: {
                '4xl': '2rem',
                '5xl': '2.5rem',
            },
            transitionTimingFunction: {
                'spring': 'cubic-bezier(0.16, 1, 0.3, 1)',
                'elastic': 'cubic-bezier(0.34, 1.56, 0.64, 1)',
            }
        },
    },
    plugins: [],
}
