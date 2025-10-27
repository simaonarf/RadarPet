<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Lib\Authentication\Auth;

class AdminController extends Controller
{
    public function index(): void
    {

        $title = 'Painel Administrativo';

        $this->render('admin/index', compact('title'));
    }
}
