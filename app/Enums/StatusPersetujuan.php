<?php

namespace App\Enums;

enum StatusPersetujuan: string {
	case review = 'review';
    case approve = 'approve';
    case reject = 'reject';
}