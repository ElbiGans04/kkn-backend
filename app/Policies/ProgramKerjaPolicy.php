<?php

namespace App\Policies;

use App\Enums\TipeAkun;
use App\Models\User;

class ProgramKerjaPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function allow (User $user) {
        return $user->tipe_akun == TipeAkun::mahasiswa->value;
    }
}
