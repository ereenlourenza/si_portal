<?php

namespace App\Http\Controllers;

use App\Models\BeritaAcaraIbadahModel;
use App\Models\BeritaAcaraPersembahanModel;
use App\Models\BeritaAcaraPetugasModel;
use App\Models\IbadahModel;
use App\Models\KategoriPersembahanModel;
use App\Models\PelayanModel;
use App\Models\PersembahanAmplopModel;
use App\Models\PersembahanLembaranModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

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
                $btn .= '<form class="d-inline-block" method="POST" action="' . url('/pengelolaan-berita-acara/berita-acara/' . $item->berita_acara_ibadah_id) . '">' .
                    csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Hapus data ini?\');">Hapus</button></form>';
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

        // Ambil hanya ibadah kategori 1
        $ibadah = IbadahModel::where('kategoriibadah_id', 1)->get();

        $pelayan = PelayanModel::whereIn('kategoripelayan_id', [1,2,3,4])->get();

        $kategoriPersembahan = KategoriPersembahanModel::all();

        return view('beritaacara.create', compact(
            'breadcrumb', 'page', 'activeMenu', 'ibadah', 'pelayan', 'kategoriPersembahan'
        ));
    }

    public function store(Request $request)
    {

        // dd($request->all());
        // Log::info('Request Data:', $request->all());

        $validator = Validator::make($request->all(), [
            'ibadah_id'         => 'required|exists:t_ibadah,ibadah_id',
            'bacaan_alkitab'    => 'nullable|string|max:255',
            'jumlah_kehadiran'  => 'nullable|integer|min:0',
            'catatan'           => 'nullable|string|max:1000',
            'ttd_pelayan_1_id'  => 'required|exists:t_pelayan,pelayan_id',
            'ttd_pelayan_4_id'  => 'required|exists:t_pelayan,pelayan_id',
            'ttd_pelayan_1_img'     => 'nullable|string', // base64
            'ttd_pelayan_4_img'     => 'nullable|string', // base64

            'petugas'                         => 'required|array|min:1',
            'petugas.*.peran'                => 'required|string|max:255',
            'petugas.*.pelayan_id_jadwal'    => 'nullable|exists:t_pelayan,pelayan_id',
            'petugas.*.pelayan_id_hadir'     => 'nullable|exists:t_pelayan,pelayan_id',

            'persembahan'                                  => 'required|array|min:1',
            'persembahan.*.kategori_persembahan_id'       => 'required|exists:t_kategori_persembahan,kategori_persembahan_id',
            'persembahan.*.jenis_input'                   => 'required|in:langsung,lembaran,amplop',
            'persembahan.*.total'                         => 'nullable|numeric|min:0',

            'persembahan.*.amplop' => 'required_if:persembahan.*.jenis_input,amplop|array',
            'persembahan.*.amplop.*.no_amplop' => 'required|string',
            'persembahan.*.amplop.*.nama_pengguna_amplop' => 'required|string',
            'persembahan.*.amplop.*.jumlah' => 'required|numeric',

            'persembahan.*.lembaran.jumlah_100'     => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_200'     => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_500'     => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_1000'    => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_2000'    => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_5000'    => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_10000'   => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_20000'   => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_50000'   => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_100000'  => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            // dd($validator->errors());
            return back()->withErrors($validator)->withInput();
        }

        $ibadah = IbadahModel::findOrFail($request->ibadah_id);

        DB::beginTransaction();

        try {
            $berita = BeritaAcaraIbadahModel::create([
                'ibadah_id'         => $request->ibadah_id,
                'bacaan_alkitab'    => $request->bacaan_alkitab,
                // 'pelayan_firman'    => $ibadah->pelayan_firman,
                'jumlah_kehadiran'  => $request->jumlah_kehadiran,
                'catatan'           => $request->catatan,
                'ttd_pelayan_1_id'  => $request->ttd_pelayan_1_id,
                'ttd_pelayan_4_id'  => $request->ttd_pelayan_4_id,
                'ttd_pelayan_1_img' => $this->saveBase64Image($request->ttd_pelayan_1, 'ttd_p1'),
                'ttd_pelayan_4_img' => $this->saveBase64Image($request->ttd_pelayan_4, 'ttd_p4'),
            ]);

            foreach ($request->petugas as $p) {
                Log::info('Data Petugas:', $p);
                BeritaAcaraPetugasModel::create([
                    'berita_acara_ibadah_id' => $berita->berita_acara_ibadah_id,
                    'peran'                  => $p['peran'],
                    'pelayan_id_jadwal'      => $p['pelayan_id_jadwal'] ?? null,
                    'pelayan_id_hadir'       => $p['pelayan_id_hadir'] ?? null,
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

                    foreach ($item['amplop'] as $amplop) {
                        PersembahanAmplopModel::create([
                            'berita_acara_persembahan_id' => $persembahan->berita_acara_persembahan_id,
                            'no_amplop'                   => $amplop['no_amplop'],
                            'nama_pengguna_amplop'        => $amplop['nama_pengguna_amplop'],
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
                        ($item['lembaran']['jumlah_1000'] ?? 0) * 1000 +
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
                        'jumlah_1000'                 => $item['lembaran']['jumlah_1000'] ?? 0,
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
            
            DB::commit();
            return redirect('pengelolaan-berita-acara/berita-acara')->with('success', 'Data berita acara berhasil disimpan');
        } catch (\Exception $e) {
            return redirect('pengelolaan-berita-acara/berita-acara')->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());

            // DB::rollBack();
            // return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    // Fungsi bantu untuk menyimpan base64 tanda tangan sebagai file
    protected function saveBase64Image($base64, $prefix = 'ttd')
    {
        if (!$base64) return null;

        $image = str_replace('data:image/png;base64,', '', $base64);
        $image = str_replace(' ', '+', $image);
        $filename = $prefix . '_' . uniqid() . '.png';
        \Illuminate\Support\Facades\Storage::put('public/ttd/' . $filename, base64_decode($image));

        return 'storage/ttd/' . $filename;
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

        $berita = BeritaAcaraIbadahModel::findOrFail($id);

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

        $pelayan = PelayanModel::whereIn('kategoripelayan_id', [1,2,3,4])->get();

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
        $validator = Validator::make($request->all(), [
            'ibadah_id'         => 'required|exists:t_ibadah,ibadah_id',
            'bacaan_alkitab'    => 'nullable|string|max:255',
            'jumlah_kehadiran'  => 'nullable|integer|min:0',
            'catatan'           => 'nullable|string|max:1000',
            'ttd_pelayan_1_id'  => 'required|exists:t_pelayan,pelayan_id',
            'ttd_pelayan_4_id'  => 'required|exists:t_pelayan,pelayan_id',
            'ttd_pelayan_1_img' => 'nullable|string', // base64
            'ttd_pelayan_4_img' => 'nullable|string', // base64

            'petugas'                         => 'required|array|min:1',
            'petugas.*.peran'                => 'required|string|max:255',
            'petugas.*.pelayan_id_jadwal'    => 'nullable|exists:t_pelayan,pelayan_id',
            'petugas.*.pelayan_id_hadir'     => 'nullable|exists:t_pelayan,pelayan_id',

            'persembahan'                                  => 'required|array|min:1',
            'persembahan.*.id'                              => 'nullable|integer',
            'persembahan.*.kategori_persembahan_id'       => 'required|exists:t_kategori_persembahan,kategori_persembahan_id',
            'persembahan.*.jenis_input'                   => 'required|in:langsung,lembaran,amplop',
            'persembahan.*.total'                         => 'nullable|numeric|min:0',

            'persembahan.*.amplop' => 'required_if:persembahan.*.jenis_input,amplop|array',
            'persembahan.*.amplop.*.no_amplop' => 'required|string',
            'persembahan.*.amplop.*.nama_pengguna_amplop' => 'required|string',
            'persembahan.*.amplop.*.jumlah' => 'required|numeric',

            'persembahan.*.lembaran' => 'required_if:persembahan.*.jenis_input,lembaran|array',
            'persembahan.*.lembaran.jumlah_100'     => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_200'     => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_500'     => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_1000'    => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_2000'    => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_5000'    => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_10000'   => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_20000'   => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_50000'   => 'nullable|integer|min:0',
            'persembahan.*.lembaran.jumlah_100000'  => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $berita = BeritaAcaraIbadahModel::findOrFail($id);

            // Update data berita acara
            $berita->update([
                'ibadah_id'         => $request->ibadah_id,
                'bacaan_alkitab'    => $request->bacaan_alkitab,
                'jumlah_kehadiran'  => $request->jumlah_kehadiran,
                'catatan'           => $request->catatan,
                'ttd_pelayan_1_id'  => $request->ttd_pelayan_1_id,
                'ttd_pelayan_4_id'  => $request->ttd_pelayan_4_id,
                'ttd_pelayan_1_img' => $this->saveBase64Image($request->ttd_pelayan_1, 'ttd_p1'),
                'ttd_pelayan_4_img' => $this->saveBase64Image($request->ttd_pelayan_4, 'ttd_p4'),
            ]);

            // Hapus data petugas lama
            BeritaAcaraPetugasModel::where('berita_acara_ibadah_id', $id)->delete();

            // Simpan data petugas baru
            foreach ($request->petugas as $p) {
                BeritaAcaraPetugasModel::create([
                    'berita_acara_ibadah_id' => $berita->berita_acara_ibadah_id,
                    'peran'                  => $p['peran'],
                    'pelayan_id_jadwal'      => $p['pelayan_id_jadwal'] ?? null,
                    'pelayan_id_hadir'       => $p['pelayan_id_hadir'] ?? null,
                ]);
            }

            $totalPersembahan = 0;

            // Simpan atau perbarui data persembahan
            foreach ($request->persembahan as $item) {

                if (isset($item['hapus']) && $item['hapus'] === 'true') {
                    // Hapus data dari database jika ID ada
                    if (!empty($item['id'])) {
                        BeritaAcaraPersembahanModel::where('berita_acara_persembahan_id', $item['id'])->delete();
                    }
                    continue; // Lewati item ini
                }

                // dd($request->persembahan);

                $persembahan = BeritaAcaraPersembahanModel::updateOrCreate(
                    [
                        'berita_acara_ibadah_id'    => $berita->berita_acara_ibadah_id,
                        'kategori_persembahan_id'   => $item['kategori_persembahan_id'],
                        'jenis_input'               => $item['jenis_input'],
                    ],
                    [
                        'total' => $item['total'] ?? 0,
                    ]
                );

                // Hitung total persembahan
                $totalPersembahan += $persembahan->total;

                // Hitung total untuk jenis input langsung
                // if ($item['jenis_input'] === 'langsung') {
                //     $totalPersembahan += $item['total'] ?? 0;
                // }

                // Simpan data amplop jika jenis input adalah amplop
                if ($item['jenis_input'] === 'amplop' && isset($item['amplop'])) {
                    $amplopTotal = 0;

                    foreach ($item['amplop'] as $amplop) {
                        PersembahanAmplopModel::updateOrCreate(
                            [
                                'berita_acara_persembahan_id' => $persembahan->berita_acara_persembahan_id,
                                'no_amplop'                   => $amplop['no_amplop'],
                            ],
                            [
                                'nama_pengguna_amplop'        => $amplop['nama_pengguna_amplop'],
                                'jumlah'                      => $amplop['jumlah'],
                            ]
                        );

                        $amplopTotal += $amplop['jumlah'] ?? 0;
                    }

                    // Perbarui kolom total di tabel BeritaAcaraPersembahanModel
                    $persembahan->update(['total' => $amplopTotal]);

                    // Tambahkan nilai amplop ke totalPersembahan
                    $totalPersembahan += $amplopTotal;
                }

                // Simpan data lembaran jika jenis input adalah lembaran
                if ($item['jenis_input'] === 'lembaran' && isset($item['lembaran'])) {
                    $lembaranTotal = (
                        ($item['lembaran']['jumlah_100'] ?? 0) * 100 +
                        ($item['lembaran']['jumlah_200'] ?? 0) * 200 +
                        ($item['lembaran']['jumlah_500'] ?? 0) * 500 +
                        ($item['lembaran']['jumlah_1000'] ?? 0) * 1000 +
                        ($item['lembaran']['jumlah_2000'] ?? 0) * 2000 +
                        ($item['lembaran']['jumlah_5000'] ?? 0) * 5000 +
                        ($item['lembaran']['jumlah_10000'] ?? 0) * 10000 +
                        ($item['lembaran']['jumlah_20000'] ?? 0) * 20000 +
                        ($item['lembaran']['jumlah_50000'] ?? 0) * 50000 +
                        ($item['lembaran']['jumlah_100000'] ?? 0) * 100000
                    );
    
                    PersembahanLembaranModel::updateOrCreate(
                        [
                            'berita_acara_ibadah_id'  => $berita->berita_acara_ibadah_id,
                            'kategori_persembahan_id' => $item['kategori_persembahan_id'],
                        ],
                        [
                            'jumlah_100'              => $item['lembaran']['jumlah_100'] ?? 0,
                            'jumlah_200'              => $item['lembaran']['jumlah_200'] ?? 0,
                            'jumlah_500'              => $item['lembaran']['jumlah_500'] ?? 0,
                            'jumlah_1000'             => $item['lembaran']['jumlah_1000'] ?? 0,
                            'jumlah_2000'             => $item['lembaran']['jumlah_2000'] ?? 0,
                            'jumlah_5000'             => $item['lembaran']['jumlah_5000'] ?? 0,
                            'jumlah_10000'            => $item['lembaran']['jumlah_10000'] ?? 0,
                            'jumlah_20000'            => $item['lembaran']['jumlah_20000'] ?? 0,
                            'jumlah_50000'            => $item['lembaran']['jumlah_50000'] ?? 0,
                            'jumlah_100000'           => $item['lembaran']['jumlah_100000'] ?? 0,
                            'total_persembahan'       => $lembaranTotal,
                        ]
                    );
    
                    // Perbarui kolom total di tabel BeritaAcaraPersembahanModel
                    $persembahan->update(['total' => $lembaranTotal]);
    
                    // Tambahkan nilai lembaran ke totalPersembahan
                    $totalPersembahan += $lembaranTotal;
                }
            }

            $berita->update(['total_persembahan' => $totalPersembahan]);

            DB::commit();
            return redirect('pengelolaan-berita-acara/berita-acara')->with('success', 'Data berita acara berhasil diperbarui');
        } catch (\Exception $e) {
            // DB::rollBack();
            return redirect('pengelolaan-berita-acara/berita-acara')->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
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

            // Hapus data berita acara
            $berita->delete();

            DB::commit();
            return redirect('pengelolaan-berita-acara/berita-acara')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('pengelolaan-berita-acara/berita-acara')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
