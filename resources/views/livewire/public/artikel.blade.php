<?php

use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\ArtikelGaleri;
use App\Models\DokumenDesa;

new #[Layout('components.layouts.public')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $kategori = 'semua'; // 'semua', 'berita', 'galeri'

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingKategori(): void
    {
        $this->resetPage();
    }

    public function with(): array
    {
        $query = ArtikelGaleri::query()->latest();

        if ($this->search) {
            $query->where('judul', 'like', '%' . $this->search . '%');
        }

        if ($this->kategori !== 'semua') {
            $query->where('kategori', $this->kategori);
        }

        return [
            'artikels' => $query->paginate(6),
            'dokumens' => DokumenDesa::where('is_publik', true)->latest()->get(),
        ];
    }
}; ?>
{{-- Halaman ARTIKEL (/artikel) - daftar berita & galeri. --}}

<div class="py-12 md:py-20 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header Halaman -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Pusat Informasi</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2 mb-4">Berita & Galeri Kegiatan</h1>
            <p class="text-slate-500 leading-relaxed text-base sm:text-lg">
                Ikuti liputan kegiatan, pembangunan desa, pengumuman resmi, dan dokumentasi foto dari berbagai program kerja Desa Kaki Air.
            </p>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white p-6 rounded-3xl shadow-xl border border-slate-100 mb-12 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <!-- Tabs Kategori -->
            <div class="flex space-x-2">
                <button wire:click="$set('kategori', 'semua')"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $kategori === 'semua' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200' : 'text-slate-600 hover:bg-slate-50' }}">
                    Semua
                </button>
                <button wire:click="$set('kategori', 'berita')"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $kategori === 'berita' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200' : 'text-slate-600 hover:bg-slate-50' }}">
                    Berita Desa
                </button>
                <button wire:click="$set('kategori', 'galeri')"
                    class="px-5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $kategori === 'galeri' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200' : 'text-slate-600 hover:bg-slate-50' }}">
                    Galeri Kegiatan
                </button>
            </div>

            <!-- Pencarian -->
            <div class="relative w-full md:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari artikel..."
                    class="block w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
            </div>
        </div>

        <!-- Grid Berita & Galeri -->
        @if($artikels->isEmpty())
            <div class="bg-white rounded-3xl p-16 text-center border border-slate-100 shadow-xl">
                <div class="w-16 h-16 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Tidak ditemukan data</h3>
                <p class="text-slate-500 text-sm">Tidak ada artikel atau galeri yang cocok dengan pencarian Anda saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach($artikels as $artikel)
                    <article class="bg-white rounded-3xl overflow-hidden border border-slate-100 hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <!-- Thumbnail / Image -->
                            <div class="relative aspect-video overflow-hidden bg-slate-100">
                                @if($artikel->foto_sampul)
                                    <img src="{{ asset('storage/' . $artikel->foto_sampul) }}" alt="{{ $artikel->judul }}" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-emerald-800 to-emerald-600 text-emerald-200 text-3xl font-bold">
                                        B
                                    </div>
                                @endif
                                <span class="absolute top-4 left-4 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $artikel->kategori === 'berita' ? 'bg-emerald-500 text-white' : 'bg-teal-500 text-white' }} shadow-sm">
                                    {{ $artikel->kategori === 'berita' ? 'Berita' : 'Galeri' }}
                                </span>
                            </div>

                            <!-- Content -->
                            <div class="p-8">
                                <span class="text-xs font-semibold text-slate-400 block mb-2">{{ $artikel->created_at->format('d M Y H:i') }}</span>
                                <h3 class="text-xl font-bold text-slate-900 mb-4 leading-snug group-hover:text-emerald-600 transition-colors">
                                    {{ $artikel->judul }}
                                </h3>
                                <div class="text-slate-500 text-sm leading-relaxed line-clamp-4">
                                    {!! strip_tags($artikel->konten) !!}
                                </div>
                            </div>
                        </div>

                        <!-- Read More (Only for Berita) -->
                        <div class="px-8 pb-8 pt-2">
                            @if($artikel->kategori === 'berita')
                                <!-- Read details modal trigger or view -->
                                <button onclick="alert('Buka Detail Berita: {{ addslashes($artikel->judul) }}\n\n{!! addslashes(strip_tags($artikel->konten)) !!}')" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700">
                                    Detail Berita <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                            @else
                                <button onclick="alert('Detail Galeri Kegiatan: {{ addslashes($artikel->judul) }}\n\nFoto Kegiatan berhasil didokumentasikan oleh Humas Desa Kaki Air.')" class="inline-flex items-center text-sm font-bold text-emerald-600 hover:text-emerald-700">
                                    Lihat Galeri <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </button>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination Links -->
            <div class="mt-8 flex justify-center">
                {{ $artikels->links() }}
            </div>
        @endif


    </div>
</div>
