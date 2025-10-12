<?php

namespace Tests\Browser;

use Tests\TestCase;

class AdminAuthenticateTest extends TestCase
{
    public function test_should_redirect_if_not_authenticated(): void
    {
        $page = file_get_contents('http://web/admin/dashboard');
        $this->assertTrue(http_response_code(302));

        $statuscode = $http_response_header[0];
        $location = $http_response_header[10];

        $this->assertEquals('HTTP/1.1 302 Found', $statuscode);
        $this->assertEquals('Location: /login', $location);
    }
}
