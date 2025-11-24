<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use App\Models\User;
use App\Models\Occurrence;
use Tests\TestCase;

class OccurrenceCrudTest extends TestCase
{
    private User $user;
    private Post $post;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = new User([
            'name' => 'Tester Ocorrência',
            'email' => 'occurrence_tester@example.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'role' => 'user',
            'type' => 'tutor',
        ]);
        $this->user->save();

        $this->post = new Post([
            'title'       => 'Post para Ocorrência',
            'description' => 'Post base para testes de ocorrência',
            'user_id'     => $this->user->id,
        ]);
        $this->post->save();
    }

    public function test_create_and_read_occurrence(): void
    {
        $occurrence = new Occurrence([
            'location'    => 'Rua Central',
            'description' => 'Cão visto na praça.',
            'post_id'     => $this->post->id,
            'user_id'     => $this->user->id,
        ]);

        $this->assertTrue($occurrence->save());

        $found = Occurrence::findById($occurrence->id);

        $this->assertNotNull($found);
        $this->assertSame('Rua Central', $found->location);
        $this->assertSame('Cão visto na praça.', $found->description);
        $this->assertSame($this->post->id, $found->post_id);
        $this->assertSame($this->user->id, $found->user_id);
    }

    public function test_update_occurrence(): void
    {
        $occurrence = new Occurrence([
            'location'    => 'Bairro A',
            'description' => 'Primeira descrição',
            'post_id'     => $this->post->id,
            'user_id'     => $this->user->id,
        ]);
        $occurrence->save();

        $occurrence->location    = 'Bairro B';
        $occurrence->description = 'Descrição atualizada';
        $this->assertTrue($occurrence->save());

        $found = Occurrence::findById($occurrence->id);

        $this->assertNotNull($found);
        $this->assertSame('Bairro B', $found->location);
        $this->assertSame('Descrição atualizada', $found->description);
    }

    public function test_delete_occurrence(): void
    {
        $occurrence = new Occurrence([
            'location'    => 'Para deletar',
            'description' => 'Desc',
            'post_id'     => $this->post->id,
            'user_id'     => $this->user->id,
        ]);
        $occurrence->save();

        $id = $occurrence->id;

        $this->assertTrue($occurrence->destroy());

        $found = Occurrence::findById($id);
        $this->assertNull($found);
    }
}
