<?php

namespace App\Helpers;

use App\Model\Category;
use App\Repository\CategoryRepository;
use Cocur\Slugify\Slugify;

class CategoryHelpers extends Helpers
{
    private $categoryRepo;

    private $slugify;

    public function __construct()
    {
        $this->categoryRepo = new CategoryRepository();
        $this->slugify = new Slugify();
    }

    public function newCategory(array $data)
    {
        $category = new Category();

        $category
            ->setName($data['name'])
            ->setStyle($data['style'])
            ->setSlug($this->slugify->slugify($data['name']));

        $this->categoryRepo->createCategory($category);

        header('Location: /admin/categories?created=1');
    }

    public function editCategory(int $id, array $data)
    {
        $category = $this->categoryRepo->find($id);

        $category
            ->setName($data['name'])
            ->setStyle($data['style'])
            ->setSlug($this->slugify->slugify($data['name']));

        $this->categoryRepo->updateCategory($category);

        header('Location: /admin/categories?edit=1');
    }
}
