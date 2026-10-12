<?php
/**
 * Model: Desa - daftar desa (nama + slug).
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    protected $table = 'desa';

    protected $fillable = ['nama', 'slug'];

    protected static function booted(): void
    {
        static::creating(function ($desa) {
            if (!$desa->slug) {
                $desa->slug = str($desa->nama)->slug();
            }
        });
    }

    public function dataDesa(): HasMany
    {
        return $this->hasMany(DataDesa::class);
    }
}
