<?php

namespace App\Http\Controllers;

use App\Models\TataIbadahModel;
use App\Models\UserModel;
use App\Models\WartaJemaatModel;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    //Pengelolaan Informasi Tata Ibadah
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Dokumen',
            'list' => ['Pengelolaan Informasi','Dokumen']
        ];

        $pageTataIbadah =(object)[
            'title' => 'Daftar tata ibadah yang terdaftar dalam sistem'
        ];
        $pageWartaJemaat =(object)[
            'title' => 'Daftar warta jemaat yang terdaftar dalam sistem'
        ];

        $activeMenu = 'dokumen'; //set menu yang sedang aktif

        $tataibadah = TataIbadahModel::all(); //ambil data tataibadah untuk filter tataibadah
        $wartajemaat = WartaJemaatModel::all(); //ambil data tataibadah untuk filter tataibadah
        // dd($tataibadah);
        return view('dokumen.index', ['breadcrumb' => $breadcrumb, 'pageTataIbadah' => $pageTataIbadah, 'pageWartaJemaat' => $pageWartaJemaat, 'tataibadah' => $tataibadah, 'wartajemaat' => $wartajemaat, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }
}
