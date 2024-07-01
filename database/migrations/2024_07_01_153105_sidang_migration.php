<?php

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
        Schema::create('sidang', function (Blueprint $table) {
            $table->id('id_sidang');
            $table->string('judul_sidang');
            $table->string('body_sidang');
            $table->date('tanggal_sidang');
            $table->enum('approve', ['approve', 'reject', 'review'])->default(StatusPersetujuan::review);
            $table->foreignId('id_kelompok')->constrained( table: 'kelompok', indexName: 'posts_kelompok9_id', column: 'id_kelompok');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidang');
    }
};
