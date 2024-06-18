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
        Schema::create('akademik', function (Blueprint $table) {
            $table->id('id_akademik');
            $table->integer('ipk');
            $table->integer('semester_saat_ini');
            $table->integer('sks');
            $table->string('nim_mahasiswa')->unique();
            $table->foreign('nim_mahasiswa')->references('nim')->on('mahasiswa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akademik');
    }
};
