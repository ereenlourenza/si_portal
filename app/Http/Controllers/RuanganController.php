<?php

namespace App\Http\Controllers;

use App\Models\RuanganModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;


class RuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Ruangan',
            'list' => ['Pengelolaan Informasi','Ruangan']
        ];

        $page =(object)[
            'title' => 'Daftar ruangan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'ruangan'; //set menu yang sedang aktif

        return view('ruangan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(){
        $ruangans = RuanganModel::select('ruangan_id','ruangan_nama','deskripsi','foto');
        
        return DataTables::of($ruangans)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('foto', function ($ruangan) {
                if ($ruangan->foto) {
                    return '<img src="' . Storage::url('images/ruangan/' . $ruangan->foto) . '" alt="Foto" width="100">';
                }
                return '<span class="text-muted">Tidak Ada Foto</span>';
            })
            ->addColumn('aksi', function ($ruangan) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/ruangan/' . $ruangan->ruangan_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/ruangan/' . $ruangan->ruangan_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/ruangan/'.$ruangan->ruangan_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['foto','aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah persembahan $persembahan
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Ruangan',
            'list' => ['Pengelolaan Informasi', 'Ruangan', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah data ruangan baru'
        ];
        
        $activeMenu = 'ruangan'; //set menu yang sedang aktif

        return view('ruangan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    // //Menyimpan data level baru
    public function store(Request $request){
        $validatedData = $request->validate([
            'ruangan_nama' => 'required|string|min:3|max:50|unique:t_ruangan,ruangan_nama',
            'deskripsi' => 'required|string',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        try{
            if ($request->hasFile('foto')) {
                // Simpan foto ke dalam storage
                $foto = $request->file('foto');
                $fotoName = Str::random(10) . '-' . $foto->getClientOriginalName(); 
                $foto->storeAs('public/images/ruangan', $fotoName);
        
                // Simpan nama foto ke database
                $validatedData['foto'] = $fotoName;
            }
    
            // Simpan data ke database
            RuanganModel::create([
                'ruangan_nama'  => $validatedData['ruangan_nama'],
                'deskripsi'  => $validatedData['deskripsi'],
                'foto' => $validatedData['foto'] ?? null,
            ]);
    
            return redirect('pengelolaan-informasi/ruangan')->with('success_ruangan', 'Data ruangan berhasil disimpan');
        } catch(\Exception $e){
            return redirect('pengelolaan-informasi/ruangan')->with('error_ruangan', 'Terjadi kesalahan saat menyimpan data: ');
        }
    }

    //Menampilkan Lihat
    public function show(string $id){
        $ruangan = RuanganModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Ruangan',
            'list'  => ['Pengelolaan Informasi', 'Ruangan', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat ruangan'
        ];

        $activeMenu = 'ruangan'; //set menu yang sedang aktif

        return view('ruangan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'ruangan' => $ruangan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit persembahan
    public function edit(string $id){
        $ruangan = RuanganModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Ubah Ruangan',
            'list'  => ['Pengelolaan Informasi', 'Ruangan', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah ruangan'
        ];

        $activeMenu = 'ruangan'; //set menu yang sedang aktif

        return view('ruangan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'ruangan' => $ruangan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            'ruangan_nama' => 'required|string|min:3|max:50|unique:t_ruangan,ruangan_nama,'.$id.',ruangan_id',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try{
            // Ambil data tata ibadah dari database
            $ruangan = RuanganModel::find($id);

            // Jika ada file baru yang diunggah, simpan file dan hapus file lama
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($ruangan->foto) {
                    Storage::delete('public/images/ruangan/' . $ruangan->foto);
                }

                // Simpan foto baru
                $foto = $request->file('foto');
                $fotoName = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('public/images/ruangan', $fotoName); // Simpan ke storage
            } else {
                // Gunakan foto lama jika tidak ada foto baru yang diunggah
                $fotoName = $ruangan->foto;
            }
            
            RuanganModel::find($id)->update([
                'ruangan_nama'  => $request->ruangan_nama,
                'deskripsi'  => $request->deskripsi,
                'foto'  => $fotoName,
            ]);

            return redirect('pengelolaan-informasi/ruangan')->with('success_ruangan', 'Data ruangan berhasil diubah');
        }catch(\Exception $e){
            return redirect('pengelolaan-informasi/ruangan')->with('error_ruangan', 'Terjadi kesalahan saat mengubah data: ');
        }    
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = RuanganModel::find($id);
        if(!$check){        //untuk mengecek apakah data tata ibadah dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-informasi/ruangan')->with('error_ruangan', 'Data ruangan tidak ditemukan');
        }

        try{
            // Hapus file foto jika ada
            if ($check->foto && Storage::exists('public/images/ruangan/' . $check->foto)) {
                Storage::delete('public/images/ruangan/' . $check->foto);
            }

            RuanganModel::destroy($id); //Hapus data ruangan

            return redirect('pengelolaan-informasi/ruangan')->with('success_ruangan', 'Data ruangan berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-informasi/ruangan')->with('error_ruangan', 'Data ruangan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
