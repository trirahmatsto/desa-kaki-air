<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\JenisSurat;
use App\Models\PengajuanSurat;

new #[Layout('components.layouts.public')] class extends Component {
    use WithFileUploads;

    // Form fields
    public string $nik_pemohon = '';
    public string $nama_pemohon = '';
    public string $no_whatsapp = '';
    public string $jenis_surat_id = '';
    public string $keperluan = '';
    public $berkas_ktp_kk;

    // Search track status fields
    public string $search_nik = '';
    public $trackedSurats = null;

    public bool $isSuccess = false;

    protected function rules(): array
    {
        return [
            'nik_pemohon' => 'required|digits:16|numeric',
            'nama_pemohon' => 'required|string|max:255',
            'no_whatsapp' => 'required|string|min:8|max:20',
            'jenis_surat_id' => 'required|exists:jenis_surat,id',
            'keperluan' => 'required|string|min:10',
            'berkas_ktp_kk' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // max 2MB
        ];
    }

    protected array $messages = [
        'nik_pemohon.required' => 'NIK wajib diisi.',
        'nik_pemohon.digits' => 'NIK harus tepat 16 digit.',
        'nik_pemohon.numeric' => 'NIK harus berupa angka.',
        'nama_pemohon.required' => 'Nama lengkap pemohon wajib diisi.',
        'no_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
        'no_whatsapp.min' => 'Nomor WhatsApp minimal berisi 8 digit.',
        'jenis_surat_id.required' => 'Jenis surat wajib dipilih.',
        'jenis_surat_id.exists' => 'Jenis surat tidak valid.',
        'keperluan.required' => 'Keperluan surat wajib diisi.',
        'keperluan.min' => 'Keperluan surat minimal berisi 10 karakter.',
        'berkas_ktp_kk.required' => 'Berkas KTP/KK wajib diunggah untuk verifikasi.',
        'berkas_ktp_kk.file' => 'Berkas harus berupa file valid.',
        'berkas_ktp_kk.mimes' => 'Berkas harus berformat PDF, JPG, JPEG, atau PNG.',
        'berkas_ktp_kk.max' => 'Ukuran berkas maksimal adalah 2MB.',
    ];

    public function with(): array
    {
        return [
            'jenisSurats' => JenisSurat::where('is_aktif', true)->orderBy('nama_surat')->get(),
        ];
    }

    public function ajukanSurat(): void
    {
        $this->validate();

        $path = $this->berkas_ktp_kk->store('pengajuan_surat', 'public');

        PengajuanSurat::create([
            'nik_pemohon' => $this->nik_pemohon,
            'nama_pemohon' => $this->nama_pemohon,
            'no_whatsapp' => $this->no_whatsapp,
            'jenis_surat_id' => $this->jenis_surat_id,
            'keperluan' => $this->keperluan,
            'berkas_ktp_kk' => $path,
            'status' => 'menunggu',
        ]);

        $this->reset(['nik_pemohon', 'nama_pemohon', 'no_whatsapp', 'jenis_surat_id', 'keperluan', 'berkas_ktp_kk']);
        $this->isSuccess = true;
    }

    public function lacakSurat(): void
    {
        $this->validate([
            'search_nik' => 'required|digits:16|numeric',
        ], [
            'search_nik.required' => 'Masukkan NIK terlebih dahulu.',
            'search_nik.digits' => 'NIK pelacakan harus berisi 16 digit.',
            'search_nik.numeric' => 'NIK pelacakan harus berupa angka.',
        ]);

        $this->trackedSurats = PengajuanSurat::with('jenisSurat')
            ->where('nik_pemohon', $this->search_nik)
            ->latest()
            ->get();
    }
}; ?>
{{-- Halaman LAYANAN SURAT (/layanan-surat) - form pengajuan surat online. --}}

<div class="py-12 md:py-20 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Halaman -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold text-emerald-600 tracking-wider uppercase">Layanan Mandiri</span>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-2 mb-4">Pengajuan Surat Online</h1>
            <p class="text-slate-500 leading-relaxed text-sm sm:text-base">
                Ajukan permohonan surat keterangan administrasi desa secara instan. Harap siapkan file scan/foto KTP atau KK asli Anda sebagai syarat validasi manual oleh aparat desa.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Form Pengajuan (Kiri) -->
            <div class="lg:col-span-7">
                @if($isSuccess)
                    <div class="bg-white rounded-3xl p-8 shadow-xl border border-emerald-100 text-center animate-fade-in">
                        <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-900 mb-3">Pengajuan Surat Terkirim!</h2>
                        <p class="text-slate-500 text-sm sm:text-base leading-relaxed mb-8">
                            Surat keterangan berhasil diajukan. Aparat desa kami akan melakukan pengecekan keabsahan data lewat berkas yang diunggah. Kami akan menghubungi Anda lewat WhatsApp aktif yang dicantumkan.
                        </p>
                        <button wire:click="$set('isSuccess', false)" class="px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white font-bold transition-all shadow-md shadow-emerald-100">
                            Ajukan Surat Lainnya
                        </button>
                    </div>
                @else
                    <div class="bg-white rounded-3xl p-6 sm:p-10 shadow-xl border border-slate-100">
                        <h3 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100 flex items-center">
                            <span class="w-2 h-6 rounded bg-emerald-500 mr-3"></span>
                            Isi Data Pengajuan Surat
                        </h3>
                        <form wire:submit.prevent="ajukanSurat" class="space-y-6">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- NIK -->
                                <div>
                                    <label for="nik_pemohon" class="block text-sm font-bold text-slate-700 mb-2">NIK Pemohon <span class="text-rose-500">*</span></label>
                                    <input wire:model.blur="nik_pemohon" type="text" id="nik_pemohon" maxlength="16" placeholder="16 digit NIK KK/KTP" 
                                        class="w-full px-4 py-3 rounded-xl border @error('nik_pemohon') border-rose-300 bg-rose-50/20 focus:ring-rose-500 @else border-slate-200 focus:ring-emerald-500 @enderror focus:outline-none focus:ring-2 transition-all">
                                    @error('nik_pemohon')
                                        <span class="text-xs font-semibold text-rose-600 block mt-1.5">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Nama Lengkap -->
                                <div>
                                    <label for="nama_pemohon" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap Pemohon <span class="text-rose-500">*</span></label>
                                    <input wire:model.blur="nama_pemohon" type="text" id="nama_pemohon" placeholder="Sesuai KTP/KK" 
                                        class="w-full px-4 py-3 rounded-xl border @error('nama_pemohon') border-rose-300 bg-rose-50/20 focus:ring-rose-500 @else border-slate-200 focus:ring-emerald-500 @enderror focus:outline-none focus:ring-2 transition-all">
                                    @error('nama_pemohon')
                                        <span class="text-xs font-semibold text-rose-600 block mt-1.5">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- WhatsApp -->
                                <div>
                                    <label for="no_whatsapp" class="block text-sm font-bold text-slate-700 mb-2">Nomor WhatsApp Aktif <span class="text-rose-500">*</span></label>
                                    <input wire:model.blur="no_whatsapp" type="text" id="no_whatsapp" placeholder="Contoh: 08123456789" 
                                        class="w-full px-4 py-3 rounded-xl border @error('no_whatsapp') border-rose-300 bg-rose-50/20 focus:ring-rose-500 @else border-slate-200 focus:ring-emerald-500 @enderror focus:outline-none focus:ring-2 transition-all">
                                    @error('no_whatsapp')
                                        <span class="text-xs font-semibold text-rose-600 block mt-1.5">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Jenis Surat -->
                                <div>
                                    <label for="jenis_surat_id" class="block text-sm font-bold text-slate-700 mb-2">Jenis Layanan Surat <span class="text-rose-500">*</span></label>
                                    <select wire:model.blur="jenis_surat_id" id="jenis_surat_id" 
                                        class="w-full px-4 py-3 rounded-xl border @error('jenis_surat_id') border-rose-300 bg-rose-50/20 focus:ring-rose-500 @else border-slate-200 focus:ring-emerald-500 @enderror focus:outline-none focus:ring-2 transition-all bg-white">
                                        <option value="">-- Pilih Jenis Surat --</option>
                                        @foreach($jenisSurats as $surat)
                                            <option value="{{ $surat->id }}">{{ $surat->nama_surat }} ({{ $surat->kode_surat }})</option>
                                        @endforeach
                                    </select>
                                    @error('jenis_surat_id')
                                        <span class="text-xs font-semibold text-rose-600 block mt-1.5">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Keperluan -->
                            <div>
                                <label for="keperluan" class="block text-sm font-bold text-slate-700 mb-2">Keperluan / Keterangan Tambahan <span class="text-rose-500">*</span></label>
                                <textarea wire:model.blur="keperluan" id="keperluan" rows="4" placeholder="Tuliskan keperluan pembuatan surat. Contoh: Digunakan sebagai syarat pendaftaran beasiswa anak sekolah..." 
                                    class="w-full px-4 py-3 rounded-xl border @error('keperluan') border-rose-300 bg-rose-50/20 focus:ring-rose-500 @else border-slate-200 focus:ring-emerald-500 @enderror focus:outline-none focus:ring-2 transition-all"></textarea>
                                @error('keperluan')
                                    <span class="text-xs font-semibold text-rose-600 block mt-1.5">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Unggah Berkas -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Unggah Berkas KTP atau KK Asli <span class="text-rose-500">*</span></label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-200 border-dashed rounded-2xl bg-slate-50/50 hover:bg-slate-50 transition-colors relative">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M4 16v1a3 3 0 003 3h10M4 16a2 2 0 012-2h10M4 16V6a2 2 0 012-2h2m16 0h2a2 2 0 012 2v20a2 2 0 01-2 2H22m0-22V4m0 0l-4 4m4-4l4 4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-slate-600">
                                            <label for="berkas_ktp_kk" class="relative cursor-pointer bg-white rounded-md font-semibold text-emerald-600 hover:text-emerald-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-emerald-500">
                                                <span>Pilih Berkas</span>
                                                <input wire:model="berkas_ktp_kk" id="berkas_ktp_kk" type="file" class="sr-only" accept="image/*,application/pdf">
                                            </label>
                                            <p class="pl-1">atau seret dan lepas</p>
                                        </div>
                                        <p class="text-xs text-slate-400">Gambar (JPG, PNG) atau PDF maksimal 2MB</p>
                                    </div>
                                </div>

                                <!-- Loading Upload -->
                                <div wire:loading wire:target="berkas_ktp_kk" class="mt-2 text-xs font-semibold text-emerald-600 flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Mengunggah berkas...
                                </div>

                                <!-- File Preview (If any) -->
                                @if($berkas_ktp_kk && !$errors->has('berkas_ktp_kk'))
                                    <div class="mt-4 p-3 bg-emerald-50 rounded-xl border border-emerald-100 flex items-center justify-between text-sm">
                                        <span class="text-emerald-800 font-semibold truncate">{{ $berkas_ktp_kk->getClientOriginalName() }}</span>
                                        <span class="text-xs text-emerald-600 ml-2 font-medium">Siap unggah</span>
                                    </div>
                                @endif

                                @error('berkas_ktp_kk')
                                    <span class="text-xs font-semibold text-rose-600 block mt-1.5">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4 border-t border-slate-100">
                                <button type="submit" wire:loading.attr="disabled" 
                                    class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-emerald-100 hover:shadow-xl text-base font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all">
                                    <span wire:loading.remove wire:target="ajukanSurat">Kirim Pengajuan Surat</span>
                                    <span wire:loading wire:target="ajukanSurat" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Memproses Pengajuan...
                                    </span>
                                </button>
                            </div>

                        </form>
                    </div>
                @endif
            </div>

            <!-- Pelacakan Surat (Kanan) -->
            <div class="lg:col-span-5 bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100">
                <h3 class="text-xl font-bold text-slate-900 mb-6 pb-4 border-b border-slate-100 flex items-center">
                    <span class="w-2 h-6 rounded bg-teal-400 mr-3"></span>
                    Lacak Status Surat Warga
                </h3>
                <div class="space-y-6">
                    <p class="text-slate-500 text-xs leading-relaxed">
                        Masukkan 16 digit NIK Anda untuk melacak riwayat pengajuan surat keterangan secara berkala.
                    </p>
                    
                    <!-- Search Input -->
                    <div class="flex space-x-2">
                        <input wire:model="search_nik" type="text" maxlength="16" placeholder="Masukkan 16 digit NIK Pemohon" 
                            class="flex-grow px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm">
                        <button wire:click="lacakSurat" class="px-5 py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm transition-all shadow-md">
                            Lacak
                        </button>
                    </div>
                    @error('search_nik')
                        <span class="text-xs font-semibold text-rose-600 block -mt-4">{{ $message }}</span>
                    @enderror

                    <!-- Track Results -->
                    @if($trackedSurats !== null)
                        <div class="pt-4 border-t border-slate-100 space-y-4 animate-fade-in">
                            <h4 class="text-sm font-bold text-slate-900">Hasil Pelacakan ({{ $trackedSurats->count() }} Pengajuan)</h4>
                            
                            @if($trackedSurats->isEmpty())
                                <div class="p-4 bg-slate-50 rounded-2xl text-center text-slate-400 text-xs">
                                    Tidak ada riwayat pengajuan surat ditemukan untuk NIK tersebut.
                                </div>
                            @else
                                <div class="space-y-4 max-h-[350px] overflow-y-auto pr-1">
                                    @foreach($trackedSurats as $surat)
                                        <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/50 space-y-2">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <span class="text-xs font-bold text-slate-900 block">{{ $surat->jenisSurat->nama_surat }}</span>
                                                    <span class="text-[10px] text-slate-400 font-medium">{{ $surat->created_at->format('d M Y H:i') }}</span>
                                                </div>
                                                <span class="text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wider
                                                    {{ $surat->status === 'menunggu' ? 'bg-amber-100 text-amber-800' : '' }}
                                                    {{ $surat->status === 'diproses' ? 'bg-blue-100 text-blue-800' : '' }}
                                                    {{ $surat->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                                    {{ $surat->status === 'ditolak' ? 'bg-rose-100 text-rose-800' : '' }}">
                                                    {{ $surat->status }}
                                                </span>
                                            </div>
                                            
                                            <!-- Success Details -->
                                            @if($surat->status === 'selesai')
                                                <div class="bg-emerald-50/80 p-2.5 rounded-xl border border-emerald-100 text-xs text-emerald-800">
                                                    <span class="font-bold block">Nomor Surat Resmi:</span>
                                                    <span class="font-mono mt-0.5 block">{{ $surat->nomor_surat_resmi }}</span>
                                                    <span class="block mt-2 text-[10px] text-emerald-600 font-medium">* Silakan ambil fisik surat di Kantor Kepala Desa dengan menunjukkan NIK Anda.</span>
                                                </div>
                                            @endif

                                            <!-- Rejection Details -->
                                            @if($surat->status === 'ditolak')
                                                <div class="bg-rose-50/80 p-2.5 rounded-xl border border-rose-100 text-xs text-rose-800">
                                                    <span class="font-bold block">Alasan Penolakan berkas:</span>
                                                    <span class="mt-0.5 block italic">"{{ $surat->alasan_penolakan }}"</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
