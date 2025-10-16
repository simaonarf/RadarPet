<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $title = 'Home Page';
        $this->render('home/index', compact('title'));
    }

    public function admin(): void
    {
        $title = 'Área Admin';
        $this->render('admin/index', compact('title'));
    }
}
