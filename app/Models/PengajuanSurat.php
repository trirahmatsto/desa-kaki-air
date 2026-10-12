<?php
/**
 * Model: PengajuanSurat - pengajuan surat online.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanSurat extends Model
{
    protected $table = 'pengajuan_surat';

    protected $fillable = [
        'nik_pemohon',
        'nama_pemohon',
        'no_whatsapp',
        'jenis_surat_id',
        'keperluan',
        'berkas_ktp_kk',
        'nomor_surat_resmi',
        'status',
        'alasan_penolakan',
    ];

    /**
     * Relasi: Menghubungkan ke tabel jenis_surat
     */
    public function jenisSurat(): BelongsTo
    {
        return $this->belongsTo(JenisSurat::class, 'jenis_surat_id');
    }
}
