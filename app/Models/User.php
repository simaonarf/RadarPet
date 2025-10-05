<?php
namespace App\Models;

class User
{
    public int $id;
    public string $name;
    public string $email;
    public string $role;

    public function __construct(array $data)
    {
        $this->id = (int)($data['id'] ?? 0);
        $this->name = (string)($data['name'] ?? '');
        $this->email = (string)($data['email'] ?? '');
        $this->role = (string)($data['role'] ?? 'user');
    }

    public static function find(int $id): ?self
    {
        if (!empty($_SESSION['user']) && (int)($_SESSION['user']['id'] ?? 0) === $id) {
            return new self($_SESSION['user']);
        }
        return null;
    }

    public static function findById(int $id): ?self
    {
        return self::find($id);
    }
}
