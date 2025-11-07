<?php

namespace Database\Populate;

use App\Models\User;
use Core\Database\Database;

class UsersPopulate
{
    public static function populate(): void
    {

        echo "Criando Administrador...\n";
        $admin = new User();
        $admin->name = 'Administrador';
        $admin->email = 'admin@demo.com';
        $admin->password = 'admin123';
        $admin->role = 'admin';

        $admin->save();
        echo "admin@demo.com' criado com sucesso!\n";

        echo "Criando Usuário Comum...\n";
        $user = new User();
        $user->name = 'Usuario Comum';
        $user->email = 'user@demo.com';
        $user->password = 'user123';
        $user->role = 'user';
        $user->type = 'tutor';

        echo "user@demo.com' criado com sucesso!\n";

        $user->save();

        echo "Criando Usuário Comum #2...\n";
        $user = new User();
        $user->name = 'Usuario Comum 2';
        $user->email = 'user2@demo.com';
        $user->password = 'user123';
        $user->role = 'user';
        $user->type = 'encontrador';

        echo "user2@demo.com' criado com sucesso!\n";

        $user->save();
    }
}
