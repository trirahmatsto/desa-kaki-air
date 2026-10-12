<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master Jenis Surat (SKTM, Domisili, dll)
        Schema::create('jenis_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_surat');
            $table->string('kode_surat')->unique();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });

        // Transaksi Pengajuan Surat (Tanpa Login Warga)
        Schema::create('pengajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nik_pemohon', 16);
            $table->string('nama_pemohon');
            $table->string('no_whatsapp', 20);
            $table->foreignId('jenis_surat_id')->constrained('jenis_surat')->cascadeOnDelete();
            $table->text('keperluan');
            $table->string('berkas_ktp_kk'); // Path file KTP/KK yang diunggah
            $table->string('nomor_surat_resmi')->nullable(); // Diisi admin saat status selesai
            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'ditolak'])->default('menunggu');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });

        // Fitur Pengaduan Masyarakat (Tanpa Login)
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('nik_pelapor', 16);
            $table->string('nama_pelapor');
            $table->string('kategori');
            $table->text('isi_laporan');
            $table->string('foto_bukti')->nullable(); // Path file foto bukti
            $table->enum('status', ['masuk', 'ditinjau', 'selesai'])->default('masuk');
            $table->timestamps();
        });

        // Artikel Berita & Galeri Desa
        Schema::create('artikel_galeri', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->longText('konten');
            $table->string('foto_sampul')->nullable();
            $table->enum('kategori', ['berita', 'galeri']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artikel_galeri');
        Schema::dropIfExists('pengaduan');
        Schema::dropIfExists('pengajuan_surat');
        Schema::dropIfExists('jenis_surat');
    }
};
