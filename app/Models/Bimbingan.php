<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bimbingan extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bimbingan';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_bimbingan';
    /**
     * Get the kelompok associated with the anggota.
     */
    public function kelompok(): BelongsTo
    {
        return  $this->belongsTo(Kelompok::class, 'id_kelompok', 'id_kelompok');
    }
    /**
     * Get the kelompok associated with the anggota.
     */
    public function dosen_pembimbing(): BelongsTo
    {
        return  $this->belongsTo(Dosen::class, 'id_dospem', 'id_dosen');
    }
}
