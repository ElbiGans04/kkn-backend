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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id('id_dosen');
            $table->string('nid')->unique();
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->foreignId('id_gender')->constrained( table: 'genders', indexName: 'posts_genders2_id', column: 'id_gender');
            $table->foreignId('id_user')->constrained( table: 'users', indexName: 'posts_user2_id', column: 'id');
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
        Schema::dropIfExists('dosen');
    }
};
