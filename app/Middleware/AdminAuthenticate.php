<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AdminAuthenticate implements Middleware
{
    public function handle(Request $request): void
    {   
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();

        if (!$user || !$user->isAdmin()) {
            FlashMessage::danger('Acesso restrito a administradores!');
            $this->redirectTo(route('home.index'));
        }
    }

    private function redirectTo(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }

}