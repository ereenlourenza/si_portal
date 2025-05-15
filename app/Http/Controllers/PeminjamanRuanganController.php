<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanRuanganModel;
use App\Models\RuanganModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PeminjamanRuanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('checklevel:ADM');
    }

    public function index(){
        $breadcrumb = (object)[
            'title' => 'Daftar Peminjaman Ruangan',
            'list' => ['Pengelolaan Informasi','Peminjaman Ruangan']
        ];

        $page =(object)[
            'title' => 'Daftar peminjaman ruangan yang terdaftar dalam sistem'
        ];

        $activeMenu = 'peminjamanruangan'; //set menu yang sedang aktif

        $ruangan = RuanganModel::all();

        return view('peminjamanruangan.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'ruangan' => $ruangan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    //Ambil data level dalam bentuk json untuk datatables
    public function list(Request $request){
        $peminjamanruangans = PeminjamanRuanganModel::select('peminjamanruangan_id','peminjam_nama','peminjam_telepon','tanggal','waktu_mulai','waktu_selesai','ruangan_id','keperluan','status','alasan_penolakan')->with('ruangan');

        // Jika ada filter tanggal, tambahkan ke peminj$peminjamanruangans
        if ($request->has('tanggal') && !empty($request->tanggal)) {
            $peminjamanruangans->whereDate('tanggal', $request->tanggal);
        }

        // Jika tidak bentrok, lanjutkan menyimpan data
        return DataTables::of($peminjamanruangans)
            ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
            ->addColumn('waktu', function ($peminjamanruangan) {
                return $peminjamanruangan->waktu_mulai . ' - ' . $peminjamanruangan->waktu_selesai;
            })
            ->editColumn('waktu', function($peminjamanruangan) {
                return  Carbon::parse($peminjamanruangan->waktu_mulai)->format('H:i') . ' - ' . 
                        Carbon::parse($peminjamanruangan->waktu_selesai)->format('H:i');
            })
            ->editColumn('status', function ($peminjamanruangan) {
                if ($peminjamanruangan->status == 1) {
                    return '<span class="text-success font-weight-bold"><em><i class="fas fa-thumbs-up nav-icon"></i> Disetujui</em></span>';
                } elseif ($peminjamanruangan->status == 2) {
                    return '<span class="text-danger font-weight-bold"><em><i class="fas fa-ban nav-icon"></i> Ditolak</em></span>';
                } else {
                    return '<span class="text-warning font-weight-bold"><em><i class="fas fa-exclamation nav-icon"></i> Menunggu Konfirmasi...</em></span>';
                }
            })
            
            ->addColumn('aksi', function ($peminjamanruangan) { // menambahkan kolom aksi
                $btn = '<a href="'.url('/pengelolaan-informasi/peminjamanruangan/updateValidation/' . $peminjamanruangan->peminjamanruangan_id ).'" class="btn btn-dark btn-sm">'.($peminjamanruangan->status == 0 ? 'Setujui' : 'Batalkan' ).'</a> ';

                // Hanya tampilkan tombol "Tolak" jika status belum ditolak (status != 2)
                if ($peminjamanruangan->status != 2) {
                    $btn .= '<button class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#rejectModal'.$peminjamanruangan->peminjamanruangan_id.'">Tolak</button>';

                    // Modal Form untuk Input Alasan Penolakan
                    $btn .= '
                    <div class="modal fade" id="rejectModal'.$peminjamanruangan->peminjamanruangan_id.'" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Tolak Peminjaman Ruangan</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <form method="POST" action="'.url('/pengelolaan-informasi/peminjamanruangan/rejectPeminjaman/'.$peminjamanruangan->peminjamanruangan_id).'">
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

                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/pengelolaan-informasi/peminjamanruangan/'.$peminjamanruangan->peminjamanruangan_id).'">'. csrf_field() . method_field('DELETE') . 
                '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>'; 
                
                return $btn;
            })
            ->rawColumns(['status','aksi']) // memberitahu bahwa kolom aksi adalah html
            ->make(true);
    }

    public function updateValidation($id)
    {
        $peminjamanruangan = PeminjamanRuanganModel::find($id);

        if (!$peminjamanruangan) {
            return redirect('pengelolaan-informasi/peminjamanruangan')->with('error_peminjamanruangan', 'Data tidak ditemukan');
        }

        // Jika statusnya 0 (belum disetujui), maka kita menyetujui peminjaman ini
        if ($peminjamanruangan->status == 0) {
            // Setujui peminjaman ini
            $peminjamanruangan->update(['status' => 1]);

            // Cari peminjaman lain yang bentrok
            $bentrokPeminjaman = PeminjamanRuanganModel::where('tanggal', $peminjamanruangan->tanggal)
                ->where('ruangan_id', $peminjamanruangan->ruangan_id)
                ->where('peminjamanruangan_id', '!=', $peminjamanruangan->peminjamanruangan_id)
                ->where(function ($query) use ($peminjamanruangan) {
                    $query->whereBetween('waktu_mulai', [$peminjamanruangan->waktu_mulai, $peminjamanruangan->waktu_selesai])
                        ->orWhereBetween('waktu_selesai', [$peminjamanruangan->waktu_mulai, $peminjamanruangan->waktu_selesai])
                        ->orWhere(function ($query) use ($peminjamanruangan) {
                            $query->where('waktu_mulai', '<=', $peminjamanruangan->waktu_mulai)
                                ->where('waktu_selesai', '>=', $peminjamanruangan->waktu_selesai);
                        });
                })
                ->where('status', 0) // Pastikan hanya menolak yang belum disetujui/tidak diproses
                ->get();

            // Update semua yang bentrok menjadi ditolak
            foreach ($bentrokPeminjaman as $bentrok) {
                $bentrok->update([
                    'status' => 2, // Ditolak
                    'alasan_penolakan' => 'Bentrok dengan peminjaman yang telah disetujui'
                ]);
            }

            // log aktivitas
            simpanLogAktivitas('Peminjaman Ruangan', 'validation', "Memvalidasi data: \n"
                . "Tanggal: {$peminjamanruangan->tanggal}\n"
                . "Peminjam Nama: {$peminjamanruangan->peminjam_nama}\n"
                . "Keperluan: {$peminjamanruangan->keperluan}\n"
                . "Ruangan: {$peminjamanruangan->ruangan_id}\n"
                . "Waktu: {$peminjamanruangan->waktu_mulai} - {$peminjamanruangan->waktu_selesai}\n"
            );

            return redirect('pengelolaan-informasi/peminjamanruangan')->with('success_peminjamanruangan', 'Peminjaman ruangan berhasil disetujui dan peminjaman lain yang bentrok telah ditolak.');
        }

        // Jika sebelumnya sudah disetujui, ubah status kembali ke belum disetujui
        $peminjamanruangan->update(['status' => 0]);

        // log aktivitas
        simpanLogAktivitas('Peminjaman Ruangan', 'cancel validation', "Batalkan validasi data: \n"
            . "Tanggal: {$peminjamanruangan->tanggal}\n"
            . "Peminjam Nama: {$peminjamanruangan->peminjam_nama}\n"
            . "Keperluan: {$peminjamanruangan->keperluan}\n"
            . "Ruangan: {$peminjamanruangan->ruangan_id}\n"
            . "Waktu: {$peminjamanruangan->waktu_mulai} - {$peminjamanruangan->waktu_selesai}\n"
        );

        return redirect('pengelolaan-informasi/peminjamanruangan')->with('success_peminjamanruangan', 'Persetujuan peminjaman ruangan telah dibatalkan.');
    }


    public function rejectPeminjaman(Request $request, $id)
    {
        $peminjamanruangan = PeminjamanRuanganModel::find($id);

        if (!$peminjamanruangan) {
            return redirect('pengelolaan-informasi/peminjamanruangan')->with('error_peminjamanruangan', 'Data tidak ditemukan');
        }

        // Validasi alasan penolakan
        $request->validate([
            'alasan_penolakan' => 'required|string|max:255'
        ]);

        // Ubah status menjadi 2 (Ditolak) dan simpan alasan
        $peminjamanruangan->update([
            'status' => 2,
            'alasan_penolakan' => $request->alasan_penolakan
        ]);

        // log aktivitas
        simpanLogAktivitas('Peminjaman Ruangan', 'reject', "Menolak data: \n"
            . "Alasan: {$peminjamanruangan->alasan_penolakan}\n"
            . "Tanggal: {$peminjamanruangan->tanggal}\n"
            . "Peminjam Nama: {$peminjamanruangan->peminjam_nama}\n"
            . "Keperluan: {$peminjamanruangan->keperluan}\n"
            . "Ruangan: {$peminjamanruangan->ruangan_id}\n"
            . "Waktu: {$peminjamanruangan->waktu_mulai} - {$peminjamanruangan->waktu_selesai}\n"
        );

        return redirect('pengelolaan-informasi/peminjamanruangan')->with('success_peminjamanruangan', 'Peminjaman ruangan berhasil ditolak dengan alasan: ' . $request->alasan_penolakan);
    }


    //Menampilkan halaman form tambah persembahan $persembahan
    public function create(){
        $breadcrumb = (object)[
            'title' => 'Tambah Peminjaman Ruangan',
            'list' => ['Pengelolaan Informasi', 'Peminjaman Ruangan', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah data peminjaman ruangan baru'
        ];
        
        $activeMenu = 'peminjamanruangan'; //set menu yang sedang aktif

        $ruangan = RuanganModel::all();

        return view('peminjamanruangan.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'ruangan' => $ruangan, 'activeMenu' => $activeMenu, 'notifUser' => UserModel::all()]);
    }

    // //Menyimpan data level baru
    public function store(Request $request){
        $request->validate([
            'peminjam_nama' => 'required|string',
            'peminjam_telepon' => 'required|string',
            'tanggal' => 'required|date_format:Y-m-d',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'ruangan_id' => 'required|integer',
            'keperluan' => 'required|string',
            'status' => 'nullable|boolean',
            'alasan_penolakan' => 'nullable|string',
        ]);
        
        try{

            // Simpan data ke database
            PeminjamanRuanganModel::create([
                'peminjam_nama'  => $request['peminjam_nama'],
                'peminjam_telepon'  => $request['peminjam_telepon'],
                'tanggal'  => $request['tanggal'],
                'waktu_mulai'  => $request['waktu_mulai'],
                'waktu_selesai'  => $request['waktu_selesai'],
                'ruangan_id'  => $request['ruangan_id'],
                'keperluan'  => $request['keperluan'],
                'status'  => $request['status'] ?? 0,
                'alasan_penolakan'  => $request['alasan_penolakan'] ?? null,
            ]);

            // log aktivitas
            simpanLogAktivitas('Peminjaman Ruangan', 'store', "Mengubah data: \n"
                . "Tanggal: {$request->tanggal}\n"
                . "Peminjam Nama: {$request->peminjam_nama}\n"
                . "Keperluan: {$request->keperluan}\n"
                . "Ruangan: {$request->ruangan_id}\n"
                . "Waktu: {$request->waktu_mulai} - {$request->waktu_selesai}\n"
            );
    
            return redirect('pengelolaan-informasi/peminjamanruangan')->with('success_peminjamanruangan', 'Data peminjaman ruangan berhasil disimpan');
        } catch(\Exception $e){
            return redirect('pengelolaan-informasi/peminjamanruangan')->with('error_peminjamanruangan', 'Terjadi kesalahan saat menyimpan data: '  . $e->getMessage());
        }
    }

    public function destroy(string $id){
        $check = PeminjamanRuanganModel::find($id);
        if(!$check){        //untuk mengecek apakah data tata ibadah dengan id yang dimaksud ada atau tidak
            return redirect('pengelolaan-informasi/peminjamanruangan')->with('error_peminjamanruangan', 'Data peminjaman ruangan tidak ditemukan');
        }

        try{
            PeminjamanRuanganModel::destroy($id); //Hapus data peminjamanruangan

            // log aktivitas
            simpanLogAktivitas('Peminjaman Ruangan', 'destroy', "Menghapus data: \n"
                . "Tanggal: {$check->tanggal}\n"
                . "Peminjam Nama: {$check->peminjam_nama}\n"
                . "Keperluan: {$check->keperluan}\n"
                . "Ruangan: {$check->ruangan_id}\n"
                . "Waktu: {$check->waktu_mulai} - {$check->waktu_selesai}\n"
            );

            return redirect('pengelolaan-informasi/peminjamanruangan')->with('success_peminjamanruangan', 'Data peminjaman ruangan berhasil dihapus');
        }catch(\Illuminate\Database\QueryException $e){

            //jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('pengelolaan-informasi/peminjamanruangan')->with('error_peminjamanruangan', 'Data peminjaman ruangan gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }
}
