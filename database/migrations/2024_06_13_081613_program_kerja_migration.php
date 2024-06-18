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
        Schema::create('program_kerja', function (Blueprint $table) {
            $table->id('id_proker');
            $table->string('judul_proker');
            $table->string('body_proker');
            $table->foreignId('id_kelompok')->constrained( table: 'kelompok', indexName: 'posts_kelompok3_id', column: 'id_kelompok');
            $table->boolean('approve');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_kerja');
    }
};
