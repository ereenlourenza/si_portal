<?php

namespace App\Http\Controllers;

use App\Exports\BeritaAcaraSingleExport;
use App\Exports\PersembahanExport;
use App\Models\BeritaAcaraIbadahModel;
use App\Models\BeritaAcaraPersembahanModel;
use App\Models\BeritaAcaraPetugasModel;
use App\Models\IbadahModel;
use App\Models\KategoriPersembahanModel;
use App\Models\PelayanModel;
use App\Models\PersembahanAmplopModel;
use App\Models\PersembahanLembaranModel;
use App\Models\UserModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class BeritaAcaraIbadahController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:MLJ,PHM,ADM');
    }

    public function index()
    {
        $breadcrumb = (object)[
            'title' => 'Daftar Berita Acara Ibadah',
            'list' => ['Pengelolaan Ibadah', 'Berita Acara']
        ];

        $page = (object)[
            'title' => 'Data berita acara ibadah'
        ];

        $activeMenu = 'beritaacara';

        $ibadah = IbadahModel::where('kategoriibadah_id', 1)->get();

        $totalPersembahan = BeritaAcaraPersembahanModel::sum('total');

        return view('beritaacara.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'ibadah' => $ibadah,
            'totalPersembahan' => $totalPersembahan,
            'notifUser' => UserModel::all()
        ]);
    }

    public function list(Request $request)
    {
        $berita = BeritaAcaraIbadahModel::with(['ibadah', 'pelayan1', 'pelayan4']);

        // Filter berdasarkan tanggal (hanya jika tanggal diisi)
        if ($request->filled('tanggal')) {
            $berita->whereHas('ibadah', function ($query) use ($request) {
                $query->whereDate('tanggal', $request->tanggal);
            });
        }

        // Filter berdasarkan ibadah_id (hanya jika ibadah_id diisi)
        if ($request->filled('ibadah_id')) {
            $berita->where('ibadah_id', $request->ibadah_id);
        }

        return DataTables::of($berita)
            ->addIndexColumn()
            ->editColumn('ibadah.waktu', function($item) {
                return Carbon::parse($item->ibadah->waktu)->format('H:i'); // Format 24 jam (contoh: 14:30)
            })
            ->addColumn('total_persembahan', function ($item) {
                // Hitung total persembahan untuk berita acara ini
                return BeritaAcaraPersembahanModel::where('berita_acara_ibadah_id', $item->berita_acara_ibadah_id)->sum('total');
            })
            ->addColumn('aksi', function ($item) {
                $btn = '<a href="' . url('/pengelolaan-berita-acara/berita-acara/' . $item->berita_acara_ibadah_id) . '" class="btn btn-success btn-sm">Lihat</a> ';
                // if (auth()->user()->level->level_kode == 'PHM') {
                    $btn .= '<a href="' . url('/pengelolaan-berita-acara/berita-acara/' . $item->berita_acara_ibadah_id . '/edit') . '" class="btn btn-warning btn-sm">Ubah</a> ';
                // }
                // if (auth()->user()->level->level_kode == 'PHM' || auth()->user()->level->level_kode == 'MLJ') {
                    $btn .= '<form class="d-inline-block" method="POST" action="' . url('/pengelolaan-berita-acara/berita-acara/' . $item->berita_acara_ibadah_id) . '">' .
                        csrf_field() . method_field('DELETE') .
                        '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Hapus data ini?\');">Hapus</button></form>';
                // }
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Berita Acara',
            'list' => ['Pengelolaan Ibadah', 'Berita Acara', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah data berita acara ibadah'
        ];

        $activeMenu = 'beritaacara';

        // Ambil semua ibadah_id yang sudah digunakan di berita acara
        $usedIbadahIds = BeritaAcaraIbadahModel::pluck('ibadah_id')->toArray();

        // Ambil hanya ibadah kategori 1 yang belum digunakan
        $ibadah = IbadahModel::where('kategoriibadah_id', 1)
                    ->whereNotIn('ibadah_id', $usedIbadahIds)
                    ->get();

        // $pelayan = PelayanModel::whereIn('kategoripelayan_id', [1,2,3,4])->get();
        $pelayan = PelayanModel::all();

        $kategoriPersembahan = KategoriPersembahanModel::all();

        return view('beritaacara.create', compact(
            'breadcrumb', 'page', 'activeMenu', 'ibadah', 'pelayan', 'kategoriPersembahan'
        ));
    }

    public function store(Request $request)
    {
        Log::info('Request Data:', $request->all());
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'ibadah_id'         => 'required|exists:t_ibadah,ibadah_id',
            'bacaan_alkitab'    => 'required|string|max:255',
            'jumlah_kehadiran'  => 'required|integer|min:0',
            'catatan'           => 'nullable|string|max:1000',
            'ttd_pelayan_1_id'  => 'required|exists:t_pelayan,pelayan_id',
            'ttd_pelayan_4_id'  => 'required|exists:t_pelayan,pelayan_id',
            'ttd_pelayan_1'     => 'required|string', // base64 - Diubah dari nullable
            'ttd_pelayan_4'     => 'required|string', // base64 - Diubah dari nullable

            'petugas'                         => 'required|array|min:1',
            'petugas.*.peran'                => 'required|string|max:255',
            'petugas.*.pelayan_id_jadwal'    => 'nullable|string|max:255',
            'petugas.*.pelayan_id_hadir'     => 'nullable|string|max:255',

            'persembahan'                                  => 'required|array|min:1',
            'persembahan.*.kategori_persembahan_id'       => 'required|exists:t_kategori_persembahan,kategori_persembahan_id',
            'persembahan.*.jenis_input'                   => 'required|in:langsung,lembaran,amplop',
            'persembahan.*.total'                         => 'nullable|numeric|min:0',

            'persembahan.*.amplop' => 'required_if:persembahan.*.jenis_input,amplop|array',
            'persembahan.*.amplop.*.no_amplop' => 'nullable|string',
            'persembahan.*.amplop.*.nama_pengguna_amplop' => 'nullable|string',
            'persembahan.*.amplop.*.jumlah' => 'required|numeric',

            'persembahan.*.lembaran.jumlah_100'     => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_200'     => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_500'     => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_1000_koin'    => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_1000_kertas'    => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_2000'    => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_5000'    => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_10000'   => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_20000'   => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_50000'   => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_100000'  => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            dd($validator->errors());
            return back()->withErrors($validator)->withInput();
        }

        $ibadah = IbadahModel::findOrFail($request->ibadah_id);

        DB::beginTransaction();

        try {
            $pathPelayan1 = $this->saveBase64Image($request->ttd_pelayan_1, 'ttd_p1');
            if ($pathPelayan1 === null && !empty($request->ttd_pelayan_1)) {
                DB::rollBack();
                return back()->withErrors(['ttd_pelayan_1' => 'Format Tanda Tangan Pelayan 1 tidak valid atau gagal disimpan.'])->withInput();
            }

            $pathPelayan4 = $this->saveBase64Image($request->ttd_pelayan_4, 'ttd_p4');
            if ($pathPelayan4 === null && !empty($request->ttd_pelayan_4)) {
                DB::rollBack();
                return back()->withErrors(['ttd_pelayan_4' => 'Format Tanda Tangan Pelayan 4 tidak valid atau gagal disimpan.'])->withInput();
            }

            $berita = BeritaAcaraIbadahModel::create([
                'ibadah_id'         => $request->ibadah_id,
                'bacaan_alkitab'    => $request->bacaan_alkitab,
                // 'pelayan_firman'    => $ibadah->pelayan_firman,
                'jumlah_kehadiran'  => $request->jumlah_kehadiran,
                'catatan'           => $request->catatan,
                'ttd_pelayan_1_id'  => $request->ttd_pelayan_1_id,
                'ttd_pelayan_4_id'  => $request->ttd_pelayan_4_id,
                'ttd_pelayan_1_img' => $pathPelayan1,
                'ttd_pelayan_4_img' => $pathPelayan4,
            ]);

            // foreach ($request->petugas as $p) {
            //     Log::info('Data Petugas:', $p);
            //     BeritaAcaraPetugasModel::create([
            //         'berita_acara_ibadah_id' => $berita->berita_acara_ibadah_id,
            //         'peran'                  => $p['peran'],
            //         'pelayan_id_jadwal'      => $p['pelayan_id_jadwal'] ?? null,
            //         'pelayan_id_hadir'       => $p['pelayan_id_hadir'] ?? null,
            //     ]);
            // }

            foreach ($request->petugas as $p_data) { // Renamed $p to $p_data to avoid conflict if $p is used elsewhere
                $pelayanJadwalId = null;
                if (!empty($p_data['pelayan_id_jadwal'])) {
                    if (is_numeric($p_data['pelayan_id_jadwal'])) {
                        $pelayanJadwalId = $p_data['pelayan_id_jadwal'];
                    } else {
                        $pelayanJadwal = PelayanModel::firstOrCreate(
                            ['nama' => trim($p_data['pelayan_id_jadwal'])],
                            ['kategoripelayan_id' => 14,'masa_jabatan_mulai' => now()->format('Y'),'masa_jabatan_selesai' => now()->format('Y'),] // Default to 'Anggota Jemaat' or similar
                        );
                        $pelayanJadwalId = $pelayanJadwal->pelayan_id;
                    }
                }

                $pelayanHadirId = null;
                if (!empty($p_data['pelayan_id_hadir'])) {
                    if (is_numeric($p_data['pelayan_id_hadir'])) {
                        $pelayanHadirId = $p_data['pelayan_id_hadir'];
                    } else {
                        $pelayanHadir = PelayanModel::firstOrCreate(
                            ['nama' => trim($p_data['pelayan_id_hadir'])],
                            ['kategoripelayan_id' => 14,'masa_jabatan_mulai' => now()->format('Y'),'masa_jabatan_selesai' => now()->format('Y'),] // Default to 'Anggota Jemaat' or similar
                        );
                        $pelayanHadirId = $pelayanHadir->pelayan_id;
                    }
                }
                
                BeritaAcaraPetugasModel::create([
                    'berita_acara_ibadah_id' => $berita->berita_acara_ibadah_id,
                    'peran'                  => $p_data['peran'],
                    'pelayan_id_jadwal'      => $pelayanJadwalId,
                    'pelayan_id_hadir'       => $pelayanHadirId,
                ]);
            }
            
            $totalPersembahan = 0; // Variabel untuk menghitung total persembahan

            foreach ($request->persembahan as $item) {
                // Log::info('Jenis Input:', ['jenis_input' => $item['jenis_input']]);

                // Hitung total untuk jenis input langsung
                if ($item['jenis_input'] === 'langsung') {
                    $totalPersembahan += $item['total'] ?? 0;
                }

                $persembahan = BeritaAcaraPersembahanModel::create([
                    'berita_acara_ibadah_id'    => $berita->berita_acara_ibadah_id,
                    'kategori_persembahan_id'   => $item['kategori_persembahan_id'],
                    'jenis_input'               => trim((string) $item['jenis_input']),
                    'total'                     => $item['total'] ?? 0,
                ]);

                // Hitung total untuk jenis input amplop
                if ($item['jenis_input'] === 'amplop' && isset($item['amplop'])) {
                    $amplopTotal = 0; // Variabel untuk menghitung total jumlah amplop
                    $counterNama = 1; // Counter untuk nama pengguna default
                    $counterAmplop = 1; // Counter untuk nomor amplop default

                    foreach ($item['amplop'] as $amplop) {
                        // Cek dan isi nama pengguna amplop jika kosong
                        $namaPengguna = !empty($amplop['nama_pengguna_amplop']) 
                            ? $amplop['nama_pengguna_amplop'] 
                            : 'NN' . str_pad($counterNama++, 2, '0', STR_PAD_LEFT);

                        // Cek dan isi nomor amplop jika kosong
                        $noAmplop = !empty($amplop['no_amplop']) 
                            ? $amplop['no_amplop'] 
                            : 'AMP' . str_pad($counterAmplop++, 2, '0', STR_PAD_LEFT);

                        PersembahanAmplopModel::create([
                            'berita_acara_persembahan_id' => $persembahan->berita_acara_persembahan_id,
                            'no_amplop'                   => $noAmplop,
                            'nama_pengguna_amplop'        => $namaPengguna,
                            'jumlah'                      => $amplop['jumlah'],
                        ]);

                        // Tambahkan nilai amplop ke total
                        $amplopTotal += $amplop['jumlah'] ?? 0;
                    }

                    // Perbarui kolom total di tabel BeritaAcaraPersembahanModel
                    $persembahan->update(['total' => $amplopTotal]);

                    // Tambahkan nilai amplop ke totalPersembahan
                    $totalPersembahan += $amplopTotal;
                }


                // Hitung total untuk jenis input lembaran
                if ($item['jenis_input'] === 'lembaran' && isset($item['lembaran'])) {
                    $lembaranTotal = (
                        ($item['lembaran']['jumlah_100'] ?? 0) * 100 +
                        ($item['lembaran']['jumlah_200'] ?? 0) * 200 +
                        ($item['lembaran']['jumlah_500'] ?? 0) * 500 +
                        ($item['lembaran']['jumlah_1000_koin'] ?? 0) * 1000 +
                        ($item['lembaran']['jumlah_1000_kertas'] ?? 0) * 1000 +
                        ($item['lembaran']['jumlah_2000'] ?? 0) * 2000 +
                        ($item['lembaran']['jumlah_5000'] ?? 0) * 5000 +
                        ($item['lembaran']['jumlah_10000'] ?? 0) * 10000 +
                        ($item['lembaran']['jumlah_20000'] ?? 0) * 20000 +
                        ($item['lembaran']['jumlah_50000'] ?? 0) * 50000 +
                        ($item['lembaran']['jumlah_100000'] ?? 0) * 100000
                    );

                    PersembahanLembaranModel::create([
                        'berita_acara_ibadah_id'      => $berita->berita_acara_ibadah_id,
                        'kategori_persembahan_id'     => $item['kategori_persembahan_id'],
                        'jumlah_100'                  => $item['lembaran']['jumlah_100'] ?? 0,
                        'jumlah_200'                  => $item['lembaran']['jumlah_200'] ?? 0,
                        'jumlah_500'                  => $item['lembaran']['jumlah_500'] ?? 0,
                        'jumlah_1000_koin'                 => $item['lembaran']['jumlah_1000_koin'] ?? 0,
                        'jumlah_1000_kertas'                 => $item['lembaran']['jumlah_1000_kertas'] ?? 0,
                        'jumlah_2000'                 => $item['lembaran']['jumlah_2000'] ?? 0,
                        'jumlah_5000'                 => $item['lembaran']['jumlah_5000'] ?? 0,
                        'jumlah_10000'                => $item['lembaran']['jumlah_10000'] ?? 0,
                        'jumlah_20000'                => $item['lembaran']['jumlah_20000'] ?? 0,
                        'jumlah_50000'                => $item['lembaran']['jumlah_50000'] ?? 0,
                        'jumlah_100000'               => $item['lembaran']['jumlah_100000'] ?? 0,
                        'total_persembahan'           => $lembaranTotal,
                    ]);

                    // Perbarui kolom total di tabel BeritaAcaraPersembahanModel
                    $persembahan->update(['total' => $lembaranTotal]);

                    // Tambahkan nilai lembaran ke totalPersembahan
                    $totalPersembahan += $lembaranTotal;
                }
            }

            $berita->update(['total_persembahan' => $totalPersembahan]);

            // log aktivitas
            simpanLogAktivitas('Berita Acara', 'store', "Menambahkan data berita acara: \n"
                . "ID : {$berita->berita_acara_ibadah_id}\n"
                . "Tanggal: {$ibadah->tanggal}\n"
                . "Waktu: {$ibadah->waktu}\n"
                . "Tempat: {$ibadah->tempat}"   
            );
            
            DB::commit();
            return redirect('pengelolaan-berita-acara/berita-acara')->with('success', 'Data berita acara berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in store method: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine(), ['trace' => $e->getTraceAsString()]);
            return redirect('pengelolaan-berita-acara/berita-acara')->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());

            // DB::rollBack();
            // return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    // Fungsi bantu untuk menyimpan base64 tanda tangan sebagai file
    protected function saveBase64Image($base64, $prefix = 'ttd')
    {
        if (!$base64) return null;

        // Validasi apakah base64 memiliki format yang benar
        if (!preg_match('/^data:image\/\w+;base64,/', $base64)) {
            // Jika tidak sesuai, kembalikan null atau error
            return null;
        }

        // Menghilangkan prefix "data:image/png;base64,"
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $image = str_replace(' ', '+', $image);

        // Nama file dengan tambahan prefix, unique ID dan timestamp
        $filename = $prefix . '_' . time() . '_' . uniqid() . '.png';

        // Tentukan path direktori berdasarkan tanggal untuk organisasi file yang lebih baik
        $datePath = date('Y/m/d');
        $directoryPath = 'public/images/ttd/' . $datePath;

        // Pastikan direktori tersedia
        Storage::makeDirectory($directoryPath);

        // Simpan file di folder yang sudah ditentukan
        $filePath = $directoryPath . '/' . $filename;

        try {
            Storage::put($filePath, base64_decode($image));
        } catch (\Exception $e) {
            // Jika terjadi error saat menyimpan, kembalikan null atau log error
            return null;
        }

        // Path yang bisa diakses via URL (symlink ke public/storage harus dibuat)
        return 'storage/images/ttd/' . $datePath . '/' . $filename;
    }

    
    public function show(string $id)
    {
        $berita = BeritaAcaraIbadahModel::findOrFail($id);

        $breadcrumb = (object)[
            'title' => 'Lihat Berita Acara',
            'list' => ['Pengelolaan Ibadah', 'Berita Acara', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Detail berita acara'
        ];

        return view('beritaacara.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'berita' => $berita,
            'activeMenu' => 'beritaacara',
            'notifUser' => UserModel::all()
        ]);
    }

    public function edit(string $id)
    {

        $berita = BeritaAcaraIbadahModel::with([
            'petugas.pelayanJadwal', // Eager load pelayanJadwal for each petugas
            'petugas.pelayanHadir'   // Eager load pelayanHadir for each petugas
            // ... other relations ...
        ])->findOrFail($id);

        // // Filter hanya data dengan jenis_input = 'lembaran'
        // $berita->persembahan = $berita->persembahan->map(function ($item) {
        //     if ($item['jenis_input'] !== 'lembaran') {
        //         $item['lembaran1'] = []; // Kosongkan lembaran1 jika jenis_input bukan 'lembaran'
        //     }
        //     return $item;
        // });

        // dd($berita->persembahan->toArray());

        // @dd($berita->persembahan);
        
        $breadcrumb = (object)[
            'title' => 'Ubah Berita Acara',
            'list' => ['Pengelolaan Ibadah', 'Berita Acara', 'Ubah']
        ];

        $page = (object)[
            'title' => 'Ubah data berita acara'
        ];

        $ibadah = IbadahModel::where('kategoriibadah_id', 1)->get();

        // $pelayan = PelayanModel::whereIn('kategoripelayan_id', [1,2,3,4,14])->get();
        $pelayan = PelayanModel::all();

        $kategoriPersembahan = KategoriPersembahanModel::all();

        return view('beritaacara.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'berita' => $berita,
            'ibadah' => $ibadah,
            'pelayan' => $pelayan,
            'kategoriPersembahan' => $kategoriPersembahan,
            'activeMenu' => 'beritaacara',
            'notifUser' => UserModel::all()
        ]);
    }

    public function update(Request $request, string $id)
    {
        Log::info('Update Request Data:', $request->all()); // Log all incoming request data

        $validator = Validator::make($request->all(), [
            'ibadah_id'         => 'required|exists:t_ibadah,ibadah_id',
            'bacaan_alkitab'    => 'required|string|max:255',
            'jumlah_kehadiran'  => 'required|integer|min:0',
            'catatan'           => 'required|string|max:1000',
            'ttd_pelayan_1_id'  => 'required|exists:t_pelayan,pelayan_id',
            'ttd_pelayan_4_id'  => 'required|exists:t_pelayan,pelayan_id',
            // TTDs are not submitted from edit, so validation is not strictly needed here
            // but keeping them nullable in case of future changes.
            'ttd_pelayan_1'     => 'nullable|string',
            'ttd_pelayan_4'     => 'nullable|string',

            'petugas'                         => 'sometimes|array', // Can be empty if no changes
            'petugas.*.id'                   => 'nullable|integer',
            'petugas.*.peran'                => 'required|string|max:255',
            'petugas.*.pelayan_id_jadwal'    => 'nullable|exists:t_pelayan,pelayan_id',
            'petugas.*.pelayan_id_hadir'     => 'nullable|exists:t_pelayan,pelayan_id',
            'petugas.*.hapus'                => 'sometimes|in:true,false',

            'persembahan'                                 => 'sometimes|array',
            'persembahan.*.id'                            => 'nullable|integer', // ID of existing persembahan
            'persembahan.*.kategori_persembahan_id'       => 'required|exists:t_kategori_persembahan,kategori_persembahan_id',
            'persembahan.*.jenis_input'                   => 'required|in:langsung,lembaran,amplop',
            'persembahan.*.total'                         => 'nullable|numeric|min:0',
            'persembahan.*.hapus'                         => 'sometimes|in:true,false', // Flag for deletion

            'persembahan.*.amplop'                      => 'sometimes|array',
            'persembahan.*.amplop.*.id'                 => 'nullable|integer', // ID of existing amplop
            'persembahan.*.amplop.*.no_amplop'          => 'nullable|string',
            'persembahan.*.amplop.*.nama_pengguna_amplop' => 'nullable|string',
            'persembahan.*.amplop.*.jumlah'             => 'required_with:persembahan.*.amplop|nullable|numeric',
            'persembahan.*.amplop.*.hapus'              => 'sometimes|in:true,false', // Flag for deletion

            'persembahan.*.lembaran'                    => 'sometimes|array',
            'persembahan.*.lembaran.jumlah_100'         => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_200'         => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_500'         => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_1000_koin'        => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_1000_kertas'        => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_2000'        => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_5000'        => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_10000'       => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_20000'       => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_50000'       => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_100000'      => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            Log::error('Validation Errors:', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $berita = BeritaAcaraIbadahModel::findOrFail($id);

            $updateData = [
                'ibadah_id'         => $request->ibadah_id,
                'bacaan_alkitab'    => $request->bacaan_alkitab,
                'jumlah_kehadiran'  => $request->jumlah_kehadiran,
                'catatan'           => $request->catatan,
                'ttd_pelayan_1_id'  => $request->ttd_pelayan_1_id,
                'ttd_pelayan_4_id'  => $request->ttd_pelayan_4_id,
            ];
            
            // TTD images are not editable in the edit form, so no need to handle image updates here.
            // The existing images will remain unless explicitly changed by a different mechanism.

            $berita->update($updateData);

            // Handle Petugas
            if ($request->has('petugas')) {
                $existingPetugasIds = $berita->petugas->pluck('berita_acara_petugas_id')->toArray();
                $submittedPetugasIds = [];

                foreach ($request->petugas as $p_data) {
                    if (isset($p_data['hapus']) && $p_data['hapus'] === 'true' && !empty($p_data['id'])) {
                        BeritaAcaraPetugasModel::where('berita_acara_petugas_id', $p_data['id'])->delete();
                        Log::info('Deleted Petugas:', ['id' => $p_data['id']]);
                        continue;
                    }
                    
                    if (empty($p_data['id'])) { // New Petugas
                        $petugas = BeritaAcaraPetugasModel::create([
                            'berita_acara_ibadah_id' => $berita->berita_acara_ibadah_id,
                            'peran'                  => $p_data['peran'],
                            'pelayan_id_jadwal'      => $p_data['pelayan_id_jadwal'] ?? null,
                            'pelayan_id_hadir'       => $p_data['pelayan_id_hadir'] ?? null,
                        ]);
                        $submittedPetugasIds[] = $petugas->berita_acara_petugas_id;
                        Log::info('Created Petugas:', $petugas->toArray());
                    } else { // Existing Petugas
                        $petugas = BeritaAcaraPetugasModel::find($p_data['id']);
                        if ($petugas) {
                            $petugas->update([
                                'peran'                  => $p_data['peran'],
                                'pelayan_id_jadwal'      => $p_data['pelayan_id_jadwal'] ?? null,
                                'pelayan_id_hadir'       => $p_data['pelayan_id_hadir'] ?? null,
                            ]);
                            $submittedPetugasIds[] = $petugas->berita_acara_petugas_id;
                            Log::info('Updated Petugas:', $petugas->toArray());
                        }
                    }
                }
                // Delete petugas that were not submitted (i.e., removed from the form without explicit hapus=true)
                // This handles cases where JS might remove the item from DOM but not set hapus.
                // Namun, logika JS saat ini *harus* mengatur hapus=true.
                $petugasToDelete = array_diff($existingPetugasIds, $submittedPetugasIds);
                if (!empty($petugasToDelete)) {
                    BeritaAcaraPetugasModel::whereIn('berita_acara_petugas_id', $petugasToDelete)->delete();
                    Log::info('Implicitly Deleted Petugas (not in submission):', $petugasToDelete);
                }
            }

            $totalKeseluruhanPersembahan = 0;

            if ($request->has('persembahan')) {
                $existingPersembahanIds = $berita->persembahan->pluck('berita_acara_persembahan_id')->toArray();
                $submittedPersembahanIds = [];

                foreach ($request->persembahan as $index => $item) {
                    Log::info("Processing Persembahan item [{$index}]:", $item);

                    if (isset($item['hapus']) && $item['hapus'] === 'true') {
                        if (!empty($item['id'])) {
                            $persembahanToDelete = BeritaAcaraPersembahanModel::find($item['id']);
                            if ($persembahanToDelete) {
                                // Hapus data amplop dan lembaran terkait sebelum menghapus persembahan
                                PersembahanAmplopModel::where('berita_acara_persembahan_id', $persembahanToDelete->berita_acara_persembahan_id)->delete();
                                // Asumsikan lembaran terhubung melalui berita_acara_ibadah_id dan kategori_persembahan_id untuk saat ini
                                // Jika PersembahanLembaranModel memiliki FK langsung ke berita_acara_persembahan_id, gunakan itu.
                                // PersembahanLembaranModel::where('berita_acara_persembahan_id', $persembahanToDelete->berita_acara_persembahan_id)->delete(); 
                                $persembahanToDelete->delete();
                                Log::info("Deleted Persembahan and its children:", ['id' => $item['id']]);
                            }
                        }
                        continue; // Lewati item persembahan berikutnya
                    }

                    $persembahanData = [
                        'berita_acara_ibadah_id'    => $berita->berita_acara_ibadah_id,
                        'kategori_persembahan_id'   => $item['kategori_persembahan_id'],
                        'jenis_input'               => trim((string) $item['jenis_input']),
                        'total'                     => 0, // Akan dihitung berdasarkan jenisnya
                    ];

                    $currentPersembahanTotal = 0;

                    if ($item['jenis_input'] === 'langsung') {
                        $currentPersembahanTotal = $item['total'] ?? 0;
                    } elseif ($item['jenis_input'] === 'lembaran' && isset($item['lembaran'])) {
                        $lembaranTotal = (
                            ($item['lembaran']['jumlah_100'] ?? 0) * 100 +
                            ($item['lembaran']['jumlah_200'] ?? 0) * 200 +
                            ($item['lembaran']['jumlah_500'] ?? 0) * 500 +
                            ($item['lembaran']['jumlah_1000_koin'] ?? 0) * 1000 +
                            ($item['lembaran']['jumlah_1000_kertas'] ?? 0) * 1000 +
                            ($item['lembaran']['jumlah_2000'] ?? 0) * 2000 +
                            ($item['lembaran']['jumlah_5000'] ?? 0) * 5000 +
                            ($item['lembaran']['jumlah_10000'] ?? 0) * 10000 +
                            ($item['lembaran']['jumlah_20000'] ?? 0) * 20000 +
                            ($item['lembaran']['jumlah_50000'] ?? 0) * 50000 +
                            ($item['lembaran']['jumlah_100000'] ?? 0) * 100000
                        );
                        $currentPersembahanTotal = $lembaranTotal;

                        $lembaranDataToStore = [
                            // berita_acara_ibadah_id dan kategori_persembahan_id akan digunakan di updateOrCreate sebagai kondisi
                            'jumlah_100'              => $item['lembaran']['jumlah_100'] ?? 0,
                            'jumlah_200'              => $item['lembaran']['jumlah_200'] ?? 0,
                            'jumlah_500'              => $item['lembaran']['jumlah_500'] ?? 0,
                            'jumlah_1000_koin'        => $item['lembaran']['jumlah_1000_koin'] ?? 0,
                            'jumlah_1000_kertas'      => $item['lembaran']['jumlah_1000_kertas'] ?? 0,
                            'jumlah_2000'             => $item['lembaran']['jumlah_2000'] ?? 0,
                            'jumlah_5000'             => $item['lembaran']['jumlah_5000'] ?? 0,
                            'jumlah_10000'            => $item['lembaran']['jumlah_10000'] ?? 0,
                            'jumlah_20000'            => $item['lembaran']['jumlah_20000'] ?? 0,
                            'jumlah_50000'            => $item['lembaran']['jumlah_50000'] ?? 0,
                            'jumlah_100000'           => $item['lembaran']['jumlah_100000'] ?? 0,
                            'total_persembahan'       => $lembaranTotal,
                        ];

                        PersembahanLembaranModel::updateOrCreate(
                            [
                                'berita_acara_ibadah_id'  => $berita->berita_acara_ibadah_id,
                                'kategori_persembahan_id' => $item['kategori_persembahan_id'],
                            ],
                            $lembaranDataToStore
                        );
                        Log::info("Updated/Created Lembaran for Persembahan [{$index}]:", $lembaranDataToStore);

                    } // Pastikan kurung kurawal penutup yang benar, bukan };

                    $persembahanData['total'] = $currentPersembahanTotal;  // Set total awal (untuk langsung/lembaran)

                    // Update or Create Item Persembahan
                    if (!empty($item['id'])) {
                        $persembahan = BeritaAcaraPersembahanModel::find($item['id']);
                        if ($persembahan) {
                            $persembahan->update($persembahanData);
                            Log::info("Updated Persembahan [{$index}]:", $persembahan->toArray());
                        }
                    } else {
                        $persembahan = BeritaAcaraPersembahanModel::create($persembahanData);
                        Log::info("Created Persembahan [{$index}]:", $persembahan->toArray());
                    }
                    $submittedPersembahanIds[] = $persembahan->berita_acara_persembahan_id;

                    // Tangani Amplop untuk item Persembahan ini
                    if ($item['jenis_input'] === 'amplop' && isset($item['amplop'])) {
                        $amplopTotalForThisPersembahan = 0;
                        $existingAmplopIds = $persembahan->amplop->pluck('persembahan_amplop_id')->toArray();
                        $submittedAmplopIds = [];

                        foreach ($item['amplop'] as $a_index => $amplop_data) {
                            Log::info("Processing Amplop [{$index}][{$a_index}]:", $amplop_data);
                            if (isset($amplop_data['hapus']) && $amplop_data['hapus'] === 'true') {
                                if (!empty($amplop_data['id'])) {
                                    PersembahanAmplopModel::where('persembahan_amplop_id', $amplop_data['id'])->delete();
                                    Log::info("Deleted Amplop:", ['id' => $amplop_data['id']]);
                                }
                                continue; // Lewati amplop berikutnya
                            }

                            $amplopRecordData = [
                                'berita_acara_persembahan_id' => $persembahan->berita_acara_persembahan_id,
                                'no_amplop'                   => $amplop_data['no_amplop'] ?? 'AMP' . Str::random(3),
                                'nama_pengguna_amplop'        => $amplop_data['nama_pengguna_amplop'] ?? 'NN',
                                'jumlah'                      => $amplop_data['jumlah'] ?? 0,
                            ];

                            if (!empty($amplop_data['id'])) {
                                $amplopRecord = PersembahanAmplopModel::find($amplop_data['id']);
                                if ($amplopRecord) {
                                    $amplopRecord->update($amplopRecordData);
                                    Log::info("Updated Amplop:", $amplopRecord->toArray());
                                }
                            } else {
                                $amplopRecord = PersembahanAmplopModel::create($amplopRecordData);
                                Log::info("Created Amplop:", $amplopRecord->toArray());
                            }
                            $submittedAmplopIds[] = $amplopRecord->persembahan_amplop_id;
                            $amplopTotalForThisPersembahan += $amplopRecord->jumlah;
                        }
                        // Hapus item amplop yang merupakan bagian dari persembahan ini tetapi tidak ada dalam pengiriman dan tidak ditandai hapus
                        $amplopToDelete = array_diff($existingAmplopIds, $submittedAmplopIds);
                        if(!empty($amplopToDelete)){
                            PersembahanAmplopModel::whereIn('persembahan_amplop_id', $amplopToDelete)->delete();
                            Log::info("Implicitly Deleted Amplop (not in submission for this persembahan):", $amplopToDelete);
                        }

                        // Perbarui total untuk item persembahan ini jika jenisnya amplop
                        $persembahan->update(['total' => $amplopTotalForThisPersembahan]);
                        Log::info("Updated Persembahan [{$index}] total for amplop type:", ['total' => $amplopTotalForThisPersembahan]);
                        $currentPersembahanTotal = $amplopTotalForThisPersembahan; // Ini adalah total sebenarnya untuk persembahan ini
                    }
                    $totalKeseluruhanPersembahan += $currentPersembahanTotal;
                }

                // Hapus item persembahan yang tidak ada dalam pengiriman (dan tidak secara eksplisit ditandai hapus)
                $persembahanItemsToDelete = array_diff($existingPersembahanIds, $submittedPersembahanIds);
                foreach ($persembahanItemsToDelete as $persembahanIdToDelete) {
                    $pDel = BeritaAcaraPersembahanModel::find($persembahanIdToDelete);
                    if ($pDel) {
                        PersembahanAmplopModel::where('berita_acara_persembahan_id', $pDel->berita_acara_persembahan_id)->delete();
                        // Juga hapus lembaran terkait jika perlu, berdasarkan struktur Anda
                        $pDel->delete();
                        Log::info('Implicitly Deleted Persembahan (not in submission):', ['id' => $persembahanIdToDelete]);
                    }
                }
            }

            $berita->update(['total_persembahan' => $totalKeseluruhanPersembahan]);
            Log::info('Final Berita Acara total_persembahan updated:', ['total' => $totalKeseluruhanPersembahan]);

            // log aktivitas
            simpanLogAktivitas('Berita Acara', 'update', "Memperbarui data berita acara: \n"
                . "ID : {$berita->berita_acara_ibadah_id}\n"
                // ... (tambahkan field relevan lainnya jika perlu)
            );

            DB::commit();
            return redirect('pengelolaan-berita-acara/berita-acara')->with('success', 'Data berita acara berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update Exception:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            // Cari data berita acara
            $berita = BeritaAcaraIbadahModel::findOrFail($id);

            // Hapus data terkait di tabel petugas
            BeritaAcaraPetugasModel::where('berita_acara_ibadah_id', $id)->delete();

            // Hapus data terkait di tabel persembahan
            $persembahan = BeritaAcaraPersembahanModel::where('berita_acara_ibadah_id', $id)->get();
            foreach ($persembahan as $item) {
                // Hapus data amplop terkait
                PersembahanAmplopModel::where('berita_acara_persembahan_id', $item->berita_acara_persembahan_id)->delete();

                // Hapus data lembaran terkait
                PersembahanLembaranModel::where('berita_acara_ibadah_id', $id)->delete();
            }

            // Hapus data persembahan
            BeritaAcaraPersembahanModel::where('berita_acara_ibadah_id', $id)->delete();

            // Hapus file gambar tanda tangan jika ada
            if ($berita->ttd_pelayan_1_img) {
                Storage::delete(str_replace('storage/', 'public/', $berita->ttd_pelayan_1_img));
            }
            if ($berita->ttd_pelayan_4_img) {
                Storage::delete(str_replace('storage/', 'public/', $berita->ttd_pelayan_4_img));
            }

            // Hapus data berita acara
            $berita->delete();

            DB::commit();
            return redirect('pengelolaan-berita-acara/berita-acara')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('pengelolaan-berita-acara/berita-acara')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function exportPdf($id)
    {
        $berita = BeritaAcaraIbadahModel::with([
            'ibadah', 'petugas.pelayanJadwal', 'petugas.pelayanHadir',
            'persembahan.kategori', 'persembahan.amplop', 'persembahan.lembaran'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('beritaacara.exportPdf', compact('berita'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('beritaacara_' . $id . '.pdf');
    }

    public function exportPdfAll()
    {
        $semua_berita = BeritaAcaraIbadahModel::with([
            'ibadah', 'petugas.pelayanJadwal', 'petugas.pelayanHadir',
            'persembahan.kategori', 'persembahan.amplop', 'persembahan.lembaran'
        ])->orderBy('created_at', 'desc')->get();
        
        $pdf = Pdf::loadView('beritaacara.exportPdfAll', compact('semua_berita'))
                ->setPaper('a4', 'portrait');
        
                

        return $pdf->download('semua_berita_acara.pdf');
    }

    public function exportPersembahan($id)
    {
        return Excel::download(new PersembahanExport($id), 'persembahan_'. $id .'.xlsx');
    }

}
