<?php

namespace App\Http\Controllers;

use App\Models\KategoriPersembahanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriPersembahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:MLJ,PHM,ADM');
    }

    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Kategori Persembahan',
            'list' => ['Pengelolaan Informasi','Kategori Persembahan']
        ];

        $page =(object)[
            'title' => 'Daftar kategori persembahan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'beritaacara-kategoripersembahan'; //set menu yang sedang aktif

        $kategoripersembahan = KategoriPersembahanModel::all(); //ambil data level untuk filter level

        return view('kategoripersembahan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoripersembahan' => $kategoripersembahan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request){
        $kategoripersembahans = KategoriPersembahanModel::select('kategori_persembahan_id', 'kategori_persembahan_nama');

        //Filter data level berdasarkan level_id
        if($request->kategori_persembahan_id){
            $kategoripersembahans->where('kategori_persembahan_id', $request->kategori_persembahan_id);
        }
        
        return DataTables::of($kategoripersembahans)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($kategori_persembahan) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-berita-acara/kategoripersembahan/' . $kategori_persembahan->kategori_persembahan_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-berita-acara/kategoripersembahan/' . $kategori_persembahan->kategori_persembahan_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-berita-acara/kategoripersembahan/'.$kategori_persembahan->kategori_persembahan_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah tataibadah $tataibadah
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Kategori Persembahan',
            'list' => ['Pengelolaan Informasi', 'Kategori Persembahan', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah kategori persembahan baru'
        ];

        $kategori_persembahan = KategoriPersembahanModel::all(); //ambil data kategori_persembahan untuk ditampilkan di form
        
        $activeMenu = 'beritaacara-kategoripersembahan'; //set menu yang sedang aktif

        return view('kategoripersembahan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori_persembahan' => $kategori_persembahan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    // //Menyimpan data level baru
    public function store(Request $request){
        $validatedData = $request->validate([
            'kategori_persembahan_nama' => 'required|string|max:50'
        ]);
        
        try{
    
            // Simpan data ke database
            KategoriPersembahanModel::create([
                'kategori_persembahan_nama'      => $request->kategori_persembahan_nama
                ]);
    
            return redirect('pengelolaan-berita-acara/kategoripersembahan')->with('success_kategoripersembahan', 'Data Kategori Persembahan berhasil disimpan');
        } catch(\Exception $e){
            return redirect('pengelolaan-berita-acara/kategoripersembahan')->with('error_kategoripersembahan', 'Terjadi kesalahan saat menyimpan data: ');
        }
    }

    //Menampilkan Lihat
    public function show(string $id){
        $kategori_persembahan = KategoriPersembahanModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Kategori Persembahan',
            'list'  => ['Pengelolaan Informasi', 'Kategori Persembahan', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat Kategori Persembahan',
        ];

        $activeMenu = 'beritaacara-kategoripersembahan'; //set menu yang sedang aktif

        return view('kategoripersembahan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori_persembahan' => $kategori_persembahan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit tataibadah
    public function edit(string $id){
        $kategori_persembahan = KategoriPersembahanModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Ubah Kategori Persembahan',
            'list'  => ['Pengelolaan Informasi', 'Kategori Persembahan', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah Kategori Persembahan'
        ];

        $activeMenu = 'beritaacara-kategoripersembahan'; //set menu yang sedang aktif

        return view('kategoripersembahan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori_persembahan' => $kategori_persembahan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            'kategori_persembahan_nama' => 'required|string|max:50'
        ]);

        try{
            
            KategoriPersembahanModel::find($id)->update([
                'kategori_persembahan_nama'  => $request->kategori_persembahan_nama,
            ]);

            return redirect('pengelolaan-berita-acara/kategoripersembahan')->with('success_kategoripersembahan', 'Data Kategori Persembahan berhasil diubah');
        }catch(\Exception $e){
            return redirect('pengelolaan-berita-acara/kategoripersembahan')->with('error_kategoripersembahan', 'Terjadi kesalahan saat mengubah data: ');
        }    
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = KategoriPersembahanModel::find($id);
        if(!$check){        //untuk mengecek apakah data Kategori Ibadah dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-berita-acara/kategoripersembahan')->with('error_kategoripersembahan', 'Data Kategori Persembahan tidak ditemukan');
        }

        try{
            KategoriPersembahanModel::destroy($id); //Hapus data Kategori Persembahan

            return redirect('pengelolaan-berita-acara/kategoripersembahan')->with('success_kategoripersembahan', 'Data Kategori Persembahan berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-berita-acara/kategoripersembahan')->with('error_kategoripersembahan', 'Data Kategori Persembahan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
