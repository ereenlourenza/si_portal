<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:SAD');
    }

    //Pengelolaan Pengguna Level
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Level',
            'list' => ['Pengelolaan Pengguna','Level']
        ];

        $page =(object)[
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level'; //set menu yang sedang aktif

        $level = LevelModel::all(); //ambil data level untuk filter level

        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request){
        $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');

        //Filter data level berdasarkan level_id
        if($request->level_id){
            $levels->where('level_id', $request->level_id);
        }
        
        return DataTables::of($levels)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($level) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-pengguna/level/' . $level->level_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-pengguna/level/' . $level->level_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-pengguna/level/'.$level->level_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah level
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Level',
            'list' => ['Pengelolaan Pengguna', 'Level', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah level baru'
        ];

        $level = LevelModel::all(); //ambil data level untuk ditampilkan di form
        
        $activeMenu = 'level'; //set menu yang sedang aktif

        return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan data level baru
    public function store(Request $request){
        $request->validate([
            'level_kode' => 'required|string|min:3|max:10|unique:t_level,level_kode',
            'level_nama' => 'required|string|max:50'
        ]);

        LevelModel::create([
            'level_kode'  => $request->level_kode,
            'level_nama'      => $request->level_nama
        ]);

        return redirect('pengelolaan-pengguna/level')->with('success', 'Data level berhasil disimpan');
    }

    //Menampilkan Lihat
    public function show(string $id){
        $level = LevelModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Level',
            'list'  => ['Pengelolaan Pengguna', 'Level', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat level'
        ];

        $activeMenu = 'level'; //set menu yang sedang aktif

        return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit level
    public function edit(string $id){
        $level = LevelModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Ubah Level',
            'list'  => ['Pengelolaan Pengguna', 'Level', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah Level'
        ];

        $activeMenu = 'level'; //set menu yang sedang aktif

        return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            'level_kode'  => 'required|string|min:3|max:10|unique:t_level,level_kode,'.$id.',level_id',
            'level_nama'  => 'required|string|max:50',   //nama harus diisi, berupa string, dan maksimal 50 karakter
        ]);
        
        LevelModel::find($id)->update([
            'level_kode'  => $request->level_kode,
            'level_nama'  => $request->level_nama,
        ]);

        return redirect('pengelolaan-pengguna/level')->with('success', 'Data level berhasil diubah');
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = LevelModel::find($id);
        if(!$check){        //untuk mengecek apakah data level dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-pengguna/level')->with('error', 'Data level tidak ditemukan');
        }

        try{
            LevelModel::destroy($id); //Hapus data level

            return redirect('pengelolaan-pengguna/level')->with('success', 'Data level berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-pengguna/level')->with('error', 'Data level gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
