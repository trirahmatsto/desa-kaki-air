<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\ArtikelGaleri;
use App\Models\DataDesa;
use App\Models\Desa;
use App\Services\ProfilService;

new #[Layout('components.layouts.public')] class extends Component {
    public function with(): array
    {
        $profil = ProfilService::profil('Kaki Air');
        $desa = Desa::where('slug', 'kaki-air')->first();
        $dataDesa = $desa
            ? DataDesa::where('desa_id', $desa->id)->get()->keyBy('kategori')
            : collect();

        $kependudukan = $dataDesa->get('kependudukan')?->data ?? [];
        $sosial = $dataDesa->get('sosial')?->data ?? [];
        $sensus = $dataDesa->get('sensus_kependudukan')?->data ?? [];
        $ekonomi = $dataDesa->get('sensus_ekonomi')?->data ?? [];
        $pariwisata = $dataDesa->get('pariwisata')?->data ?? [];
        $transportasi = $dataDesa->get('transportasi')?->data ?? [];

        // Statistik Penduduk
        $pd = $kependudukan['kpd_penduduk_dusun'] ?? [];
        $totalPenduduk = (int) collect($pd)->sum('total');
        $totalLaki = (int) collect($pd)->sum('laki_laki');
        $totalPerempuan = (int) collect($pd)->sum('perempuan');
        $totalKK = (int) collect($kependudukan['kpd_kk_dusun'] ?? [])->sum('jumlah_kk');
        $dusunCount = count($pd);
        $bansos = (int) collect($sosial['sosial_bansos'] ?? [])->sum('jumlah_penerima');

        $statistik = [
            ['label' => 'Total Penduduk', 'nilai' => number_format($totalPenduduk, 0, ',', '.'), 'icon' => 'group', 'warna' => 'emerald', 'sub' => [[number_format($totalLaki), 'Laki-laki'], [number_format($totalPerempuan), 'Perempuan']]],
            ['label' => 'Kartu Keluarga', 'nilai' => number_format($totalKK, 0, ',', '.'), 'icon' => 'id', 'warna' => 'yellow', 'sub' => [[number_format($totalKK), 'Kepala Keluarga Terdaftar']]],
            ['label' => 'Wilayah Dusun', 'nilai' => $dusunCount, 'icon' => 'edu', 'warna' => 'blue', 'sub' => collect($pd)->pluck('nama_dusun')->map(fn ($n) => [$n, '']) ->all()],
            ['label' => 'Penerima Bansos', 'nilai' => number_format($bansos, 0, ',', '.'), 'icon' => 'heart', 'warna' => 'red', 'sub' => [['Program', 'Keluarga Penerima Manfaat']]],
        ];

        // Pendidikan
        $pendidikan = collect($kependudukan['kpd_pendidikan'] ?? [])
            ->sortByDesc('jumlah')
            ->take(6)
            ->map(fn ($r) => [$r['tingkat_pendidikan'] ?? '-', (int) ($r['jumlah'] ?? 0)])
            ->values();
        $totalPendidikan = $pendidikan->sum(fn ($p) => $p[1]);

        return [
            'profil' => $profil,
            'beritas' => ArtikelGaleri::berita()->latest()->take(3)->get(),
            'galeris' => ArtikelGaleri::galeri()->latest()->take(6)->get(),
            'kepala_desa' => $profil['kepala_desa'] ?? 'Kepala Desa Kaki Air',
            'perangkat' => ProfilService::perangkat('Kaki Air'),
            'programs' => ProfilService::program('Kaki Air'),
            'kependudukan' => $kependudukan,
            'sensus' => $sensus,
            'sosial' => $sosial,
            'ekonomi' => $ekonomi,
            'pariwisata' => $pariwisata,
            'transportasi' => $transportasi,
            'statistik' => $statistik,
            'pendidikan' => $pendidikan,
        ];
    }
};
?>
{{-- Halaman BERANDA (/) - hero slider, statistik, profil singkat, fasilitas, galeri, berita. --}}

<div>
    <!-- ===== 1. HERO SLIDER ===== -->
    <section id="beranda" class="relative h-[85vh] min-h-[600px] overflow-hidden bg-emerald-950"
        x-data="{
            slides: [
                {
                    image: '{{ asset('images/village_banner.png') }}',
                    badge: 'Selamat Datang di Portal Resmi',
                    title: 'Layanan Digital & Informasi Publik',
                    highlight: 'Desa Kaki Air',
                    text: 'Ajukan permohonan surat administrasi secara online, sampaikan aduan secara langsung, dan ikuti perkembangan berita serta dokumentasi pembangunan desa kami.',
                    cta: '{{ route('layanan-surat') }}',
                    ctaLabel: 'Ajukan Surat Online',
                    cta2: '{{ route('pengaduan') }}',
                    cta2Label: 'Laporkan Pengaduan',
                },
                {
                    image: '{{ asset('images/Perencanaan Kaki Air.jpg') }}',
                    badge: 'Perencanaan & Pembangunan',
                    title: 'Perencanaan Pembangunan yang Transparan',
                    highlight: '',
                    text: 'Keterbukaan informasi perencanaan dan anggaran desa demi pembangunan yang merata dan berkelanjutan.',
                    cta: '{{ route('profil-desa-dan-infografis') }}',
                    ctaLabel: 'Lihat Infografis',
                    cta2: '',
                    cta2Label: '',
                },
                {
                    image: '{{ asset('images/Pemerintahan Kaki Air.jpg') }}',
                    badge: 'Pemerintahan Terbuka',
                    title: 'Tata Kelola Pemerintahan yang Terbuka',
                    highlight: 'dan Berwibawa',
                    text: 'Wujudkan pemerintahan desa yang akuntabel, informatif, dan melayani masyarakat.',
                    cta: '{{ route('profil-desa') }}',
                    ctaLabel: 'Profil Desa',
                    cta2: '',
                    cta2Label: '',
                },
            ],
            current: 0,
            timer: null,
            start() { this.timer = setInterval(() => this.next(), 6000); },
            stop() { clearInterval(this.timer); },
            next() { this.current = (this.current + 1) % this.slides.length; },
            prev() { this.current = (this.current - 1 + this.slides.length) % this.slides.length; },
        }"
        x-init="start()" @mouseenter="stop()" @mouseleave="start()">

        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="current === index" x-cloak x-transition:enter="transition-opacity duration-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0">
                <img :src="slide.image" :alt="slide.title" class="absolute inset-0 h-full w-full object-cover">
                <div class="absolute inset-0 bg-black/30"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-950/90 via-emerald-900/60 to-teal-900/30"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            </div>
        </template>

        <div class="relative z-10 flex h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 items-center">
            <template x-for="(slide, index) in slides" :key="'c' + index">
                <div x-show="current === index" x-cloak x-transition:enter="transition-all duration-700" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0" class="max-w-3xl">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-6 uppercase tracking-wider" x-text="slide.badge"></span>
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white tracking-tight leading-tight">
                        <span x-text="slide.title"></span>
                        <span x-show="slide.highlight" class="bg-gradient-to-r from-emerald-400 to-teal-300 bg-clip-text text-transparent" x-text="' ' + slide.highlight"></span>
                    </h1>
                    <p class="mt-6 text-lg text-emerald-100/90 max-w-2xl leading-relaxed" x-text="slide.text"></p>
                    <div class="mt-10 flex flex-wrap gap-4">
                        <a :href="slide.cta" class="px-8 py-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-600 hover:to-teal-500 text-white font-bold shadow-lg shadow-emerald-900/50 hover:shadow-xl hover:-translate-y-0.5 transition-all" x-text="slide.ctaLabel"></a>
                        <a x-show="slide.cta2" :href="slide.cta2" class="px-8 py-4 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold border border-white/20 backdrop-blur-sm transition-all" x-text="slide.cta2Label"></a>
                    </div>
                </div>
            </template>
        </div>

        <button @click="prev()" type="button" class="absolute inset-y-0 left-4 z-30 my-auto h-12 w-12 rounded-full border border-white/20 bg-white/10 text-white backdrop-blur-sm hover:bg-white hover:text-gray-900 transition-all hidden md:flex items-center justify-center" aria-label="Sebelumnya">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button @click="next()" type="button" class="absolute inset-y-0 right-4 z-30 my-auto h-12 w-12 rounded-full border border-white/20 bg-white/10 text-white backdrop-blur-sm hover:bg-white hover:text-gray-900 transition-all hidden md:flex items-center justify-center" aria-label="Berikutnya">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </button>

        <div class="absolute bottom-20 inset-x-0 z-20 flex justify-center gap-2">
            <template x-for="(slide, index) in slides" :key="'d' + index">
                <button @click="current = index" type="button" class="h-2.5 rounded-full transition-all duration-300" :class="current === index ? 'w-8 bg-emerald-400' : 'w-2.5 bg-white/40 hover:bg-white/70'" :aria-label="'Slide ' + (index + 1)"></button>
            </template>
        </div>

        <!-- Running text -->
        <div class="relative z-20 w-full overflow-hidden border-t border-white/10 py-3 bg-emerald-600 text-white shadow-lg animate-marquee-hover">
            @php
                $marquee = [
                    '🏛️ Selamat Datang di Website Resmi Desa Kaki Air',
                    '⚖️ Wujudkan Tata Kelola Desa yang Transparan, Akuntabel, dan Informatif',
                    '📄 Nikmati Layanan Administrasi Mandiri Secara Online Lebih Cepat dan Mudah',
                    '💰 Pantau Terus Penggunaan Dana Desa demi Pembangunan yang Merata',
                    '🚀 Kaki Air Maju dengan Digitalisasi Desa Menuju Desa Digital yang Mandiri',
                ];
            @endphp
            <div class="animate-marquee flex w-max items-center">
                @foreach (array_merge($marquee, $marquee) as $teks)
                    <span class="flex shrink-0 items-center gap-20 pr-20 whitespace-nowrap text-sm font-semibold">{{ $teks }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== 2. WELCOME BANNER + PROFIL ===== -->
    <section id="profil" class="relative overflow-hidden py-20 md:py-24 bg-white">
        <div class="absolute inset-0 opacity-10 bg-cover bg-center" style="background-image: url('{{ asset('images/village_banner.png') }}');"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-white via-white/80 to-emerald-50"></div>
        <div class="relative container mx-auto px-4 text-center">
            <h1 class="mb-6 text-3xl md:text-5xl font-bold text-slate-900">Selamat Datang di<br><span class="capitalize text-emerald-600">Desa Kaki Air</span></h1>
            <p class="mx-auto max-w-2xl text-slate-600 leading-relaxed">Desa yang asri dengan pemandangan alam yang indah, kaya akan budaya dan tradisi. Kami berkomitmen untuk memberikan pelayanan terbaik bagi masyarakat.</p>
        </div>
    </section>

    <section class="bg-gray-50 pb-20">
        <div class="relative z-20 container mx-auto -mt-16 px-4">
            <div class="overflow-hidden rounded-3xl border-t-4 border-emerald-600 bg-white shadow-xl" x-data="{ sejarah: false }">
                <div class="grid grid-cols-1 lg:grid-cols-12">
                    <div class="lg:col-span-7 relative min-h-[300px] lg:min-h-[500px] bg-slate-900 flex items-center justify-center">
                        <div class="text-center text-white/70">
                            <svg class="w-16 h-16 mx-auto mb-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <p class="text-sm font-medium">Video Profil Belum Tersedia</p>
                        </div>
                        {{-- ponytail: ganti blok ini dengan <video src="/storage/{video_url}"> atau iframe YouTube saat ada video. --}}
                    </div>
                    <div class="flex flex-col justify-center p-8 lg:col-span-5 lg:p-12">
                        <span class="mb-6 inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-sm font-bold text-emerald-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Tentang Kami
                        </span>
                        <h2 class="mb-4 text-3xl font-bold text-gray-900 lg:text-4xl">Mengenal Lebih Dekat<br><span class="text-emerald-600 capitalize">Desa Kaki Air</span></h2>
                        @php
                            $sejarah = trim(strip_tags($profil['sejarah'] ?? ''));
                        @endphp
                        <p class="prose prose-sm mb-8 line-clamp-4 text-gray-600 leading-relaxed">{{ $sejarah ?: 'Sejarah Desa Kaki Air belum tersedia.' }}</p>
                        <div class="border-t border-gray-100 pt-6 mb-8">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="rounded-lg bg-emerald-100 p-2 text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500">Luas Wilayah</p>
                                        <p class="text-sm font-bold text-gray-800">{{ number_format($profil['luas_wilayah_ha'] ?? 0, 0, ',', '.') }} Ha</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="rounded-lg bg-teal-100 p-2 text-teal-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </span>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500">RT / RW</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $profil['jumlah_rt'] ?? 0 }} RT / {{ $profil['jumlah_rw'] ?? 0 }} RW</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- ponytail: koordinat desa tak ada di data; pakai RT/RW. --}}
                        <button @click="sejarah = true" class="group inline-flex w-full items-center justify-center rounded-xl bg-emerald-600 px-6 py-3.5 font-bold text-white shadow-lg shadow-emerald-200 hover:-translate-y-1 hover:bg-emerald-700 transition-all sm:w-auto">
                            Baca Sejarah Lengkap
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Sejarah --}}
                <div x-cloak x-show="sejarah" class="fixed inset-0 z-[999] bg-black/80 backdrop-blur-sm overflow-y-auto" @keydown.escape.window="sejarah = false">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="w-full max-w-4xl rounded-3xl bg-white shadow-2xl" @click.outside="sejarah = false">
                            <div class="flex items-center justify-between border-b border-gray-100 p-6">
                                <h3 class="text-2xl font-bold text-gray-900">Sejarah Lengkap Desa Kaki Air</h3>
                                <button @click="sejarah = false" class="rounded-full p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="p-6 lg:p-8 space-y-6">
                                <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $sejarah ?: 'Sejarah Desa Kaki Air belum tersedia.' }}</p>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="rounded-lg bg-gray-50 p-4 text-center">
                                        <p class="text-xs font-bold text-gray-500 uppercase">Luas Wilayah</p>
                                        <p class="mt-1 text-lg font-bold text-gray-800">{{ number_format($profil['luas_wilayah_ha'] ?? 0, 0, ',', '.') }} Ha</p>
                                    </div>
                                    <div class="rounded-lg bg-gray-50 p-4 text-center">
                                        <p class="text-xs font-bold text-gray-500 uppercase">Tahun Berdiri</p>
                                        <p class="mt-1 text-lg font-bold text-gray-800">{{ $profil['tahun_berdiri'] ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-lg bg-gray-50 p-4 text-center">
                                        <p class="text-xs font-bold text-gray-500 uppercase">Potensi Unggulan</p>
                                        <p class="mt-1 text-lg font-bold text-gray-800">{{ $profil['potensi_unggulan'] ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== 3. PROFIL PEMERINTAHAN DESA ===== -->
    <section id="pemerintahan" class="bg-gray-50 py-20">
        <div class="container mx-auto px-4">
            <h2 class="mb-12 text-center text-3xl font-bold text-gray-800">Profil Pemerintahan Desa</h2>
            <p class="mt-[-32px] mb-12 text-center text-gray-500">Struktur organisasi dan lembaga kemasyarakatan desa.</p>
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3" x-data="{ struktur: false }">
                <!-- Kepala Desa -->
                <div class="rounded-2xl border-t-4 border-emerald-600 bg-white p-8 text-center shadow-lg hover:shadow-xl transition-shadow">
                    <div class="relative mx-auto mb-6 w-fit">
                        <div class="absolute inset-0 -rotate-6 rounded-full bg-emerald-100"></div>
                        <img src="{{ asset('images/kepala_desa.png') }}" alt="{{ $kepala_desa }}" class="relative h-40 w-40 rounded-full border-4 border-white object-cover shadow-lg">
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $kepala_desa }}</h3>
                    <p class="mb-4 font-medium text-emerald-600 capitalize">Kepala Desa Kaki Air</p>
                    <div class="rounded-xl border-t border-gray-100 bg-gray-50 px-4 py-4 text-sm">
                        <div class="flex justify-between py-1"><span class="text-gray-500">NIP</span><span class="font-bold text-gray-800">-</span></div>
                        <div class="flex justify-between py-1"><span class="text-gray-500">Periode</span><span class="font-bold text-gray-800">-</span></div>
                    </div>
                    <div class="mt-4 rounded-xl bg-emerald-50/60 p-4 text-left">
                        <p class="mb-1 text-xs font-bold text-emerald-700 uppercase">Visi</p>
                        <p class="line-clamp-3 text-sm italic text-gray-600">"{{ $profil['visi'] ?? 'Belum tersedia.' }}"</p>
                    </div>
                    {{-- ponytail: NIP & Periode tak ada di sumber data. --}}
                </div>

                <!-- Struktur Pemerintah Desa -->
                <div class="rounded-2xl border-t-4 border-emerald-600 bg-white shadow-lg hover:shadow-xl transition-shadow flex flex-col">
                    <h4 class="mb-6 border-b p-6 pb-4 text-xl font-bold text-gray-900">Struktur Pemerintah Desa</h4>
                    <div class="custom-scrollbar flex-1 overflow-y-auto px-6 pb-6" style="max-height:600px">
                        @forelse($perangkat as $orang)
                            <div class="group mb-3 flex items-center gap-3 rounded-lg bg-gray-50 p-3 hover:bg-emerald-50 transition-colors">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-400 text-white font-bold shadow ring-2 ring-white">
                                    {{ str($orang['nama'] ?? '?')->substr(0, 1)->upper() }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-emerald-600 uppercase">{{ $orang['jabatan'] ?? '-' }}</p>
                                    <p class="truncate text-sm font-bold text-gray-800">{{ $orang['nama'] ?? '-' }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-gray-400">
                                <svg class="mx-auto mb-3 w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="text-sm">Data perangkat belum tersedia.</p>
                            </div>
                        @endforelse
                    </div>
                    @if(count($perangkat) > 6)
                        <button @click="struktur = true" class="m-6 mt-0 rounded-lg bg-gray-100 py-2 font-bold text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">Lihat Selengkapnya</button>
                    @endif
                </div>

                <!-- Pengurus RT / RW -->
                <div class="rounded-2xl border-t-4 border-emerald-600 bg-white shadow-lg hover:shadow-xl transition-shadow">
                    <h4 class="mb-6 border-b p-6 pb-4 text-xl font-bold text-gray-900">Pengurus RT / RW</h4>
                    <div class="px-6 pb-6 space-y-4">
                        @php
                            $rtRw = collect($perangkat)->filter(fn ($o) => str_contains(($o['jabatan'] ?? ''), 'RT') || str_contains(($o['jabatan'] ?? ''), 'RW'))->values();
                        @endphp
                        @forelse($rtRw as $orang)
                            <div class="rounded-xl border border-gray-200 hover:border-emerald-300 transition-colors overflow-hidden">
                                <div class="bg-gray-50 p-3 text-sm font-bold uppercase text-gray-700">{{ $orang['jabatan'] ?? '-' }}</div>
                                <div class="p-3">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border-t-4 border-emerald-600 bg-gray-100 text-emerald-600 font-bold">
                                            {{ str($orang['nama'] ?? '?')->substr(0, 1)->upper() }}
                                        </div>
                                        <div>
                                            <p class="text-[10px] uppercase text-gray-400">Pengurus</p>
                                            <p class="text-sm font-bold text-gray-800">{{ $orang['nama'] ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-sm text-gray-400">Data RT/RW belum tersedia.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Modal Struktur Lengkap --}}
                <div x-cloak x-show="struktur" class="fixed inset-0 z-[999] bg-black/80 backdrop-blur-sm overflow-y-auto" @keydown.escape.window="struktur = false">
                    <div class="flex min-h-full items-center justify-center p-4">
                        <div class="w-full max-w-3xl rounded-3xl bg-white shadow-2xl" @click.outside="struktur = false">
                            <div class="flex items-center justify-between border-b border-gray-100 p-6">
                                <h3 class="text-2xl font-bold text-gray-900">Perangkat Desa</h3>
                                <button @click="struktur = false" class="rounded-full p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600" aria-label="Tutup">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($perangkat as $orang)
                                    <div class="flex items-center gap-3 rounded-lg bg-gray-50 p-3">
                                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-400 text-white font-bold">
                                            {{ str($orang['nama'] ?? '?')->substr(0, 1)->upper() }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-emerald-600 uppercase">{{ $orang['jabatan'] ?? '-' }}</p>
                                            <p class="truncate text-sm font-bold text-gray-800">{{ $orang['nama'] ?? '-' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== 4. STATISTIK PENDUDUK ===== -->
    <section id="statistik" class="bg-white py-20">
        <div class="container mx-auto px-4">
            <div class="mb-16 text-center">
                <h2 class="mb-2 text-3xl font-bold text-gray-800">Statistik Penduduk Desa</h2>
                <div class="mx-auto h-1 w-20 rounded-full bg-emerald-600"></div>
            </div>


            <div class="mb-12 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach($statistik as $s)
                    <div class="rounded-xl border-t-4 border-emerald-600 bg-white p-6 text-center shadow-lg hover:-translate-y-[5px] transition-transform">
                        <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-{{ $s['warna'] }}-50 text-{{ $s['warna'] }}-600">
                            @if($s['icon'] === 'group')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            @elseif($s['icon'] === 'id')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0a2 2 0 104 0m-4 0a2 2 0 114 0m-6 6a2 2 0 104 0 2 2 0 00-4 0zm8-2h4m-4 3h4"/></svg>
                            @elseif($s['icon'] === 'heart')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            @else
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            @endif
                        </div>
                        <h3 class="mb-1 text-4xl font-extrabold text-gray-800">{{ $s['nilai'] }}</h3>
                        <p class="text-sm font-semibold text-gray-500">{{ $s['label'] }}</p>
                        <div class="mt-4 border-t border-gray-100 pt-4 space-y-1.5 text-xs text-gray-500">
                            @foreach($s['sub'] as $row)
                                <div class="flex justify-between"><span>{{ $row[0] }}</span><span>{{ $row[1] }}</span></div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

             <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">

                <div class="rounded-xl border-t-4 border-emerald-600 bg-white shadow-lg">
                    <div class="border-b border-gray-100 bg-gray-50 p-6">
                        <h4 class="text-lg font-bold text-gray-800">Rincian Data Pendidikan</h4>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($pendidikan as $p)
                             @php $totalPend = collect($pendidikan)->sum(fn ($p) => $p[1]); $persen = $totalPend ? round($p[1] / $totalPend * 100) : 0; @endphp
                            <div>
                                <div class="mb-1 flex justify-between text-sm">
                                    <span class="font-medium text-gray-600">{{ $p[0] }}</span>
                                    <span class="font-bold text-gray-800">{{ number_format($p[1]) }} <span class="font-normal text-gray-400">({{ $persen }}%)</span></span>
                                </div>
                                <div class="h-2.5 rounded-full bg-gray-200">
                                    <div class="h-2.5 rounded-full bg-gradient-to-r from-emerald-500 to-teal-400" style="width: {{ $persen }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="py-8 text-center text-sm text-gray-400">Belum ada data pendidikan.</p>
                        @endforelse
                    </div>
                </div>


                 {{-- Distribusi Usia --}}
                @php
                    $usia = collect($sensus['sens_kelompok_umur'] ?? [])
                        ->map(fn ($r) => [$r['kelompok_umur'] ?? '-', (int) ($r['laki_laki'] ?? 0) + (int) ($r['perempuan'] ?? 0)])
                        ->values();
                    $maxU = $usia->max(fn ($u) => $u[1]) ?: 1;
                @endphp
                <div class="rounded-xl border-t-4 border-emerald-600 bg-white shadow-lg">
                    <div class="border-b border-gray-100 bg-gray-50 p-6">
                        <h4 class="text-lg font-bold text-gray-800">Distribusi Usia Penduduk</h4>
                    </div>
                    <div class="p-6">
                        @if($usia->isNotEmpty())
                            <div class="flex items-end gap-1.5 h-56">
                                @foreach($usia as $u)
                                    <div class="flex-1 flex flex-col items-center gap-1 group">
                                        <span class="text-[10px] font-bold text-gray-500 opacity-0 group-hover:opacity-100 transition-opacity">{{ $u[1] }}</span>
                                        <div class="w-full rounded-t-md bg-gradient-to-t from-emerald-600 to-teal-400 group-hover:from-emerald-500 group-hover:to-teal-300 transition-colors" style="height: {{ round($u[1] / $maxU * 100) }}%"></div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-2 flex justify-between text-[10px] font-semibold text-gray-400">
                                @foreach($usia as $u)<span>{{ $u[0] }}</span>@endforeach
                            </div>
                        @else
                            <p class="py-12 text-center text-sm text-gray-400">Belum ada data kelompok umur.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== 5. FASILITAS DESA ===== -->
    <section id="fasilitas" class="bg-gray-50 py-20">
        <div class="container mx-auto px-4">
            <div class="mb-10 text-center">
                <h2 class="mb-2 text-3xl font-bold text-gray-800">Fasilitas Desa</h2>
                <div class="mx-auto h-1 w-20 rounded-full bg-emerald-600"></div>
                <p class="mt-3 text-gray-500">Sarana publik untuk mendukung produktivitas dan kesejahteraan warga.</p>
            </div>

            @php
                $fasilitas = [];
                foreach (array_slice($sosial['sosial_sekolah'] ?? [], 0, 3) as $sk) {
                    $jumlah = (int) ($sk['negeri'] ?? 0) + (int) ($sk['swasta'] ?? 0);
                    if ($jumlah > 0) {
                        $fasilitas[] = ['nama' => $sk['tingkat_pendidikan'] ?? 'Sekolah', 'jenis' => 'PENDIDIKAN', 'alamat' => $jumlah . ' lembaga', 'kondisi' => 'Baik', 'status' => 'MILIK PEMERINTAH'];
                    }
                }
                foreach (array_slice($sosial['sosial_posyandu'] ?? [], 0, 1) as $po) {
                    $fasilitas[] = ['nama' => $po['nama_posyandu'] ?? 'Posyandu', 'jenis' => 'KESEHATAN', 'alamat' => (int) ($po['jumlah_kader'] ?? 0) . ' kader', 'kondisi' => 'Baik', 'status' => 'MILIK DESA'];
                }
                $faskes = collect($sosial['sosial_faskes'] ?? []);
                $totalFaskes = $faskes->sum(fn ($r) => (int) ($r['puskesmas'] ?? 0) + (int) ($r['pustu'] ?? 0) + (int) ($r['polindes'] ?? 0) + (int) ($r['posyandu'] ?? 0) + (int) ($r['klinik'] ?? 0));
                if ($totalFaskes > 0) {
                    $fasilitas[] = ['nama' => 'Fasilitas Kesehatan', 'jenis' => 'KESEHATAN', 'alamat' => $totalFaskes . ' unit', 'kondisi' => 'Baik', 'status' => 'MILIK PEMERINTAH'];
                }
                foreach (array_slice($kependudukan['kpd_masjid'] ?? [], 0, 1) as $mj) {
                    $fasilitas[] = ['nama' => $mj['nama_masjid'] ?? 'Masjid', 'jenis' => 'IBADAH', 'alamat' => $mj['alamat'] ?? ($mj['nama_dusun'] ?? 'Kaki Air'), 'kondisi' => 'Baik', 'status' => 'SWASTA'];
                }
                foreach (collect($kependudukan['kpd_fasilitas_olahraga'] ?? [])->filter(fn ($r) => (int) ($r['jumlah'] ?? 0) > 0)->take(1) as $ol) {
                    $fasilitas[] = ['nama' => $ol['jenis_fasilitas'] ?? 'Sarana Olahraga', 'jenis' => 'UMUM', 'alamat' => (int) $ol['jumlah'] . ' unit', 'kondisi' => $ol['kondisi'] ?? 'Baik', 'status' => 'MILIK DESA'];
                }
                $ibadah = collect($kependudukan['kpd_tempat_ibadah'] ?? []);
                $totalIbadah = $ibadah->sum(fn ($r) => (int) ($r['masjid'] ?? 0) + (int) ($r['musholla'] ?? 0) + (int) ($r['gereja'] ?? 0) + (int) ($r['pura'] ?? 0) + (int) ($r['vihara'] ?? 0));
                if ($totalIbadah > 0) {
                    $fasilitas[] = ['nama' => 'Tempat Ibadah', 'jenis' => 'IBADAH', 'alamat' => $totalIbadah . ' unit', 'kondisi' => 'Baik', 'status' => 'UMUM'];
                }
            @endphp
            @if($fasilitas)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($fasilitas as $f)
                    <div class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm hover:shadow-md transition-shadow">
                        <div class="relative h-44 bg-gradient-to-br from-emerald-100 to-teal-50 flex items-center justify-center">
                            <svg class="w-16 h-16 text-emerald-400/60 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="absolute top-3 left-3 rounded-md bg-white/90 px-2 py-1 text-[10px] font-bold text-gray-700">{{ $f['jenis'] }}</span>
                        </div>
                        <div class="p-5">
                            <h3 class="truncate text-md font-bold text-gray-900">{{ $f['nama'] }}</h3>
                            <p class="mt-1 text-xs text-gray-400">{{ $f['alamat'] }}</p>
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <div class="rounded-lg bg-gray-50 p-2">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">Kondisi</p>
                                    <p class="text-[11px] font-bold {{ $f['kondisi'] === 'Baik' ? 'text-emerald-600' : 'text-red-500' }}">{{ $f['kondisi'] }}</p>
                                </div>
                                <div class="rounded-lg bg-gray-50 p-2">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase">Status</p>
                                    <p class="text-[11px] font-bold text-gray-700">{{ $f['status'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @else
                <div class="rounded-2xl bg-white p-12 text-center text-gray-400 border border-gray-100">
                    <p class="font-medium">Belum ada data fasilitas desa.</p>
                </div>
            @endif
            {{-- ponytail: fasilitas disusun dari data_desa (sekolah/posyandu/faskes/ibadah/olahraga). --}}
            <div class="mt-10 text-center">
                <a href="{{ route('data-desa') }}" class="inline-flex items-center font-bold text-emerald-600 hover:text-emerald-700">
                    Lihat Semua Fasilitas Desa
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    <!-- ===== 6. TRANSPARANSI & PERENCANAAN ===== -->
    <section id="anggaran" class="bg-white py-20">
        <div class="container mx-auto px-4">
            <div class="mb-16 text-center">
                <h2 class="mb-2 text-3xl font-bold text-gray-800">Transparansi & Perencanaan</h2>
                <div class="mx-auto h-1 w-20 rounded-full bg-emerald-600"></div>
                <p class="mt-3 text-gray-500">Informasi APBDes dan Rencana Pembangunan Desa.</p>
            </div>

            @php
                $totalApbdes = (int) ($profil['total_apbdesa'] ?? 0);
                $sumber = [
                    ['Pendapatan Asli Desa', (int) ($profil['pendapatan_asli_desa'] ?? 0), 'from-emerald-500 to-emerald-400'],
                    ['Dana Desa', (int) ($profil['dana_desa'] ?? 0), 'from-blue-500 to-blue-400'],
                    ['Alokasi Dana Desa', (int) ($profil['alokasi_dana_desa'] ?? 0), 'from-yellow-500 to-yellow-400'],
                ];
                $maxSumber = max(array_column($sumber, 1)) ?: 1;
                $maxProgram = collect($programs)->max('anggaran') ?: 1;
                $statusWarna = ['Selesai' => 'bg-emerald-600', 'Berjalan' => 'bg-sky-600', 'default' => 'bg-amber-500'];
            @endphp
            <div class="mb-16 grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="rounded-2xl border-t-4 border-emerald-600 bg-white p-6 shadow-lg">
                    <h5 class="text-center text-sm font-bold text-emerald-600 uppercase">APBDes Tahun {{ date('Y') }}</h5>
                    <div class="my-4 text-center">
                        <h3 class="text-3xl font-extrabold text-emerald-600">Rp {{ number_format($totalApbdes, 0, ',', '.') }}</h3>
                        <p class="text-xs text-gray-400">Total Pendapatan Desa</p>
                    </div>
                    <div class="space-y-3">
                        @foreach($sumber as $s)
                            <div>
                                <div class="mb-1 flex justify-between text-xs">
                                    <span class="font-medium text-gray-600">{{ $s[0] }}</span>
                                    <span class="font-bold text-gray-800">Rp {{ number_format($s[1], 0, ',', '.') }}</span>
                                </div>
                                <div class="h-2.5 rounded-full bg-gray-100">
                                    <div class="h-2.5 rounded-full bg-gradient-to-r {{ $s[2] }}" style="width: {{ round($s[1] / $maxSumber * 100) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border-t-4 border-emerald-600 bg-white p-6 shadow-lg">
                    <h5 class="text-center text-sm font-bold text-emerald-600 uppercase">Program Prioritas</h5>
                    <div class="my-4 text-center">
                        <h3 class="text-3xl font-extrabold text-emerald-600">{{ count($programs) }}</h3>
                        <p class="text-xs text-gray-400">Program Pembangunan Desa</p>
                    </div>
                    <div class="space-y-4">
                        @foreach(array_slice($programs, 0, 4) as $p)
                            <div>
                                <div class="flex items-center justify-between gap-3 text-sm">
                                    <span class="line-clamp-2 text-xs font-medium text-gray-600 flex-1">{{ $p['nama_program'] }}</span>
                                    <span class="shrink-0 rounded px-2 py-0.5 text-[10px] font-bold text-white {{ $statusWarna[$p['status']] ?? $statusWarna['default'] }}">{{ $p['status'] }}</span>
                                </div>
                                <div class="mt-1.5 h-2 rounded-full bg-gray-100">
                                    <div class="h-2 rounded-full bg-gradient-to-r from-emerald-500 to-teal-400" style="width: {{ round(($p['anggaran'] ?? 0) / $maxProgram * 100) }}%"></div>
                                </div>
                                <p class="mt-1 text-right text-[10px] font-bold text-gray-400">Rp {{ number_format($p['anggaran'] ?? 0, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                        @if(count($programs) > 4)
                            <a href="{{ route('profil-desa') }}" class="block text-center text-xs font-bold text-emerald-600 hover:underline">Lihat Semua Program</a>
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border-t-4 border-emerald-600 bg-white p-6 shadow-lg">
                    <h5 class="text-center text-sm font-bold text-emerald-600 uppercase">Ringkasan APBDes</h5>
                    <div class="mt-6 space-y-3">
                        @foreach(array_merge([['Total APBDesa', $totalApbdes, 'text-emerald-600', 'bg-emerald-50']], array_map(fn ($s) => [$s[0], $s[1], 'text-gray-800', 'bg-gray-50'], $sumber)) as $row)
                            <div class="flex items-center justify-between rounded-xl {{ $row[3] }} p-4">
                                <span class="text-sm font-semibold text-gray-600">{{ $row[0] }}</span>
                                <span class="text-sm font-extrabold {{ $row[2] }}">Rp {{ number_format($row[1], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-4 text-center text-xs text-gray-400">Data keuangan Desa Kaki Air.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <div class="rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between border-b border-emerald-100 bg-emerald-50 p-4">
                        <h4 class="font-bold text-gray-800">Rencana Jangka Pendek</h4>
                        <span class="rounded-full bg-emerald-600 px-3 py-1 text-[10px] font-bold text-white">1 TAHUN</span>
                    </div>
                    <ul class="p-4 space-y-2 text-sm text-gray-600">
                        <li class="flex items-start gap-2"><span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-emerald-400"></span>Belum ada data.</li>
                    </ul>
                </div>
                <div class="rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between border-b border-yellow-100 bg-yellow-50 p-4">
                        <h4 class="font-bold text-gray-800">Rencana Jangka Menengah</h4>
                        <span class="rounded-full bg-yellow-500 px-3 py-1 text-[10px] font-bold text-white">2-4 TAHUN</span>
                    </div>
                    <ul class="p-4 space-y-2 text-sm text-gray-600">
                        <li class="flex items-start gap-2"><span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-yellow-400"></span>Belum ada data.</li>
                    </ul>
                </div>
                <div class="rounded-xl border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between border-b border-purple-100 bg-purple-50 p-4">
                        <h4 class="font-bold text-gray-800">Rencana Jangka Panjang</h4>
                        <span class="rounded-full bg-purple-600 px-3 py-1 text-[10px] font-bold text-white">5-10 TAHUN</span>
                    </div>
                    <ul class="p-4 space-y-2 text-sm text-gray-600">
                        <li class="flex items-start gap-2"><span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-purple-400"></span>Belum ada data.</li>
                    </ul>
                </div>
            </div>
            {{-- ponytail: rencana ambil dari model RPJM bila dibuat. --}}
        </div>
    </section>

    <!-- ===== 7. POTENSI & INFRASTRUKTUR ===== -->
    <section id="potensi" class="bg-gray-50 py-20">
        <div class="container mx-auto px-4">
            <div class="mb-16 text-center">
                <h2 class="mb-2 text-3xl font-bold text-gray-800">Potensi & Infrastruktur Desa</h2>
                <div class="mx-auto h-1 w-20 rounded-full bg-emerald-600"></div>
                <p class="mt-3 text-gray-500">Gambaran mata pencaharian, pariwisata, dan ketersediaan infrastruktur.</p>
            </div>

            @php
                $pekerjaan = collect($kependudukan['kpd_pekerjaan'] ?? [])->sortByDesc('jumlah')->take(6)->values();
                $maxKerja = $pekerjaan->max('jumlah') ?: 1;

                $resto = collect($pariwisata['wisata_restoran'] ?? [])->sum(fn ($r) => (int) ($r['restoran'] ?? 0) + (int) ($r['kedai'] ?? 0) + (int) ($r['warung_makan'] ?? 0));
                $toko = collect($pariwisata['wisata_toko'] ?? [])->sum(fn ($r) => (int) ($r['toko_kelontong'] ?? 0) + (int) ($r['warung'] ?? 0) + (int) ($r['minimarket'] ?? 0));
                $inap = collect($pariwisata['wisata_penginapan'] ?? [])->sum(fn ($r) => (int) ($r['hotel'] ?? 0) + (int) ($r['penginapan'] ?? 0) + (int) ($r['homestay'] ?? 0));

                $kendaraan = collect($transportasi['trans_kendaraan'] ?? [])->sum('jumlah');
                $menara = collect($transportasi['trans_menara'] ?? [])->sum('jumlah_menara');
                $bengkel = collect($transportasi['trans_bengkel'] ?? [])->sum(fn ($r) => (int) ($r['bengkel_motor'] ?? 0) + (int) ($r['bengkel_mobil'] ?? 0) + (int) ($r['bengkel_las'] ?? 0));
                $pos = collect($transportasi['trans_kantor_pos'] ?? [])->filter(fn ($r) => !empty($r['ada_kantor_pos']))->count();

                $infrastruktur = [
                    ['Kendaraan Bermotor', $kendaraan, 'unit', 'road', 'bg-gray-100 text-gray-600'],
                    ['Menara Seluler', $menara, 'menara', 'signal', 'bg-indigo-100 text-indigo-600'],
                    ['Bengkel', $bengkel, 'unit', 'bolt', 'bg-yellow-100 text-yellow-600'],
                    ['Kantor POS', $pos, 'unit', 'drop', 'bg-blue-100 text-blue-600'],
                ];
            @endphp
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="flex h-full flex-col overflow-hidden rounded-2xl border-t-4 border-emerald-600 bg-white shadow-lg">
                    <div class="border-b border-gray-100 bg-blue-50/50 p-6 text-center">
                        <h4 class="text-xl font-bold text-gray-800">Mata Pencaharian</h4>
                    </div>
                    <div class="flex-1 p-6 space-y-4">
                        @forelse($pekerjaan as $pk)
                            <div>
                                <div class="mb-1 flex justify-between text-sm">
                                    <span class="text-xs font-medium text-gray-600">{{ $pk['jenis_pekerjaan'] }}</span>
                                    <span class="text-xs font-bold text-blue-600">{{ number_format($pk['jumlah']) }} Orang</span>
                                </div>
                                <div class="h-2 rounded-full bg-gray-100">
                                    <div class="h-2 rounded-full bg-gradient-to-r from-blue-500 to-blue-400" style="width: {{ round($pk['jumlah'] / $maxKerja * 100) }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="py-10 text-center text-sm text-gray-400">Data belum tersedia.</p>
                        @endforelse
                    </div>
                </div>

                <div class="flex h-full flex-col overflow-hidden rounded-2xl border-t-4 border-emerald-600 bg-white shadow-lg">
                    <div class="border-b border-gray-100 bg-green-50/50 p-6 text-center">
                        <h4 class="text-xl font-bold text-gray-800">Potensi Pariwisata & Usaha</h4>
                    </div>
                    <div class="flex-1 p-6">
                        @if($resto + $toko + $inap > 0)
                            <div class="mb-6 grid grid-cols-1 gap-3">
                                <div class="rounded-xl bg-green-50 p-4 text-center">
                                    <p class="text-2xl font-extrabold text-green-700">{{ $resto }}</p>
                                    <p class="text-xs font-bold uppercase text-green-600">Restoran / Kedai / Warung</p>
                                </div>
                                <div class="rounded-xl bg-emerald-50 p-4 text-center">
                                    <p class="text-2xl font-extrabold text-emerald-700">{{ $toko }}</p>
                                    <p class="text-xs font-bold uppercase text-emerald-600">Toko / Warung / Minimarket</p>
                                </div>
                                <div class="rounded-xl bg-teal-50 p-4 text-center">
                                    <p class="text-2xl font-extrabold text-teal-700">{{ $inap }}</p>
                                    <p class="text-xs font-bold uppercase text-teal-600">Hotel / Penginapan / Homestay</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center justify-center py-10 text-gray-300">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="py-4 text-center text-sm text-gray-400">Belum ada data usaha & pariwisata.</p>
                        @endif
                    </div>
                </div>

                <div class="flex h-full flex-col overflow-hidden rounded-2xl border-t-4 border-emerald-600 bg-white shadow-lg">
                    <div class="border-b border-gray-100 bg-gray-50 p-6 text-center">
                        <h4 class="text-xl font-bold text-gray-800">Infrastruktur Desa</h4>
                    </div>
                    <div class="custom-scrollbar flex-1 overflow-y-auto p-4 space-y-3" style="max-height:600px">
                        @foreach($infrastruktur as $inf)
                            <div class="flex items-center gap-3 rounded-lg p-3">
                                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $inf[4] }}">
                                    @if($inf[3] === 'road')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 21V3m0 18l16-6M4 3l16 6"/></svg>
                                    @elseif($inf[3] === 'bolt')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    @elseif($inf[3] === 'drop')
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2.69l5.66 5.66a8 8 0 11-11.31 0z"/></svg>
                                    @else
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.07c3.904-3.905 10.236-3.905 14.14 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                                    @endif
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-gray-700">{{ $inf[0] }}</p>
                                    <p class="text-xs text-gray-400">{{ number_format($inf[1]) }} {{ $inf[2] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- ponytail: potensi diambil dari data_desa (pekerjaan, usaha, transportasi). --}}
        </div>
    </section>

    <!-- ===== 8. FITUR & LAYANAN DIGITAL ===== -->
    <section id="layanan" class="bg-white py-20">
        <div class="container mx-auto px-4">
            <div class="mb-16 text-center">
                <h2 class="mb-2 text-3xl font-bold text-gray-800">Fitur & Layanan Digital</h2>
                <div class="mx-auto h-1 w-20 rounded-full bg-emerald-600"></div>
                <p class="mt-3 text-gray-500">Akses berbagai layanan publik dan informasi desa secara mudah dan cepat.</p>
            </div>

            @php
                $layanan = [
                    ['title' => 'Layanan Surat Online', 'desc' => 'Ajukan permohonan surat keterangan, pengantar, dan dokumen administrasi lainnya langsung dari rumah.', 'href' => route('layanan-surat'), 'label' => 'Buat Surat', 'warna' => 'emerald', 'icon' => 'doc'],
                    ['title' => 'Layanan Pengaduan', 'desc' => 'Sampaikan kritik, saran, dan laporan aspirasi Anda melalui formulir pengaduan online desa.', 'href' => route('pengaduan'), 'label' => 'Kirim Aduan', 'warna' => 'green', 'icon' => 'chat'],
                    ['title' => 'Portal Kabupaten', 'desc' => 'Integrasi informasi dan layanan publik tingkat Kabupaten Buru dalam satu pintu.', 'href' => 'https://burukab.go.id', 'label' => 'Kunjungi Portal', 'warna' => 'yellow', 'icon' => 'globe', 'target' => true],
                    ['title' => 'Data Statistik BPS', 'desc' => 'Akses data statistik makro dan mikro desa yang terintegrasi langsung dengan Badan Pusat Statistik.', 'href' => 'https://keparukab.bps.go.id/id', 'label' => 'Lihat Data', 'warna' => 'red', 'icon' => 'chart', 'target' => true],
                    ['title' => 'Infografis Desa', 'desc' => 'Lihat infografis seputar pemerintahan, keuangan, perencanaan, dan pembangunan desa.', 'href' => route('profil-desa-dan-infografis'), 'label' => 'Lihat Infografis', 'warna' => 'teal', 'icon' => 'photo', 'target' => true],
                    ['title' => 'Kontak Kantor Desa', 'desc' => 'Kunjungi kantor Kepala Desa Kaki Air untuk pelayanan langsung dan informasi lebih lanjut.', 'href' => route('profil-desa'), 'label' => 'Profil Desa', 'warna' => 'gray', 'icon' => 'pin'],
                ];
            @endphp
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach($layanan as $l)
                    <div class="group rounded-2xl border-t-4 border-emerald-600 bg-white p-8 text-center shadow-lg hover:-translate-y-2 transition-all">
                        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-{{ $l['warna'] }}-50 text-{{ $l['warna'] }}-600 group-hover:bg-{{ $l['warna'] }}-600 group-hover:text-white transition-colors">
                            @if($l['icon'] === 'doc')
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @elseif($l['icon'] === 'chat')
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            @elseif($l['icon'] === 'globe')
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @elseif($l['icon'] === 'chart')
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            @elseif($l['icon'] === 'photo')
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            @else
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            @endif
                        </div>
                        <h4 class="mb-3 text-xl font-bold text-gray-800">{{ $l['title'] }}</h4>
                        <p class="mb-6 text-sm text-gray-600">{{ $l['desc'] }}</p>
                        <a href="{{ $l['href'] }}" {{ !empty($l['target']) ? 'target="_blank" rel="noopener"' : '' }} class="inline-flex w-full items-center justify-center rounded-lg bg-{{ $l['warna'] }}-600 px-6 py-2.5 font-bold text-white hover:bg-{{ $l['warna'] }}-700 transition-colors">{{ $l['label'] }}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ===== 9. PUBLIKASI DESA ===== -->
    <section id="publikasi" class="bg-gray-50 py-20">
        <div class="container mx-auto px-4">
            <div class="mb-16 text-center">
                <h2 class="mb-2 text-3xl font-bold text-gray-800">Publikasi Desa</h2>
                <div class="mx-auto h-1 w-20 rounded-full bg-emerald-600"></div>
                <p class="mt-3 text-gray-500">Transparansi informasi publik melalui dokumen dan laporan desa.</p>
            </div>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="group rounded-2xl border-t-4 border-emerald-600 bg-white p-8 text-center shadow-lg hover:-translate-y-2 transition-all">
                    <p class="mb-4 text-xs font-bold uppercase text-gray-400">Peraturan & SK Desa</p>
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h4 class="mb-3 text-xl font-bold text-gray-800">Dokumen Resmi</h4>
                    <p class="mb-6 text-sm text-gray-600">Kumpulan dokumen resmi, peraturan desa, dan surat keputusan yang telah disahkan pemerintah.</p>
                    <a href="{{ route('data-desa') }}" class="inline-block w-full rounded-lg border-2 border-blue-600 py-2.5 font-bold text-blue-600 hover:bg-blue-600 hover:text-white transition-colors">Lihat Dokumen</a>
                </div>
                <div class="group rounded-2xl border-t-4 border-emerald-600 bg-white p-8 text-center shadow-lg hover:-translate-y-2 transition-all">
                    <p class="mb-4 text-xs font-bold uppercase text-gray-400">Leaflet & Brosur</p>
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-cyan-50 text-cyan-600 group-hover:bg-cyan-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5h18M3 5v14a2 2 0 002 2h14a2 2 0 002-2V5M3 5l6 6m9-6v14M9 11l-3 3m3-3l3-3"/></svg>
                    </div>
                    <h4 class="mb-3 text-xl font-bold text-gray-800">Promosi Desa</h4>
                    <p class="mb-6 text-sm text-gray-600">Materi promosi, leaflet, dan brosur digital untuk memperkenalkan potensi desa.</p>
                    <a href="{{ route('data-desa') }}" class="inline-block w-full rounded-lg border-2 border-cyan-600 py-2.5 font-bold text-cyan-600 hover:bg-cyan-600 hover:text-white transition-colors">Lihat Promosi</a>
                </div>
                <div class="group rounded-2xl border-t-4 border-emerald-600 bg-white p-8 text-center shadow-lg hover:-translate-y-2 transition-all">
                    <p class="mb-4 text-xs font-bold uppercase text-gray-400">Transparansi Keuangan</p>
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h4 class="mb-3 text-xl font-bold text-gray-800">Laporan & Statistik</h4>
                    <p class="mb-6 text-sm text-gray-600">Informasi APBDes, realisasi anggaran, dan statistik tahunan desa.</p>
                    <a href="{{ route('data-desa') }}" class="inline-block w-full rounded-lg border-2 border-emerald-600 py-2.5 font-bold text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors">Lihat Laporan</a>
                </div>
            </div>
            {{-- ponytail: publikasi ambil dari model DokumenDesa bila is_publik. --}}
        </div>
    </section>

    <!-- ===== 10. DOKUMENTASI & GALERI ===== -->
    <section id="galeri" class="bg-gray-50 py-20" x-data="{ tab: 'foto', preview: null }">
        <div class="container mx-auto px-4">
            <div class="mb-10 text-center">
                <h2 class="mb-2 text-3xl font-bold text-gray-800">Dokumentasi & Galeri</h2>
                <div class="mx-auto h-1 w-20 rounded-full bg-emerald-600"></div>
                <p class="mt-3 text-gray-500">Potret kegiatan, keindahan alam, dan dokumentasi desa.</p>
            </div>

            <div class="mb-10 flex justify-center">
                <ul class="flex flex-wrap rounded-full border-t-4 border-emerald-600 bg-white p-1.5 text-sm shadow-sm">
                    <li><button @click="tab = 'foto'" type="button" class="rounded-full px-6 py-2.5 font-bold transition-colors" :class="tab === 'foto' ? 'bg-emerald-600 text-white' : 'text-gray-500 hover:text-gray-700'">Foto</button></li>
                    <li><button @click="tab = 'video'" type="button" class="rounded-full px-6 py-2.5 font-bold transition-colors" :class="tab === 'video' ? 'bg-red-600 text-white' : 'text-gray-500 hover:text-gray-700'">Video</button></li>
                </ul>
            </div>

            <div x-show="tab === 'foto'" x-transition.opacity.duration.300ms>
                @if($galeris->filter(fn ($g) => $g->foto_sampul)->isEmpty())
                    <div class="py-16 text-center text-gray-400">
                        <svg class="mx-auto mb-4 w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="font-medium">Belum ada dokumentasi foto yang diunggah.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($galeris as $foto)
                            @if($foto->foto_sampul)
                            <button type="button" @click="preview = '{{ $foto->foto_sampul }}'" class="group relative aspect-[4/3] overflow-hidden rounded-2xl border-t-4 border-emerald-600 bg-white shadow-sm hover:shadow-xl transition-all">
                                <img src="{{ asset('storage/' . $foto->foto_sampul) }}" alt="{{ $foto->judul }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="mb-8 rounded-full bg-white/20 p-3 text-white backdrop-blur-md">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </span>
                                </div>
                                <div class="absolute inset-x-0 bottom-0 p-4 text-left text-white opacity-0 group-hover:opacity-100 transition-opacity">
                                    <p class="line-clamp-2 text-sm font-bold">{{ $foto->judul }}</p>
                                    <p class="text-xs text-white/70">Klik untuk memperbesar</p>
                                </div>
                            </button>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <div x-show="tab === 'video'" x-cloak x-transition.opacity.duration.300ms>
                <div class="py-16 text-center text-gray-400">
                    <svg class="mx-auto mb-4 w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <p class="font-medium">Belum ada dokumentasi video yang ditambahkan.</p>
                </div>
            </div>
        </div>

        {{-- Modal Preview --}}
        <div x-cloak x-show="preview" class="fixed inset-0 z-[999] bg-black/90 backdrop-blur-sm overflow-y-auto" @keydown.escape.window="preview = null">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="w-full max-w-4xl rounded-3xl bg-white shadow-2xl overflow-hidden" @click.outside="preview = null">
                    <img :src="'/storage/' + preview" alt="Preview galeri" class="w-full aspect-video object-contain bg-black">
                    <div class="flex justify-end p-4">
                        <button @click="preview = null" class="rounded-lg bg-gray-900 px-6 py-2.5 font-bold text-white hover:bg-gray-700">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== 11. BERITA & PENGUMUMAN ===== -->
    <section id="berita" class="bg-white py-20">
        <div class="container mx-auto px-4">
            <h2 class="mb-12 text-center text-3xl font-bold text-gray-800">Berita & Pengumuman</h2>
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    @forelse($beritas as $berita)
                        <article class="flex flex-col gap-6 rounded-xl border-t-4 border-emerald-600 bg-gray-50 p-4 shadow-sm hover:shadow-md transition-shadow sm:flex-row">
                            <div class="relative h-32 w-full sm:h-32 sm:w-48 shrink-0 overflow-hidden rounded-lg bg-gray-200">
                                @if($berita->foto_sampul)
                                    <img src="{{ asset('storage/' . $berita->foto_sampul) }}" alt="{{ $berita->judul }}" class="h-full w-full object-cover hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-tr from-emerald-800 to-emerald-600 text-emerald-200 text-3xl font-bold">{{ mb_strtoupper(mb_substr($berita->judul, 0, 1)) }}</div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <span class="rounded bg-emerald-50 px-2 py-1 text-xs font-bold text-emerald-600">{{ $berita->created_at->format('d M Y') }}</span>
                                <h3 class="mt-2 text-lg font-bold text-gray-900 hover:text-emerald-600 transition-colors">{{ $berita->judul }}</h3>
                                <p class="mt-2 line-clamp-2 text-sm text-gray-500">{!! strip_tags($berita->konten) !!}</p>
                                <a href="{{ route('artikel') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-bold text-emerald-600 hover:underline">Baca Selengkapnya <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg></a>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-xl bg-gray-50 p-12 text-center text-gray-400">
                            <p class="font-medium">Belum ada berita yang diterbitkan saat ini.</p>
                        </div>
                    @endforelse
                </div>

                <div>
                    <div class="overflow-hidden rounded-xl border border-yellow-200 bg-white shadow-md">
                        <div class="flex items-center border-b border-yellow-100 bg-yellow-50 p-4 font-bold text-gray-800">
                            <span class="mr-2 h-2 w-2 animate-pulse rounded-full bg-yellow-500"></span>
                            Pengumuman Terbaru
                        </div>
                        @php
                            $pengumuman = collect($programs)->where('status', 'Berjalan')->take(3);
                        @endphp
                        @forelse($pengumuman as $pgm)
                            <a href="{{ route('profil-desa') }}" class="block p-4 hover:bg-yellow-50/50 transition-colors">
                                <span class="mb-1 inline-block rounded bg-sky-600 px-2 py-0.5 text-[10px] font-bold text-white">{{ $pgm['kategori'] ?? 'Program' }}</span>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-yellow-700">{{ $pgm['nama_program'] }}</p>
                                <p class="mt-1 text-xs text-gray-400">Rp {{ number_format($pgm['anggaran'] ?? 0, 0, ',', '.') }} • Berjalan</p>
                            </a>
                        @empty
                            <div class="p-6 text-center text-sm text-gray-400">Belum ada pengumuman.</div>
                        @endforelse
                        <div class="border-t border-gray-100 bg-gray-50 p-3 text-center">
                            <a href="{{ route('artikel') }}" class="text-sm font-bold text-emerald-600 hover:underline">Lihat Semua Pengumuman</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== 12. INFO & SUPPORT ===== -->
    <section id="informasi" class="border-t border-gray-100 bg-gray-50 py-20">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="rounded-2xl border-t-4 border-emerald-600 bg-white p-8 shadow-lg hover:-translate-y-1 transition-transform">
                    <h4 class="mb-4 flex items-center gap-3 text-xl font-bold text-gray-900">
                        <span class="rounded-lg bg-emerald-50 p-3 text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        Tentang Website
                    </h4>
                    <p class="text-sm leading-relaxed text-gray-600 mb-3">Website resmi Desa Kaki Air dikelola langsung oleh pemerintah desa sebagai sarana pusat informasi, transparansi publik, dan pelayanan digital terpadu bagi masyarakat.</p>
                    <p class="text-sm leading-relaxed text-gray-600">Dikembangkan dengan teknologi terkini untuk mewujudkan visi <strong class="text-emerald-700">Desa Digital</strong> yang melayani lebih cepat, mudah, dan transparan.</p>
                </div>

                <div class="rounded-2xl border-t-4 border-emerald-600 bg-white p-8 shadow-lg hover:-translate-y-1 transition-transform">
                    <h4 class="mb-6 flex items-center gap-3 text-xl font-bold text-gray-900">
                        <span class="h-6 w-1 rounded-full bg-emerald-500"></span>
                        Link Terintegrasi
                    </h4>
                    <div class="space-y-2">
                        @php
                            $links = [
                                ['Portal Kabupaten', 'burukab.go.id', 'https://burukab.go.id', 'bg-blue-50 text-blue-600'],
                                ['Badan Pusat Statistik', 'burukab.bps.go.id', 'https://burukab.bps.go.id/id', 'bg-emerald-50 text-emerald-600'],
                                ['Kementerian Desa PDTT', 'kemendesa.go.id', 'https://kemendesa.go.id', 'bg-yellow-50 text-yellow-600'],
                                ['Kemendagri', 'kemendagri.go.id', 'https://kemendagri.go.id', 'bg-red-50 text-red-600'],
                                ['Cek DPT Online', 'cekdptonline.kpu.go.id', 'https://cekdptonline.kpu.go.id', 'bg-orange-50 text-orange-600'],
                            ];
                        @endphp
                        @foreach($links as $link)
                            <a href="{{ $link[2] }}" target="_blank" rel="noopener" class="group flex items-center rounded-lg p-2 hover:bg-gray-50 transition-colors">
                                <span class="mr-3 rounded-md {{ $link[3] }} p-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 010 5.656l-3 3a4 4 0 01-5.656-5.656l1.5-1.5m10.656-2.172l1.5-1.5a4 4 0 00-5.656-5.656l-3 3a4 4 0 000 5.656"/></svg>
                                </span>
                                <span class="flex-1">
                                    <span class="block text-sm font-bold text-gray-800 group-hover:text-emerald-600">{{ $link[0] }}</span>
                                    <span class="block text-xs text-gray-400">{{ $link[1] }}</span>
                                </span>
                                <svg class="w-4 h-4 text-gray-300 group-hover:text-emerald-500 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-2xl border-t-4 border-emerald-600 bg-white p-8 shadow-lg hover:-translate-y-1 transition-transform">
                    <h4 class="mb-4 flex items-center gap-3 text-xl font-bold text-gray-900">
                        <span class="h-6 w-1 rounded-full bg-emerald-500"></span>
                        Support By
                    </h4>
                    <div class="mb-4 flex items-center gap-4 rounded-xl border border-gray-200 bg-gray-50 p-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-teal-400 text-white font-bold">AM</div>
                        <div>
                            <h5 class="font-extrabold text-gray-900">...</h5>
                            <span class="rounded bg-emerald-600 px-2 py-0.5 text-[10px] font-bold uppercase text-white">IT Consultant</span>
                        </div>
                    </div>
                    <p class="mb-6 text-sm italic leading-relaxed text-gray-600">"Website desa digital yang inovatif adalah kunci untuk mentransformasi desa menjadi lebih maju, efisien, dan sejahtera."</p>
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener" class="flex w-full items-center justify-center gap-2 rounded-lg border border-emerald-600 py-2.5 text-sm font-bold text-emerald-600 hover:bg-emerald-50 transition-colors">Hubungi Developer</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Back to top -->
    <button x-data="{ show: false }" x-init="window.addEventListener('scroll', () => show = window.scrollY > 300)" @click="window.scrollTo({ top: 0, behavior: 'smooth' })" x-show="show" x-cloak class="fixed bottom-6 right-6 z-[99] h-12 w-12 rounded-full bg-emerald-600 text-white shadow-2xl hover:bg-emerald-700 hover:-translate-y-1 transition-all" aria-label="Kembali ke atas">
        <svg class="mx-auto h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/></svg>
    </button>
</div>
