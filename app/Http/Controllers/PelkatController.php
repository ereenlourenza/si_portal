<?php

namespace App\Http\Controllers;

use App\Models\PelkatModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PelkatController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    //Pengelolaan Pengguna User
    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Pelkat',
            'list' => ['Pengelolaan Pelkat', 'Pelkat']
        ];

        $page =(object)[
            'title' => 'Daftar pelkat yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pelkat'; //set menu yang sedang aktif
        
        return view('pelkat.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);

    }

    //Ambil data user dalam bentuk json untuk datatables
    public function list(){
        $pelkats = PelkatModel::select('pelkat_id', 'pelkat_nama', 'deskripsi');
        
        return DataTables::of($pelkats)
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
            ->addColumn('aksi', function ($pelkat) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/pelkat/' . $pelkat->pelkat_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/pelkat/' . $pelkat->pelkat_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/pelkat/'.$pelkat->pelkat_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['deskripsi','aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah user
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Pelkat',
            'list' => ['Pengelolaan Pelkat', 'Pelkat', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah pelkat baru'
        ];

        $activeMenu = 'pelkat'; //set menu yang sedang aktif

        return view('pelkat.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan data user baru
    public function store(Request $request){
        $request->validate([
            'pelkat_nama'       => 'required|string|min:3|max:50|unique:t_pelkat,pelkat_nama',  
            'deskripsi'         => 'required|string',                                    
        ]);

        $kontenBaru = $request->deskripsi;


        $pelkat = PelkatModel::create([
            'pelkat_nama'   => $request->pelkat_nama,  
            'deskripsi'     => $request->deskripsi,      
        ]);

        // Bersihkan gambar yang tidak digunakan di konten lain
        $this->hapusGambarTidakDipakai('', $kontenBaru, $pelkat->id);

        // log aktivitas
        simpanLogAktivitas('Pelkat', 'store', "Menambahkan data: \n"
            . "{$request->pelkat_nama}\n"
        );

        return redirect('/pengelolaan-informasi/pelkat')->with('success', 'Data pelkat berhasil disimpan');
    }

    //Menampilkan detail
    public function show(string $id){
        $pelkat = PelkatModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Pelkat',
            'list'  => ['Pengelolaan Pelkat', 'Pelkat', 'Detail']
        ];

        $page = (object)[
            'title' => 'Detail pelkat'
        ];

        $activeMenu = 'pelkat'; //set menu yang sedang aktif

        return view('pelkat.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'pelkat' => $pelkat, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit user
    public function edit(string $id){
        $pelkat = PelkatModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit Pelkat',
            'list'  => ['Pengelolaan Pelkat', 'Pelkat', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit pelkat'
        ];

        $activeMenu = 'pelkat'; //set menu yang sedang aktif

        return view('pelkat.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'pelkat' => $pelkat, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data user
    public function update(Request $request, string $id){
        $request->validate([
            'pelkat_nama'       => 'required|string|min:3|max:50|unique:t_pelkat,pelkat_nama,'.$id.',pelkat_id',  
            'deskripsi'         => 'required|string',     
        ]);

        $pelkat = PelkatModel::find($id);
        $kontenLama = $pelkat->deskripsi;
        $kontenBaru = $request->deskripsi;
        
        $pelkat->update([
            'pelkat_nama'      => $request->pelkat_nama,
            'deskripsi'        => $request->deskripsi,
        ]);

        // Hapus gambar yang tidak dipakai
        $this->hapusGambarTidakDipakai($kontenLama, $kontenBaru, $id);

        // Update sejarah
        $pelkat->update([
            'pelkat_nama'      => $request->pelkat_nama,
            'deskripsi'        => $request->deskripsi,
        ]);

        // log aktivitas
        simpanLogAktivitas('Pelkat', 'update', "Mengubah data: \n"
            . "{$request->pelkat_nama}\n"
        );

        return redirect('/pengelolaan-informasi/pelkat')->with('success', 'Data pelkat berhasil diubah');
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
            $dipakaiDiLain = PelkatModel::where('pelkat_id', '!=', $id)
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
        $check = PelkatModel::find($id);
        if(!$check){        //untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
            return redirect('/pengelolaan-informasi/pelkat')->with('error', 'Data pelkat tidak ditemukan');
        }

        try{
            PelkatModel::destroy($id); //Hapus data level

            // log aktivitas
            simpanLogAktivitas('Pelkat', 'destroy', "Menghapus data: \n"
                . "{$check->pelkat_nama}\n"
            );
            
            return redirect('/pengelolaan-informasi/pelkat')->with('success', 'Data pelkat berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/pengelolaan-informasi/pelkat')->with('error', 'Data pelkat gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
