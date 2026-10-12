{{-- LAYOUT halaman publik - header, navigasi, footer. Edit branding / menu di sini. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Desa Kaki Air' }} - Portal Pelayanan Digital</title>

    <meta name="description" content="Website Resmi Desa Kaki Air. Akses pelayanan surat online, sampaikan pengaduan masyarakat secara instan, dan temukan informasi berita terbaru seputar desa kami.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-['Plus_Jakarta_Sans',sans-serif] bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-emerald-500 selection:text-white">

    <!-- Top Info Bar -->
    <div class="bg-emerald-900 text-emerald-100 text-xs py-2 px-4 sm:px-6 lg:px-8 flex justify-between items-center border-b border-emerald-800">
        <div class="flex items-center space-x-4">
            <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse mr-2"></span>Pelayanan Online Aktif</span>
            <span class="hidden md:inline">|</span>
            <span class="hidden md:inline">Senin - Jumat: 08:00 - 15:30</span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('filament.admin.auth.login') }}" class="hover:text-white font-medium transition-colors flex items-center">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                Portal Aparat
            </a>
        </div>
    </div>

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-100 shadow-sm" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('beranda') }}" class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white font-bold shadow-md shadow-emerald-200">
                            B
                        </div>
                        <div>
                            <span class="block text-lg font-bold tracking-tight text-slate-900 leading-tight">Kaki Air</span>
                            <span class="block text-xs font-semibold text-emerald-600 tracking-wider uppercase">Portal Desa</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-1 lg:space-x-2">
                    <a href="{{ route('beranda') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('beranda') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Beranda</a>
                    <a href="{{ route('profil-desa') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('profil-desa') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Profil Desa</a>
                    <a href="{{ route('data-desa') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('data-desa') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Data Desa</a>
                    <a href="{{ route('artikel') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('artikel') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Berita & Galeri</a>
                    <a href="{{ route('profil-desa-dan-infografis') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('profil-desa-dan-infografis') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Infografis</a>
                    <a href="{{ route('pengaduan') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ request()->routeIs('pengaduan') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">Pengaduan</a>
                </nav>

                <!-- Action Button -->
                <div class="hidden md:block">
                    <a href="{{ route('layanan-surat') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white font-semibold text-sm shadow-lg shadow-emerald-200 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Layanan Surat
                    </a>
                </div>

                <!-- Hamburger Button (Mobile) -->
                <div class="md:hidden">
                    <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2.5 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-50 focus:outline-none transition-all" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" stroke-d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <svg x-show="open" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="open" x-cloak class="md:hidden bg-white border-b border-slate-100 animate-fade-in" id="mobile-menu">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="{{ route('beranda') }}" class="block px-4 py-3 rounded-xl text-base font-semibold transition-all {{ request()->routeIs('beranda') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">Beranda</a>
                <a href="{{ route('profil-desa') }}" class="block px-4 py-3 rounded-xl text-base font-semibold transition-all {{ request()->routeIs('profil-desa') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">Profil Desa</a>
                <a href="{{ route('data-desa') }}" class="block px-4 py-3 rounded-xl text-base font-semibold transition-all {{ request()->routeIs('data-desa') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">Data Desa</a>
                <a href="{{ route('artikel') }}" class="block px-4 py-3 rounded-xl text-base font-semibold transition-all {{ request()->routeIs('artikel') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">Berita & Galeri</a>
                <a href="{{ route('profil-desa-dan-infografis') }}" class="block px-4 py-3 rounded-xl text-base font-semibold transition-all {{ request()->routeIs('profil-desa-dan-infografis') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">Infografis</a>
                <a href="{{ route('pengaduan') }}" class="block px-4 py-3 rounded-xl text-base font-semibold transition-all {{ request()->routeIs('pengaduan') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-50' }}">Pengaduan</a>

                <div class="pt-4 border-t border-slate-100">
                    <a href="{{ route('layanan-surat') }}" class="flex w-full items-center justify-center px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 text-white font-semibold text-base shadow-lg shadow-emerald-100">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Layanan Surat
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-12">
                <!-- Branding/Description -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-5">
                        <div class="w-9 h-9 rounded-lg bg-emerald-500 flex items-center justify-center text-white font-bold">
                            B
                        </div>
                        <span class="text-white text-lg font-bold tracking-tight">Desa Kaki Air</span>
                    </div>
                    <p class="text-sm leading-relaxed mb-6 max-w-sm">
                        Menghadirkan pelayanan administrasi desa secara transparan, akuntabel, dan berbasis teknologi untuk mewujudkan masyarakat mandiri dan sejahtera.
                    </p>
                    <div class="flex space-x-4">
                        <!-- Social Media Placeholder Icons -->
                        <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors">
                            <span class="sr-only">Facebook</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-emerald-600 hover:text-white flex items-center justify-center transition-colors">
                            <span class="sr-only">Instagram</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Fast Links -->
                <div>
                    <h3 class="text-white font-semibold text-sm tracking-wider uppercase mb-5">Navigasi Cepat</h3>
                    <ul class="space-y-3.5 text-sm">
                        <li><a href="{{ route('profil-desa') }}" class="hover:text-emerald-400 transition-colors">Profil Desa</a></li>
                        <li><a href="{{ route('data-desa') }}" class="hover:text-emerald-400 transition-colors">Potensi & Data Wilayah</a></li>
                        <li><a href="{{ route('artikel') }}" class="hover:text-emerald-400 transition-colors">Daftar Berita Desa</a></li>
                        <li><a href="{{ route('layanan-surat') }}" class="hover:text-emerald-400 transition-colors">Ajukan Surat Online</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-white font-semibold text-sm tracking-wider uppercase mb-5">Kontak Kantor</h3>
                    <ul class="space-y-3.5 text-sm">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span>Jalan Raya Utama Kaki Air No. 1, Kecamatan Kaki Air, Indonesia</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span>info@kaki-air.desa.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright -->
            <div class="mt-12 md:mt-16 pt-8 border-t border-slate-800 flex flex-col md:flex-row justify-between items-center text-xs">
                <p>&copy; {{ date('Y') }} Kantor Kepala Desa Kaki Air. Hak Cipta Dilindungi Undang-Undang.</p>
                <div class="mt-4 md:mt-0 flex space-x-6">
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
