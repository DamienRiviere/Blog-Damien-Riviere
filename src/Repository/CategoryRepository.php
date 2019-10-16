<?php

namespace App\Repository;

use PDO;
use App\Model\Category;

class CategoryRepository extends Repository {
    
    protected $repository = 'category';

    protected $class = Category::class;

    public function createCategory(Category $category): void
    {
        $id = $this->create([
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'style' => $category->getStyle()
        ]);
        $category->setId($id);
    }

    /**
     * Method to hydrate a post with his categories
     *
     * @param array $posts
     * @return void
     */
    public function hydratePosts(?array $posts): void
    {
        $postsById = [];
        foreach($posts as $post) {
            $postsById[$post->getId()] = $post;   
        }

        // Get post's categories with $postsById's key
        $categories = self::getDb()
            ->query('SELECT c.*, pc.post_id
                    FROM post_category pc
                    JOIN category c ON c.id = pc.category_id
                    WHERE pc.post_id IN ('. implode(',', array_keys($postsById)) . ')'
            )->fetchAll(PDO::FETCH_CLASS, $this->class);

        // Add all categories in $postsById
        foreach($categories as $category) {
            $postsById[$category->getPostId()]->addCategory($category);
        }
    }
}