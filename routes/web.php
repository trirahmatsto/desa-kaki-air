<?php
/**
 * RUTE WEB - file tunggal. Halaman publik pakai Livewire/Volt (Volt::route). Admin panel Filament otomatis di /admin (lihat app/Providers/Filament).
 */

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'public.beranda')->name('beranda');
Route::redirect('/home', '/')->name('home');
Volt::route('/profil-desa', 'public.profil-desa')->name('profil-desa');
Volt::route('/data-desa', 'public.data-desa')->name('data-desa');
Volt::route('/artikel', 'public.artikel')->name('artikel');
Volt::route('/pengaduan', 'public.pengaduan')->name('pengaduan');
Volt::route('/layanan-surat', 'public.layanan-surat')->name('layanan-surat');
Volt::route('/profil-desa-dan-infografis', 'public.profil-desa-dan-infografis')
    ->name('profil-desa-dan-infografis');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::get('/admin/infografis', App\Livewire\Admin\InfographicController::class)
    ->middleware(['auth'])
    ->name('admin.infografis');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
