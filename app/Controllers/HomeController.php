<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $title = 'Home Page';
        $this->render('home/index', compact('title'));
    }

    public function admin(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo 'Acesso negado (somente admin).';
            exit;
        }

        $title = 'Área Admin';
        $this->render('admin/index', compact('title'));
    }
}
