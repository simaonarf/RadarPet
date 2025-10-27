<?php

namespace Tests;

use PHPUnit\Framework\TestCase;

abstract class ControllerTestCase extends TestCase
{
    protected function get(string $controllerName, string $action): string
    {
        ob_start();
        (new $controllerName())->$action();
        return ob_get_clean() ?: '';
    }
}
