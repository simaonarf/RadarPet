<?php

namespace Tests\Browser;

use Tests\TestCase;

class SuccessfulLoginTest extends TestCase
{
    public function test_should_authenticate_successfully(): void
    {
        $ctx = stream_context_create([
            'http' => [
                'method'        => 'POST',
                'header'        => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content'       => http_build_query([
                    'user' => [
                        'email'    => 'admin@demo.com',
                        'password' => 'admin123',
                    ],
                ]),
                'ignore_errors' => true,
            ]
        ]);

        @file_get_contents('http://web/login', false, $ctx);

        $status = $http_response_header[0] ?? '';
        $location = '';
        foreach ($http_response_header as $h) {
            if (stripos($h, 'Location:') === 0) {
                $location = trim(substr($h, 9));
                break;
            }
        }

        // Sucesso = 302 para o dashboard
        $this->assertEquals('HTTP/1.1 302 Found', $status);
        $this->assertStringContainsString('/admin/dashboard', $location);
    }
}
