<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\Infographic;
use App\Models\DokumenDesa;
use Illuminate\Support\Facades\Storage;

new #[Layout('components.layouts.public')] class extends Component {
    public $previewId = null;
    public $dokumenPreviewId = null;

    public function with(): array
    {
        return [
            'infographics' => Infographic::latest()->get(),
            'dokumens' => DokumenDesa::where('is_publik', true)->latest()->get(),
            'preview' => $this->previewId ? Infographic::find($this->previewId) : null,
            'dokumenPreview' => $this->dokumenPreviewId ? DokumenDesa::find($this->dokumenPreviewId) : null,
        ];
    }

    public function setPreview($id)
    {
        $this->previewId = $id;
    }

    public function closePreview()
    {
        $this->previewId = null;
    }

    public function setDokumenPreview($id)
    {
        $this->dokumenPreviewId = $id;
    }

    public function closeDokumenPreview()
    {
        $this->dokumenPreviewId = null;
    }

    public function download($id)
    {
        $infographic = Infographic::findOrFail($id);
        return Storage::disk('public')->download(
            $infographic->file_path,
            $infographic->title . '.' . $infographic->file_type
        );
    }
}; ?>
{{-- Halaman PROFIL & INFOGRAFIS (/profil-desa-dan-infografis) - dokumen dan infografis desa. --}}

<div class="py-12 md:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Informasi Desa</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2 mb-4">Infografis & Dokumen Desa</h1>
            <p class="text-slate-500 leading-relaxed text-base sm:text-lg">
                Lihat dan unduh infografis serta dokumen resmi profil Desa Kaki Air.
            </p>
        </div>

        {{-- Dokumen Profil Desa --}}
        <div class="mb-16">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Dokumen Resmi</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2 mb-4">Dokumen Profil Desa</h2>
                <p class="text-slate-500 leading-relaxed text-sm sm:text-base">
                    Unduh dokumen-dokumen resmi profil Desa Kaki Air seperti RPJMDes, RKPDes, APBDes, dan lainnya.
                </p>
            </div>

            @if($dokumens->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-xl max-w-4xl mx-auto">
                    <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Dokumen</h3>
                    <p class="text-slate-500 text-sm">Saat ini belum ada dokumen profil desa yang diunggah.</p>
                </div>
            @else
                <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden max-w-5xl mx-auto">
                    <div class="divide-y divide-slate-100">
                        @foreach($dokumens as $dokumen)
                            <div class="p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6 hover:bg-slate-50/50 transition-colors">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                        @if(str_ends_with(strtolower($dokumen->nama_file), '.pdf'))
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2zM9 9h1v1H9V9zm0 4h5v1H9v-1zm0 4h5v1H9v-1z"/></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <button type="button" wire:click="setDokumenPreview({{ $dokumen->id }})" class="text-left">
                                            <h3 class="font-bold text-slate-800 text-base sm:text-lg hover:text-emerald-600 transition-colors">{{ $dokumen->nama_dokumen }}</h3>
                                        </button>
                                        @if($dokumen->deskripsi)
                                            <p class="text-slate-500 text-sm mt-1 leading-relaxed max-w-2xl">{{ $dokumen->deskripsi }}</p>
                                        @endif
                                        <div class="flex items-center gap-2 mt-2 text-xs text-slate-400">
                                            <span>Diunggah {{ $dokumen->created_at->format('d M Y') }}</span>
                                            <span>•</span>
                                            <span>{{ strtoupper(pathinfo($dokumen->nama_file, PATHINFO_EXTENSION)) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center gap-3 flex-shrink-0">
                                    <button type="button" wire:click="setDokumenPreview({{ $dokumen->id }})"
                                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold border-2 border-emerald-600 text-emerald-600 hover:bg-emerald-50 transition-all w-full sm:w-auto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat
                                    </button>
                                    <a href="{{ asset('storage/' . $dokumen->nama_file) }}" target="_blank" download
                                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-100 hover:shadow-lg transition-all w-full sm:w-auto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Infografis Desa --}}
        <div>
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Visual Data</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2 mb-4">Infografis Desa</h2>
                <p class="text-slate-500 leading-relaxed text-sm sm:text-base">
                    Lihat dan unduh infografis seputar data statistik, potensi desa, dan informasi visual menarik lainnya.
                </p>
            </div>

            @if($infographics->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-xl max-w-4xl mx-auto">
                    <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Infografis</h3>
                    <p class="text-slate-500 text-sm">Infografis desa akan segera tersedia. Pantau terus halaman ini untuk informasi terbaru.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($infographics as $item)
                        <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-xl hover:shadow-2xl transition-all duration-300 flex flex-col group">
                            <button wire:click="setPreview({{ $item->id }})" class="relative aspect-[4/3] overflow-hidden bg-slate-100 w-full text-left" type="button">
                                @if(in_array($item->file_type, ['jpg', 'jpeg', 'png']))
                                    <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->title }}"
                                        class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-tr from-emerald-800 to-emerald-600 text-emerald-200">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <span class="absolute top-4 right-4 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/90 text-slate-700 shadow-sm">
                                    {{ $item->file_type }}
                                </span>
                                <span class="absolute inset-0 flex items-center justify-center bg-emerald-900/0 group-hover:bg-emerald-900/30 transition-colors">
                                    <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 scale-75 group-hover:scale-100 transition-all" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 10-.7.7l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0A4.5 4.5 0 1110 5.5 4.5 4.5 0 0110 14z"/></svg>
                                </span>
                            </button>
                            <div class="p-6 flex flex-col flex-1">
                                <button type="button" wire:click="setPreview({{ $item->id }})" class="text-left">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2 leading-snug hover:text-emerald-600 transition-colors">{{ $item->title }}</h3>
                                </button>
                                @if($item->description)
                                    <p class="text-slate-500 text-sm leading-relaxed mb-4 line-clamp-3">{{ $item->description }}</p>
                                @endif
                                <div class="mt-auto pt-4">
                                    <button wire:click="download({{ $item->id }})"
                                        class="inline-flex w-full items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-100 hover:shadow-lg transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if($preview)
            <div wire:click="closePreview" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm p-4 sm:p-8 flex items-center justify-center cursor-pointer" role="dialog" aria-modal="true">
                <div wire:click.stop class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col cursor-default">
                    <div class="flex items-center justify-between gap-4 px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900">{{ $preview->title }}</h3>
                        <button type="button" wire:click="closePreview" class="flex-shrink-0 w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors" aria-label="Tutup preview">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="bg-slate-100 flex-1 min-h-0 overflow-auto">
                        @if(in_array($preview->file_type, ['jpg', 'jpeg', 'png']))
                            <div class="h-full min-h-[50vh] flex items-center justify-center">
                                <img src="{{ asset('storage/' . $preview->file_path) }}" alt="{{ $preview->title }}"
                                    style="max-width:100%; max-height:75vh; object-fit:contain;">
                            </div>
                        @elseif($preview->file_type === 'pdf')
                            <iframe src="{{ asset('storage/' . $preview->file_path) }}" style="width:100%; height:75vh; border:0;" title="{{ $preview->title }}"></iframe>
                        @else
                            <div class="p-12 text-center text-slate-500">Preview tidak tersedia untuk tipe berkas ini.</div>
                        @endif
                    </div>
                    <div class="flex items-center justify-between gap-4 px-6 py-4 border-t border-slate-100">
                        @if($preview->description)
                            <p class="text-sm text-slate-500 leading-relaxed max-w-xl line-clamp-2">{{ $preview->description }}</p>
                        @else
                            <span></span>
                        @endif
                        <button wire:click="download({{ $preview->id }})"
                            class="flex-shrink-0 inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-100 hover:shadow-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download
                        </button>
                    </div>
                </div>
            </div>
        @endif

        @if($dokumenPreview)
            @php($dokumenExt = strtolower(pathinfo($dokumenPreview->nama_file, PATHINFO_EXTENSION)))
            <div wire:click="closeDokumenPreview" class="fixed inset-0 z-50 bg-slate-900/80 backdrop-blur-sm p-4 sm:p-8 flex items-center justify-center cursor-pointer" role="dialog" aria-modal="true">
                <div wire:click.stop class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] flex flex-col cursor-default">
                    <div class="flex items-center justify-between gap-4 px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900">{{ $dokumenPreview->nama_dokumen }}</h3>
                        <button type="button" wire:click="closeDokumenPreview" class="flex-shrink-0 w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center transition-colors" aria-label="Tutup preview">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="bg-slate-100 flex-1 min-h-0 overflow-auto">
                        @if(in_array($dokumenExt, ['jpg', 'jpeg', 'png']))
                            <div class="h-full min-h-[50vh] flex items-center justify-center">
                                <img src="{{ asset('storage/' . $dokumenPreview->nama_file) }}" alt="{{ $dokumenPreview->nama_dokumen }}"
                                    style="max-width:100%; max-height:75vh; object-fit:contain;">
                            </div>
                        @elseif($dokumenExt === 'pdf')
                            <iframe src="{{ asset('storage/' . $dokumenPreview->nama_file) }}" style="width:100%; height:75vh; border:0;" title="{{ $dokumenPreview->nama_dokumen }}"></iframe>
                        @else
                            <div class="p-12 text-center text-slate-500">Preview tidak tersedia untuk tipe berkas ini. Silakan unduh untuk membukanya.</div>
                        @endif
                    </div>
                    <div class="flex items-center justify-between gap-4 px-6 py-4 border-t border-slate-100">
                        @if($dokumenPreview->deskripsi)
                            <p class="text-sm text-slate-500 leading-relaxed max-w-xl line-clamp-2">{{ $dokumenPreview->deskripsi }}</p>
                        @else
                            <span></span>
                        @endif
                        <a href="{{ asset('storage/' . $dokumenPreview->nama_file) }}" target="_blank" download
                            class="flex-shrink-0 inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-100 hover:shadow-lg transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Download
                        </a>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
