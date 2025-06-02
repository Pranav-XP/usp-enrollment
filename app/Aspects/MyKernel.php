<?php

namespace App\Aspects;

use App\Aspects\LoggerAspect;
use Okapi\Aop\AopKernel;

// Extend from the "AopKernel" class
class MyKernel extends AopKernel
{
    // Define a list of aspects
    protected array $aspects = [
        LoggerAspect::class,
    ];
}
