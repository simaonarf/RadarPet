<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;


class UserTest extends TestCase
{
    public function test_authenticate_returns_true_for_correct_password(): void
    {
        $user = new User([
            'name' => 'Test',
            'email' => 'a@a.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'role' => 'user',
            'type' => 'tutor',
        ]);
        $user->save();

        $this->assertTrue($user->authenticate('123456'));
    }

    public function test_authenticate_returns_false_for_incorrect_password(): void
    {
        $user = new User([
            'name' => 'Test',
            'email' => 'a@a.com',
            'password' => '123456',
            'password_confirmation' => '123456',
            'role' => 'user',
            'type' => 'tutor',
        ]);
        $user->save();

        $this->assertFalse($user->authenticate('senha_errada'));
    }
}
