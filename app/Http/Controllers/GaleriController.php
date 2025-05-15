<?php

namespace App\Http\Controllers;

use App\Models\GaleriModel;
use App\Models\KategoriGaleriModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class GaleriController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Galeri',
            'list' => ['Pengelolaan Informasi','Galeri']
        ];

        $page =(object)[
            'title' => 'Daftar galeri yang terdaftar dalam sistem'
        ];

        $activeMenu = 'galeri'; //set menu yang sedang aktif

        $kategorigaleri = KategoriGaleriModel::all(); //ambil data level untuk filter level

        return view('galeri.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategorigaleri' => $kategorigaleri, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request){
        $galeris = GaleriModel::select('galeri_id', 'kategorigaleri_id', 'judul', 'deskripsi', 'foto') ->with('kategorigaleri');
        
        //Filter data user berdasarkan level_id
        if($request->kategorigaleri_id){
            $galeris->where('kategorigaleri_id', $request->kategorigaleri_id);
        }
        
        return DataTables::of($galeris)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('aksi', function ($galeri) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/galeri/' . $galeri->galeri_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/galeri/' . $galeri->galeri_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/galeri/'.$galeri->galeri_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah galeri $galeri
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Galeri',
            'list' => ['Pengelolaan Informasi', 'Galeri', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah data galeri baru'
        ];

        $kategorigaleri = KategoriGaleriModel::all(); //ambil data galeri untuk ditampilkan di form
        
        $activeMenu = 'galeri'; //set menu yang sedang aktif

        return view('galeri.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategorigaleri' => $kategorigaleri, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    // //Menyimpan data level baru
    public function store(Request $request){
        $validatedData = $request->validate([
            'judul' => 'required|string|min:3|max:50|unique:t_galeri,judul',
            'deskripsi' => 'nullable|string',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:20048',
            'kategorigaleri_id' => 'required|integer',
        ]);
        
        try{
            if ($request->hasFile('foto')) {
                // Simpan foto ke dalam storage
                $foto = $request->file('foto');
                $fotoName = Str::random(10) . '-' . $foto->getClientOriginalName(); 
                $foto->storeAs('public/images/galeri', $fotoName);
        
                // Simpan nama foto ke database
                $validatedData['foto'] = $fotoName;
            }
    
            // Simpan data ke database
            GaleriModel::create([
                'kategorigaleri_id'  => $validatedData['kategorigaleri_id'],
                'judul'  => $validatedData['judul'],
                'deskripsi' => $validatedData['deskripsi'],
                'foto' => $validatedData['foto']
            ]);

            // log aktivitas
            simpanLogAktivitas('Galeri', 'store', "Menambahkan data: \n"
                . "$request->judul"
            );
    
            return redirect('pengelolaan-informasi/galeri')->with('success_galeri', 'Data galeri berhasil disimpan');
        } catch(\Exception $e){
            return redirect('pengelolaan-informasi/galeri')->with('error_galeri', 'Terjadi kesalahan saat menyimpan data: ');
        }
    }

    //Menampilkan Lihat
    public function show(string $id){
        $galeri = GaleriModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Galeri',
            'list'  => ['Pengelolaan Informasi', 'Galeri', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat galeri'
        ];

        $activeMenu = 'galeri'; //set menu yang sedang aktif

        return view('galeri.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'galeri' => $galeri, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit galeri
    public function edit(string $id){
        $galeri = GaleriModel::find($id);
        $kategorigaleri = KategoriGaleriModel::all();

        $breadcrumb = (object)[
            'title' => 'Ubah Galeri',
            'list'  => ['Pengelolaan Informasi', 'Galeri', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah galeri'
        ];

        $activeMenu = 'galeri'; //set menu yang sedang aktif

        return view('galeri.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'galeri' => $galeri, 'kategorigaleri' => $kategorigaleri, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            'judul' => 'required|string|min:3|max:50|unique:t_galeri,judul,'.$id.',galeri_id',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:20048',
            'kategorigaleri_id' => 'required|integer',
        ]);

        try{
            // Ambil data tata ibadah dari database
            $galeri = GaleriModel::find($id);

            // Jika ada file baru yang diunggah, simpan file dan hapus file lama
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($galeri->foto) {
                    Storage::delete('public/images/galeri/' . $galeri->foto);
                }

                // Simpan foto baru
                $foto = $request->file('foto');
                $fotoName = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('public/images/galeri', $fotoName); // Simpan ke storage
            } else {
                // Gunakan foto lama jika tidak ada foto baru yang diunggah
                $fotoName = $galeri->foto;
            }
            
            GaleriModel::find($id)->update([
                'kategorigaleri_id'  => $request->kategorigaleri_id,
                'judul'  => $request->judul,
                'deskripsi'  => $request->deskripsi,
                'foto'  => $fotoName,
            ]);

            // log aktivitas
            simpanLogAktivitas('Galeri', 'update', "Mengubah data: \n"
                . "$galeri->judul"
            );

            return redirect('pengelolaan-informasi/galeri')->with('success_galeri', 'Data galeri berhasil diubah');
        }catch(\Exception $e){
            return redirect('pengelolaan-informasi/galeri')->with('error_galeri', 'Terjadi kesalahan saat mengubah data: ');
        }    
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = GaleriModel::find($id);
        if(!$check){        //untuk mengecek apakah data tata ibadah dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-informasi/galeri')->with('error_galeri', 'Data galeri tidak ditemukan');
        }

        try{
            // Hapus file foto jika ada
            if ($check->foto && Storage::exists('public/images/galeri/' . $check->foto)) {
                Storage::delete('public/images/galeri/' . $check->foto);
            }
            
            GaleriModel::destroy($id); //Hapus data galeri

            // log aktivitas
            simpanLogAktivitas('Galeri', 'destroy', "Menghapus data: \n"
                . "$check->judul"
            );

            return redirect('pengelolaan-informasi/galeri')->with('success_galeri', 'Data galeri berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-informasi/galeri')->with('error_galeri', 'Data galeri gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
