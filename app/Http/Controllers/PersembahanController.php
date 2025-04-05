<?php

namespace App\Http\Controllers;

use App\Models\PersembahanModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class PersembahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Persembahan',
            'list' => ['Pengelolaan Informasi','Persembahan']
        ];

        $page =(object)[
            'title' => 'Daftar persembahan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'persembahan'; //set menu yang sedang aktif

        return view('persembahan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(){
        $persembahans = PersembahanModel::select('persembahan_id','persembahan_nama','nomor_rekening','atas_nama','barcode');
        
        return DataTables::of($persembahans)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('barcode', function ($persembahan) {
                if ($persembahan->barcode) {
                    return '<img src="' . Storage::url('images/barcode/' . $persembahan->barcode) . '" alt="Barcode" width="100">';
                }
                return '<span class="text-muted">Tidak Ada Barcode</span>';
            })
            ->addColumn('aksi', function ($persembahan) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/persembahan/' . $persembahan->persembahan_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/persembahan/' . $persembahan->persembahan_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/persembahan/'.$persembahan->persembahan_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['barcode','aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah persembahan $persembahan
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Persembahan',
            'list' => ['Pengelolaan Informasi', 'Persembahan', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah data persembahan baru'
        ];
        
        $activeMenu = 'persembahan'; //set menu yang sedang aktif

        return view('persembahan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    // //Menyimpan data level baru
    public function store(Request $request){
        $validatedData = $request->validate([
            'persembahan_nama' => 'required|string|min:3|max:50|unique:t_persembahan,persembahan_nama',
            'nomor_rekening' => 'required|string|max:30',
            'atas_nama' => 'required|string|max:100',
            'barcode' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        
        try{
            if ($request->hasFile('barcode')) {
                // Simpan barcode ke dalam storage
                $barcode = $request->file('barcode');
                $barcodeName = Str::random(10) . '-' . $barcode->getClientOriginalName(); 
                $barcode->storeAs('public/images/barcode', $barcodeName);
        
                // Simpan nama barcode ke database
                $validatedData['barcode'] = $barcodeName;
            }
    
            // Simpan data ke database
            PersembahanModel::create([
                'persembahan_nama'  => $validatedData['persembahan_nama'],
                'nomor_rekening'  => $validatedData['nomor_rekening'],
                'atas_nama' => $validatedData['atas_nama'],
                'barcode' => $validatedData['barcode'] ?? null,
            ]);
    
            return redirect('pengelolaan-informasi/persembahan')->with('success_persembahan', 'Data persembahan berhasil disimpan');
        } catch(\Exception $e){
            return redirect('pengelolaan-informasi/persembahan')->with('error_persembahan', 'Terjadi kesalahan saat menyimpan data: ');
        }
    }

    //Menampilkan Lihat
    public function show(string $id){
        $persembahan = PersembahanModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Persembahan',
            'list'  => ['Pengelolaan Informasi', 'Persembahan', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat persembahan'
        ];

        $activeMenu = 'persembahan'; //set menu yang sedang aktif

        return view('persembahan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'persembahan' => $persembahan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit persembahan
    public function edit(string $id){
        $persembahan = PersembahanModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Ubah Persembahan',
            'list'  => ['Pengelolaan Informasi', 'Persembahan', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah persembahan'
        ];

        $activeMenu = 'persembahan'; //set menu yang sedang aktif

        return view('persembahan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'persembahan' => $persembahan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            'persembahan_nama' => 'required|string|min:3|max:50|unique:t_persembahan,persembahan_nama,'.$id.',persembahan_id',
            'nomor_rekening' => 'required|string|max:30',
            'atas_nama' => 'required|string|max:100',
            'barcode' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try{
            // Ambil data tata ibadah dari database
            $persembahan = PersembahanModel::find($id);

            // Jika ada file baru yang diunggah, simpan file dan hapus file lama
            if ($request->hasFile('barcode')) {
                // Hapus barcode lama jika ada
                if ($persembahan->barcode) {
                    Storage::delete('public/images/barcode/' . $persembahan->barcode);
                }

                // Simpan barcode baru
                $barcode = $request->file('barcode');
                $barcodeName = time() . '_' . $barcode->getClientOriginalName();
                $barcode->storeAs('public/images/barcode', $barcodeName); // Simpan ke storage
            } else {
                // Gunakan barcode lama jika tidak ada barcode baru yang diunggah
                $barcodeName = $persembahan->barcode;
            }
            
            PersembahanModel::find($id)->update([
                'persembahan_nama'  => $request->persembahan_nama,
                'nomor_rekening'  => $request->nomor_rekening,
                'atas_nama'  => $request->atas_nama,
                'barcode'  => $barcodeName,
            ]);

            return redirect('pengelolaan-informasi/persembahan')->with('success_persembahan', 'Data persembahan berhasil diubah');
        }catch(\Exception $e){
            return redirect('pengelolaan-informasi/persembahan')->with('error_persembahan', 'Terjadi kesalahan saat mengubah data: ');
        }    
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = PersembahanModel::find($id);
        if(!$check){        //untuk mengecek apakah data tata ibadah dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-informasi/persembahan')->with('error_persembahan', 'Data persembahan tidak ditemukan');
        }

        try{
            // Hapus file foto jika ada
            if ($check->barcode && Storage::exists('public/images/barcode/' . $check->barcode)) {
                Storage::delete('public/images/barcode/' . $check->barcode);
            }

            PersembahanModel::destroy($id); //Hapus data persembahan

            return redirect('pengelolaan-informasi/persembahan')->with('success_persembahan', 'Data persembahan berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-informasi/persembahan')->with('error_persembahan', 'Data persembahan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
