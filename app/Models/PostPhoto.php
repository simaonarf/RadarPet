<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property int $post_id
 * @property string $path
 * @property string $register_date
 */
class PostPhoto extends Model
{
    protected static string $table = 'post_photos';
    protected static array $columns = ['post_id', 'path', 'register_date'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('post_id', $this);
        Validations::notEmpty('path', $this);
    }
}