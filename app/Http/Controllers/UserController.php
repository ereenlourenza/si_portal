<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:SAD');
    }

    //Pengelolaan Pengguna User
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Pengguna',
            'list' => ['Pengelolaan Pengguna', 'Pengguna']
        ];

        $page =(object)[
            'title' => 'Daftar pengguna yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; //set menu yang sedang aktif

        $level = LevelModel::all(); //ambil data level untuk filter level
        
        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level,'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);

    }

    //Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request){
        $users = UserModel::select('user_id', 'username', 'name', 'level_id') ->with('level');

        //Filter data user berdasarkan level_id
        if($request->level_id){
            $users->where('level_id', $request->level_id);
        }
        
        return DataTables::of($users)
            ->addIndexColumn() // menambahkan kolom index / no urut (default name kolom: DT_RowIndex)
            ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-pengguna/user/' . $user->user_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-pengguna/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-pengguna/user/'.$user->user_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah user
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Pengguna',
            'list' => ['Pengelolaan Pengguna', 'Pengguna', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah pengguna baru'
        ];

        $level = LevelModel::all(); //ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; //set menu yang sedang aktif

        return view('user.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan data user baru
    public function store(Request $request){
        $newUser = $request->validate([
            'level_id'      => 'required|integer',          //level_id harus diisi dan berupa angka
            'username'      => 'required|string|min:3|unique:t_user,username',  
            'name'          => 'required|string|max:100',   //name harus diisi, berupa string, dan maksimal 100 karakter                     
            'password'      => 'required|min:5',            //password harus diisi dan minimal 5 karakter                      
        ]);

        // Hash password sebelum menyimpan ke database
        $newUser['password'] = Hash::make($newUser['password']);

        UserModel::create($newUser);

        return redirect('/pengelolaan-pengguna/user')->with('success', 'Data pengguna berhasil disimpan');
    }

    //Menampilkan detail
    public function show(string $id){
        $user = UserModel::with('level')->find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Pengguna',
            'list'  => ['Pengelolaan Pengguna', 'Pengguna', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail pengguna'
        ];

        $activeMenu = 'user'; //set menu yang sedang aktif

        return view('user.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit user
    public function edit(string $id){
        $user = UserModel::find($id);
        $level = LevelModel::all();

        $breadcrumb = (object)[
            'title' => 'Edit Pengguna',
            'list'  => ['Pengelolaan Pengguna', 'Pengguna', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit pengguna'
        ];

        $activeMenu = 'user'; //set menu yang sedang aktif

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'user' => $user,'level' => $level, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data user
    public function update(Request $request, string $id){
        $request->validate([
            'level_id'      => 'required|integer',          //level_id harus diisi dan berupa angka
            'username'      => 'required|string|min:3|unique:t_user,username,'.$id.',user_id',
            'name'          => 'required|string|max:100',   //name harus diisi, berupa string, dan maksimal 100 karakter
            'password'      => 'nullable|min:5',            //password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
        ]);
        
        UserModel::find($id)->update([
            'level_id'      => $request->level_id,
            'username'      => $request->username,
            'name'          => $request->name,
            'password'      => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
        ]);

        return redirect('/pengelolaan-pengguna/user')->with('success', 'Data user berhasil diubah');
    }

    //Menghapus data user
    public function destroy(string $id){
        $check = UserModel::find($id);
        if(!$check){        //untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
            return redirect('/pengelolaan-pengguna/user')->with('error', 'Data pengguna tidak ditemukan');
        }

        try{
            UserModel::destroy($id); //Hapus data level

            return redirect('/pengelolaan-pengguna/user')->with('success', 'Data pengguna berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/pengelolaan-pengguna/user')->with('error', 'Data pengguna gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
