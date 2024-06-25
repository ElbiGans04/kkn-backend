<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelompok extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kelompok';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_kelompok';
    /**
     * Get the anggota associated with the kelompok.
     */
    public function anggota(): HasMany
    {
        return  $this->hasMany(Anggota::class, 'id_kelompok', 'id_kelompok');
    }
    /**
     * Get the program kerja associated with the kelompok.
     */
    public function program_kerja(): HasMany
    {
        return  $this->hasMany(ProgramKerja::class, 'id_kelompok', 'id_kelompok');
    }
}
