<?php

namespace App\Http\Controllers;

use App\Models\BeritaAcaraIbadahModel;
use App\Models\IbadahModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM,SAD,MLJ,PHM');
    }

    public function index(Request $request){
        $breadcrumb = (object)[
            'title' => 'Welcome!',
            'list'  => ['Home','Welcome']
        ];

        $activeMenu = 'dashboard';

        // Ambil filter hari, bulan, dan tahun dari request
        $hari = $request->input('hari'); // Bisa null jika tidak ada filter
        $bulan = $request->input('bulan'); // Bisa null jika tidak ada filter
        $tahun = $request->input('tahun'); // Bisa null jika tidak ada filter

        // Query data kehadiran dan persembahan
        $query = BeritaAcaraIbadahModel::with(['ibadah', 'persembahan.kategori']);

        // Tambahkan filter jika hari, bulan, dan tahun ada
        if ($tahun) {
            $query->whereHas('ibadah', function ($q) use ($hari, $bulan, $tahun) {
                $q->whereYear('tanggal', $tahun);

                if ($bulan) {
                    $q->whereMonth('tanggal', $bulan);
                }

                if ($hari) {
                    $q->whereDay('tanggal', $hari);
                }
            });
        }

        // Urutkan data berdasarkan tanggal ASC
        $query->orderBy(IbadahModel::select('tanggal')
            ->whereColumn('t_ibadah.ibadah_id', 't_berita_acara_ibadah.ibadah_id'), 'asc');

        // Ambil data
        // $data = $query->get()->map(function ($item) {
        //     return [
        //         'tanggal' => $item->ibadah->tanggal, // Ambil tanggal dari IbadahModel
        //         'jumlah_kehadiran' => $item->jumlah_kehadiran, // Ambil jumlah kehadiran
        //         'total_persembahan' => $item->persembahan->sum('total'), // Total persembahan
        //         'kategori_persembahan' => $item->persembahan->groupBy('kategori.kategori_persembahan_nama') // Kelompokkan berdasarkan kategori
        //             ->map(function ($group) {
        //                 return $group->sum('total'); // Hitung total per kategori
        //             }),
        //     ];
        // });

        // Ambil data sekali untuk semua pemrosesan
        $beritaAcaraItems = $query->get();

        // Ambil data untuk grafik kehadiran dan persembahan per tanggal
        $data = $beritaAcaraItems->map(function ($item) {
            return [
                'tanggal' => $item->ibadah->tanggal, // Ambil tanggal dari IbadahModel
                'jumlah_kehadiran' => $item->jumlah_kehadiran, // Ambil jumlah kehadiran
                'total_persembahan' => $item->persembahan->sum('total'), // Total persembahan
                // Kategori persembahan per item tidak lagi diproses di sini untuk chart agregat
            ];
        });

        // Hitung total persembahan per kategori untuk semua data yang difilter
        $totalKategoriPersembahan = $beritaAcaraItems->flatMap(function ($item) {
            return $item->persembahan; // Dapatkan semua item persembahan
        })->groupBy('kategori.kategori_persembahan_nama') // Kelompokkan berdasarkan nama kategori
        ->map(function ($group) {
            return $group->sum('total'); // Jumlahkan total untuk setiap kategori
        });

        // Distribusi persembahan berdasarkan minggu
        $persembahanMinggu = $query->get()->groupBy(function ($item) {
            return 'Minggu ke-' . ceil(date('j', strtotime($item->ibadah->tanggal)) / 7);
        })->map(function ($group) {
            return $group->flatMap(function ($item) {
                return $item->persembahan;
            })->sum('total');
        });

        return view('beranda.index', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'data' => $data, 'totalKategoriPersembahan' => $totalKategoriPersembahan, 'persembahanMinggu' => $persembahanMinggu, 'hari' => $hari,'bulan' => $bulan, 'tahun' => $tahun]);
    }

}
