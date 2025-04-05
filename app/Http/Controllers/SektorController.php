<?php

namespace App\Http\Controllers;

use App\Models\PelayanModel;
use App\Models\SektorModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SektorController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    //Pengelolaan Pengguna User
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Sektor',
            'list' => ['Pengelolaan Sektor', 'Sektor']
        ];

        $page =(object)[
            'title' => 'Daftar sektor yang terdaftar dalam sistem'
        ];

        $activeMenu = 'sektor'; //set menu yang sedang aktif

        $pelayan = PelayanModel::all();
        
        return view('sektor.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'pelayan' => $pelayan,'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);

    }

    //Ambil data user dalam bentuk json untuk datatables
    public function list(){
        $sektors = SektorModel::select('sektor_id', 'sektor_nama', 'deskripsi', 'pelayan_id') ->with('pelayan');
        
        return DataTables::of($sektors)
            ->addIndexColumn() // menambahkan kolom index / no urut (default name kolom: DT_RowIndex)
            ->addColumn('aksi', function ($sektor) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/sektor/' . $sektor->sektor_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/sektor/' . $sektor->sektor_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/sektor/'.$sektor->sektor_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah user
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Sektor',
            'list' => ['Pengelolaan Sektor', 'Sektor', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah sektor baru'
        ];

        // $pelayan = PelayanModel::all(); //ambil data level untuk ditampilkan di form
        $pelayan = PelayanModel::whereIn('kategoripelayan_id', [3, 4])->get();
        $activeMenu = 'sektor'; //set menu yang sedang aktif

        return view('sektor.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'pelayan' => $pelayan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan data user baru
    public function store(Request $request){
        $request->validate([
            'sektor_nama'       => 'required|string|min:3|max:50|unique:t_sektor,sektor_nama',  
            'deskripsi'         => 'required|string',    
            'pelayan_id'            => 'required|integer',                                 
        ]);


        SektorModel::create([
            'sektor_nama'   => $request->sektor_nama,  
            'deskripsi'     => $request->deskripsi,    
            'pelayan_id'        => $request->pelayan_id,   
        ]);

        return redirect('/pengelolaan-informasi/sektor')->with('success', 'Data sektor berhasil disimpan');
    }

    //Menampilkan detail
    public function show(string $id){
        $sektor = SektorModel::with('pelayan')->find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Sektor',
            'list'  => ['Pengelolaan Sektor', 'Sektor', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail sektor'
        ];

        $activeMenu = 'sektor'; //set menu yang sedang aktif

        return view('sektor.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'sektor' => $sektor, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit user
    public function edit(string $id){
        $sektor = SektorModel::find($id);
        $pelayan = PelayanModel::all();

        $breadcrumb = (object)[
            'title' => 'Edit Sektor',
            'list'  => ['Pengelolaan Sektor', 'Sektor', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit sektor'
        ];

        $activeMenu = 'sektor'; //set menu yang sedang aktif

        return view('sektor.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'sektor' => $sektor, 'pelayan' => $pelayan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data user
    public function update(Request $request, string $id){
        $request->validate([
            'sektor_nama'       => 'required|string|min:3|max:50|unique:t_sektor,sektor_nama,'.$id.',sektor_id',  
            'deskripsi'         => 'required|string',    
            'pelayan_id'            => 'required|integer',  
        ]);
        
        SektorModel::find($id)->update([
            'sektor_nama'      => $request->sektor_nama,
            'deskripsi'          => $request->deskripsi,
            'pelayan_id'      => $request->korsek 
        ]);

        return redirect('/pengelolaan-infromasi/sektor')->with('success', 'Data sektor berhasil diubah');
    }

    //Menghapus data user
    public function destroy(string $id){
        $check = SektorModel::find($id);
        if(!$check){        //untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
            return redirect('/pengelolaan-informasi/sektor')->with('error', 'Data sektor tidak ditemukan');
        }

        try{
            SektorModel::destroy($id); //Hapus data level

            return redirect('/pengelolaan-informasi/sektor')->with('success', 'Data sektor berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/pengelolaan-informasi/sektor')->with('error', 'Data sektor gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
