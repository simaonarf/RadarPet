<?php

namespace Tests\Browser;

use Tests\TestCase;

class LogoutTest extends TestCase
{
    public function test_should_logout_successfully(): void
    {
        // 1) Faz login para obter o cookie de sessão
        $ctxLogin = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query([
                    'user' => [
                        'email'    => 'admin@demo.com',
                        'password' => 'admin123',
                    ],
                ]),
                'ignore_errors' => true,
            ]
        ]);

        @file_get_contents('http://web/login', false, $ctxLogin);

        // Captura cookie de sessão
        $cookie = '';
        foreach ($http_response_header as $h) {
            if (stripos($h, 'Set-Cookie:') === 0) {
                $cookie = strtok(trim(substr($h, 11)), ';');
                break;
            }
        }

        // 2) Faz logout com o cookie
        $ctxLogout = stream_context_create([
            'http' => [
                'method'  => 'GET',
                'header'  => "Cookie: {$cookie}\r\n",
                'ignore_errors' => true,
            ]
        ]);

        @file_get_contents('http://web/logout', false, $ctxLogout);

        // 3) Verifica se foi redirecionado para /login
        $status = $http_response_header[0] ?? '';
        $location = '';
        foreach ($http_response_header as $h) {
            if (stripos($h, 'Location:') === 0) {
                $location = trim(substr($h, 9));
                break;
            }
        }

        $this->assertEquals('HTTP/1.1 302 Found', $status);
        $this->assertStringContainsString('/login', $location);
    }
}
