<?php

namespace App\Controller;

use App\Model\Category;
use App\Repository\CategoryRepository;
use Cocur\Slugify\Slugify;

class AdminCategoryController extends Controller
{

    private $category;

    private $slugify;

    public function __construct()
    {
        parent::__construct();
        $this->checkRole();
        $this->category = new CategoryRepository();
        $this->slugify = new Slugify();
    }

    public function categories()
    {
        $this->twig->display('admin/category/index.html.twig', [
            'categories'    => $this->category->all()
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

            $this->category->createCategory($category);

            header('Location: /admin/categories?created=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs");
        }
    }

    public function showEdit(int $id)
    {
        $this->twig->display('admin/category/edit.html.twig', [
            'category' => $this->category->find($id)
        ]);
    }

    public function edit(int $id)
    {
        if (!in_array("", $_POST)) {
            $category = $this->category->find($id);

            $category
                ->setName($_POST['name'])
                ->setStyle($_POST['style'])
                ->setSlug($this->slugify->slugify($_POST['name']));

            $this->category->updateCategory($category);

            header('Location: /admin/categories?edit=1');
        } else {
            throw new \Exception("Veuillez remplir tous les champs !");
        }
    }

    public function delete(int $id)
    {
        $this->category->deleteCategory($id);
        header('Location: /admin/categories?delete=1');
    }
}
