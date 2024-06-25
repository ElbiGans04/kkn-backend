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
        Schema::create('komentar', function (Blueprint $table) {
            $table->id('id_komentar');
            $table->string('judul_komentar');
            $table->string('body_komentar');            
            $table->enum('jenis_komentar', ['REVIEW_KOMENTAR', 'REVIEW_PROGRAM_KERJA']);
            $table->foreignId('id_kelompok')->constrained( table: 'kelompok', indexName: 'posts_kelompok4_id', column: 'id_kelompok');
            $table->foreignId('id_dospem')->constrained( table: 'dosen', indexName: 'posts_dosen2_id', column: 'id_dosen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komentar');
    }
};
