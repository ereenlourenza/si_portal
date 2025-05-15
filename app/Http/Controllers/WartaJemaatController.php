<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\WartaJemaatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class WartaJemaatController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request){
        $wartajemaats = WartaJemaatModel::select('wartajemaat_id', 'tanggal', 'judul', 'deskripsi', 'file');

        // Filter berdasarkan tahun dan bulan dari kolom 'tanggal'
        if ($request->tanggal) {
            $dateParts = explode('-', $request->tanggal); // Memisahkan input yyyy-mm
            if (count($dateParts) == 2) {
                $year = $dateParts[0];
                $month = $dateParts[1];

                $wartajemaats->whereYear('tanggal', $year)
                            ->whereMonth('tanggal', $month);
            }
        }
        
        return DataTables::of($wartajemaats)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('file', function ($wartajemaat) {
                if ($wartajemaat->file) {
                    return '<a href="' . Storage::url('dokumen/wartajemaat/' . $wartajemaat->file) . '" target="_blank" class="btn btn-info btn-sm"><i class="nav-icon far fa-eye"></i></a>';
                }
                return '<span class="text-muted">Tidak Ada File</span>';
            })
            ->addColumn('aksi', function ($wartajemaat) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/wartajemaat/' . $wartajemaat->wartajemaat_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/wartajemaat/' . $wartajemaat->wartajemaat_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/wartajemaat/'.$wartajemaat->wartajemaat_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['file','aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah wartajemaat $wartajemaat
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Warta Jemaat',
            'list' => ['Pengelolaan Informasi', 'Warta Jemaat', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah warta jemaat baru'
        ];

        $wartajemaat = WartaJemaatModel::all(); //ambil data wartajemaat untuk ditampilkan di form
        
        $activeMenu = 'dokumen'; //set menu yang sedang aktif

        return view('wartajemaat.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'wartajemaat' => $wartajemaat, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    // //Menyimpan data level baru
    public function store(Request $request){
        $validatedData = $request->validate([
            'tanggal' => 'required|date_format:Y-m-d',
            'judul' => 'required|string|min:3|max:50',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|mimes:pdf'
        ]);
        try{
            if ($request->hasFile('file')) {
                // Simpan file ke dalam storage
                $file = $request->file('file');
                $fileName = Str::random(10) . '-' . $file->getClientOriginalName(); 
                $file->storeAs('public/dokumen/wartajemaat', $fileName);
        
                // Simpan nama file ke database
                $validatedData['file'] = $fileName;
            }

            // Simpan data ke database
            WartaJemaatModel::create([
                'tanggal'  => $validatedData['tanggal'],
                'judul'  => $validatedData['judul'],
                'deskripsi' => $validatedData['deskripsi'],
                'file' => $validatedData['file'] ?? null, // Jika tidak ada file, simpan NULL
            ]);

            // log aktivitas
            simpanLogAktivitas('Warta Jemaat', 'store', "Menambahkan data: \n"
                . "{$request->judul}\n"
            );

            return redirect('pengelolaan-informasi/wartajemaat')->with('success_wartajemaat', 'Data warta jemaat berhasil disimpan');
        }catch(\Exception $e){
            return redirect('pengelolaan-informasi/wartajemaat')->with('error_wartajemaat', 'Terjadi kesalahan saat menyimpan data. ');
        }
    }

    //Menampilkan Lihat
    public function show(string $id){
        $wartajemaat = WartaJemaatModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Warta Jemaat',
            'list'  => ['Pengelolaan Informasi', 'Warta Jemaat', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat warta jemaat'
        ];

        $activeMenu = 'dokumen'; //set menu yang sedang aktif

        return view('wartajemaat.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'wartajemaat' => $wartajemaat, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit wartajemaat
    public function edit(string $id){
        $wartajemaat = WartaJemaatModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Ubah Warta Jemaat',
            'list'  => ['Pengelolaan Informasi', 'Warta Jemaat', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah Warta Jemaat'
        ];

        $activeMenu = 'dokumen'; //set menu yang sedang aktif

        return view('wartajemaat.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'wartajemaat' => $wartajemaat, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            'tanggal' => 'required|date_format:Y-m-d',
            'judul' => 'required|string|min:3|max:50',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|mimes:pdf|max:2048'
        ]);

        try{
            // Ambil data warta jemaat dari database
            $wartajemaat = WartaJemaatModel::find($id);

            // Jika ada file baru yang diunggah, simpan file dan hapus file lama
            if ($request->hasFile('file')) {
                // Hapus file lama jika ada
                if ($wartajemaat->file) {
                    Storage::delete('public/dokumen/wartajemaat/' . $wartajemaat->file);
                }

                // Simpan file baru
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/dokumen/wartajemaat', $fileName); // Simpan ke storage
            } else {
                // Gunakan file lama jika tidak ada file baru yang diunggah
                $fileName = $wartajemaat->file;
            }
            
            WartaJemaatModel::find($id)->update([
                'tanggal'  => $request->tanggal,
                'judul'  => $request->judul,
                'deskripsi'  => $request->deskripsi,
                'file'  => $fileName,
            ]);

            // log aktivitas
            simpanLogAktivitas('Warta Jemaat', 'update', "Mengubah data: \n"
                . "{$request->judul}\n"
            );

            return redirect('pengelolaan-informasi/wartajemaat')->with('success_wartajemaat', 'Data warta jemaat berhasil diubah');
        }catch(\Exception $e){
            return redirect('pengelolaan-informasi/wartajemaat')->with('error_wartajemaat', 'Terjadi kesalahan saat mengubah data: ');
        }    
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = WartaJemaatModel::find($id);
        if(!$check){        //untuk mengecek apakah data warta jemaat dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-informasi/wartajemaat')->with('error_wartajemaat', 'Data warta jemaat tidak ditemukan');
        }

        try{
            // Hapus file foto jika ada
            if ($check->file && Storage::exists('public/dokumen/wartajemaat/' . $check->file)) {
                Storage::delete('public/dokumen/wartajemaat/' . $check->file);
            }

            WartaJemaatModel::destroy($id); //Hapus data warta jemaat

            // log aktivitas
            simpanLogAktivitas('Warta Jemaat', 'destroy', "Menghapus data: \n"
                . "{$check->judul}\n"
            );

            return redirect('pengelolaan-informasi/wartajemaat')->with('success_wartajemaat', 'Data warta jemaat berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-informasi/wartajemaat')->with('error_wartajemaat', 'Data warta jemaat gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
