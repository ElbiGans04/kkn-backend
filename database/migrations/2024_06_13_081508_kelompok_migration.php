<?php

use App\Enums\JenisKelompok;
use App\Enums\StatusPersetujuan;
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
            $table->foreignId('id_dospem')->nullable()->constrained( table: 'dosen', indexName: 'posts_dospem_id', column: 'id_dosen');
            $table->string('nim_ketua_kelompok');
            $table->foreign('nim_ketua_kelompok')->references('nim')->on('mahasiswa');
            $table->enum('approve', ['approve', 'reject', 'review'])->default(StatusPersetujuan::review);
            $table->string('lokasi_kkn');
            $table->enum('jenis', ['KKN', 'KKP'])->default(JenisKelompok::KKN);
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
