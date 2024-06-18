<?php

namespace App\Enums;

enum TipeAkun: string {
	case admin = 'admin';
    case dosen_pembimbing = 'dosen_pembimbing';
    case mahasiswa = 'mahasiswa';
}