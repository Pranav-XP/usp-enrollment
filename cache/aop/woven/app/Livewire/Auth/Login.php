<?php

namespace App\Livewire\Auth;

use Okapi\Aop\Core\JoinPoint\JoinPointInjector;
use Okapi\CodeTransformer\Core\DI;

#[\Livewire\Attributes\Layout('components.layouts.auth')]
class Login extends Login__AopProxied
{
	private static array $__joinPoints = ['method' => ['login' => ['App\Aspects\LoggerAspect::logImportantAction']]];


	/**
	 * Handle an incoming authentication request.
	 */
	#[\App\Aspects\LoggerAspect]
	public function login(): void
	{
		call_user_func_array(self::$__joinPoints['method']['login'], [$this]);
	}
}

DI::get(JoinPointInjector::class)->injectJoinPoints(Login::class);