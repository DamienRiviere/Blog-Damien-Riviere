<?php

namespace App\Helpers;

use App\Model\Category;
use App\Repository\CategoryRepository;
use Cocur\Slugify\Slugify;

class CategoryHelpers
{
    private $categoryRepo;

    private $slugify;

    public function __construct()
    {
        $this->categoryRepo = new CategoryRepository();
        $this->slugify = new Slugify();
    }

    public function newCategory()
    {
        $category = new Category();

        $category
            ->setName($_POST['name'])
            ->setStyle($_POST['style'])
            ->setSlug($this->slugify->slugify($_POST['name']));

        $this->categoryRepo->createCategory($category);

        header('Location: /admin/categories?created=1');
    }

    public function editCategory(int $id)
    {
        $category = $this->categoryRepo->find($id);

        $category
            ->setName($_POST['name'])
            ->setStyle($_POST['style'])
            ->setSlug($this->slugify->slugify($_POST['name']));

        $this->categoryRepo->updateCategory($category);

        header('Location: /admin/categories?edit=1');
    }
}
