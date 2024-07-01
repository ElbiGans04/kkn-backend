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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->string('judul_laporan');
            $table->string('body_laporan');
            $table->foreignId('id_kelompok')->constrained( table: 'kelompok', indexName: 'posts_kelompok5_id', column: 'id_kelompok');
            $table->enum('approve', ['approve', 'reject', 'review']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
