<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Welcome!',
            'list'  => ['Home','Welcome']
        ];

        $activeMenu = 'dashboard';

        return view('beranda.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
}
