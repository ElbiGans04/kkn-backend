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
        Schema::create('komentar_laporan', function (Blueprint $table) {
            $table->id('id_komentar_laporan');
            $table->string('judul_komentar');
            $table->string('body_komentar');
            $table->foreignId('id_laporan')->constrained( table: 'laporan', indexName: 'posts_laporan_id', column: 'id_laporan');
            $table->foreignId('id_user')->constrained( table: 'users', indexName: 'posts_user9_id', column: 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komentar_laporan');
    }
};
