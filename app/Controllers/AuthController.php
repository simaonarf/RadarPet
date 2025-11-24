<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthController extends Controller
{
    public function showLogin(Request $request): void
    {
        $title = 'Login';
        $this->render('auth/login', compact('title'));
    }

    public function authenticate(Request $request): void
    {
        $params = $request->getParam('user');

        if (!$params || !isset($params['email']) || !isset($params['password'])) {
            FlashMessage::danger('Ocorreu um erro. Tente novamente.');
            $this->redirectTo(route('login'));
            return;
        }

        $user = User::findBy(['email' => $params['email']]);

        if ($user && $user->authenticate($params['password'])) {
            Auth::login($user);
            FlashMessage::success('Login realizado com sucesso!');

            if ($user->isAdmin()) {
                $this->redirectTo(route('admin.dashboard'));
            } else {
                $this->redirectTo(route('home.index'));
            }
        } else {
            FlashMessage::danger('Email e/ou senha inválidos!');
            $this->redirectTo(route('login'));
        }
    }

    public function destroy(): void
    {
        Auth::logout();
        FlashMessage::success('Logout realizado com sucesso!');
        $this->redirectTo(route('login'));
    }
}
