<?php

namespace Tests\Unit\Access;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class AuthAccessTest extends TestCase
{
    public function test_guest_cannot_access_posts_new(): void
    {
        $client = new Client([
            'allow_redirects' => false,
            'base_uri' => 'http://web:8080',
        ]);

        $resp = $client->get('/posts/new');

        $this->assertSame(302, $resp->getStatusCode());
        $this->assertSame('/login', $resp->getHeaderLine('Location'));
    }

    public function test_guest_cannot_post_create(): void
    {
        $client = new \GuzzleHttp\Client([
            'allow_redirects' => false,
            'base_uri' => 'http://web:8080',
        ]);

        $resp = $client->post('/posts', [
            'form_params' => [
                'post[title]' => 'x',
                'post[description]' => 'x',
                'post[url_photo]' => '/x.jpg',
            ],
        ]);

        $this->assertSame(302, $resp->getStatusCode());
        $this->assertSame('/login', $resp->getHeaderLine('Location'));
    }

    public function test_guest_cannot_access_posts_edit(): void
    {
        $client = new \GuzzleHttp\Client([
            'allow_redirects' => false,
            'base_uri' => 'http://web:8080',
        ]);

        $resp = $client->get('/posts/1/edit');

        $this->assertSame(302, $resp->getStatusCode());
        $this->assertSame('/login', $resp->getHeaderLine('Location'));
    }

    public function test_guest_cannot_put_update(): void
    {
        $client = new \GuzzleHttp\Client([
            'allow_redirects' => false,
            'base_uri' => 'http://web:8080',
        ]);

        $resp = $client->request('PUT', '/posts/1', [
            'form_params' => [
                'post[title]' => 'x',
                'post[description]' => 'y',
                'post[url_photo]' => '/z.jpg',
            ],
        ]);

        $this->assertSame(302, $resp->getStatusCode());
        $this->assertSame('/login', $resp->getHeaderLine('Location'));
    }

    public function test_guest_cannot_delete_post(): void
    {
        $client = new \GuzzleHttp\Client([
            'allow_redirects' => false,
            'base_uri' => 'http://web:8080',
        ]);

        $resp = $client->request('DELETE', '/', [
            'query' => ['id' => 1],
        ]);

        $this->assertSame(302, $resp->getStatusCode());
        $this->assertSame('/login', $resp->getHeaderLine('Location'));
    }
}
