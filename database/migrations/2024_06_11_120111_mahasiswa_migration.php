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
        Schema::create('mahasiswa', function (Blueprint $table) {
            // $table->id();
            $table->string('nim')->unique()->primary();
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('nomor_telephone');
            $table->foreignId('id_gender')->constrained( table: 'genders', indexName: 'posts_genders_id', column: 'id_gender');
            $table->foreignId('id_kelas')->constrained( table: 'kelas', indexName: 'posts_kelas_id', column: 'id_kelas');
            $table->foreignId('id_prodi')->constrained( table: 'prodi', indexName: 'posts_prodi_id', column: 'id_prodi');
            $table->foreignId('id_user')->constrained( table: 'users', indexName: 'posts_user_id', column: 'id');
            // $table->foreign('id_gender')->references('id_gender')->on('genders');
            // $table->foreign('id_kelas')->references('id_kelas')->on('kelas');
            // $table->foreign('id_prodi')->references('id_prodi')->on('prodi');
            // $table->foreign('id_user')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
