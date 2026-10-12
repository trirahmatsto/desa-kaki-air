<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Services\ProfilService;

new #[Layout('components.layouts.public')] class extends Component {
    public function with(): array
    {
        return [
            'profil' => ProfilService::profil('Kaki Air'),
            'perangkat' => ProfilService::perangkat('Kaki Air'),
            'programs' => ProfilService::program('Kaki Air'),
        ];
    }
}; ?>
{{-- Halaman PROFIL DESA (/profil-desa) - profil, perangkat, program prioritas. --}}

<div class="py-12 md:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Halaman -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Tentang Kami</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2 mb-4">Profil Desa Kaki Air</h1>
            <p class="text-slate-500 leading-relaxed text-base sm:text-lg">
                Mengenal sejarah, arah kebijakan (visi-misi), batas wilayah, struktur kepengurusan pamong, program prioritas, dan keuangan Desa Kaki Air dalam melayani masyarakat.
            </p>
        </div>

        <!-- Gambaran Umum / Sejarah Desa -->
        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-slate-100 mb-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <div class="lg:col-span-7">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 mb-4">
                        Sejarah Singkat
                    </span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-6">Asal Usul Desa Kaki Air</h2>
                    <div class="space-y-4 text-slate-600 leading-relaxed text-sm sm:text-base whitespace-pre-line">
                        {{ $profil['sejarah'] ?? 'Belum ada data sejarah desa.' }}
                    </div>
                </div>
                <div class="lg:col-span-5">
                    <div class="aspect-video lg:aspect-square rounded-2xl bg-gradient-to-tr from-emerald-800 to-teal-600 overflow-hidden relative shadow-lg">
                        <div class="absolute inset-0 bg-cover bg-center mix-blend-overlay opacity-30" style="background-image: url('{{ asset('images/village_banner.png') }}');"></div>
                        <div class="absolute inset-0 flex flex-col justify-end p-8 text-white">
                            <span class="text-xs font-bold uppercase tracking-widest text-emerald-300 mb-1">Motto Desa</span>
                            <blockquote class="text-xl font-bold italic leading-relaxed">
                                "Mengabdi dengan Hati, Melayani dengan Teknologi."
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visi & Misi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            <!-- Visi -->
            <div class="bg-gradient-to-br from-emerald-800 to-teal-900 text-white rounded-3xl p-8 md:p-12 shadow-xl relative overflow-hidden">
                <div class="absolute right-0 bottom-0 opacity-10 translate-x-12 translate-y-12">
                    <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 mb-4 uppercase tracking-wider">
                    Visi Utama
                </span>
                <h3 class="text-2xl sm:text-3xl font-extrabold mb-6">"{{ $profil['visi'] ?? '-' }}"</h3>
            </div>

            <!-- Misi -->
            <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-slate-100">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 mb-4 uppercase tracking-wider">
                    Misi Kerja
                </span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-6">Pilar Pembangunan Desa</h3>
                <ol class="space-y-4 text-slate-600 text-sm sm:text-base">
                    @foreach(collect(preg_split('/\s*\d+[\.\)]\s+/', trim($profil['misi'] ?? '')))->filter()->values() as $idx => $misi)
                        <li class="flex items-start">
                            <div class="w-6 h-6 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0 mr-3.5 mt-0.5 font-bold">{{ $idx + 1 }}</div>
                            <span>{{ preg_replace('/^\s*\d+[\.\)]\s*/', '', trim($misi)) }}</span>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>

        <!-- Identitas Desa -->
        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-slate-100 mb-12">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Identitas</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Fakta Desa Kaki Air</h3>
                <p class="text-slate-500 text-sm mt-3">
                    Ringkasan identitas dan potensi unggulan Desa Kaki Air.
                </p>
            </div>

            @php
                $identitas = [
                    ['label' => 'Tahun Berdiri', 'nilai' => $profil['tahun_berdiri'] ?? '-'],
                    ['label' => 'Luas Wilayah', 'nilai' => ($profil['luas_wilayah_ha'] ?? 0) . ' Ha'],
                    ['label' => 'Jumlah Dusun', 'nilai' => ($profil['jumlah_dusun'] ?? 0) . ' Dusun'],
                    ['label' => 'Jumlah RT / RW', 'nilai' => ($profil['jumlah_rt'] ?? 0) . ' RT / ' . ($profil['jumlah_rw'] ?? 0) . ' RW'],
                    ['label' => 'Potensi Unggulan', 'nilai' => $profil['potensi_unggulan'] ?? '-'],
                    ['label' => 'Batas Timur', 'nilai' => $profil['batas_timur'] ?? '-'],
                    ['label' => 'Batas Barat', 'nilai' => $profil['batas_barat'] ?? '-'],
                    ['label' => 'Batas Utara', 'nilai' => $profil['batas_utara'] ?? '-'],
                    ['label' => 'Batas Selatan', 'nilai' => $profil['batas_selatan'] ?? '-'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($identitas as $item)
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md transition-shadow">
                        <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-2">{{ $item['label'] }}</p>
                        <p class="text-lg font-extrabold text-slate-900">{{ $item['nilai'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Perangkat Desa -->
        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-slate-100 mb-12">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Pamong Desa</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Struktur Organisasi Pemerintah Desa</h3>
                <p class="text-slate-500 text-sm mt-3">
                    Susunan kepengurusan aparat pemerintahan Desa Kaki Air periode masa jabatan aktif.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($perangkat as $orang)
                    <div class="bg-slate-50 rounded-2xl p-6 text-center border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="w-24 h-24 rounded-full bg-emerald-100 text-emerald-500 flex items-center justify-center mx-auto mb-4 border-2 border-emerald-500 overflow-hidden">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.42 0-8 2.69-8 6v2h16v-2c0-3.31-3.58-6-8-6z"/></svg>
                        </div>
                        <h4 class="text-base font-bold text-slate-900">{{ $orang['nama'] }}</h4>
                        <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mt-1">{{ $orang['jabatan'] }}</p>
                    </div>
                @empty
                    <p class="text-slate-500 col-span-full text-center">Belum ada data perangkat desa.</p>
                @endforelse
            </div>
        </div>

        <!-- Program Prioritas -->
        <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-slate-100 mb-12">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Pembangunan</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">Program Prioritas Desa</h3>
                <p class="text-slate-500 text-sm mt-3">
                    Program prioritas pembangunan Desa Kaki Air.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($programs as $program)
                    <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 hover:shadow-md transition-shadow flex flex-col">
                        <div class="flex items-start justify-between mb-3">
                            <span class="text-xs font-bold px-3 py-1 rounded-full {{ $program['kategori'] === 'Infrastruktur' ? 'bg-emerald-100 text-emerald-700' : ($program['kategori'] === 'Perikanan' ? 'bg-sky-100 text-sky-700' : 'bg-amber-100 text-amber-700') }}">
                                {{ $program['kategori'] }}
                            </span>
                            <span class="text-xs font-bold px-3 py-1 rounded-full {{ $program['status'] === 'Selesai' ? 'bg-emerald-600 text-white' : 'bg-sky-100 text-sky-700' }}">
                                {{ $program['status'] }}
                            </span>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 mb-2 leading-snug">{{ $program['nama_program'] }}</h4>
                        <p class="text-slate-500 text-sm leading-relaxed mb-4">{{ $program['deskripsi'] }}</p>
                        <div class="mt-auto pt-4 border-t border-slate-200 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Anggaran</span>
                                <span class="font-bold text-slate-800">Rp {{ number_format($program['anggaran'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Realisasi</span>
                                <span class="font-bold text-emerald-600">Rp {{ number_format($program['realisasi'] ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500 col-span-full text-center">Belum ada data program prioritas.</p>
                @endforelse
            </div>
        </div>

        <!-- APBDesa -->
        <div class="bg-gradient-to-br from-emerald-900 to-teal-900 text-white rounded-3xl p-8 md:p-12 shadow-xl relative overflow-hidden">
            <div class="absolute right-0 bottom-0 opacity-10 translate-x-12 translate-y-12">
                <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 24 24"><path d="M3 6h18v12H3zM5 9v6h14V9zM12 10a3 3 0 100 6 3 3 0 000-6z"/></svg>
            </div>
            <div class="text-center max-w-3xl mx-auto mb-12 relative">
                <span class="text-xs font-bold text-emerald-300 tracking-wider uppercase">Keuangan Desa</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold mt-2">Realisasi APBDesa</h3>
                <p class="text-emerald-100/80 text-sm mt-3">
                    Ringkasan pendapatan Anggaran Pendapatan dan Belanja Desa Kaki Air.
                </p>
            </div>

            @php
                $apbdesa = [
                    ['label' => 'Total APBDesa', 'nilai' => $profil['total_apbdesa'] ?? 0],
                    ['label' => 'Pendapatan Asli Desa', 'nilai' => $profil['pendapatan_asli_desa'] ?? 0],
                    ['label' => 'Dana Desa', 'nilai' => $profil['dana_desa'] ?? 0],
                    ['label' => 'Alokasi Dana Desa', 'nilai' => $profil['alokasi_dana_desa'] ?? 0],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                @foreach($apbdesa as $item)
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                        <p class="text-sm font-semibold text-emerald-200 mb-2">{{ $item['label'] }}</p>
                        <p class="text-xl font-extrabold">Rp {{ number_format($item['nilai'], 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
