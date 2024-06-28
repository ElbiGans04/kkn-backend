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
        Schema::create('komentar_program_kerja', function (Blueprint $table) {
            $table->id('id_komentar_proker');
            $table->string('judul_komentar');
            $table->string('body_komentar');            
            $table->foreignId('id_proker')->constrained( table: 'program_kerja', indexName: 'posts_proker_id', column: 'id_proker');
            $table->foreignId('id_dospem')->constrained( table: 'dosen', indexName: 'posts_dosen3_id', column: 'id_dosen');
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
