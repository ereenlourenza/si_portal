<?php

namespace App\Http\Controllers;

use App\Models\BaptisModel;
use App\Models\KatekisasiModel;
use App\Models\PernikahanModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;


class PendaftaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Pendaftaran Sakramen',
            'list' => ['Pengelolaan Informasi','Pendaftaran Sakramen']
        ];

        $page =(object)[
            'title' => 'Daftar pendaftaran sakramen yang terdaftar dalam sistem'
        ];

        $activeMenu = 'pendaftaran'; //set menu yang sedang aktif

        // $ruangan = RuanganModel::all();

        return view('pendaftaran.pilih_form', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request) {
        $jenis = $request->input('jenis');
    
        if ($jenis == 'baptis') {
            $pendaftarans = BaptisModel::select(
                'baptis_id as pendaftaran_id','nama_lengkap','tempat_lahir','tanggal_lahir','jenis_kelamin','nama_ayah','nama_ibu','tempat_pernikahan', 'tanggal_pernikahan', 'tempat_sidi_ayah', 'tanggal_sidi_ayah', 'tempat_sidi_ibu', 'tanggal_sidi_ibu', 'alamat', 'nomor_telepon', 'tanggal_baptis', 'dilayani', 'surat_nikah_ortu', 'akta_kelahiran_anak','status','alasan_penolakan',
                DB::raw('"baptis" as jenis_pendaftaran')
            );
        } elseif ($jenis == 'sidi') {
            $pendaftarans = KatekisasiModel::select(
                'katekisasi_id as pendaftaran_id', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat_katekumen', 'nomor_telepon_katekumen', 'pendidikan_terakhir', 'pekerjaan', 'is_baptis', 'tempat_baptis', 'no_surat_baptis', 'tanggal_surat_baptis', 'dilayani', 'nama_ayah', 'nama_ibu', 'alamat_ortu', 'nomor_telepon_ortu', 'akta_kelahiran', 'surat_baptis', 'pas_foto', 'status', 'alasan_penolakan',
                DB::raw('"sidi" as jenis_pendaftaran')
            );
        } elseif ($jenis == 'pernikahan') {
            $pendaftarans = PernikahanModel::select(
                'pernikahan_id as pendaftaran_id', 'nama_lengkap_pria', 'nama_lengkap_pria', 'tempat_lahir_pria', 'tanggal_lahir_pria', 'tempat_sidi_pria', 'tanggal_sidi_pria', 'pekerjaan_pria', 'alamat_pria', 'nomor_telepon_pria', 'nama_ayah_pria', 'nama_ibu_pria', 'nama_lengkap_wanita', 'nama_lengkap_wanita', 'tempat_lahir_wanita', 'tanggal_lahir_wanita', 'tempat_sidi_wanita', 'tanggal_sidi_wanita', 'pekerjaan_wanita', 'alamat_wanita', 'nomor_telepon_wanita', 'nama_ayah_wanita', 'nama_ibu_wanita', 'tanggal_pernikahan', 'waktu_pernikahan', 'dilayani', 'ktp', 'kk', 'surat_sidi', 'akta_kelahiran', 'sk_nikah', 'sk_asalusul', 'sp_mempelai', 'sk_ortu', 'akta_perceraian_kematian', 'si_kawin_komandan', 'sp_gereja_asal', 'foto', 'biaya', 'status', 'alasan_penolakan',
                DB::raw('"pernikahan" as jenis_pendaftaran')
            );
        } else {
            return response()->json(['error' => 'Jenis pendaftaran tidak valid'], 400);
        }
    
        return DataTables::of($pendaftarans)
            ->addIndexColumn()
            ->editColumn('waktu_pernikahan', function($pendaftaran) {
                return Carbon::parse($pendaftaran->waktu_pernikahan)->format('H:i'); // Format 24 jam (contoh: 14:30)
            })
            ->editColumn('status', function ($pendaftaran) {
                if ($pendaftaran->status == 1) {
                    return '<span class="text-success font-weight-bold"><em><i class="fas fa-thumbs-up nav-icon"></i> Disetujui</em></span>';
                } elseif ($pendaftaran->status == 2) {
                    return '<span class="text-danger font-weight-bold"><em><i class="fas fa-ban nav-icon"></i> Ditolak</em></span>';
                } else {
                    return '<span class="text-warning font-weight-bold"><em><i class="fas fa-exclamation nav-icon"></i> Menunggu Konfirmasi...</em></span>';
                }
            })
            ->addColumn('aksi_status', function ($pendaftaran) {
                $btn = '<a href="'.url('/pengelolaan-informasi/pendaftaran/' . $pendaftaran->pendaftaran_id. '?jenis='.$pendaftaran->jenis_pendaftaran).'" class="btn btn-success btn-sm">Lihat</a> ';
                $btn .= '<a href="'.url('/pengelolaan-informasi/pendaftaran/' . $pendaftaran->pendaftaran_id . '/edit?jenis=' . $pendaftaran->jenis_pendaftaran).'" class="btn btn-warning btn-sm">Ubah</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/pendaftaran/'.$pendaftaran->pendaftaran_id. '?jenis='.$pendaftaran->jenis_pendaftaran).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;

            })
            ->addColumn('aksi', function ($pendaftaran) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/pendaftaran/updateValidation/' . $pendaftaran->pendaftaran_id . '?jenis='.$pendaftaran->jenis_pendaftaran).'" class="btn btn-dark btn-sm">'.($pendaftaran->status == 0 ? 'Setujui' : 'Batalkan' ).'</a> ';

                // Hanya tampilkan tombol "Tolak" jika status belum ditolak (status != 2)
                if ($pendaftaran->status != 2) {
                    $btn .= '<button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#rejectModal'.$pendaftaran->pendaftaran_id.'">Tolak</button>';

                    // Modal Form untuk Input Alasan Penolakan
                    $btn .= '
                    <div class="modal fade" id="rejectModal'.$pendaftaran->pendaftaran_id.'" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tolak Pendaftaran Sakramen</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form method="POST" action="'.url('/pengelolaan-informasi/pendaftaran/rejectPendaftaran/'.$pendaftaran->pendaftaran_id. '?jenis='.$pendaftaran->jenis_pendaftaran).'">
                                    '.csrf_field().'
                                    <div class="modal-body">
                                        <label>Alasan Penolakan:</label>
                                        <textarea name="alasan_penolakan" class="form-control" required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Tolak</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
                }

                return $btn;
            })
            ->rawColumns(['status','aksi_status','aksi'])
            ->make(true);
    }
    
    public function updateValidation(Request $request, $id)
    {
        $jenis = $request->input('jenis');

        if ($jenis == 'baptis') {
            $pendaftaran = BaptisModel::find($id);

            // log aktivitas
            simpanLogAktivitas('Sakramen Baptisan', 'validation', "Memvalidasi data: \n"
                . "Tanggal Baptis: {$pendaftaran->tanggal_baptis}\n"
                . "Nama: {$pendaftaran->nama_lengkap}\n"
            );
        } elseif ($jenis == 'sidi') {
            $pendaftaran = KatekisasiModel::find($id);

            // log aktivitas
            simpanLogAktivitas('Katekisasi', 'validation', "Memvalidasi data: \n"
                . "Tanggal Lahir: {$pendaftaran->tanggal_lahir}\n"
                . "Nama: {$pendaftaran->nama_lengkap}\n"
            );
        } elseif ($jenis == 'pernikahan') {
            $pendaftaran = PernikahanModel::find($id);

            // log aktivitas
            simpanLogAktivitas('Sakramen Pernikahan', 'validation', "Memvalidasi data: \n"
                . "Tanggal Nikah: {$pendaftaran->tanggal_pernikahan}\n"
                . "Nama Pria: {$pendaftaran->nama_lengkap_pria}\n"
                . "Nama Wanita: {$pendaftaran->nama_lengkap_wanita}\n"
            );
        } else {
            return redirect('pengelolaan-informasi/pendaftaran')->with('error_pendaftaran', 'Jenis pendaftaran tidak valid');
        }

        if (!$pendaftaran) {
            return redirect('pengelolaan-informasi/pendaftaran')->with('error_pendaftaran', 'Data tidak ditemukan');
        }

         // Ubah status & simpan pesan sukses yang sesuai
        $pendaftaran->status = $pendaftaran->status == 0 ? 1 : 0;
        $pendaftaran->save();

        $pesan = $pendaftaran->status == 1 
            ? 'Pendaftaran sakramen telah disetujui.' 
            : 'Persetujuan pendaftaran sakramen telah dibatalkan.';

        if ($jenis == 'baptis') {

            // log aktivitas
            simpanLogAktivitas('Sakramen Baptisan', 'cancel validation', "Batalkan validasi data: \n"
                . "Tanggal Baptis: {$pendaftaran->tanggal_baptis}\n"
                . "Nama: {$pendaftaran->nama_lengkap}\n"
            );
        } elseif ($jenis == 'sidi') {

            // log aktivitas
            simpanLogAktivitas('Katekisasi', 'cancel validation', "Batalkan validasi data: \n"
                . "Tanggal Lahir: {$pendaftaran->tanggal_lahir}\n"
                . "Nama: {$pendaftaran->nama_lengkap}\n"
            );
        } elseif ($jenis == 'pernikahan') {

            // log aktivitas
            simpanLogAktivitas('Sakramen Pernikahan', 'cancel validation', "Batakan validasi data: \n"
                . "Tanggal Nikah: {$pendaftaran->tanggal_pernikahan}\n"
                . "Nama Pria: {$pendaftaran->nama_lengkap_pria}\n"
                . "Nama Wanita: {$pendaftaran->nama_lengkap_wanita}\n"
            );
        }

        return redirect('pengelolaan-informasi/pendaftaran')->with('success_pendaftaran', $pesan);
    }


    public function rejectPendaftaran(Request $request, $id)
    {
        $jenis = $request->input('jenis');

        if ($jenis == 'baptis') {
            $pendaftaran = BaptisModel::find($id);

            // log aktivitas
            simpanLogAktivitas('Sakramen Baptisan', 'reject', "Menolak data: \n"
                . "Alasan: {$pendaftaran->alasan_penolakan}\n"
                . "Tanggal Baptis: {$pendaftaran->tanggal_baptis}\n"
                . "Nama: {$pendaftaran->nama_lengkap}\n"
            );
        } elseif ($jenis == 'sidi') {
            $pendaftaran = KatekisasiModel::find($id);

            // log aktivitas
            simpanLogAktivitas('Katekisasi', 'reject', "Menolak data: \n"
                . "Alasan: {$pendaftaran->alasan_penolakan}\n"    
                . "Tanggal Lahir: {$pendaftaran->tanggal_lahir}\n"
                . "Nama: {$pendaftaran->nama_lengkap}\n"
            );
        } elseif ($jenis == 'pernikahan') {
            $pendaftaran = PernikahanModel::find($id);

            // log aktivitas
            simpanLogAktivitas('Sakramen Pernikahan', 'reject', "Menolak data: \n"
                . "Alasan: {$pendaftaran->alasan_penolakan}\n"
                . "Tanggal Nikah: {$pendaftaran->tanggal_pernikahan}\n"
                . "Nama Pria: {$pendaftaran->nama_lengkap_pria}\n"
                . "Nama Wanita: {$pendaftaran->nama_lengkap_wanita}\n"
            );
        } else {
            return redirect('pengelolaan-informasi/pendaftaran')->with('error_pendaftaran', 'Jenis pendaftaran tidak valid');
        }

        if (!$pendaftaran) {
            return redirect('pengelolaan-informasi/pendaftaran')->with('error_pendaftaran', 'Data tidak ditemukan');
        }

        // Validasi alasan penolakan
        $request->validate([
            'alasan_penolakan' => 'required|string|max:255'
        ]);

        // Ubah status menjadi 2 (Ditolak) dan simpan alasan
        $pendaftaran->update([
            'status' => 2,
            'alasan_penolakan' => $request->alasan_penolakan
        ]);

        return redirect('pengelolaan-informasi/pendaftaran')
            ->with('success_pendaftaran', 'Pendaftaran sakramen berhasil ditolak dengan alasan: ' . $request->alasan_penolakan);
    }


    public function create(Request $request)
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Pendaftaran Sakramen ' . ucfirst($request->jenis),
            'list' => ['Pengelolaan Pendaftaran Sakramen', 'Pendaftaran Sakramen ', ucfirst($request->jenis)]
        ];

        $page = (object)[
            'title' => 'Tambah pendaftaran sakramen baru'
        ];

        $activeMenu = 'pendaftaran'; //set menu yang sedang aktif

        $jenis = $request->jenis;

        if (!in_array($jenis, ['baptis', 'sidi', 'pernikahan'])) {
            abort(404);
        }

        return view("pendaftaran.form_$jenis", ['breadcrumb' => $breadcrumb, 'page' => $page, 'jenis' => $jenis, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    public function store(Request $request)
    {
        try {
            $jenis = $request->jenis;

            // Validasi berdasarkan jenis pendaftaran
            if ($jenis === 'baptis') {
                $validatedData = $request->validate([
                    'nama_lengkap' => 'required|string|max:255',
                    'tempat_lahir' => 'required|string',
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required|string',
                    'nama_ayah' => 'required|string',
                    'nama_ibu' => 'required|string',
                    'tempat_pernikahan' => 'required|string',
                    'tanggal_pernikahan' => 'required|date',
                    'tempat_sidi_ayah' => 'required|string',
                    'tanggal_sidi_ayah' => 'required|date',
                    'tempat_sidi_ibu' => 'required|string',
                    'tanggal_sidi_ibu' => 'required|date',
                    'alamat' => 'required|string',
                    'nomor_telepon' => 'required|string',
                    'tanggal_baptis' => 'required|date',
                    'dilayani' => 'required|string',
                    'surat_nikah_ortu' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                    'akta_kelahiran_anak' => 'required|image|mimes:jpg,jpeg,png|max:2048',
                    'status' => 'nullable|boolean',
                    'alasan_penolakan' => 'nullable|string',
                ]);

                $model = new BaptisModel();
                $folder = 'baptis';

                // log aktivitas
                simpanLogAktivitas('Sakramen Baptisan', 'store', "Menambahkan data: \n"
                    . "Tanggal Baptis: {$request->tanggal_baptis}\n"
                    . "Nama: {$request->nama_lengkap}\n"
                );

            } elseif ($jenis === 'sidi') {
                $validatedData = $request->validate([
                    'nama_lengkap' => 'required|string|max:255',
                    'tempat_lahir' => 'required|string',
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required|string',
                    'alamat_katekumen' => 'required|string', 
                    'nomor_telepon_katekumen' => 'required|string', 
                    'pendidikan_terakhir' => 'required|string', 
                    'pekerjaan' => 'required|string', 
                    'is_baptis' => 'required|string',
                    'tempat_baptis' => 'nullable|string',
                    'no_surat_baptis' => 'nullable|string',
                    'tanggal_surat_baptis' => 'nullable|date',
                    'dilayani' => 'nullable|string',
                    'nama_ayah' => 'nullable|string', 
                    'nama_ibu' => 'nullable|string', 
                    'alamat_ortu' => 'nullable|string', 
                    'nomor_telepon_ortu' => 'nullable|string', 
                    'akta_kelahiran' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
                    'surat_baptis' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'pas_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
                    'status' => 'nullable|boolean',
                    'alasan_penolakan' => 'nullable|string'
                ]);

                $model = new KatekisasiModel();
                $folder = 'sidi';

                // log aktivitas
                simpanLogAktivitas('Katekisasi', 'store', "Menambahkan data: \n"
                    . "Tanggal Lahir: {$request->tanggal_lahir}\n"
                    . "Nama: {$request->nama_lengkap}\n"
                );

            } elseif ($jenis === 'pernikahan') {
                $validatedData = $request->validate([
                    'nama_lengkap_pria' => 'required|string|max:255',
                    'tempat_lahir_pria' => 'required|string',
                    'tanggal_lahir_pria' => 'required|date',
                    'tempat_sidi_pria' => 'required|string', 
                    'tanggal_sidi_pria' => 'required|date',
                    'pekerjaan_pria' => 'required|string', 
                    'alamat_pria' => 'required|string', 
                    'nomor_telepon_pria' => 'required|string', 
                    'nama_ayah_pria' => 'required|string', 
                    'nama_ibu_pria' => 'required|string', 
                    'nama_lengkap_wanita' => 'required|string|max:255',
                    'tempat_lahir_wanita' => 'required|string',
                    'tanggal_lahir_wanita' => 'required|date',
                    'tempat_sidi_wanita' => 'required|string', 
                    'tanggal_sidi_wanita' => 'required|date',
                    'pekerjaan_wanita' => 'required|string', 
                    'alamat_wanita' => 'required|string', 
                    'nomor_telepon_wanita' => 'required|string', 
                    'nama_ayah_wanita' => 'required|string', 
                    'nama_ibu_wanita' => 'required|string', 
                    'tanggal_pernikahan' => 'required|date', 
                    'waktu_pernikahan' => 'required|date_format:H:i', 
                    'dilayani' => 'required|string', 
                    'ktp' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
                    'kk' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
                    'surat_sidi' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
                    'akta_kelahiran' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
                    'sk_nikah' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
                    'sk_asalusul' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
                    'sp_mempelai' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
                    'sk_ortu' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
                    'akta_perceraian_kematian' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'si_kawin_komandan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'sp_gereja_asal' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
                    'biaya' => 'required|image|mimes:jpg,jpeg,png|max:2048', 
                    'status' => 'nullable|boolean',
                    'alasan_penolakan' => 'nullable|string'
                ]);

                $model = new PernikahanModel();
                $folder = 'pernikahan';

                // log aktivitas
                simpanLogAktivitas('Sakramen Pernikahan', 'store', "Menambahkan data: \n"
                    . "Tanggal Nikah: {$request->tanggal_pernikahan}\n"
                    . "Nama Pria: {$request->nama_lengkap_pria}\n"
                    . "Nama Wanita: {$request->nama_lengkap_wanita}\n"
                );
            } else {
                return redirect()->back()->with('error_pendaftaran', 'Jenis pendaftaran tidak valid.');
            }

            // Proses penyimpanan file
            foreach ($request->allFiles() as $key => $file) {
                $filename = Str::random(10) . '-' . $file->getClientOriginalName();
                $file->storeAs("public/images/$folder", $filename);
                $validatedData[$key] = $filename;
            }

            // Simpan ke database dengan model yang sesuai
            $model->create($validatedData);
            
            return redirect('pengelolaan-informasi/pendaftaran')->with('success_pendaftaran', 'Pendaftaran berhasil dikirim.');
        } catch (\Exception $e) {
            return redirect('pengelolaan-informasi/pendaftaran')->with('error_pendaftaran', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function show($id, Request $request)
    {

        $breadcrumb = (object)[
            'title' => 'Lihat Pendaftaran Sakramen',
            'list'  => ['Pengelolaan Informasi', 'Pendaftaran Sakramen', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat pendaftaran sakramen'
        ];

        $activeMenu = 'pendaftaran'; //set menu yang sedang aktif
        
        $jenis = $request->jenis;

        // Pastikan pencarian dilakukan berdasarkan jenis yang sesuai
        if ($jenis == 'baptis') {
            $pendaftaran = BaptisModel::find($id);
            if ($pendaftaran) {
                $pendaftaran->jenis_pendaftaran = 'baptis';
                return view('pendaftaran.show_baptis', compact('breadcrumb', 'page', 'pendaftaran', 'activeMenu'));
            }
        } elseif ($jenis == 'sidi') {
            $pendaftaran = KatekisasiModel::find($id);
            if ($pendaftaran) {
                $pendaftaran->jenis_pendaftaran = 'sidi';
                return view('pendaftaran.show_sidi', compact('breadcrumb', 'page', 'pendaftaran', 'activeMenu'));
            }
        } elseif ($jenis == 'pernikahan') {
            $pendaftaran = PernikahanModel::find($id);
            if ($pendaftaran) {
                $pendaftaran->jenis_pendaftaran = 'pernikahan';
                return view('pendaftaran.show_pernikahan', compact('breadcrumb', 'page', 'pendaftaran', 'activeMenu'));
            }
        }

        // Jika tidak ditemukan
        return redirect()->back()->with('error', 'Data pendaftaran tidak ditemukan.');
    }

    public function edit(Request $request, $id)
    {
        $breadcrumb = (object)[
            'title' => 'Edit Pendaftaran Sakramen ' . ucfirst($request->jenis),
            'list' => ['Pengelolaan Pendaftaran Sakramen', 'Pendaftaran Sakramen ', ucfirst($request->jenis), 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit pendaftaran sakramen'. ucfirst($request->jenis)
        ];

        $activeMenu = 'pendaftaran'; //set menu yang sedang aktif

        $jenis = $request->input('jenis');

        if ($jenis == 'baptis') {
            $pendaftaran = BaptisModel::find($id);
        } elseif ($jenis == 'sidi') {
            $pendaftaran = KatekisasiModel::find($id);
        } elseif ($jenis == 'pernikahan') {
            $pendaftaran = PernikahanModel::find($id);
        } else {
            return redirect('pengelolaan-informasi/pendaftaran')->with('error_pendaftaran', 'Jenis pendaftaran tidak valid');
        }

        if (!$pendaftaran) {
            return redirect('pengelolaan-informasi/pendaftaran')->with('error_pendaftaran', 'Data tidak ditemukan');
        }

        return view("pendaftaran.edit_$jenis", ['breadcrumb' => $breadcrumb, 'page' => $page, 'pendaftaran' => $pendaftaran, 'jenis' => $jenis, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    public function update(Request $request, $id)
    {
        try{
            $jenis = $request->input('jenis');

            if ($jenis == 'baptis') {
                $pendaftaran = BaptisModel::find($id);
            } elseif ($jenis == 'sidi') {
                $pendaftaran = KatekisasiModel::find($id);
            } elseif ($jenis == 'pernikahan') {
                $pendaftaran = PernikahanModel::find($id);
            } else {
                return redirect('pengelolaan-informasi/pendaftaran')->with('error_pendaftaran', 'Jenis pendaftaran tidak valid');
            }

            if (!$pendaftaran) {
                return redirect('pengelolaan-informasi/pendaftaran')->with('error_pendaftaran', 'Data tidak ditemukan');
            }

            // Validasi berdasarkan jenis pendaftaran
            if ($jenis === 'baptis') {
                $validatedData = $request->validate([
                    'nama_lengkap' => 'required|string|max:255',
                    'tempat_lahir' => 'required|string',
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required|string',
                    'nama_ayah' => 'required|string',
                    'nama_ibu' => 'required|string',
                    'tempat_pernikahan' => 'required|string',
                    'tanggal_pernikahan' => 'required|date',
                    'tempat_sidi_ayah' => 'required|string',
                    'tanggal_sidi_ayah' => 'required|date',
                    'tempat_sidi_ibu' => 'required|string',
                    'tanggal_sidi_ibu' => 'required|date',
                    'alamat' => 'required|string',
                    'nomor_telepon' => 'required|string',
                    'tanggal_baptis' => 'required|date',
                    'dilayani' => 'required|string',
                    'surat_nikah_ortu' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'akta_kelahiran_anak' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                    'status' => 'nullable|boolean',
                    'alasan_penolakan' => 'nullable|string',
                ]);

                $folder = 'baptis';

                // log aktivitas
                simpanLogAktivitas('Sakramen Baptisan', 'update', "Mengubah data: \n"
                    . "Tanggal: {$request->tanggal_baptis}\n"
                    . "Nama: {$request->nama_lengkap}\n"
                );
            } elseif ($jenis === 'sidi'){
                $validatedData = $request->validate([
                    'nama_lengkap' => 'required|string|max:255',
                    'tempat_lahir' => 'required|string',
                    'tanggal_lahir' => 'required|date',
                    'jenis_kelamin' => 'required|string',
                    'alamat_katekumen' => 'required|string', 
                    'nomor_telepon_katekumen' => 'required|string', 
                    'pendidikan_terakhir' => 'required|string', 
                    'pekerjaan' => 'required|string', 
                    'is_baptis' => 'required|string',
                    'tempat_baptis' => 'nullable|string',
                    'no_surat_baptis' => 'nullable|string',
                    'tanggal_surat_baptis' => 'nullable|date',
                    'dilayani' => 'nullable|string',
                    'nama_ayah' => 'nullable|string', 
                    'nama_ibu' => 'nullable|string', 
                    'alamat_ortu' => 'nullable|string', 
                    'nomor_telepon_ortu' => 'nullable|string', 
                    'akta_kelahiran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'surat_baptis' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'pas_foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'status' => 'nullable|boolean',
                    'alasan_penolakan' => 'nullable|string',
                ]);

                $folder = 'sidi';

                // log aktivitas
                simpanLogAktivitas('Katekisasi', 'update', "Mengubah data: \n"
                    . "Tanggal Lahir: {$request->tanggal_lahir}\n"
                    . "Nama: {$request->nama_lengkap}\n"
                );
            } elseif ($jenis === 'pernikahan'){
                $validatedData = $request->validate([
                    'nama_lengkap_pria' => 'required|string|max:255',
                    'tempat_lahir_pria' => 'required|string',
                    'tanggal_lahir_pria' => 'required|date',
                    'tempat_sidi_pria' => 'required|string', 
                    'tanggal_sidi_pria' => 'required|date',
                    'pekerjaan_pria' => 'required|string', 
                    'alamat_pria' => 'required|string', 
                    'nomor_telepon_pria' => 'required|string', 
                    'nama_ayah_pria' => 'required|string', 
                    'nama_ibu_pria' => 'required|string', 
                    'nama_lengkap_wanita' => 'required|string|max:255',
                    'tempat_lahir_wanita' => 'required|string',
                    'tanggal_lahir_wanita' => 'required|date',
                    'tempat_sidi_wanita' => 'required|string', 
                    'tanggal_sidi_wanita' => 'required|date',
                    'pekerjaan_wanita' => 'required|string', 
                    'alamat_wanita' => 'required|string', 
                    'nomor_telepon_wanita' => 'required|string', 
                    'nama_ayah_wanita' => 'required|string', 
                    'nama_ibu_wanita' => 'required|string', 
                    'tanggal_pernikahan' => 'required|date', 
                    'waktu_pernikahan' => 'required|date_format:H:i', 
                    'dilayani' => 'required|string', 
                    'ktp' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'kk' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'surat_sidi' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'akta_kelahiran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'sk_nikah' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'sk_asalusul' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'sp_mempelai' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'sk_ortu' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'akta_perceraian_kematian' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'si_kawin_komandan' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'sp_gereja_asal' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', 
                    'biaya' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',  
                    'status' => 'nullable|boolean',
                    'alasan_penolakan' => 'nullable|string'
                ]);

                $folder = 'pernikahan';

                // log aktivitas
                simpanLogAktivitas('Sakramen Pernikahan', 'update', "Mengubah data: \n"
                    . "Tanggal Nikah: {$request->tanggal_pernikahan}\n"
                    . "Nama Pria: {$request->nama_lengkap_pria}\n"
                    . "Nama Wanita: {$request->nama_lengkap_wanita}\n"
                );
            } else {
                return redirect()->back()->with('error_pendaftaran', 'Jenis pendaftaran tidak valid.');
            }

            // Proses penyimpanan file
            foreach ($request->allFiles() as $key => $file) {
                $cleanName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());
                $filename = Str::random(10) . '-' . $cleanName;
                if ($pendaftaran->$key && Storage::exists("public/images/$folder/" . $pendaftaran->$key)) {
                    Storage::delete("public/images/$folder/" . $pendaftaran->$key);
                }
                $file->storeAs("public/images/$folder", $filename);
                $validatedData[$key] = $filename;
            }

            // Proses update data pendaftaran
            $pendaftaran->update($validatedData);

            return redirect('pengelolaan-informasi/pendaftaran')->with('success_pendaftaran', 'Pendaftaran sakramen berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect('pengelolaan-informasi/pendaftaran')->with('error_pendaftaran', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id, Request $request)
    {
        $jenis = $request->input('jenis');

        if ($jenis == 'baptis') {
            $pendaftaran = BaptisModel::find($id);

            // log aktivitas
            simpanLogAktivitas('Sakramen Baptisan', 'destroy', "Menghapus data: \n"
                . "Tanggal: {$pendaftaran->tanggal_baptis}\n"
                . "Nama: {$pendaftaran->nama_lengkap}\n"
            );
        } elseif ($jenis == 'sidi') {
            $pendaftaran = KatekisasiModel::find($id);

            // log aktivitas
            simpanLogAktivitas('Katekisasi', 'destroy', "Menghapus data: \n"
                . "Tanggal Lahir: {$pendaftaran->tanggal_lahir}\n"
                . "Nama: {$pendaftaran->nama_lengkap}\n"
            );
        } elseif ($jenis == 'pernikahan') {
            $pendaftaran = PernikahanModel::find($id);

            // log aktivitas
            simpanLogAktivitas('Sakramen Pernikahan', 'destroy', "Menghapus data: \n"
                . "Tanggal Nikah: {$pendaftaran->tanggal_pernikahan}\n"
                . "Nama Pria: {$pendaftaran->nama_lengkap_pria}\n"
                . "Nama Wanita: {$pendaftaran->nama_lengkap_wanita}\n"
            );
        } else {
            return redirect('pengelolaan-informasi/pendaftaran')->with('error_pendaftaran', 'Jenis pendaftaran tidak valid');
        }

        if (!$pendaftaran) {
            return redirect('pengelolaan-informasi/pendaftaran')->with('error_pendaftaran', 'Data tidak ditemukan');
        }

        // Hapus semua file terkait
        $folder = $jenis;
        foreach ($pendaftaran->getAttributes() as $key => $value) {
            if ($value && is_string($value) && preg_match('/\.(jpg|jpeg|png)$/i', $value)) {
                $path = "public/images/$folder/" . $value;
                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
            }
        }

        // Hapus data dari database
        $pendaftaran->delete();

        return redirect('pengelolaan-informasi/pendaftaran')->with('success_pendaftaran', 'Data pendaftaran berhasil dihapus.');
    }



}
