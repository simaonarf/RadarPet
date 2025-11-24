<?php

namespace Database\Populate;

use App\Models\Post;
use App\Models\User;

class PostsPopulate
{
    public static function populate(): void
    {
        $admin = User::findBy(['email' => 'admin@demo.com']);
        $user = User::findBy(['email' => 'user@demo.com']);

        if (!$admin || !$user) {
            echo "Erro: user não encontrado.\n";
            return;
        }

        echo "Usuários encontrados. Criando posts...\n";

        $post2 = new Post();
        $post2->user_id = $user->id;
        $post2->title = 'Toby (Bairro Centro)';
        $post2->description = 'Pessoal, meu cachorro chamado "Toby", fugiu hoje de manhã perto da praça central. Ele é pequeno e assustado.';
        $post2->url_photo = 'https://www.hepper.com/wp-content/uploads/2022/10/Long-haired-cream-dachshund-running.jpg';
        $post2->register_date = date('Y-m-d H:i:s');
        $post2->save();
        echo "Post 'Toby (Bairro Centro)' (User) criado com sucesso!\n";

        $post3 = new Post();
        $post3->user_id = $user->id;
        $post3->title = 'Cachorro encontrado (Vila Nova)';
        $post3->description = 'Encontrei este cachorro diferente na minha garagem. Parece bem cuidado, mas está sem coleira. Está alimentado e seguro comigo.';
        $post3->url_photo = 'https://static.biologianet.com/2023/01/capivara.jpg';
        $post3->register_date = date('Y-m-d H:i:s');
        $post3->save();
        echo "Post 'Cachorro encontrado' (User) criado com sucesso!\n";

        $post4 = new Post();
        $post4->user_id = $user->id;
        $post4->title = 'Jack';
        $post4->description = 'Lorem Ipsum Dolor Sit Amet us';
        $post4->url_photo = 'https://www.shutterstock.com/image-photo/caramel-dog-light-chest-lying-600nw-2658308279.jpg';
        $post4->register_date = date('Y-m-d H:i:s');
        $post4->save();
        echo "Post 'Jack' (User) criado com sucesso!\n";

        // Post usado nos testes de ocorrência
        $post5 = new Post();
        $post5->user_id = $user->id;
        $post5->title = 'Post para Teste de Ocorrência';
        $post5->description = 'Post criado automaticamente para testes de ocorrência.';
        $post5->url_photo = 'https://placehold.co/600x400?text=Post+Teste+Ocorrencia';
        $post5->register_date = date('Y-m-d H:i:s');
        $post5->save();
        echo "Post 'Post para Teste de Ocorrência' (User) criado com sucesso!\n";
    }
}
