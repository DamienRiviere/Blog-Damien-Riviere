<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;

class AdminDashboardController extends Controller
{

    private $postRepo;

    private $userRepo;

    private $commentRepo;

    private $roleRepo;

    private $categoryRepo;

    public function __construct()
    {
        $this->checkRole();
        $this->postRepo = new PostRepository();
        $this->userRepo = new UserRepository();
        $this->commentRepo = new CommentRepository();
        $this->roleRepo = new RoleRepository();
        $this->categoryRepo = new CategoryRepository();
        parent::__construct();
    }

    public function dashboard()
    {
        return $this->twig->display('admin/dashboard.html.twig', [
            "countPosts" => $this->postRepo->findCountPosts(),
            "countUsers" => $this->userRepo->findCountUsers(),
            "countCommentsNotPublicated" => $this->commentRepo->findCountCommentsNotPublicated(),
            "countCommentsReported" => $this->commentRepo->findCountCommentsReported(),
            "countCommentsModerated" => $this->commentRepo->findCountCommentsModerated(),
            "countRoles" => $this->roleRepo->findCountRole(),
            "countCategories" => $this->categoryRepo->findCountCategories(),
            "lastCommentsReported" => $this->commentRepo->findLastFiveCommentsReported()
        ]);
    }
}
