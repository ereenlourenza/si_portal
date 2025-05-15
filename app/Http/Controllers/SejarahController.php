<?php

namespace App\Http\Controllers;

use App\Models\SejarahModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SejarahController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    //Pengelolaan Pengguna User
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Sejarah',
            'list' => ['Pengelolaan Sejarah', 'Sejarah']
        ];

        $page =(object)[
            'title' => 'Daftar sejarah yang terdaftar dalam sistem'
        ];

        $activeMenu = 'sejarah'; //set menu yang sedang aktif

        $sejarah = SejarahModel::all();
        
        return view('sejarah.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'sejarah' => $sejarah,'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);

    }

    //Ambil data user dalam bentuk json untuk datatables
    public function list(){
        $sejarahs = SejarahModel::select('sejarah_id', 'judul_subbab', 'isi_konten');
        
        return DataTables::of($sejarahs)
            ->addIndexColumn() // menambahkan kolom index / no urut (default name kolom: DT_RowIndex)
            ->editColumn('isi_konten', function ($row) {
                $html = $row->isi_konten;
            
                // Kecilkan gambar
                $html = preg_replace(
                    '/<img(.*?)>/i',
                    '<img$1 style="max-width: 100px; height: auto;">',
                    $html
                );
            
                // Ambil semua gambar
                preg_match_all('/<img[^>]+>/i', $html, $gambar);
            
                // Hapus semua tag kecuali <a> dan <img>
                $filteredHtml = strip_tags($html, '<a><img><table><tr><td><th><tbody><thead>');
            
                // Batasi panjang teks dengan tag <a> tetap ada
                // Caranya: potong manual string HTML-nya
                $textLimited = Str::limit($filteredHtml, 150);
            
                return '<div class="isi-konten-table">'
                    . implode('', $gambar[0]) .
                    '<p>' . $textLimited . '</p>' .
                    '</div>';
            })
            
            ->addColumn('aksi', function ($sejarah) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/sejarah/' . $sejarah->sejarah_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/sejarah/' . $sejarah->sejarah_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/sejarah/'.$sejarah->sejarah_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['isi_konten','aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah user
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Sejarah',
            'list' => ['Pengelolaan Sejarah', 'Sejarah', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah sejarah baru'
        ];

        $sejarah = SejarahModel::all();

        $activeMenu = 'sejarah'; //set menu yang sedang aktif

        return view('sejarah.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'sejarah' => $sejarah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan data user baru
    public function store(Request $request){
        $request->validate([
            'judul_subbab' => 'required|string|max:255',
            'isi_konten' => 'required',                                 
        ]);

        $kontenBaru = $request->isi_konten;

        $sejarah = SejarahModel::create([
            'judul_subbab'   => $request->judul_subbab,  
            'isi_konten'     => $request->isi_konten,   
        ]);

        // Bersihkan gambar yang tidak digunakan di konten lain
        $this->hapusGambarTidakDipakai('', $kontenBaru, $sejarah->id);

        // log aktivitas
        simpanLogAktivitas('Sejarah', 'store', "Menambahkan data: \n"
            . "{$request->judul_subbab}\n"
        );

        return redirect('/pengelolaan-informasi/sejarah')->with('success', 'Konten berhasil disimpan');
    }

    //Menampilkan detail
    public function show(string $id){
        $sejarah = SejarahModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Sejarah',
            'list'  => ['Pengelolaan Sejarah', 'Sejarah', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail sejarah'
        ];

        $activeMenu = 'sejarah'; //set menu yang sedang aktif

        return view('sejarah.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'sejarah' => $sejarah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit user
    public function edit(string $id){
        $sejarah = SejarahModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit Sejarah',
            'list'  => ['Pengelolaan Sejarah', 'Sejarah', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit sejarah'
        ];

        $activeMenu = 'sejarah'; //set menu yang sedang aktif

        return view('sejarah.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'sejarah' => $sejarah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data user
    public function update(Request $request, string $id){
        $request->validate([
            'judul_subbab' => 'required|string|max:255',
            'isi_konten' => 'required', 
        ]);
        
        $sejarah = SejarahModel::find($id);
        $kontenLama = $sejarah->isi_konten;
        $kontenBaru = $request->isi_konten;

        // Simpan update
        $sejarah->update([
            'judul_subbab'  => $request->judul_subbab,
            'isi_konten'    => $kontenBaru,
        ]);

        // Hapus gambar yang tidak dipakai
        $this->hapusGambarTidakDipakai($kontenLama, $kontenBaru, $id);

        // Update sejarah
        $sejarah->update([
            'judul_subbab' => $request->judul_subbab,
            'isi_konten' => $kontenBaru,
        ]);

        // log aktivitas
        simpanLogAktivitas('Sejarah', 'update', "Mengubah data: \n"
            . "{$request->judul_subbab}\n"
        );

        return redirect('/pengelolaan-informasi/sejarah')->with('success', 'Konten berhasil diubah');
    }

    protected function hapusGambarTidakDipakai($kontenLama, $kontenBaru, $id)
    {
        // Ambil semua URL gambar dari konten lama
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $kontenLama, $gambarLama);
        $gambarLama = $gambarLama[1];

        // Ambil semua URL gambar dari konten baru
        preg_match_all('/<img[^>]+src="([^">]+)"/i', $kontenBaru, $gambarBaru);
        $gambarBaru = $gambarBaru[1];

        // Bandingkan, cari gambar yang tidak ada di konten baru
        $gambarTerhapus = array_diff($gambarLama, $gambarBaru);

        foreach ($gambarTerhapus as $imgUrl) {
            // Cek apakah gambar ini dipakai entri lain
            $dipakaiDiLain = SejarahModel::where('sejarah_id', '!=', $id)
                ->where('isi_konten', 'LIKE', '%' . $imgUrl . '%')
                ->exists();

            if (!$dipakaiDiLain) {
                // Ubah URL ke path
                $relativePath = str_replace(url('/storage'), 'public', $imgUrl);
                $fullPath = storage_path('app/' . $relativePath);

                if (file_exists($fullPath)) {
                    @unlink($fullPath);
                }
            }
        }
    }


    //Menghapus data user
    public function destroy(string $id){
        $check = SejarahModel::find($id);
        if(!$check){        //untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
            return redirect('/pengelolaan-informasi/sejarah')->with('error', 'Konten tidak ditemukan');
        }

        try{
            // Hapus gambar karena kontennya akan dihapus
            $this->hapusGambarTidakDipakai($check->isi_konten, '', $id);

            $check->delete(); //Hapus data level

            // log aktivitas
            simpanLogAktivitas('Sejarah', 'destroy', "Menghapus data: \n"
                . "{$check->judul_subbab}\n"
            );

            return redirect('/pengelolaan-informasi/sejarah')->with('success', 'Konten berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/pengelolaan-informasi/sejarah')->with('error', 'Konten gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
