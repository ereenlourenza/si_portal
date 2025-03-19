<?php

namespace App\Http\Controllers;

use App\Models\TataIbadahModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TataIbadahController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request){
        $tataibadahs = TataIbadahModel::select('tataibadah_id', 'tanggal', 'judul', 'deskripsi', 'file');

        // Filter berdasarkan tahun dan bulan dari kolom 'tanggal'
        if ($request->tanggal) {
            $dateParts = explode('-', $request->tanggal); // Memisahkan input yyyy-mm
            if (count($dateParts) == 2) {
                $year = $dateParts[0];
                $month = $dateParts[1];

                $tataibadahs->whereYear('tanggal', $year)
                            ->whereMonth('tanggal', $month);
            }
        }
        
        return DataTables::of($tataibadahs)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('file', function ($tataibadah) {
                if ($tataibadah->file) {
                    return '<a href="' . Storage::url('dokumen/tataibadah/' . $tataibadah->file) . '" target="_blank" class="btn btn-info btn-sm"><i class="nav-icon far fa-eye"></i></a>';
                }
                return '<span class="text-muted">Tidak Ada File</span>';
            })
            ->addColumn('aksi', function ($tataibadah) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/tataibadah/' . $tataibadah->tataibadah_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/tataibadah/' . $tataibadah->tataibadah_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/tataibadah/'.$tataibadah->tataibadah_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['file','aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah tataibadah $tataibadah
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Tata Ibadah',
            'list' => ['Pengelolaan Informasi', 'Tata Ibadah', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah tata ibadah baru'
        ];

        $tataibadah = TataIbadahModel::all(); //ambil data tataibadah untuk ditampilkan di form
        
        $activeMenu = 'dokumen'; //set menu yang sedang aktif

        return view('tataibadah.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'tataibadah' => $tataibadah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    // //Menyimpan data level baru
    public function store(Request $request){
        $validatedData = $request->validate([
            //judul harus diisi, berupa string, minimal 3 karakter, maksimal 10 karakter, dan bernilai unik di tabel m_level kolom judul
            'tanggal' => 'required|date_format:Y-m-d',
            'judul' => 'required|string|min:3|max:50|unique:t_tataibadah,judul',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|mimes:pdf|max:2048'
        ]);
        
        try{
            if ($request->hasFile('file')) {
                // Simpan file ke dalam storage
                $file = $request->file('file');
                $fileName = Str::random(10) . '-' . $file->getClientOriginalName(); 
                $file->storeAs('public/dokumen/tataibadah', $fileName);
        
                // Simpan nama file ke database
                $validatedData['file'] = $fileName;
            }
    
            // Simpan data ke database
            TataIbadahModel::create([
                'tanggal'  => $validatedData['tanggal'],
                'judul'  => $validatedData['judul'],
                'deskripsi' => $validatedData['deskripsi'],
                'file' => $validatedData['file'] ?? null, // Jika tidak ada file, simpan NULL
            ]);
    
            return redirect('pengelolaan-informasi/tataibadah')->with('success_tataibadah', 'Data tata ibadah berhasil disimpan');
        } catch(\Exception $e){
            return redirect('pengelolaan-informasi/tataibadah')->with('error_tataibadah', 'Terjadi kesalahan saat menyimpan data: ');
        }
    }

    //Menampilkan Lihat
    public function show(string $id){
        $tataibadah = TataIbadahModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Tata Ibadah',
            'list'  => ['Pengelolaan Informasi', 'Tata Ibadah', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat tata ibadah'
        ];

        $activeMenu = 'dokumen'; //set menu yang sedang aktif

        return view('tataibadah.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'tataibadah' => $tataibadah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit tataibadah
    public function edit(string $id){
        $tataibadah = TataIbadahModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Ubah Tata Ibadah',
            'list'  => ['Pengelolaan Informasi', 'Tata Ibadah', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah Tata Ibadah'
        ];

        $activeMenu = 'dokumen'; //set menu yang sedang aktif

        return view('tataibadah.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'tataibadah' => $tataibadah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            //level kode harus diisi, berupa string, minimal 3 karakter, maksimal 10 karakter
            //dan bernilai unik di tabel m_level kolom level_kode kecuali untuk level dengan id yang sedang diedit
            'tanggal' => 'required|date_format:Y-m-d',
            'judul' => 'required|string|min:3|max:50|unique:t_tataibadah,judul,'.$id.',tataibadah_id',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|mimes:pdf|max:2048'
        ]);

        try{
            // Ambil data tata ibadah dari database
            $tataibadah = TataIbadahModel::find($id);

            // Jika ada file baru yang diunggah, simpan file dan hapus file lama
            if ($request->hasFile('file')) {
                // Hapus file lama jika ada
                if ($tataibadah->file) {
                    Storage::delete('public/dokumen/tataibadah/' . $tataibadah->file);
                }

                // Simpan file baru
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/dokumen/tataibadah', $fileName); // Simpan ke storage
            } else {
                // Gunakan file lama jika tidak ada file baru yang diunggah
                $fileName = $tataibadah->file;
            }
            
            TataIbadahModel::find($id)->update([
                'tanggal'  => $request->tanggal,
                'judul'  => $request->judul,
                'deskripsi'  => $request->deskripsi,
                'file'  => $fileName,
            ]);

            return redirect('pengelolaan-informasi/tataibadah')->with('success_tataibadah', 'Data tata ibadah berhasil diubah');
        }catch(\Exception $e){
            return redirect('pengelolaan-informasi/tataibadah')->with('error_tataibadah', 'Terjadi kesalahan saat mengubah data: ');
        }    
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = TataIbadahModel::find($id);
        if(!$check){        //untuk mengecek apakah data tata ibadah dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-informasi/tataibadah')->with('error_tataibadah', 'Data tata ibadah tidak ditemukan');
        }

        try{
            TataIbadahModel::destroy($id); //Hapus data tata ibadah

            return redirect('pengelolaan-informasi/tataibadah')->with('success_tataibadah', 'Data tata ibadah berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-informasi/tataibadah')->with('error_tataibadah', 'Data tata ibadah gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
