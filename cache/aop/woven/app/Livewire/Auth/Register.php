<?php

namespace App\Livewire\Auth;

use Okapi\Aop\Core\JoinPoint\JoinPointInjector;
use Okapi\CodeTransformer\Core\DI;

#[\Livewire\Attributes\Layout('components.layouts.app')]
class Register extends Register__AopProxied
{
	private static array $__joinPoints = ['method' => ['register' => ['App\Aspects\LoggerAspect::logImportantAction']]];


	/**
	 * Handle an incoming registration request.
	 */
	#[\App\Aspects\LoggerAspect]
	public function register(): void
	{
		call_user_func_array(self::$__joinPoints['method']['register'], [$this]);
	}
}

DI::get(JoinPointInjector::class)->injectJoinPoints(Register::class);