<?php

namespace App\Http\Controllers;

use App\Models\KategoriPelayanModel;
use App\Models\PelayanModel;
use App\Models\PelkatModel;
use App\Models\PHMJModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PelayanController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Pelayan',
            'list' => ['Pengelolaan Informasi','Pelayan']
        ];

        $page =(object)[
            'title' => 'Daftar pelayan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pelayan'; //set menu yang sedang aktif

        $kategoripelayan = KategoriPelayanModel::all(); //ambil data level untuk filter level
        $pelkat = PelkatModel::all(); //ambil data level untuk filter level

        return view('pelayan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoripelayan' => $kategoripelayan, 'pelkat' => $pelkat, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request){
        $pelayans = PelayanModel::select('pelayan_id', 'kategoripelayan_id', 'pelkat_id', 'nama', 'foto', 'masa_jabatan_mulai', 'masa_jabatan_selesai', 'keterangan') ->with('kategoripelayan','pelkat','phmj');

        // Ambil tahun terkecil dan terbesar dari data pelayan
        $minYear = PelayanModel::min('masa_jabatan_mulai');
        $maxYear = PelayanModel::max('masa_jabatan_selesai');

        // Filter berdasarkan tahun yang dipilih di dropdown
        if ($request->tahun) {
            $pelayans->where('masa_jabatan_mulai', '<=', $request->tahun)
                    ->where('masa_jabatan_selesai', '>=', $request->tahun);
        }
        
        return DataTables::of($pelayans)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('masa_jabatan', function ($pelayan) {
                return $pelayan->masa_jabatan_mulai . ' - ' . $pelayan->masa_jabatan_selesai;
            })
            ->addColumn('aksi', function ($pelayan) { 

                $btn = '<a href="'.url('/pengelolaan-informasi/pelayan/' . $pelayan->pelayan_id).'" class="btn btn-success btn-sm">Lihat</a> ';

                if ($pelayan->phmj) { 
                    // URL khusus untuk PHMJ
                    $btn .= '<a href="'.url('/pengelolaan-informasi/phmj/' . $pelayan->phmj->phmj_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                    $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/phmj/'.$pelayan->phmj->phmj_id).'">'. csrf_field() . method_field('DELETE') . 
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                } else {
                    // URL default
                    $btn .= '<a href="'.url('/pengelolaan-informasi/pelayan/' . $pelayan->pelayan_id . '/edit').'" class="btn btn-warning btn-sm">Ubah</a> ';
                    $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/pelayan/'.$pelayan->pelayan_id).'">'. csrf_field() . method_field('DELETE') . 
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                }
                
                return $btn;
            })            
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
            ->with(['minYear' => $minYear, 'maxYear' => $maxYear]) // Kirim min/max year ke frontend
            ->make(true);
    }

    //Menampilkan halaman form tambah pelayan $pelayan
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Pelayan',
            'list' => ['Pengelolaan Informasi', 'Pelayan', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah data pelayan baru'
        ];

        $kategoripelayan = KategoriPelayanModel::all(); //ambil data pelayan untuk ditampilkan di form
        $pelkat = PelkatModel::all();

        // Ambil daftar pelayan yang termasuk kategori Diaken atau Penatua
        $diakenPenatua = DB::table('t_pelayan')
            ->join('t_kategoripelayan', 't_pelayan.kategoripelayan_id', '=', 't_kategoripelayan.kategoripelayan_id')
            ->whereIn('t_kategoripelayan.kategoripelayan_nama', ['Diaken', 'Penatua'])
            ->select('t_pelayan.pelayan_id')
            ->get();

        $activeMenu = 'pelayan'; //set menu yang sedang aktif

        return view('pelayan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategoripelayan' => $kategoripelayan, 'pelkat' => $pelkat, 'diakenPenatua' => $diakenPenatua, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    // //Menyimpan data level baru
    public function store(Request $request){
        $validatedData = $request->validate([
            //judul harus diisi, berupa string, minimal 3 karakter, maksimal 10 karakter, dan bernilai unik di tabel m_level kolom judul
            'nama' => ['required', 
                        Rule::unique('t_pelayan')->where(function ($query) use ($request) {
                            return $query->where('masa_jabatan_mulai', $request->masa_jabatan_mulai)
                                        ->where('masa_jabatan_selesai', $request->masa_jabatan_selesai);
                        })
            ],

            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'masa_jabatan_mulai' => 'required|date_format:Y',
            'masa_jabatan_selesai' => 'required|date_format:Y|after_or_equal:masa_jabatan_mulai',
            'keterangan' => 'nullable|string|max:50',
            'kategoripelayan_id' => 'required|integer',
            'pelkat_id' => 'nullable|integer',
        ]);

        // Cari periode terakhir pelayan ini
        $lastPeriod = PelayanModel::where('nama', $request->nama)
        ->orderBy('masa_jabatan_selesai', 'desc')
        ->first();

        if ($lastPeriod) {
            // Cek apakah periode mulai yang baru harus sesuai dengan periode selesai sebelumnya
            if ($request->masa_jabatan_mulai != $lastPeriod->masa_jabatan_selesai) {
                return back()->withErrors([
                    'masa_jabatan_mulai' => 'Periode baru harus dimulai dari ' . $lastPeriod->masa_jabatan_selesai . '.'
                ])->withInput();
            }
        }

        // dd($request->all());
        
        try{
            if ($request->hasFile('foto')) {
                // Simpan foto ke dalam storage
                $foto = $request->file('foto');
                $fotoName = Str::random(10) . '-' . $foto->getClientOriginalName(); 
                $foto->storeAs('public/images/pelayan', $fotoName);
        
                // Simpan nama foto ke database
                $validatedData['foto'] = $fotoName;
            }
    
            // Simpan data ke database
            PelayanModel::create([
                'kategoripelayan_id'  => $validatedData['kategoripelayan_id'],
                'pelkat_id'  => $validatedData['pelkat_id'],
                'nama'  => $validatedData['nama'],
                'foto' => $validatedData['foto'] ?? null, // Jika tidak ada file, simpan NULL
                'masa_jabatan_mulai'  => $validatedData['masa_jabatan_mulai'],
                'masa_jabatan_selesai'  => $validatedData['masa_jabatan_selesai'],
                'keterangan'  => $validatedData['keterangan'],
            ]);

            // log aktivitas
            simpanLogAktivitas('Pelayan', 'store', "Menambahkan data: \n"
                . "Kategori ID: {$request->kategoripelayan_id}\n"
                . "Nama: {$request->nama}\n"
            );
    
            return redirect('pengelolaan-informasi/pelayan')->with('success_pelayan', 'Data pelayan berhasil disimpan');
        } catch(\Exception $e){
            return redirect('pengelolaan-informasi/pelayan')->with('error_pelayan', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    //Menampilkan Lihat
    public function show(string $id){
        $pelayan = PelayanModel::with(['phmj','kategoripelayan'])->find($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Pelayan',
            'list'  => ['Pengelolaan Informasi', 'Pelayan', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat pelayan'
        ];

        $activeMenu = 'pelayan'; //set menu yang sedang aktif

        return view('pelayan.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'pelayan' => $pelayan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menampilkan halaman form edit pelayan
    public function edit(string $id){
        $pelayan = PelayanModel::find($id);
        $kategoripelayan = KategoriPelayanModel::all();

        $breadcrumb = (object)[
            'title' => 'Ubah Pelayan',
            'list'  => ['Pengelolaan Informasi', 'Pelayan', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah Pelayan'
        ];

        $activeMenu = 'pelayan'; //set menu yang sedang aktif

        return view('pelayan.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'pelayan' => $pelayan, 'kategoripelayan' => $kategoripelayan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Menyimpan perubahan data level
    public function update(Request $request, string $id){
        $request->validate([
            // 'nama' => 'required|string|min:3|max:100',
            'nama' => ['required', 
                        Rule::unique('t_pelayan')->where(function ($query) use ($request) {
                            return $query->where('masa_jabatan_mulai', $request->masa_jabatan_mulai)
                                        ->where('masa_jabatan_selesai', $request->masa_jabatan_selesai);
                        })
            ],
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'masa_jabatan_mulai' => 'required|date_format:Y',
            'masa_jabatan_selesai' => 'required|date_format:Y|after_or_equal:masa_jabatan_mulai',
            'keterangan' => 'nullable|string|max:50',
            'kategoripelayan_id' => 'required|integer',
            'pelkat_id' => 'nullable|integer',
        ]);

        try{
            // Ambil data tata ibadah dari database
            $pelayan = PelayanModel::find($id);

            // Jika ada file baru yang diunggah, simpan file dan hapus file lama
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($pelayan->foto) {
                    Storage::delete('public/images/pelayan/' . $pelayan->foto);
                }

                // Simpan foto baru
                $foto = $request->file('foto');
                $fotoName = time() . '_' . $foto->getClientOriginalName();
                $foto->storeAs('public/images/pelayan', $fotoName); // Simpan ke storage
            } else {
                // Gunakan foto lama jika tidak ada foto baru yang diunggah
                $fotoName = $pelayan->foto;
            }
            
            PelayanModel::find($id)->update([
                'kategoripelayan_id'  => $request->kategoripelayan_id,
                'pelkat_id'  => $request->pelkat_id,
                'nama'  => $request->nama,
                'foto'  => $fotoName,
                'masa_jabatan_mulai'  => $request->masa_jabatan_mulai,
                'masa_jabatan_selesai'  => $request->masa_jabatan_selesai,
                'keterangan'  => $request->keterangan,
            ]);

            // log aktivitas
            simpanLogAktivitas('Pelayan', 'update', "Mengubah data: \n"
                . "Kategori ID: {$request->kategoripelayan_id}\n"
                . "Nama: {$request->nama}\n"
            );

            return redirect('pengelolaan-informasi/pelayan')->with('success_pelayan', 'Data pelayan berhasil diubah');
        }catch(\Exception $e){
            return redirect('pengelolaan-informasi/pelayan')->with('error_pelayan', 'Terjadi kesalahan saat mengubah data: ' . $e->getMessage());
        }    
    }

    //Menghapus data level
    public function destroy(string $id){
        $check = PelayanModel::find($id);
        if(!$check){        //untuk mengecek apakah data tata ibadah dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-informasi/pelayan')->with('error_pelayan', 'Data pelayan tidak ditemukan');
        }

        try{
            // Hapus file foto jika ada
            if ($check->foto && Storage::exists('public/images/pelayan/' . $check->foto)) {
                Storage::delete('public/images/pelayan/' . $check->foto);
            }

            PelayanModel::destroy($id); //Hapus data pelayan

            // log aktivitas
            simpanLogAktivitas('Pelayan', 'destroy', "Menghapus data: \n"
                . "Kategori ID: {$check->kategoripelayan_id}\n"
                . "Nama: {$check->nama}\n"
            );

            return redirect('pengelolaan-informasi/pelayan')->with('success_pelayan', 'Data pelayan berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-informasi/pelayan')->with('error_pelayan', 'Data pelayan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
