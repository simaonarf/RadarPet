<?php

namespace App\Controllers;

use App\Models\Post;
use Core\Http\Controllers\Controller;
use Core\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): void
    {
        if (!$this->current_user) {
            $this->render('auth/login');
            return;
        }

        $pageParam = $request->getParam('page', 1);
        $page = is_numeric($pageParam) ? (int)$pageParam : 1;

        $paginator = $this->current_user->posts()->paginate($page);

        $posts = $paginator->registers();

        $title = 'Home Page';

        if ($request->acceptJson()) {
            $this->renderJson('home/index', compact('paginator', 'posts', 'title'));
        } else {
            $this->render('home/index', compact('paginator', 'title', 'posts'));
        }
    }

    public function admin(): void
    {
        $title = 'Área Admin';
        $this->render('admin/index', compact('title'));
    }
}
