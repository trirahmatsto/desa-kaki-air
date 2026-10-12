<?php
/**
 * Model: ArtikelGaleri - berita & galeri desa (kolom jenis: berita / galeri).
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ArtikelGaleri extends Model
{
    protected $table = 'artikel_galeri';

    protected $fillable = [
        'judul',
        'konten',
        'foto_sampul',
        'kategori',
    ];

    /**
     * Scope: hanya artikel berita.
     */
    public function scopeBerita(Builder $query): Builder
    {
        return $query->where('kategori', 'berita');
    }

    /**
     * Scope: hanya galeri kegiatan.
     */
    public function scopeGaleri(Builder $query): Builder
    {
        return $query->where('kategori', 'galeri');
    }
}
