<?php

namespace Tests\Browser;

use Tests\TestCase;

class IncorrectCredentialsTest extends TestCase
{
    public function test_should_fail_with_incorrect_credentials(): void
    {
        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query([
                    'email'    => 'usuario@invalido.com',
                    'password' => 'senhaerrada',
                ]),
                'ignore_errors' => true, // captura corpo mesmo com 302
            ]
        ]);

        @file_get_contents('http://web/login', false, $context);

        $status = $http_response_header[0] ?? '';
        $location = '';
        foreach ($http_response_header as $h) {
            if (stripos($h, 'Location:') === 0) {
                $location = trim(substr($h, 9));
                break;
            }
        }

        // Falha deve REDIRECIONAR de volta ao login (302)
        $this->assertEquals('HTTP/1.1 302 Found', $status);

        // Não pode mandar para o dashboard
        $this->assertStringNotContainsString('/admin/dashboard', $location);

        // Deve apontar para /login
        $this->assertStringContainsString('/login', $location);
    }
}
