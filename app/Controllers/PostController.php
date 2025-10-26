<?php

namespace App\Controllers;

use App\Models\Post;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Core\Router\Route;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class PostController extends Controller
{
    public function new(Request $request): void
    {
        $post = new Post();

        $title = 'Create';

        $this->render('posts/new', compact('post', 'title'));
    }

    public function create(Request $request): void
    {
        $title = 'Create';

        $params = $request->getParam('post');

        $post = new Post();
        $post->title = $params['title'];
        $post->description = $params['description'];
        $post->url_photo = $params['url_photo'];

        $post->user_id = Auth::user()->id; 

        $post->validates();

        if (!$post->hasErrors()) {
            $post->save();
            FlashMessage::success('Post Criado com sucesso!');
            $this->redirectTo(route('posts.new'));
        } else {
            FlashMessage::danger('Ocorreu um erro ao criar o post. Verifique os dados.');  
            $this->render('posts/new', compact('post', 'title'));
        }
    }

    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $post = $this->current_user->posts()->findById($params['id']);

        if (!$post) {
            FlashMessage::danger('Post não encontrado');
            $this->redirectTo('/');
            return;
        }

        $title = "Editar Post #{$post->id}";

        $this->render('posts/edit', compact('post','title'));   
    }

    public function update(Request $request): void
    {
        $id = $request->getParam('id');
        $params = $request->getParam('post');

        $post = $this->current_user->posts()->findById($id);

        $post->title = $params['title'];
        $post->description = $params['description'];
        $post->url_photo = $params['url_photo'];

        $post->validates();

        if ($post->save()) {
            FlashMessage::success('Post atualizado com sucesso!');
            $this->redirectTo(route('home.index'));
        } else {
            FlashMessage::danger('Existem dados incorretos! Por verifique!');
            $title = "Editar Post #{$post->id}";
            $this->render('posts/edit', compact('post', 'title'));
        }
    }

    public function destroy(Request $request): void
    {
        $id = $request->getParam('id');

        /** @var \App\Models\Post|null $post */
        $post = $this->current_user->posts()->findById($id);

        if (!$post) {
            FlashMessage::danger('Post não encontrado.');
            $this->redirectTo(route('home.index'));
            return;
        }

        if ($post->destroy()) { 
            FlashMessage::success('Post excluído com sucesso!');
        } else {
            FlashMessage::danger('Ocorreu um erro ao excluir o post.');
        }

        $this->redirectTo(route('home.index'));
    }
}