<?php

namespace App\Http\Controllers;

use App\Models\PelayanModel;
use App\Models\PHMJModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PHMJController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    //Menampilkan halaman form tambah user
    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Informasi',
            'list' => ['Pengelolaan Informasi', 'PHMJ', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah PHMJ baru'
        ];

        $currentYear = Carbon::now()->year;

        // Ambil ID pelayan yang sudah digunakan
        $pemakaiPelayan = DB::table('t_phmj')->pluck('pelayan_id')->toArray();

        // Ambil pelayan yang belum digunakan & masih aktif
        $pelayan = PelayanModel::whereNotIn('pelayan_id', $pemakaiPelayan)
            ->whereIn('kategoripelayan_id', [1, 3, 4])
            ->where('masa_jabatan_mulai', '<=', $currentYear)
            ->where('masa_jabatan_selesai', '>=', $currentYear)
            ->get();

        $activeMenu = 'pelayan';

        return view('phmj.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'pemakaiPelayan' => $pemakaiPelayan,
            'pelayan' => $pelayan,
            'activeMenu' => $activeMenu,
            'notifUser' => UserModel::all()
        ]);
    }


    //Menyimpan data user baru
    public function store(Request $request){
        $request->validate([
            'jabatan' => 'required|string|min:3|max:50',
            'periode_mulai' => 'required|date_format:Y',
            'periode_selesai' => 'required|date_format:Y|after_or_equal:periode_mulai',
            'pelayan_id' => 'required|integer|unique:t_phmj,pelayan_id',
        ]);

        $pelayan = PelayanModel::find($request->pelayan_id);
        
        try{
    
            // Simpan data ke database
            PHMJModel::create([
                'pelayan_id' => $request->pelayan_id,
                'jabatan' => $request->jabatan,
                'periode_mulai' => $request->periode_mulai,
                'periode_selesai' => $request->periode_selesai,
            ]);

            // log aktivitas
            simpanLogAktivitas('PHMJ', 'store', "Menambahkan data: \n"
                . "Nama: {$pelayan->nama}\n"
                . "Jabatan: {$request->jabatan}\n"
            );
    
            return redirect('pengelolaan-informasi/pelayan')->with('success_pelayan', 'Data PHMJ berhasil disimpan');
        } catch(\Exception $e){
            return redirect('pengelolaan-informasi/pelayan')->with('error_pelayan', 'Terjadi kesalahan saat menyimpan data: ' );
        }
    }

    //Menampilkan halaman form edit pelayan
    public function edit(string $id){
        $phmj = PHMJModel::find($id);
        $pelayan = PelayanModel::whereIn('kategoripelayan_id', [3, 4])->get();

        $pemakaiPelayan = DB::table('t_phmj')->pluck('pelayan_id')->toArray(); // Ambil pelayan yang sudah digunakan


        $breadcrumb = (object)[
            'title' => 'Ubah PHMJ',
            'list'  => ['Pengelolaan Informasi', 'PHMJ', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah PHMJ'
        ];

        $activeMenu = 'pelayan'; //set menu yang sedang aktif

        return view('phmj.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'pelayan' => $pelayan, 'phmj' => $phmj, 'pemakaiPelayan' => $pemakaiPelayan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            'jabatan' => 'required|string|min:3|max:50',
            'periode_mulai' => 'required|date_format:Y',
            'periode_selesai' => 'required|date_format:Y|after_or_equal:periode_mulai',
            'pelayan_id' => 'required|integer|unique:t_phmj,pelayan_id,'.$id.',phmj_id',
        ]);

        $pelayan = PelayanModel::find($request->pelayan_id);

        // dd($request->all());

        try{
            
            PHMJModel::find($id)->update([
                'pelayan_id'  => $request->pelayan_id,
                'jabatan'  => $request->jabatan,
                'periode_mulai'  => $request->periode_mulai,
                'periode_selesai'  => $request->periode_selesai,
            ]);

            // log aktivitas
            simpanLogAktivitas('PHMJ', 'update', "Mengubah data: \n"
                . "Nama: {$pelayan->nama}\n"
                . "Jabatan: {$request->jabatan}\n"
            );

            return redirect('pengelolaan-informasi/pelayan')->with('success_pelayan', 'Data PHMJ berhasil diubah');
        }catch(\Exception $e){
            return redirect('pengelolaan-informasi/pelayan')->with('error_pelayan', 'Terjadi kesalahan saat mengubah data: ' . $e->getMessage());
        }    
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = PHMJModel::find($id);
        $pelayan = PelayanModel::find($check->pelayan_id);

        if(!$check){        //untuk mengecek apakah data tata ibadah dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-informasi/pelayan')->with('error_pelayan', 'Data PHMJ tidak ditemukan');
        }

        try{
            PHMJModel::destroy($id); //Hapus data pelayan

            // log aktivitas
            simpanLogAktivitas('PHMJ', 'destroy', "Menghapus data: \n"
                . "Nama: {$pelayan->nama}\n"
                . "Jabatan: {$check->jabatan}\n"
            );
    
            return redirect('pengelolaan-informasi/pelayan')->with('success_pelayan', 'Data PHMJ berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-informasi/pelayan')->with('error_pelayan', 'Data PHMJ gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
