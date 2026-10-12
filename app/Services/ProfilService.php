<?php
/**
 * Service: ambil profil / perangkat / program desa dari Supabase REST, cache 15 menit.
 */

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ProfilService
{
    protected static function get(string $table, array $query = []): ?array
    {
        $url = config('data-desa.supabase_url');
        $key = config('data-desa.supabase_key');

        $response = Http::withHeaders(['apikey' => $key])
            ->withToken($key, 'Bearer')
            ->timeout(30)
            ->get("{$url}/rest/v1/{$table}", $query);

        return $response->successful() ? $response->json() : null;
    }

    public static function profil(string $desa = 'Kaki Air'): array
    {
        return Cache::remember('profil_desa_' . str()->slug($desa), now()->addMinutes(15), function () use ($desa) {
            $rows = static::get('profil_desa', ['desa' => "eq.{$desa}", 'select' => '*']);

            return $rows[0] ?? [];
        });
    }

    public static function perangkat(string $desa = 'Kaki Air'): array
    {
        return Cache::remember('perangkat_desa_' . str()->slug($desa), now()->addMinutes(15), function () use ($desa) {
            return static::get('perangkat_desa', [
                'desa' => "eq.{$desa}",
                'select' => '*',
                'order' => 'urutan.asc',
            ]) ?? [];
        });
    }

    public static function program(string $desa = 'Kaki Air'): array
    {
        return Cache::remember('program_prioritas_' . str()->slug($desa), now()->addMinutes(15), function () use ($desa) {
            return static::get('program_prioritas', [
                'desa' => "eq.{$desa}",
                'select' => '*',
                'order' => 'anggaran.desc',
            ]) ?? [];
        });
    }
}