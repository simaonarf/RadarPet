<?php

namespace Tests\Acceptance;

use Core\Database\Database;
use Core\Env\EnvLoader;
use Tests\Support\AcceptanceTester;

class BaseAcceptanceCest
{
    public function _before(AcceptanceTester $page): void
    {
        EnvLoader::init();
        Database::create();
        Database::migrate();

        \Database\Populate\UsersPopulate::populate();
        \Database\Populate\PostsPopulate::populate();
    }

    public function _after(AcceptanceTester $page): void
    {
        Database::drop();
    }
}
