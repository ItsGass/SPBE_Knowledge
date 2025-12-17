/** @type {import('tailwindcss').Config} */
module.exports = {
    // 1. AKTIFKAN DARK MODE BERDASARKAN CLASS
    darkMode: 'class',

    content: [
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                // WARNA KUNING UTAMA
                poco: {
                    DEFAULT: '#ffd400',
                    50: '#fffdf3',
                    100: '#fff8e0',
                    200: '#ffefb8',
                    300: '#ffe58f',
                    400: '#ffd956',
                    500: '#ffd400', // Paling terang
                    600: '#f2c200',
                    700: '#b38900',
                    800: '#7a5b00',
                    900: '#4b3500' // Paling gelap
                },
                // WARNA DEFAULT BACKGROUND/TEXT
                'base-light': '#ffffff', // Putih terang (Latar belakang Light Mode)
                'base-dark': '#111111',  // Hitam pekat (Latar belakang Dark Mode)

                // WARNA DEFAULT TEKS MUDAH DIBACA
                'text-light': '#000000', // Hitam (Teks di Light Mode)
                'text-dark': '#f0f0f0',  // Putih keabuan (Teks di Dark Mode)

                muted: '#6b7280'
            },
            boxShadow: {
                // Bayangan lembut
                soft: '0 8px 24px rgba(16,24,40,0.06)',
                // Bayangan kuning untuk menonjolkan elemen
                accent: '0 12px 40px rgba(255, 212, 0, 0.12)'
            },
            borderRadius: {
                // Membuat sudut sedikit lebih membulat
                xl: '14px',
                '2xl': '20px'
            },
            // Tambahkan transisi default untuk Dark Mode yang lebih halus
            transitionProperty: {
                'colors': 'color, background-color, border-color, text-decoration-color, fill, stroke',
            }
        },
    },
    plugins: []
}