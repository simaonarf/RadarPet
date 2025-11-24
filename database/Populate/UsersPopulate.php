<?php

namespace Database\Populate;

use App\Models\User;

class UsersPopulate
{
    public static function populate(): void
    {
        self::createUser('Administrador', 'admin@demo.com', 'admin');
        self::createUser('Usuário Comum', 'user@demo.com', 'user');
        self::createUser('Usuário Comum #2', 'user2@demo.com', 'user');
    }

    private static function createUser(string $name, string $email, string $role): void
    {
        $user = User::findBy(['email' => $email]);

        if ($user) {
            echo "$email já existe.\n";
            return;
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->role = $role;

        // NÃO mexe em type
        // $user->type = 'local';

        $user->password = 'user123';
        $user->password_confirmation = 'user123';
        $user->register_date = date('Y-m-d H:i:s');

        if ($user->save()) {
            echo "$email criado com sucesso!\n";
        } else {
            echo "Erro ao criar $email\n";
        }
    }
}
