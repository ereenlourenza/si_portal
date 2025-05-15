<?php

namespace App\Http\Controllers;

use App\Models\IbadahModel;
use App\Models\KategoriIbadahModel;
use App\Models\PelayanModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class IbadahController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Ibadah',
            'list' => ['Pengelolaan Informasi','Ibadah']
        ];

        $page =(object)[
            'title' => 'Daftar ibadah yang terdaftar dalam sistem'
        ];

        $activeMenu = 'ibadah'; //set menu yang sedang aktif

        $kategoriibadah = KategoriIbadahModel::all(); //ambil data level untuk filter level

        return view('ibadah.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoriibadah' => $kategoriibadah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request){
        $ibadahs = IbadahModel::select('ibadah_id', 'kategoriibadah_id', 'tanggal', 'waktu', 'tempat', 'lokasi', 'sektor', 'nama_pelkat', 'ruang', 'pelayan_firman')->with('kategoriibadah');

        // Filter berdasarkan tahun dan bulan dari kolom 'tanggal'
        if ($request->tanggal) {
            $dateParts = explode('-', $request->tanggal); // Memisahkan input yyyy-mm
            if (count($dateParts) == 2) {
                $year = $dateParts[0];
                $month = $dateParts[1];

                $ibadahs->whereYear('tanggal', $year)
                            ->whereMonth('tanggal', $month);
            }
        }
        
        return DataTables::of($ibadahs)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->editColumn('waktu', function($ibadah) {
                return Carbon::parse($ibadah->waktu)->format('H:i'); // Format 24 jam (contoh: 14:30)
            })
            ->addColumn('aksi', function ($ibadah) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/ibadah/' . $ibadah->ibadah_id).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/ibadah/' . $ibadah->ibadah_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/ibadah/'.$ibadah->ibadah_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    //Menampilkan halaman form tambah ibadah $ibadah
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Ibadah',
            'list' => ['Pengelolaan Informasi', 'Ibadah', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah ibadah baru'
        ];

        $kategoriibadah = KategoriIbadahModel::all(); //ambil data ibadah untuk ditampilkan di form
        $pelayan = PelayanModel::all(); //ambil data ibadah untuk ditampilkan di form
        
        $activeMenu = 'ibadah'; //set menu yang sedang aktif

        return view('ibadah.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoriibadah' => $kategoriibadah,  'pelayan' => $pelayan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    // //Menyimpan data level baru
    public function store(Request $request){
        $request->validate([
            'tanggal' => 'required|date_format:Y-m-d',
            'waktu' => 'required|date_format:H:i',
            'tempat' => 'required|string',
            'lokasi' => 'nullable|string',
            'sektor' => 'nullable|integer',
            'nama_pelkat' => 'nullable|string',
            'ruang' => 'nullable|string',
            'kategoriibadah_id' => 'required|integer',
            'pelayan_firman' => 'required|string',
        ]);
        
        try{
    
            // Simpan data ke database
            IbadahModel::create([
                'kategoriibadah_id' => $request->kategoriibadah_id,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'tempat' => $request->tempat,
                'lokasi' => $request->lokasi,
                'sektor' => $request->sektor,
                'nama_pelkat' => $request->nama_pelkat,
                'ruang' => $request->ruang,
                'pelayan_firman' => $request->pelayan_firman,
            ]);

            // log aktivitas
            simpanLogAktivitas('Ibadah', 'store', "Menambahkan data: \n"
                . "ID: {$request->ibadah_id}\n"
                . "Tanggal: {$request->tanggal}\n"
                . "Waktu: {$request->waktu}\n"
                . "Tempat: {$request->tempat}\n"
            );
    
            return redirect('pengelolaan-informasi/ibadah')->with('success_ibadah', 'Data ibadah berhasil disimpan');
        } catch(\Exception $e){
            return redirect('pengelolaan-informasi/ibadah')->with('error_ibadah', 'Terjadi kesalahan saat menyimpan data: ' );
        }
    }

    //Menampilkan Lihat
    public function show(string $id){
        $ibadah = IbadahModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Ibadah',
            'list'  => ['Pengelolaan Informasi', 'Ibadah', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat ibadah'
        ];

        $activeMenu = 'ibadah'; //set menu yang sedang aktif

        return view('ibadah.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'ibadah' => $ibadah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit pelayan
    public function edit(string $id){
        $ibadah = IbadahModel::find($id);
        $kategoriibadah = KategoriIbadahModel::all();
        $pelayan = PelayanModel::all();

        $breadcrumb = (object)[
            'title' => 'Ubah Ibadah',
            'list'  => ['Pengelolaan Informasi', 'Ibadah', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah Ibadah'
        ];

        $activeMenu = 'ibadah'; //set menu yang sedang aktif

        return view('ibadah.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'ibadah' => $ibadah, 'pelayan' => $pelayan, 'kategoriibadah' => $kategoriibadah, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            'tanggal' => 'required|date_format:Y-m-d',
            'waktu' => 'required|date_format:H:i',
            'tempat' => 'required|string',
            'lokasi' => 'nullable|string',
            'sektor' => 'nullable|integer',
            'nama_pelkat' => 'nullable|string',
            'ruang' => 'nullable|string',
            'kategoriibadah_id' => 'required|integer',
            'pelayan_firman' => 'required|string',
        ]);

        try{
            
            IbadahModel::find($id)->update([
                'kategoriibadah_id' => $request->kategoriibadah_id,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'tempat' => $request->tempat,
                'lokasi' => $request->lokasi,
                'sektor' => $request->sektor,
                'nama_pelkat' => $request->nama_pelkat,
                'ruang' => $request->ruang,
                'pelayan_firman' => $request->pelayan_firman,
            ]);

            // log aktivitas
            simpanLogAktivitas('Ibadah', 'update', "Mengubah data: \n"
                . "ID: {$id}\n"
                . "Tanggal: {$request->tanggal}\n"
                . "Waktu: {$request->waktu}\n"
                . "Tempat: {$request->tempat}\n"
            );

            return redirect('pengelolaan-informasi/ibadah')->with('success_ibadah', 'Data ibadah berhasil diubah');
        }catch(\Exception $e){
            return redirect('pengelolaan-informasi/ibadah')->with('error_ibadah', 'Terjadi kesalahan saat mengubah data: ' );
        }    
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = IbadahModel::find($id);
        if(!$check){        //untuk mengecek apakah data tata ibadah dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-informasi/ibadah')->with('error_ibadah', 'Data ibadah tidak ditemukan');
        }

        try{
            IbadahModel::destroy($id); //Hapus data ibadah

            // log aktivitas
            simpanLogAktivitas('Ibadah', 'destroy', "Menghapus data: \n"
                . "ID: {$check->ibadah_id}\n"
                . "Tanggal: {$check->tanggal}\n"
                . "Waktu: {$check->waktu}\n"
                . "Tempat: {$check->tempat}\n"
            );

            return redirect('pengelolaan-informasi/ibadah')->with('success_ibadah', 'Data ibadah berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-informasi/ibadah')->with('error_ibadah', 'Data ibadah gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
