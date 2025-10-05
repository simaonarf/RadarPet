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
}
