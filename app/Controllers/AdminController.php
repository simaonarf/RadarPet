<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Lib\Authentication\Auth;

class AdminController extends Controller
{

    private function requireAdminLogin(): void
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            $this->redirectTo(route('login'));
            exit;
        }
    }

    public function index(): void
    {
        $this->requireAdminLogin();
        
        $title = 'Painel Administrativo';

        $this->render('admin/index', compact('title'));
    }
}