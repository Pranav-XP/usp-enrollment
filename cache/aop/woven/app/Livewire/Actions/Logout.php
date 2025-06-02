<?php

namespace App\Livewire\Actions;

use Okapi\Aop\Core\JoinPoint\JoinPointInjector;
use Okapi\CodeTransformer\Core\DI;

#[\App\Aspects\LoggerAspect]
class Logout extends Logout__AopProxied
{
	private static array $__joinPoints = ['method' => ['__invoke' => ['App\Aspects\LoggerAspect::logImportantAction']]];


	/**
	 * Log the current user out of the application.
	 */
	#[\App\Aspects\LoggerAspect]
	public function __invoke()
	{
		return call_user_func_array(self::$__joinPoints['method']['__invoke'], [$this]);
	}
}

DI::get(JoinPointInjector::class)->injectJoinPoints(Logout::class);