<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated($request, $user)
    {
        if ($user->hasRole('superadmin')) {
            return redirect()->route('inicio'); // Dashboard o reportes
        }

        if ($user->hasRole('mesero')) {
            return redirect()->route('tables.index'); // Módulo Mesas
        }

        if ($user->hasRole('cocinero')) {
            return redirect()->route('kitchen.index'); // Módulo Cocina
        }

        // Por si no tiene rol asignado
        return redirect()->route('inicio');
    }
}
