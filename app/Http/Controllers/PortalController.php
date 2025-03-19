<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Portal!',
            'list'  => ['Home','Portal']
        ];

        $activeMenu = 'portal';

        return view('portal.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
}
