<?php
/**
 * Model: Infographic - infografis desa (judul + gambar).
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Infographic extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_type',
    ];
}
