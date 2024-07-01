<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sidang extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sidang';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_sidang';
    /**
     * Get the kelompok associated with the sidang.
     */
    public function kelompok(): BelongsTo
    {
        return  $this->belongsTo(Kelompok::class, 'id_kelompok', 'id_kelompok');
    }
}
