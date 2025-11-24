<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function test_post_validations_fail_when_required_fields_missing(): void
    {
        $post = new Post([]);
        $post->validates();

        $errors = $post->getErrors();

        $this->assertArrayHasKey('title', $errors);
        $this->assertArrayHasKey('description', $errors);
        $this->assertArrayHasKey('url_photo', $errors);
        $this->assertArrayHasKey('user_id', $errors);
    }
}
