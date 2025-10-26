<?php

namespace App\Controllers;

use App\Models\Post;
use Core\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $posts = Post::all();

        $title = 'Home Page';
        $this->render('home/index', compact('title', 'posts'));
    }

    public function admin(): void
    {
        $title = 'Área Admin';
        $this->render('admin/index', compact('title'));
    }
}
