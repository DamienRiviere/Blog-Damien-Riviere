<?php

namespace App\Controller;

class AdminDashboardController extends Controller
{

    public function dashboard()
    {
        $this->twig->display('admin/dashboard.html.twig');
    }
}
