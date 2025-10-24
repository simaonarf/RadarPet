<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $url_photo
 * @property string $register_date  
 * 
 */
class Post extends Model
{
    protected static string $table = 'posts';
    protected static array $columns = ['user_id', 'title', 'description', 'url_photo', 'register_date'];

    public function validates(): void
    {
        Validations::notEmpty('title', $this);
        Validations::notEmpty('description', $this);
        Validations::notEmpty('url_photo', $this);
        Validations::notEmpty('user_id', $this);
    }

    public function user(): ?User
    {
        return User::findBy([$this-> $user_id]);
    }

    public function addError(string $attribute, string $message): void
    {
    $this->errors[$attribute] = "{$attribute} {$message}";
    }

    /**
    *@return string[] List of error messages, each as a string.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

}

