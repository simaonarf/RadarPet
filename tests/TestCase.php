<?php

namespace Tests;

use PHPUnit\Framework\TestCase as FrameworkTestCase;

class TestCase extends FrameworkTestCase
{
    public function setUp(): void {}

    public function tearDown(): void
    {
        $file = '/var/www/database' . $_ENV['DB_NAME'];
        if (file_exists($file)) unlink($file);
    }
}
