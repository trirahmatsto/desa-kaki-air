<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Pengaduan;

new #[Layout('components.layouts.public')] class extends Component {
    use WithFileUploads;

    public string $nik_pelapor = '';
    public string $nama_pelapor = '';
    public string $kategori = '';
    public string $isi_laporan = '';
    public $foto_bukti;

    public bool $isSuccess = false;

    protected function rules(): array
    {
        return [
            'nik_pelapor' => 'required|digits:16|numeric',
            'nama_pelapor' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'isi_laporan' => 'required|string|min:15',
            'foto_bukti' => 'nullable|image|max:2048', // max 2MB
        ];
    }

    protected array $messages = [
        'nik_pelapor.required' => 'NIK wajib diisi.',
        'nik_pelapor.digits' => 'NIK harus tepat 16 digit.',
        'nik_pelapor.numeric' => 'NIK harus berupa angka.',
        'nama_pelapor.required' => 'Nama lengkap wajib diisi.',
        'kategori.required' => 'Kategori pengaduan wajib diisi.',
        'isi_laporan.required' => 'Detail laporan wajib diisi.',
        'isi_laporan.min' => 'Detail laporan minimal berisi 15 karakter.',
        'foto_bukti.image' => 'Berkas harus berupa gambar (JPG, JPEG, PNG).',
        'foto_bukti.max' => 'Berkas gambar maksimal berukuran 2MB.',
    ];

    public function kirimAduan(): void
    {
        $this->validate();

        $path = null;
        if ($this->foto_bukti) {
            $path = $this->foto_bukti->store('pengaduan', 'public');
        }

        Pengaduan::create([
            'nik_pelapor' => $this->nik_pelapor,
            'nama_pelapor' => $this->nama_pelapor,
            'kategori' => $this->kategori,
            'isi_laporan' => $this->isi_laporan,
            'foto_bukti' => $path,
            'status' => 'masuk',
        ]);

        $this->reset(['nik_pelapor', 'nama_pelapor', 'kategori', 'isi_laporan', 'foto_bukti']);
        $this->isSuccess = true;
    }
}; ?>
{{-- Halaman PENGADUAN (/pengaduan) - form aduan masyarakat. --}}

<div class="py-12 md:py-20 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Halaman -->
        <div class="text-center mb-12">
            <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Layanan Pengaduan</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2 mb-4">Pengaduan & Aspirasi Warga</h1>
            <p class="text-slate-500 leading-relaxed text-sm sm:text-base max-w-2xl mx-auto">
                Laporkan masalah fasilitas umum, keluhan pelayanan, atau berikan saran pembangunan desa. Layanan ini bebas login dengan validasi identitas manual oleh aparat desa.
            </p>
        </div>

        @if($isSuccess)
            <!-- Success Card -->
            <div class="bg-white rounded-3xl p-8 md:p-12 shadow-xl border border-emerald-100 text-center animate-fade-in">
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 mb-3">Laporan Pengaduan Berhasil Terkirim!</h2>
                <p class="text-slate-500 text-sm sm:text-base leading-relaxed max-w-md mx-auto mb-8">
                    Terima kasih atas laporan Anda. Aparat desa kami akan segera memeriksa detail laporan dan mengambil tindakan verifikasi secepatnya.
                </p>
                <button wire:click="$set('isSuccess', false)" class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white font-bold transition-all shadow-md shadow-emerald-100">
                    Kirim Pengaduan Baru
                </button>
            </div>
        @else
            <!-- Form Card -->
            <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-xl border border-slate-100">
                <form wire:submit.prevent="kirimAduan" class="space-y-6">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- NIK -->
                        <div>
                            <label for="nik_pelapor" class="block text-sm font-bold text-slate-700 mb-2">NIK Pelapor <span class="text-rose-500">*</span></label>
                            <input wire:model.blur="nik_pelapor" type="text" id="nik_pelapor" maxlength="16" placeholder="Masukkan 16 digit NIK" 
                                class="w-full px-4 py-3 rounded-xl border @error('nik_pelapor') border-rose-300 bg-rose-50/20 focus:ring-rose-500 @else border-slate-200 focus:ring-emerald-500 @enderror focus:outline-none focus:ring-2 transition-all">
                            @error('nik_pelapor')
                                <span class="text-xs font-semibold text-rose-600 block mt-1.5">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Nama Lengkap -->
                        <div>
                            <label for="nama_pelapor" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input wire:model.blur="nama_pelapor" type="text" id="nama_pelapor" placeholder="Masukkan nama lengkap Anda" 
                                class="w-full px-4 py-3 rounded-xl border @error('nama_pelapor') border-rose-300 bg-rose-50/20 focus:ring-rose-500 @else border-slate-200 focus:ring-emerald-500 @enderror focus:outline-none focus:ring-2 transition-all">
                            @error('nama_pelapor')
                                <span class="text-xs font-semibold text-rose-600 block mt-1.5">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori" class="block text-sm font-bold text-slate-700 mb-2">Kategori Pengaduan <span class="text-rose-500">*</span></label>
                        <select wire:model.blur="kategori" id="kategori" 
                            class="w-full px-4 py-3 rounded-xl border @error('kategori') border-rose-300 bg-rose-50/20 focus:ring-rose-500 @else border-slate-200 focus:ring-emerald-500 @enderror focus:outline-none focus:ring-2 transition-all bg-white">
                            <option value="">-- Pilih Kategori Laporan --</option>
                            <option value="Infrastruktur & Jalan">Infrastruktur & Jalan</option>
                            <option value="Fasilitas Umum & Kebersihan">Fasilitas Umum & Kebersihan</option>
                            <option value="Pelayanan Aparat Desa">Pelayanan Aparat Desa</option>
                            <option value="Keamanan & Ketertiban">Keamanan & Ketertiban</option>
                            <option value="Masalah Sosial & Bantuan">Masalah Sosial & Bantuan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        @error('kategori')
                            <span class="text-xs font-semibold text-rose-600 block mt-1.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Isi Laporan -->
                    <div>
                        <label for="isi_laporan" class="block text-sm font-bold text-slate-700 mb-2">Detail Laporan Pengaduan <span class="text-rose-500">*</span></label>
                        <textarea wire:model.blur="isi_laporan" id="isi_laporan" rows="6" placeholder="Jelaskan secara rinci detail permasalahan, lokasi kejadian, dan waktu kejadian..." 
                            class="w-full px-4 py-3 rounded-xl border @error('isi_laporan') border-rose-300 bg-rose-50/20 focus:ring-rose-500 @else border-slate-200 focus:ring-emerald-500 @enderror focus:outline-none focus:ring-2 transition-all"></textarea>
                        @error('isi_laporan')
                            <span class="text-xs font-semibold text-rose-600 block mt-1.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Foto Bukti -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Unggah Foto Bukti Kejadian</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl bg-slate-50/50 hover:bg-slate-50 transition-colors relative">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h20a4 4 0 004-4V20m-10-4l-8 8m0 0l8 8m-8-8h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-slate-600">
                                    <label for="foto_bukti" class="relative cursor-pointer bg-white rounded-md font-semibold text-emerald-600 hover:text-emerald-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-emerald-500">
                                        <span>Unggah berkas foto</span>
                                        <input wire:model="foto_bukti" id="foto_bukti" type="file" class="sr-only" accept="image/*">
                                    </label>
                                    <p class="pl-1">atau seret dan lepas</p>
                                </div>
                                <p class="text-xs text-slate-400">PNG, JPG, JPEG sampai dengan 2MB</p>
                            </div>
                        </div>

                        <!-- Progress indicator for upload -->
                        <div wire:loading wire:target="foto_bukti" class="mt-2 text-xs font-semibold text-emerald-600 flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Mengunggah foto...
                        </div>

                        <!-- Preview Image -->
                        @if ($foto_bukti && !$errors->has('foto_bukti'))
                            <div class="mt-4">
                                <span class="text-xs font-semibold text-slate-400 block mb-2">Pratinjau Foto:</span>
                                <img src="{{ $foto_bukti->temporaryUrl() }}" class="h-40 w-auto rounded-xl object-cover border border-slate-200">
                            </div>
                        @endif

                        @error('foto_bukti')
                            <span class="text-xs font-semibold text-rose-600 block mt-1.5">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-slate-100">
                        <button type="submit" wire:loading.attr="disabled" 
                            class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-emerald-100 hover:shadow-xl text-base font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                            <span wire:loading.remove wire:target="kirimAduan">Kirim Laporan Pengaduan</span>
                            <span wire:loading wire:target="kirimAduan" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Mengirim Laporan...
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        @endif

    </div>
</div>
