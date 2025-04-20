<?php

namespace App\Http\Controllers;

use App\Models\KomisiModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class KomisiController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    //Pengelolaan Pengguna User
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Komisi',
            'list' => ['Pengelolaan Komisi', 'Komisi']
        ];

        $page =(object)[
            'title' => 'Daftar komisi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'komisi'; //set menu yang sedang aktif
        
        return view('komisi.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);

    }

    //Ambil data user dalam bentuk json untuk datatables
    public function list(){
        $komisis = KomisiModel::select('komisi_id', 'komisi_nama', 'deskripsi');
        
        return DataTables::of($komisis)
            ->addIndexColumn() // menambahkan kolom index / no urut (default name kolom: DT_RowIndex)
            ->editColumn('deskripsi', function ($row) {
                $html = $row->deskripsi;
            
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
            
                return '<div class="deskripsi-table">'
                    . implode('', $gambar[0]) .
                    '<p>' . $textLimited . '</p>' .
                    '</div>';
            })
            ->addColumn('aksi', function ($komisi) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/komisi/' . $komisi->komisi_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/komisi/' . $komisi->komisi_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/komisi/'.$komisi->komisi_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['deskripsi','aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah user
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Komisi',
            'list' => ['Pengelolaan Komisi', 'Komisi', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah Komisi baru'
        ];

        $activeMenu = 'komisi'; //set menu yang sedang aktif

        return view('komisi.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan data user baru
    public function store(Request $request){
        $request->validate([
            'komisi_nama'       => 'required|string|min:3|max:50|unique:t_komisi,komisi_nama',  
            'deskripsi'         => 'required|string',                                    
        ]);

        $kontenBaru = $request->deskripsi;

        $komisi = KomisiModel::create([
            'komisi_nama'   => $request->komisi_nama,  
            'deskripsi'     => $request->deskripsi,      
        ]);

        // Bersihkan gambar yang tidak digunakan di konten lain
        $this->hapusGambarTidakDipakai('', $kontenBaru, $komisi->id);

        return redirect('/pengelolaan-informasi/komisi')->with('success', 'Data komisi berhasil disimpan');
    }

    //Menampilkan detail
    public function show(string $id){
        $komisi = KomisiModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail komisi',
            'list'  => ['Pengelolaan komisi', 'komisi', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail komisi'
        ];

        $activeMenu = 'komisi'; //set menu yang sedang aktif

        return view('komisi.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'komisi' => $komisi, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit user
    public function edit(string $id){
        $komisi = KomisiModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit komisi',
            'list'  => ['Pengelolaan komisi', 'komisi', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit komisi'
        ];

        $activeMenu = 'komisi'; //set menu yang sedang aktif

        return view('komisi.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'komisi' => $komisi, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data user
    public function update(Request $request, string $id){
        $request->validate([
            'komisi_nama'       => 'required|string|min:3|max:50|unique:t_komisi,komisi_nama,'.$id.',komisi_id',  
            'deskripsi'         => 'required|string',     
        ]);

        $komisi = KomisiModel::find($id);
        $kontenLama = $komisi->deskripsi;
        $kontenBaru = $request->deskripsi;
        
        $komisi->update([
            'komisi_nama'      => $request->komisi_nama,
            'deskripsi'        => $request->deskripsi,
        ]);

        // Hapus gambar yang tidak dipakai
        $this->hapusGambarTidakDipakai($kontenLama, $kontenBaru, $id);

        // Update sejarah
        $komisi->update([
            'komisi_nama'      => $request->komisi_nama,
            'deskripsi'        => $request->deskripsi,
        ]);

        return redirect('/pengelolaan-informasi/komisi')->with('success', 'Data komisi berhasil diubah');
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
            $dipakaiDiLain = KomisiModel::where('komisi_id', '!=', $id)
                ->where('deskripsi', 'LIKE', '%' . $imgUrl . '%')
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
        $check = KomisiModel::find($id);
        if(!$check){        //untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
            return redirect('/pengelolaan-informasi/komisi')->with('error', 'Data komisi tidak ditemukan');
        }

        try{
            KomisiModel::destroy($id); //Hapus data level

            return redirect('/pengelolaan-informasi/komisi')->with('success', 'Data komisi berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/pengelolaan-informasi/komisi')->with('error', 'Data komisi gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
