<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nilai';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_nilai';
    /**
     * Get the nilai associated with the program_kerja.
     */
    public function mahasiswa(): BelongsTo
    {
        return  $this->belongsTo(Mahasiswa::class, 'nim_mahasiswa', 'nim');
    }
}
