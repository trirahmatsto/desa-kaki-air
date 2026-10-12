<?php
/**
 * Model: DataDesa - data agregat per kategori (9 kategori potensi + sensus), isi JSON di kolom data.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataDesa extends Model
{
    protected $table = 'data_desa';

    protected $fillable = ['desa_id', 'kategori', 'data'];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }
}
