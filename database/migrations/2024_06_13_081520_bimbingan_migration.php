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
        Schema::create('bimbingan', function (Blueprint $table) {
            $table->id('id_bimbingan');
            $table->string('judul');
            $table->string('body');
            $table->date('tanggal_bimbingan');
            $table->string('link_bimbingan');
            $table->foreignId('id_kelompok')->constrained( table: 'kelompok', indexName: 'posts_kelompok2_id', column: 'id_kelompok');
            $table->foreignId('id_dospem')->constrained( table: 'dosen', indexName: 'posts_dosen_id', column: 'id_dosen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bimbingan');
    }
};
