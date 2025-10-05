<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $title = 'Login';
        $this->render('auth/login', compact('title'));
    }

    public function showRegister(): void
    {
        $title = 'Register';
        $this->render('auth/register', compact('title'));
    }

    public function authenticate(): void
    {
        $email = trim($_POST['email'] ?? '');
        $pass  = (string)($_POST['password'] ?? '');

        try {
            $host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
            $db   = $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?? 'radarpet';
            $user = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?? 'root';
            $pwd  = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? '';
            $port = (string)($_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? '3306');

            $pdo = new \PDO("mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4", $user, $pwd, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);

            $stmt = $pdo->prepare('SELECT id,name,email,role,password_hash FROM users WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $row = $stmt->fetch();

            if ($row && password_verify($pass, $row['password_hash'])) {
                $_SESSION['user'] = [
                    'id'    => (int)$row['id'],
                    'name'  => $row['name'],
                    'role'  => $row['role'],
                    'email' => $row['email'],
                ];
                header('Location: /');
                exit;
            }

            $_SESSION['flash_error'] = 'Credenciais inválidas';
            header('Location: /login');
            exit;
        } catch (\Throwable $e) {
            $_SESSION['flash_error'] = 'DB: ' . $e->getMessage();
            header('Location: /login');
            exit;
        }
    }

    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        header('Location: /login');
        exit;
    }
}
