<?php

namespace App\Controller;

use App\Helpers\CategoryHelpers;
use App\Repository\CategoryRepository;

class AdminCategoryController extends Controller
{

    private $categoryRepo;

    private $categoryHelpers;

    public function __construct()
    {
        $this->checkRole();
        $this->categoryRepo = new CategoryRepository();
        $this->categoryHelpers = new CategoryHelpers();
        parent::__construct();
    }

    public function categories()
    {
        $this->twig->display('admin/category/index.html.twig', [
            'categories'    => $this->categoryRepo->all()
        ]);
    }

    public function showNew()
    {
        $this->twig->display('admin/category/new.html.twig');
    }

    public function new()
    {
        $this->categoryHelpers->newCategory();
    }

    public function showEdit(int $id)
    {
        $this->twig->display('admin/category/edit.html.twig', [
            'category' => $this->categoryRepo->find($id)
        ]);
    }

    public function edit(int $id)
    {
        $this->categoryHelpers->editCategory($id);
    }

    public function delete(int $id)
    {
        $this->categoryRepo->deleteCategory($id);
        header('Location: /admin/categories?delete=1');
    }
}
