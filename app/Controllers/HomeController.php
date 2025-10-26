<?php

namespace App\Controllers;

use App\Models\Post;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): void
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
