<?php

namespace Tests\Unit\Controllers;

use App\Models\Post;
use App\Models\User;

class PetsControllerTest extends ControllerTestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();


        $this->user = new User([
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'role' => 'user',
            'type' => 'tutor',
        ]);
        $this->user->save();


        $_SESSION['user']['id'] = $this->user->id;
    }

    public function test_index_lists_posts(): void
    {

        $posts = [];
        $posts[] = new Post([
            'title' => 'Post 1',
            'description' => 'Desc 1',
            'url_photo' => '/uploads/p1.jpg',
            'user_id' => $this->user->id,
        ]);
        $posts[] = new Post([
            'title' => 'Post 2',
            'description' => 'Desc 2',
            'url_photo' => '/uploads/p2.jpg',
            'user_id' => $this->user->id,
        ]);
        foreach ($posts as $post) {
            $post->save();
        }


        $response = $this->get(
            action: 'index',
            controllerName: 'App\Controllers\HomeController'
        );


        foreach ($posts as $post) {
            $this->assertMatchesRegularExpression("/{$post->title}/", $response);
        }
    }


    public function test_unsuccessfully_create_post(): void
    {

        $params = [
            'post' => [
                'title'       => '',
                'description' => '',
                'url_photo'   => '',
            ],
        ];


        $response = $this->post(
            action: 'create',
            controllerName: 'App\Controllers\PostController',
            params: $params
        );


        $this->assertMatchesRegularExpression('/Location:\s*\/posts\/new/', $response);

        $this->assertSame(
            'Ocorreu um erro ao criar o post. Verifique os dados.',
            $_SESSION['flash']['danger'] ?? null
        );
    }


    public function test_successfully_create_post(): void
    {
        $params = [
            'post' => [
                'title'       => 'Título válido',
                'description' => 'Descrição válida',
                'url_photo'   => '/uploads/ok.jpg',
            ],
        ];

        $response = $this->post(
            action: 'create',
            controllerName: 'App\Controllers\PostController',
            params: $params
        );


        $this->assertMatchesRegularExpression('/Location:\s*\/posts\/new/', $response);


        $this->assertSame(
            'Post Criado com sucesso!',
            $_SESSION['flash']['success'] ?? null
        );
    }

    public function test_unsuccessfully_update_post(): void
    {
        $post = new Post([
            'title' => 'Título original',
            'description' => 'Desc original',
            'url_photo' => '/uploads/img.jpg',
            'user_id' => $this->user->id,
        ]);
        $post->save();

        $params = [
            'id' => $post->id,
            'post' => [
                'title'       => '',
                'description' => '',
                'url_photo'   => '',
            ],
        ];

        $response = $this->put(
            action: 'update',
            controllerName: 'App\Controllers\PostController',
            params: $params
        );


        $this->assertDoesNotMatchRegularExpression('/Location:/', $response);
        $this->assertMatchesRegularExpression('/Existem dados incorretos! Por verifique!/', $response);
    }

    public function test_successfully_update_post(): void
    {
        $post = new Post([
            'title' => 'Título original',
            'description' => 'Descrição original',
            'url_photo' => '/uploads/original.jpg',
            'user_id' => $this->user->id,
        ]);
        $post->save();

        $params = [
            'id' => $post->id,
            'post' => [
                'title'       => 'Título atualizado',
                'description' => 'Descrição atualizada',
                'url_photo'   => '/uploads/novo.jpg',
            ],
        ];

        $response = $this->put(
            action: 'update',
            controllerName: 'App\Controllers\PostController',
            params: $params
        );


        $this->assertMatchesRegularExpression('/^Location:\s*\/$/m', $response);

        $this->assertSame(
            'Post atualizado com sucesso!',
            $_SESSION['flash']['success'] ?? null
        );
    }

    public function test_successfully_destroy_post(): void
    {
        $post = new Post([
            'title' => 'Título para deletar',
            'description' => 'Descrição qualquer',
            'url_photo' => '/uploads/delete.jpg',
            'user_id' => $this->user->id,
        ]);
        $post->save();

        $params = ['id' => $post->id];

        $response = $this->post(
            action: 'destroy',
            controllerName: 'App\Controllers\PostController',
            params: $params
        );

        $this->assertMatchesRegularExpression('/^Location:\s*\/$/m', $response);
        $this->assertSame(
            'Post excluído com sucesso!',
            $_SESSION['flash']['success'] ?? null
        );
    }

    public function test_unsuccessfully_destroy_post(): void
    {
        $params = ['id' => 9999]; // ID inexistente

        $response = $this->post(
            action: 'destroy',
            controllerName: 'App\Controllers\PostController',
            params: $params
        );

        $this->assertMatchesRegularExpression('/^Location:\s*\/$/m', $response);
        $this->assertSame(
            'Post não encontrado.',
            $_SESSION['flash']['danger'] ?? null
        );
    }

    public function test_edit_post_form_prefilled(): void
    {
        $post = new Post([
            'title' => 'Título original',
            'description' => 'Desc original',
            'url_photo' => '/uploads/original.jpg',
            'user_id' => $this->user->id,
        ]);
        $post->save();

        $response = $this->get(
            action: 'edit',
            controllerName: 'App\Controllers\PostController',
            params: ['id' => $post->id]
        );

        $this->assertDoesNotMatchRegularExpression('/Location:/', $response);

        $regexTitle =
            '/<input\s[^>]*type=[\'"]text[\'"][^>]*name=[\'"]post\[title\][\'"][^>]*value=[\'"]Título original[\'"][^>]*>/i';
        $this->assertMatchesRegularExpression($regexTitle, $response);

        $regexDesc =
            '/<textarea[^>]*name=[\'"]post\[description\][\'"][^>]*>.*Desc original.*<\/textarea>/is';
        $this->assertMatchesRegularExpression($regexDesc, $response);


        $regexUrl =
            '/<input\s[^>]*type=[\'"]text[\'"][^>]*name=[\'"]post\[url_photo\][\'"][^>]*value=[\'"]'
            . '\/uploads\/original\.jpg[\'"][^>]*>/i';
        $this->assertMatchesRegularExpression($regexUrl, $response);
    }

    public function test_edit_post_not_found_redirects(): void
    {
        $response = $this->get(
            action: 'edit',
            controllerName: 'App\Controllers\PostController',
            params: ['id' => 9999]
        );

        $this->assertMatchesRegularExpression('/^Location:\s*\/$/m', $response);

        $this->assertSame('Post não encontrado', $_SESSION['flash']['danger'] ?? null);
    }

    public function test_home_shows_all_posts(): void
    {
        for ($i = 1; $i <= 11; $i++) {
            (new Post([
                'title'       => "Post $i",
                'description' => "Desc $i",
                'url_photo'   => "/uploads/p$i.jpg",
                'user_id'     => $this->user->id,
            ]))->save();
        }

        $response = $this->get(
            action: 'index',
            controllerName: 'App\Controllers\HomeController'
        );

        $this->assertMatchesRegularExpression('/Post 11\b/', $response);
    }
}
