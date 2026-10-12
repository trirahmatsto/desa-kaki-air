<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('data_desa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desa')->cascadeOnDelete();
            $table->string('kategori');
            $table->json('data');
            $table->timestamps();

            $table->unique(['desa_id', 'kategori']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_desa');
        Schema::dropIfExists('desa');
    }
};
