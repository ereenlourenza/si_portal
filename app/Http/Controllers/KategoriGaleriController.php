<?php

namespace App\Http\Controllers;

use App\Models\KategoriGaleriModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriGaleriController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Kategori Galeri',
            'list' => ['Pengelolaan Informasi','Kategori Galeri']
        ];

        $page =(object)[
            'title' => 'Daftar kategori galeri yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategorigaleri'; //set menu yang sedang aktif

        $kategorigaleri = KategoriGaleriModel::all(); //ambil data level untuk filter level

        return view('kategorigaleri.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategorigaleri' => $kategorigaleri, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request){
        $kategorigaleris = KategoriGaleriModel::select('kategorigaleri_id', 'kategorigaleri_kode', 'kategorigaleri_nama');

        //Filter data level berdasarkan level_id
        if($request->kategorigaleri_id){
            $kategorigaleris->where('kategorigaleri_id', $request->kategorigaleri_id);
        }
        
        return DataTables::of($kategorigaleris)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($kategorigaleri) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/kategorigaleri/' . $kategorigaleri->kategorigaleri_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/kategorigaleri/' . $kategorigaleri->kategorigaleri_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/kategorigaleri/'.$kategorigaleri->kategorigaleri_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah tataibadah $tataibadah
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Kategori Galeri',
            'list' => ['Pengelolaan Informasi', 'Kategori Galeri', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah kategori galeri baru'
        ];

        $kategorigaleri = KategoriGaleriModel::all(); //ambil data kategorigaleri untuk ditampilkan di form
        
        $activeMenu = 'kategorigaleri'; //set menu yang sedang aktif

        return view('kategorigaleri.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategorigaleri' => $kategorigaleri, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    // //Menyimpan data level baru
    public function store(Request $request){
        $validatedData = $request->validate([
            //judul harus diisi, berupa string, minimal 3 karakter, maksimal 10 karakter, dan bernilai unik di tabel m_level kolom judul
            'kategorigaleri_kode' => 'required|string|min:3|max:10|unique:t_kategorigaleri,kategorigaleri_kode',
            'kategorigaleri_nama' => 'required|string|max:50'
        ]);
        
        try{
    
            // Simpan data ke database
            KategoriGaleriModel::create([
                'kategorigaleri_kode'  => $request->kategorigaleri_kode,
                'kategorigaleri_nama'      => $request->kategorigaleri_nama
                ]);
    
            return redirect('pengelolaan-informasi/kategorigaleri')->with('success_kategorigaleri', 'Data kategori galeri berhasil disimpan');
        } catch(\Exception $e){
            return redirect('pengelolaan-informasi/kategorigaleri')->with('error_kategorigaleri', 'Terjadi kesalahan saat menyimpan data: ' );
        }
    }

    //Menampilkan Lihat
    public function show(string $id){
        $kategorigaleri = KategoriGaleriModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Kategori Galeri',
            'list'  => ['Pengelolaan Informasi', 'Kategori Galeri', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat kategori galeri'
        ];

        $activeMenu = 'kategorigaleri'; //set menu yang sedang aktif

        return view('kategorigaleri.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategorigaleri' => $kategorigaleri, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit tataibadah
    public function edit(string $id){
        $kategorigaleri = KategoriGaleriModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Ubah Kategori Galeri',
            'list'  => ['Pengelolaan Informasi', 'Kategori Galeri', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah kategori galeri'
        ];

        $activeMenu = 'kategorigaleri'; //set menu yang sedang aktif

        return view('kategorigaleri.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategorigaleri' => $kategorigaleri, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            //level kode harus diisi, berupa string, minimal 3 karakter, maksimal 10 karakter
            //dan bernilai unik di tabel m_level kolom level_kode kecuali untuk level dengan id yang sedang diedit
            'kategorigaleri_kode' => 'required|string|min:3|max:10|unique:t_kategorigaleri,kategorigaleri_kode,'.$id.',kategorigaleri_id',
            'kategorigaleri_nama' => 'required|string|max:50'
        ]);

        try{
            
            KategoriGaleriModel::find($id)->update([
                'kategorigaleri_kode'  => $request->kategorigaleri_kode,
                'kategorigaleri_nama'  => $request->kategorigaleri_nama,
            ]);

            return redirect('pengelolaan-informasi/kategorigaleri')->with('success_kategorigaleri', 'Data Kategori galeri berhasil diubah');
        }catch(\Exception $e){
            return redirect('pengelolaan-informasi/kategorigaleri')->with('error_kategorigaleri', 'Terjadi kesalahan saat mengubah data: ' );
        }    
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = KategoriGaleriModel::find($id);
        if(!$check){        //untuk mengecek apakah data Kategori galeri dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-informasi/kategorigaleri')->with('error_kategorigaleri', 'Data Kategori galeri tidak ditemukan');
        }

        try{
            KategoriGaleriModel::destroy($id); //Hapus data Kategori galeri

            return redirect('pengelolaan-informasi/kategorigaleri')->with('success_kategorigaleri', 'Data Kategori galeri berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-informasi/kategorigaleri')->with('error_kategorigaleri', 'Data Kategori galeri gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
