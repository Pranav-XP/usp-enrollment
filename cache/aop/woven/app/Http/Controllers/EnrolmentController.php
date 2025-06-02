<?php

namespace App\Http\Controllers;

use Okapi\Aop\Core\JoinPoint\JoinPointInjector;
use Okapi\CodeTransformer\Core\DI;

class EnrolmentController extends EnrolmentController__AopProxied
{
	private static array $__joinPoints = ['method' => ['enrolStudent' => ['App\Aspects\LoggerAspect::logImportantAction']]];


	#[\App\Aspects\LoggerAspect]
	public function enrolStudent(\Illuminate\Http\Request $request, $courseId)
	{
		return call_user_func_array(self::$__joinPoints['method']['enrolStudent'], [$this, ['request' => $request, 'courseId' => $courseId]]);
	}
}

DI::get(JoinPointInjector::class)->injectJoinPoints(EnrolmentController::class);