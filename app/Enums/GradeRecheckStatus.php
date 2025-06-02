<?php

namespace App\Enums;

enum GradeRecheckStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
