<?php

namespace App\Models;

use App\Models\Post;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use Core\Database\ActiveRecord\HasMany;

/**
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $encrypted_password
 * @property string $type
 * @property string $role  // 'user' ou 'admin'
 * @property string $register_date
 */
class User extends Model
{
    protected static string $table = 'users';
    protected static array $columns = ['name', 'email', 'encrypted_password', 'type', 'role', 'register_date'];

    protected ?string $password = null;

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('name', $this);
        Validations::notEmpty('email', $this);

        Validations::notEmpty('role', $this);

        Validations::uniqueness('email', $this);

        if ($this->newRecord()) {
            Validations::notEmpty('password', $this);
        }
    }


    public function authenticate(string $password): bool
    {
        if ($this->encrypted_password == null) {
            return false;
        }
        return password_verify($password, $this->encrypted_password);
    }


    public static function findByEmail(string $email): ?self
    {
        return self::findBy(['email' => $email]);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function __set(string $property, mixed $value): void
    {
        parent::__set($property, $value);

        if (
            $property === 'password' &&
            $this->newRecord() &&
            $value !== null && $value !== ''
        ) {
            $this->encrypted_password = password_hash($value, PASSWORD_DEFAULT);
        }
    }
}
