<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelompok', function (Blueprint $table) {
            $table->id('id_kelompok');
            $table->foreignId('id_dospem')->constrained( table: 'dosen', indexName: 'posts_dospem_id', column: 'id_dosen');
            $table->string('nim_ketua_kelompok')->unique();
            $table->foreign('nim_ketua_kelompok')->references('nim')->on('mahasiswa');
            $table->boolean('approve');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok');
    }
};
