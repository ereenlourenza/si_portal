<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitasModel;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:SAD');
    }

    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Log Aktivitas',
            'list' => ['Pengelolaan Pengguna','Log']
        ];

        $page =(object)[
            'title' => 'Daftar log yang terdaftar dalam sistem'
        ];

        $activeMenu = 'log'; //set menu yang sedang aktif

        $logs = LogAktivitasModel::with('user')->latest()->paginate(20);
        return view('log.index', compact('logs','breadcrumb', 'page', 'activeMenu'));
    }
}
