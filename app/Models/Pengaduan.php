<?php
/**
 * Model: Pengaduan - pengaduan masyarakat.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';

    protected $fillable = [
        'nik_pelapor',
        'nama_pelapor',
        'kategori',
        'isi_laporan',
        'foto_bukti',
        'status',
    ];
}
