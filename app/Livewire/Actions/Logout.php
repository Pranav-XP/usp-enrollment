<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Aspects\LoggerAspect;
use Illuminate\Support\Facades\Log;

#[LoggerAspect]
class Logout
{
    /**
     * Log the current user out of the application.
     */
    #[LoggerAspect]
    public function __invoke()
    {
        Auth::guard('web')->logout();

        Session::invalidate();
        Session::regenerateToken();


        return redirect('/');
    }
}
