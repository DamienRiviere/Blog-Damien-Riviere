<?php

namespace App\Repository;

use App\Model\Post;

class PostRepository extends Repository
{

    protected $repository = "post";

    protected $class = Post::class;

    public function createPost(Post $post): void 
    {
        $post = $this->create([
            'title' => $post->getTitle(),
            'introduction' => $post->getIntroduction(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s'),
            'cover_image' => $post->getCoverImage(),
            'slug' => $post->getSlug()
        ]);
    }
}
