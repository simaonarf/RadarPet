<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class PostCrudTest extends TestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = new User([
            'name' => 'Tester',
            'email' => 'tester@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'role' => 'user',
            'type' => 'tutor',
        ]);
        $this->user->save();
    }

    public function test_create_and_read_post(): void
    {
        $post = new Post([
            'title' => 'Criar e ler',
            'description' => 'Desc A',
            'url_photo' => '/uploads/a.jpg',
            'user_id' => $this->user->id,
        ]);
        $this->assertTrue($post->save());

        $found = Post::findById($post->id);
        $this->assertNotNull($found);
        $this->assertSame('Criar e ler', $found->title);
        $this->assertSame('Desc A', $found->description);
        $this->assertSame('/uploads/a.jpg', $found->url_photo);
        $this->assertSame($this->user->id, $found->user_id);
    }

    public function test_update_post(): void
    {
        $post = new Post([
            'title' => 'Original',
            'description' => 'Desc Original',
            'url_photo' => '/uploads/original.jpg',
            'user_id' => $this->user->id,
        ]);
        $post->save();

        $post->title = 'Atualizado';
        $post->description = 'Desc Atualizada';
        $post->url_photo = '/uploads/novo.jpg';
        $this->assertTrue($post->save());

        $found = Post::findById($post->id);
        $this->assertNotNull($found);
        $this->assertSame('Atualizado', $found->title);
        $this->assertSame('Desc Atualizada', $found->description);
        $this->assertSame('/uploads/novo.jpg', $found->url_photo);
    }

    public function test_delete_post(): void
    {
        $post = new Post([
            'title' => 'Para deletar',
            'description' => 'Desc',
            'url_photo' => '/uploads/d.jpg',
            'user_id' => $this->user->id,
        ]);
        $post->save();

        $id = $post->id;
        $this->assertTrue($post->destroy());

        $found = Post::findById($id);
        $this->assertNull($found);
    }
}
