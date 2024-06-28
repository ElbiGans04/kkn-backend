<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomentarProgramKerja extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'komentar_program_kerja';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_komentar_proker';
    /**
     * Get the Program Kerja associated with the anggota.
     */
    public function program_kerja(): BelongsTo
    {
        return  $this->belongsTo(ProgramKerja::class, 'id_proker', 'id_proker');
    }
    /**
     * Get the kelompok associated with the anggota.
     */
    public function dosen_pembimbing(): BelongsTo
    {
        return  $this->belongsTo(Dosen::class, 'id_dospem', 'id_dosen');
    }
}
