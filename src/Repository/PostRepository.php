<?php

namespace App\Repository;

use App\Model\Post;

class PostRepository extends Repository {

    protected $repository = "post";

    protected $class = Post::class;
}