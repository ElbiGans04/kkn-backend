<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomentarLaporan extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'komentar_laporan';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_komentar_laporan';
    /**
     * Get the Laporan associated with the Komentar.
     */
    public function laporan(): BelongsTo
    {
        return  $this->belongsTo(Laporan::class, 'id_laporan', 'id_laporan');
    }
    /**
     * Get the User associated with the Komentar.
     */
    public function user(): BelongsTo
    {
        return  $this->belongsTo(User::class, 'id_user', 'id');
    }
}
