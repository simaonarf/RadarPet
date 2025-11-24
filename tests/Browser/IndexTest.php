<?php

namespace Tests\Browser;

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function test_should_redirect_if_not_authenticated(): void
    {
        $page = file_get_contents('http://web/admin/dashboard');
        $this->assertTrue(http_response_code(302));

        $location = $http_response_header[10];
        $this->assertEquals('Location: /login', $location);
    }
}
