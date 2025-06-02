<?php

namespace App\Http\Controllers;

use Okapi\Aop\Core\JoinPoint\JoinPointInjector;
use Okapi\CodeTransformer\Core\DI;

class AdminController extends AdminController__AopProxied
{
	private static array $__joinPoints = [
		'method' => [
			'showGradeForm' => ['App\Aspects\LoggerAspect::logImportantAction'],
			'updateGrades' => ['App\Aspects\LoggerAspect::logImportantAction'],
		],
	];


	#[\App\Aspects\LoggerAspect]
	public function showGradeForm($studentId)
	{
		return call_user_func_array(self::$__joinPoints['method']['showGradeForm'], [$this, ['studentId' => $studentId]]);
	}


	#[\App\Aspects\LoggerAspect]
	public function updateGrades(\Illuminate\Http\Request $request, $studentId)
	{
		return call_user_func_array(self::$__joinPoints['method']['updateGrades'], [$this, ['request' => $request, 'studentId' => $studentId]]);
	}
}

DI::get(JoinPointInjector::class)->injectJoinPoints(AdminController::class);