<?php

require __DIR__ . '/../../config/bootstrap.php';

use Database\Populate\UsersPopulate;
use Database\Populate\PostsPopulate;

UsersPopulate::populate();
PostsPopulate::populate();
