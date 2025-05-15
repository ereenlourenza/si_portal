<?php

namespace App\Http\Controllers;

use App\Models\KategoriPelayanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class KategoriPelayanController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Kategori Pelayan',
            'list' => ['Pengelolaan Informasi','Kategori Pelayan']
        ];

        $page =(object)[
            'title' => 'Daftar kategori pelayan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategoripelayan'; //set menu yang sedang aktif

        $kategoripelayan = KategoriPelayanModel::all(); //ambil data level untuk filter level

        return view('kategoripelayan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoripelayan' => $kategoripelayan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request){
        $kategoripelayans = KategoriPelayanModel::select('kategoripelayan_id', 'kategoripelayan_kode', 'kategoripelayan_nama');

        //Filter data level berdasarkan level_id
        if($request->kategoripelayan_id){
            $kategoripelayans->where('kategoripelayan_id', $request->kategoripelayan_id);
        }
        
        return DataTables::of($kategoripelayans)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($kategoripelayan) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/kategoripelayan/' . $kategoripelayan->kategoripelayan_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/kategoripelayan/' . $kategoripelayan->kategoripelayan_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/kategoripelayan/'.$kategoripelayan->kategoripelayan_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah tataibadah $tataibadah
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Kategori Pelayan',
            'list' => ['Pengelolaan Informasi', 'Kategori Pelayan', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah kategori pelayan baru'
        ];

        $kategoripelayan = KategoriPelayanModel::all(); //ambil data kategoripelayan untuk ditampilkan di form
        
        $activeMenu = 'kategoripelayan'; //set menu yang sedang aktif

        return view('kategoripelayan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoripelayan' => $kategoripelayan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    // //Menyimpan data level baru
    public function store(Request $request){
        $validatedData = $request->validate([
            //judul harus diisi, berupa string, minimal 3 karakter, maksimal 10 karakter, dan bernilai unik di tabel m_level kolom judul
            'kategoripelayan_kode' => 'required|string|min:3|max:10|unique:t_kategoripelayan,kategoripelayan_kode',
            'kategoripelayan_nama' => 'required|string|max:50'
        ]);
        
        try{
    
            // Simpan data ke database
            KategoriPelayanModel::create([
                'kategoripelayan_kode'  => $request->kategoripelayan_kode,
                'kategoripelayan_nama'      => $request->kategoripelayan_nama
                ]);
            
            // log aktivitas
            simpanLogAktivitas('Kategori Pelayan', 'store', "Menambahkan data: \n"
                . "{$request->kategoripelayan_nama}\n"
            );
    
            return redirect('pengelolaan-informasi/kategoripelayan')->with('success_kategoripelayan', 'Data kategori pelayan berhasil disimpan');
        } catch(\Exception $e){
            return redirect('pengelolaan-informasi/kategoripelayan')->with('error_kategoripelayan', 'Terjadi kesalahan saat menyimpan data: ');
        }
    }

    //Menampilkan Lihat
    public function show(string $id){
        $kategoripelayan = KategoriPelayanModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Kategori Pelayan',
            'list'  => ['Pengelolaan Informasi', 'Kategori Pelayan', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat kategori pelayan'
        ];

        $activeMenu = 'kategoripelayan'; //set menu yang sedang aktif

        return view('kategoripelayan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoripelayan' => $kategoripelayan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit tataibadah
    public function edit(string $id){
        $kategoripelayan = KategoriPelayanModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Ubah Kategori Pelayan',
            'list'  => ['Pengelolaan Informasi', 'Kategori Pelayan', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah kategori pelayan'
        ];

        $activeMenu = 'kategoripelayan'; //set menu yang sedang aktif

        return view('kategoripelayan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoripelayan' => $kategoripelayan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            'kategoripelayan_kode' => 'required|string|min:3|max:10|unique:t_kategoripelayan,kategoripelayan_kode,'.$id.',kategoripelayan_id',
            'kategoripelayan_nama' => 'required|string|max:50'
        ]);

        try{
            
            KategoriPelayanModel::find($id)->update([
                'kategoripelayan_kode'  => $request->kategoripelayan_kode,
                'kategoripelayan_nama'  => $request->kategoripelayan_nama,
            ]);

            // log aktivitas
            simpanLogAktivitas('Kategori Pelayan', 'update', "Mengubah data: \n"
                . "{$request->kategoripelayan_nama}\n"
            );

            return redirect('pengelolaan-informasi/kategoripelayan')->with('success_kategoripelayan', 'Data Kategori Pelayan berhasil diubah');
        }catch(\Exception $e){
            return redirect('pengelolaan-informasi/kategoripelayan')->with('error_kategoripelayan', 'Terjadi kesalahan saat mengubah data: ');
        }    
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = KategoriPelayanModel::find($id);
        if(!$check){        //untuk mengecek apakah data Kategori Pelayan dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-informasi/kategoripelayan')->with('error_kategoripelayan', 'Data Kategori Pelayan tidak ditemukan');
        }

        try{
            KategoriPelayanModel::destroy($id); //Hapus data Kategori Pelayan

            // log aktivitas
            simpanLogAktivitas('Kategori Pelayan', 'destroy', "Menghapus data: \n"
                . "{$check->kategoripelayan_nama}\n"
            );

            return redirect('pengelolaan-informasi/kategoripelayan')->with('success_kategoripelayan', 'Data Kategori Pelayan berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-informasi/kategoripelayan')->with('error_kategoripelayan', 'Data Kategori Pelayan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
