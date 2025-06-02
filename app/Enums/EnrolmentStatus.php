<?php

namespace App\Enums;

enum EnrolmentStatus: string
{
    case ENROLLED = 'enrolled';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
}
