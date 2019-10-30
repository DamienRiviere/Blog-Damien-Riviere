<?php

namespace App\Controller;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->checkRole();
        parent::__construct();
    }

    public function dashboard()
    {
        return $this->twig->display('admin/dashboard.html.twig');
    }
}
