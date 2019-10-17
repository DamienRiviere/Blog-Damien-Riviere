<?php

namespace App\Controller;

use App\Model\Category;
use App\Repository\CategoryRepository;
use Cocur\Slugify\Slugify;

class AdminCategoryController extends Controller
{

    private $categories;

    private $slugify;

    public function __construct()
    {
        parent::__construct();
        $this->categories = new CategoryRepository();
        $this->slugify = new Slugify();
    }

    public function categories()
    {
        $this->twig->display('admin/category/index.html.twig', [
            'categories'    => $this->categories->all()
        ]);
    }

    public function showNew()
    {
        $this->twig->display('admin/category/new.html.twig');
    }

    public function new()
    {
        if (!in_array("", $_POST)) {
            $category = new Category();

            $category
                ->setName($_POST['name'])
                ->setStyle($_POST['style'])
                ->setSlug($this->slugify->slugify($_POST['name']));

            $this->categories->createCategory($category);

            header('Location: /admin/categories?created=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs");
        }
    }

    public function showEdit(int $id)
    {
        $this->twig->display('admin/category/edit.html.twig', [
            'category' => $this->categories->find($id)
        ]);
    }

    public function edit(int $id)
    {
        if(!in_array("", $_POST)) {
            $category = $this->categories->find($id);

            $category
                ->setName($_POST['name'])
                ->setStyle($_POST['style'])
                ->setSlug($this->slugify->slugify($_POST['name']));

            $this->categories->updateCategory($category);

            header('Location: /admin/categories?edit=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs !");
        }
    }
}
