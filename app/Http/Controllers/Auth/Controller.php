<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller as AppController;

class Controller extends AppController
{
    /**
     *
     */
    protected function redirectTo()
    {
        return route('home');
    }
}
