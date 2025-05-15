<?php

namespace App\Http\Controllers;

use App\Models\KategoriIbadahModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KategoriIbadahController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Kategori Ibadah',
            'list' => ['Pengelolaan Informasi','Kategori Ibadah']
        ];

        $page =(object)[
            'title' => 'Daftar kategori ibadah yang terdaftar dalam sistem'
        ];

        $activeMenu = 'kategoriibadah'; //set menu yang sedang aktif

        $kategoriibadah = KategoriIbadahModel::all(); //ambil data level untuk filter level

        return view('kategoriibadah.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoriibadah' => $kategoriibadah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request){
        $kategoriibadahs = KategoriIbadahModel::select('kategoriibadah_id', 'kategoriibadah_kode', 'kategoriibadah_nama');

        //Filter data level berdasarkan level_id
        if($request->kategoriibadah_id){
            $kategoriibadahs->where('kategoriibadah_id', $request->kategoriibadah_id);
        }
        
        return DataTables::of($kategoriibadahs)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($kategoriibadah) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/kategoriibadah/' . $kategoriibadah->kategoriibadah_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/kategoriibadah/' . $kategoriibadah->kategoriibadah_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/kategoriibadah/'.$kategoriibadah->kategoriibadah_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah tataibadah $tataibadah
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Kategori Ibadah',
            'list' => ['Pengelolaan Informasi', 'Kategori Ibadah', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah kategori ibadah baru'
        ];

        $kategoriibadah = KategoriIbadahModel::all(); //ambil data kategoriibadah untuk ditampilkan di form
        
        $activeMenu = 'kategoriibadah'; //set menu yang sedang aktif

        return view('kategoriibadah.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoriibadah' => $kategoriibadah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    // //Menyimpan data level baru
    public function store(Request $request){
        $validatedData = $request->validate([
            'kategoriibadah_kode' => 'required|string|min:3|max:10|unique:t_kategoriibadah,kategoriibadah_kode',
            'kategoriibadah_nama' => 'required|string|max:50'
        ]);
        
        try{
    
            // Simpan data ke database
            KategoriIbadahModel::create([
                'kategoriibadah_kode'  => $request->kategoriibadah_kode,
                'kategoriibadah_nama'      => $request->kategoriibadah_nama
                ]);
            
            // log aktivitas
            simpanLogAktivitas('Kategori Ibadah', 'store', "Menambahkan data: \n"
                . "{$request->kategoriibadah_nama}\n"
            );
    
            return redirect('pengelolaan-informasi/kategoriibadah')->with('success_kategoriibadah', 'Data Kategori Ibadah berhasil disimpan');
        } catch(\Exception $e){
            return redirect('pengelolaan-informasi/kategoriibadah')->with('error_kategoriibadah', 'Terjadi kesalahan saat menyimpan data: ');
        }
    }

    //Menampilkan Lihat
    public function show(string $id){
        $kategoriibadah = kategoriibadahModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Kategori Ibadah',
            'list'  => ['Pengelolaan Informasi', 'Kategori Ibadah', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat Kategori Ibadah'
        ];

        $activeMenu = 'kategoriibadah'; //set menu yang sedang aktif

        return view('kategoriibadah.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoriibadah' => $kategoriibadah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit tataibadah
    public function edit(string $id){
        $kategoriibadah = KategoriIbadahModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Ubah Kategori Ibadah',
            'list'  => ['Pengelolaan Informasi', 'Kategori Ibadah', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah Kategori Ibadah'
        ];

        $activeMenu = 'kategoriibadah'; //set menu yang sedang aktif

        return view('kategoriibadah.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoriibadah' => $kategoriibadah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            //level kode harus diisi, berupa string, minimal 3 karakter, maksimal 10 karakter
            //dan bernilai unik di tabel m_level kolom level_kode kecuali untuk level dengan id yang sedang diedit
            'kategoriibadah_kode' => 'required|string|min:3|max:10|unique:t_kategoriibadah,kategoriibadah_kode,'.$id.',kategoriibadah_id',
            'kategoriibadah_nama' => 'required|string|max:50'
        ]);

        try{
            
            kategoriibadahModel::find($id)->update([
                'kategoriibadah_kode'  => $request->kategoriibadah_kode,
                'kategoriibadah_nama'  => $request->kategoriibadah_nama,
            ]);

            // log aktivitas
            simpanLogAktivitas('Kategori Ibadah', 'update', "Mengubah data: \n"
                . "{$request->kategoriibadah_nama}\n"
            );

            return redirect('pengelolaan-informasi/kategoriibadah')->with('success_kategoriibadah', 'Data Kategori Ibadah berhasil diubah');
        }catch(\Exception $e){
            return redirect('pengelolaan-informasi/kategoriibadah')->with('error_kategoriibadah', 'Terjadi kesalahan saat mengubah data: ');
        }    
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = kategoriibadahModel::find($id);
        if(!$check){        //untuk mengecek apakah data Kategori Ibadah dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-informasi/kategoriibadah')->with('error_kategoriibadah', 'Data Kategori Ibadah tidak ditemukan');
        }

        try{
            kategoriibadahModel::destroy($id); //Hapus data Kategori Ibadah

            // log aktivitas
            simpanLogAktivitas('Kategori Ibadah', 'destroy', "Menghapus data: \n"
                . "{$check->kategoriibadah_nama}\n"
            );

            return redirect('pengelolaan-informasi/kategoriibadah')->with('success_kategoriibadah', 'Data Kategori Ibadah berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-informasi/kategoriibadah')->with('error_kategoriibadah', 'Data Kategori Ibadah gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
