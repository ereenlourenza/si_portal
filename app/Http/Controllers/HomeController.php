<?php

namespace App\Http\Controllers;

use App\Models\SejarahModel;
use App\Models\SektorModel;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $jumlah_keluarga = SektorModel::sum('jumlah_jemaat');
        $jumlah_sektor = SektorModel::count();

        return view('global.home', ['jumlah_keluarga' => $jumlah_keluarga, 'jumlah_sektor' => $jumlah_sektor]);
    }

    public function sejarah()
    {
        $sejarah = SejarahModel::all(); // Ambil semua data sejarah

        return view('global.sejarah-gereja', ['sejarah' => $sejarah]);
    }

    public function sektor()
    {
        $sektor = SektorModel::all(); // Ambil semua data sejarah

        return view('global.wilayah-pelayanan', ['sektor' => $sektor]);
    }


}