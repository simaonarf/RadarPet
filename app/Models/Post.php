<?php

namespace App\Models;

use Lib\Validations;
use Core\Database\ActiveRecord\Model;
use Core\Database\ActiveRecord\HasMany; 

/**
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $description
 * @property string $register_date
 *
 */
class Post extends Model
{
    protected static string $table = 'posts';
    protected static array $columns = ['user_id', 'title', 'description','register_date'];

    public function validates(): void
    {
        Validations::notEmpty('title', $this);
        Validations::notEmpty('description', $this);
        Validations::notEmpty('user_id', $this);
    }

    public function user(): ?User
    {
        return User::findBy(['id' => $this->user_id])[0] ?? null;
    }

     public function photos(): HasMany
    {
     return $this->hasMany(PostPhoto::class, 'post_id');
    }

    public function firstPhotoUrl(): ?string
    {
        $photos = $this->photos()->get();

        if (empty($photos)) {
            return '/assets/img/no-photo.png';
        }

        usort($photos, fn($a, $b) => $a->id <=> $b->id);

        return $photos[0]->path;
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
