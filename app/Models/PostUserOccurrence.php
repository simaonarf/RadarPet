<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\Model;
use Lib\Validations;

class PostUserOccurrence extends Model
{
    protected static string $table = 'post_user_occurrences';
    protected static array $columns = ['user_id', 'post_id', 'location', 'description', 'created_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('user_id', $this);
        Validations::notEmpty('post_id', $this);

        Validations::uniqueness(['user_id', 'post_id'], $this);
    }
}