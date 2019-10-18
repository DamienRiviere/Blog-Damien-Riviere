<?php

namespace App\Repository;

use PDO;
use App\Model\Category;
use Exception;

class CategoryRepository extends Repository
{
    
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

    public function updateCategory(Category $category): void
    {
        $this->update([
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
            'style' => $category->getStyle()
        ], $category->getId());
    }

    /**
     * Hydrate a post with his categories
     *
     * @param array $posts
     * @return void
     */
    public function hydratePostsWithCategories(?array $posts): void
    {
        $postsById = [];
        foreach ($posts as $post) {
            $postsById[$post->getId()] = $post;
        }

        // Get post's categories with $postsById's key
        $query = self::getDb()->prepare('
            SELECT c.*, pc.post_id
            FROM post_category pc
            JOIN category c ON c.id = pc.category_id
            WHERE pc.post_id 
            IN (' . implode(',', array_keys($postsById)) . ')
        ');

        $query->execute();
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $categories = $query->fetchAll();

        // Add all categories with his post
        foreach ($categories as $category) {
            $postsById[$category->getPostId()]->addCategory($category);
        }
    }

    public function deleteCategory(int $id)
    {
        $query = self::getDb()->prepare("
            DELETE category, post_category
            FROM category
            LEFT JOIN post_category ON category.id = post_category.category_id
            WHERE category.id = ?
        ");
        $query->execute([$id]);
    }
}
