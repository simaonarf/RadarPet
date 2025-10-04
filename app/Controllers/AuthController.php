<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function showRegister(): void
    {
        $title = 'Register';
        $this->render('auth/register', compact('title'));
    }

    public function showLogin(): void
    {
        $title = 'Login';
        $this->render('auth/login', compact('title'));
    }
}
