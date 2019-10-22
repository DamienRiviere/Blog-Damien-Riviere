<?php

namespace App\Controller;

class AdminDashboardController extends Controller
{
    public function __construct()
    {   
        parent::__construct();
        $this->checkRole();
    }

    public function dashboard()
    {
        return $this->twig->display('admin/dashboard.html.twig');
    }
}
