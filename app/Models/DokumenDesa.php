<?php
/**
 * Model: DokumenDesa - dokumen publik desa (peraturan, SK, laporan).
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenDesa extends Model
{
    protected $fillable = [
        'nama_dokumen',
        'deskripsi',
        'nama_file',
        'is_publik',
    ];

    protected $casts = [
        'is_publik' => 'boolean',
    ];
}
