<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\PostPhoto;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Core\Router\Route;
use Lib\Authentication\Auth;
use Lib\FlashMessage;
use App\services\GalleryService;

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
        $params = $request->getParam('post');
        $files = $_FILES['photos'] ?? [];

        $galleryService = new GalleryService();
        $filesNormalized = $galleryService->normalizeFilesArray($files);
        $post = $galleryService->createPostWithPhotos($params, $filesNormalized, Auth::user());

        if ($post->hasErrors()) {
            FlashMessage::danger('Ocorreu um erro ao criar o post.');
            $this->render('posts/new', ['post' => $post]); 
            return;
        }
        
        FlashMessage::success('Post Criado com sucesso!');
        $this->redirectTo(route('posts.new'));
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();
        $post = $this->current_user->posts()->findById($params['id']);

        if (!$post instanceof Post) {
            FlashMessage::danger('Post não encontrado');
            $this->redirectTo('/');
            return;
        }

        $title = "Post #{$post->id}";
        $username = $this->current_user->name;

        $this->render('posts/show', compact('post', 'title', 'username'));
    }

    public function edit(Request $request): void
    {
        $params = $request->getParams();
        $post = $this->current_user->posts()->findById($params['id']);

        if (!$post instanceof Post) {
            FlashMessage::danger('Post não encontrado');
            $this->redirectTo('/');
            return;
        }

        $title = "Editar Post #{$post->id}";

        $this->render('posts/edit', compact('post', 'title'));
    }

    public function update(Request $request): void
    {
        $id = $request->getParam('id');
        $params = $request->getParam('post');
        $files = $_FILES['photos'] ?? [];

        $post = $this->current_user->posts()->findById($id);
        /** @var Post|null $post */

        if (!$post instanceof Post) {
            FlashMessage::danger('Post não encontrado');
            $this->redirectTo(route('home.index'));
            return;
        }

        $post->title = $params['title'];
        $post->description = $params['description'];

        if (!$post->save()) {
            FlashMessage::danger('Existem dados incorretos! Por favor verifique!');
            $title = "Editar Post #{$post->id}";
            $this->render('posts/edit', compact('post', 'title'));
            return;
        }
        
        $galleryService = new GalleryService();
        if (!$galleryService->addPhotos($post, $files)) {
            FlashMessage::danger('Post atualizado, mas ocorreu erro ao adicionar fotos.');
            $title = "Editar Post #{$post->id}";
            $this->render('posts/edit', compact('post', 'title'));
            return;
        }

        FlashMessage::success('Post atualizado com sucesso!');
        $this->redirectTo(route('home.index'));
    }

    public function destroyPhoto(Request $request): void
    {
        $photoId = (int) $request->getParam('photo_id');
        $postId = (int) $request->getParam('id');

        /** @var Post|null $post */
        $post = $this->current_user->posts()->findById($postId);

        if (!$post) {
            FlashMessage::danger('Post não encontrado.');
            $this->redirectTo(route('home.index'));
            return;
        }

        $galleryService = new GalleryService();
        
        if ($galleryService->deletePhoto($photoId, $post)) {
            FlashMessage::success('Foto excluída com sucesso!');
        } else {
            FlashMessage::danger('Erro ao excluir a foto.');
        }

        $this->redirectTo(route('posts.show', ['id' => $postId]));
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

        $galleryService = new GalleryService();
        $galleryService->deleteAllPhotos($post);


        if ($post->destroy()) {
            FlashMessage::success('Post excluído com sucesso!');
        } else {
            FlashMessage::danger('Ocorreu um erro ao excluir o post.');
        }

        $this->redirectTo(route('home.index'));
    }
}
