<?php

namespace App\Http\Controllers;

use App\Models\BaptisModel;
use App\Models\GaleriModel;
use App\Models\IbadahModel;
use App\Models\KategoriGaleriModel;
use App\Models\KategoriPelayanModel;
use App\Models\KatekisasiModel;
use App\Models\KomisiModel;
use App\Models\PelayanModel;
use App\Models\PelkatModel;
use App\Models\PeminjamanRuanganModel;
use App\Models\PernikahanModel;
use App\Models\PersembahanModel;
use App\Models\PHMJModel;
use App\Models\RuanganModel;
use App\Models\SejarahModel;
use App\Models\SektorModel;
use App\Models\TataIbadahModel;
use App\Models\WartaJemaatModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $jumlah_keluarga = SektorModel::sum('jumlah_jemaat');
        $jumlah_sektor = SektorModel::count();

        return view('global.home', ['jumlah_keluarga' => $jumlah_keluarga, 'jumlah_sektor' => $jumlah_sektor]);
    }

    public function sejarah()
    {
        $sejarah = SejarahModel::all(); // Ambil semua data sejarah

        return view('global.sejarah-gereja', ['sejarah' => $sejarah]);
    }

    public function sektor()
    {
        $sektor = SektorModel::all(); // Ambil semua data sejarah

        return view('global.wilayah-pelayanan', ['sektor' => $sektor]);
    }

    private function cariIbadah($data, $lokasiKeyword, $jam)
    {
        return $data->first(function ($item) use ($lokasiKeyword, $jam) {
            $tempat = Str::lower($item->tempat); // jadiin lowercase
            $waktu = Carbon::parse($item->waktu)->format('H:i'); // ambil jam-nya aja

            return Str::contains($tempat, Str::lower($lokasiKeyword)) && $waktu === $jam;
        });
    }

    public function ibadah()
    {
        // Ambil semua data ibadah + relasi kategorinya
        $ibadah = IbadahModel::with('kategoriibadah')->get();

        // Kelompokkan berdasarkan nama kategori ibadah
        $ibadahByKategori = $ibadah->groupBy(function ($item) {
            return $item->kategoriibadah->kategoriibadah_nama;
        });

        // Tanggal Minggu terdekat
        $tanggal_minggu = Carbon::now();
        $hariIni = $tanggal_minggu->dayOfWeek;
        if ($hariIni !== Carbon::SUNDAY) {
            $tanggal_minggu->addDays((7 - $hariIni));
        }

        // Tanggal Rabu terdekat
        $tanggal_rabu = Carbon::now();
        $hariIni = $tanggal_rabu->dayOfWeek;
        $daysToAdd = (Carbon::WEDNESDAY - $hariIni + 7) % 7;
        $tanggal_rabu->addDays($daysToAdd);

        // Filter data berdasarkan tanggal minggu terdekat
        $jadwalMinggu = ($ibadahByKategori['Ibadah Minggu'] ?? collect())->filter(function ($item) use ($tanggal_minggu) {
            return Carbon::parse($item->tanggal)->toDateString() === $tanggal_minggu->toDateString();
        });

        // Ambil kategori lain tanpa filter
        $jadwalRabu = $ibadahByKategori['Ibadah Keluarga'] ?? collect();
        $jadwalSyukur = $ibadahByKategori['Ibadah Pengucapan Syukur'] ?? collect();
        $jadwalDiakonia = $ibadahByKategori['Ibadah Diakonia'] ?? collect();
        $jadwalPelkat = $ibadahByKategori['Ibadah Pelkat'] ?? collect();

        // Ambil ibadah sesuai lokasi dan jam
        $ebed_pagi = $this->cariIbadah($jadwalMinggu, 'ebed', '06:00');
        $immanuel_pagi = $this->cariIbadah($jadwalMinggu, 'immanuel', '08:00');
        $pakisaji = $this->cariIbadah($jadwalMinggu, 'pakisaji', '09:00');
        $immanuel_sore = $this->cariIbadah($jadwalMinggu, 'immanuel', '17:00');

        return view('global.ibadah-rutin', [
            'jadwalMinggu' => $jadwalMinggu,
            'jadwalRabu' => $jadwalRabu,
            'jadwalSyukur' => $jadwalSyukur,
            'jadwalDiakonia' => $jadwalDiakonia,
            'jadwalPelkat' => $jadwalPelkat,
            'tanggal_minggu' => $tanggal_minggu,
            'tanggal_rabu' => $tanggal_rabu,
            'ebed_pagi' => $ebed_pagi,
            'immanuel_pagi' => $immanuel_pagi,
            'pakisaji' => $pakisaji,
            'immanuel_sore' => $immanuel_sore,
        ]);
    }

    public function persembahan()
    {
        $pengucapan_syukur = PersembahanModel::whereRaw('LOWER(persembahan_nama) = ?', ['pengucapan syukur'])->first();
        $persembahan_lain = PersembahanModel::whereRaw('LOWER(persembahan_nama) != ?', ['pengucapan syukur'])->get();


        return view('global.persembahan', compact('pengucapan_syukur', 'persembahan_lain'));
    }

    public function showLatestVideo()
    {
        $rss = simplexml_load_file('https://www.youtube.com/feeds/videos.xml?channel_id=UCyLvl8HZpe4tDFbnT3eNnVw');

        if (!$rss || !isset($rss->entry[0])) {
            $videoId = null;
        } else {
            $rawId = (string)$rss->entry[0]->id; // Contoh: yt:video:VIDEO_ID
            $videoId = str_replace('yt:video:', '', $rawId);
        }

        return view('global.kanal-youtube', compact('videoId'));
    }

    public function baptisCreate()
    {
        $page = (object)[
            'title' => 'Form Pendaftaran Baptis'
        ];

        return view('global.baptisan-form', [
            'page' => $page
        ]);
    }

    public function baptisStore(Request $request)
    {
        try {
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
            ]);

            foreach ($request->allFiles() as $key => $file) {
                $filename = Str::random(10) . '-' . $file->getClientOriginalName();
                $file->storeAs('public/images/baptis', $filename);
                $validatedData[$key] = $filename;
            }

            $validatedData['status'] = 0; // default status "belum diverifikasi"
            $validatedData['alasan_penolakan'] = null;

            BaptisModel::create($validatedData);

            return redirect('pelayanan/pelayanan-jemaat/baptisan')->with('success_baptisan', 'Pendaftaran baptis berhasil dikirim! Silahkan pantau status verifikasi melalui halaman status pendaftaran');
        } catch (\Exception $e) {
            return redirect('pelayanan/pelayanan-jemaat/baptisan')->with('error_baptisan', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function baptisStatus(Request $request)
    {
        $query = BaptisModel::query();

        // Filter pencarian berdasarkan nama
        if ($request->filled('q')) {
            $query->where('nama_lengkap', 'like', '%' . $request->q . '%');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil dari terbaru
        $data = $query->orderByDesc('created_at')->get();

        return view('global.baptisan-status', compact('data'));
    }

    public function katekisasiCreate()
    {
        $page = (object)[
            'title' => 'Form Pendaftaran Katekisasi'
        ];

        return view('global.katekisasi-form', [
            'page' => $page
        ]);
    }

    public function katekisasiStore(Request $request)
    {
        try {
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

            foreach ($request->allFiles() as $key => $file) {
                $filename = Str::random(10) . '-' . $file->getClientOriginalName();
                $file->storeAs('public/images/sidi', $filename);
                $validatedData[$key] = $filename;
            }

            // Pastikan key surat_baptis tetap ada walaupun tidak diupload
            $validatedData += [
                'surat_baptis' => null
            ];

            $validatedData['status'] = 0; // default status "belum diverifikasi"
            $validatedData['alasan_penolakan'] = null;

            KatekisasiModel::create($validatedData);

            return redirect('pelayanan/pelayanan-jemaat/katekisasi')->with('success_katekisasi', 'Pendaftaran katekisasi berhasil dikirim! Silahkan pantau status verifikasi melalui halaman status pendaftaran');
        } catch (\Exception $e) {
            return redirect('pelayanan/pelayanan-jemaat/katekisasi')->with('error_katekisasi', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function katekisasiStatus(Request $request)
    {
        $query = KatekisasiModel::query();

        // Filter pencarian berdasarkan nama
        if ($request->filled('q')) {
            $query->where('nama_lengkap', 'like', '%' . $request->q . '%');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil dari terbaru
        $data = $query->orderByDesc('created_at')->get();

        return view('global.katekisasi-status', compact('data'));
    }

    public function pernikahanCreate()
    {
        $page = (object)[
            'title' => 'Form Pendaftaran Pemberkatan Nikah'
        ];

        return view('global.pernikahan-form', [
            'page' => $page
        ]);
    }

    public function pernikahanStore(Request $request)
    {
        try {
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

            foreach ($request->allFiles() as $key => $file) {
                $filename = Str::random(10) . '-' . $file->getClientOriginalName();
                $file->storeAs('public/images/pernikahan', $filename);
                $validatedData[$key] = $filename;
            }

            $validatedData['status'] = 0; // default status "belum diverifikasi"
            $validatedData['alasan_penolakan'] = null;

            PernikahanModel::create($validatedData);

            return redirect('pelayanan/pelayanan-jemaat/pemberkatan-nikah')->with('success_pernikahan', 'Pendaftaran pemberkatan nikah berhasil dikirim! Silahkan pantau status verifikasi melalui halaman status pendaftaran');
        } catch (\Exception $e) {
            return redirect('pelayanan/pelayanan-jemaat/pemberkatan-nikah')->with('error_pernikahan', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function pernikahanStatus(Request $request)
    {
        $query = PernikahanModel::query();

        // Filter pencarian berdasarkan nama
        if ($request->filled('q')) {
            $query->where('nama_lengkap_pria', 'like', '%' . $request->q . '%');
        }

        // Filter pencarian berdasarkan nama
        if ($request->filled('x')) {
            $query->where('nama_lengkap_wanita', 'like', '%' . $request->x . '%');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil dari terbaru
        $data = $query->orderByDesc('created_at')->get();

        return view('global.pernikahan-status', compact('data'));
    }

    
    public function ruanganCreate()
    {
        $page = (object)[
            'title' => 'Form Peminjaman Ruangan'
        ];

        $ruangan = RuanganModel::all();

        return view('global.ruangan-form', [
            'page' => $page, 'ruangan' => $ruangan
        ]);
    }

    public function ruanganStore(Request $request)
    {
        try {
            $validatedData = $request->validate([
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

            PeminjamanRuanganModel::create($validatedData);
    
            return redirect('pelayanan/pelayanan-jemaat/peminjaman-ruangan')->with('success_ruangan', 'Pengajuan peminjaman ruangan berhasil dikirim! Silahkan pantau status verifikasi melalui halaman status pendaftaran');
        } catch(\Exception $e){
            return redirect('pelayanan/pelayanan-jemaat/peminjaman-ruangan')->with('error_ruangan', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function ruanganStatus(Request $request)
    {
        $query = PeminjamanRuanganModel::query();

        // Filter pencarian berdasarkan nama
        if ($request->filled('q')) {
            $query->where('peminjam_nama', 'like', '%' . $request->q . '%');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Ambil dari terbaru
        $data = $query->orderByDesc('created_at')->get();

        return view('global.ruangan-status', compact('data'));
    }

    public function pendeta()
    {
        // Ambil kategori dengan nama "Pendeta" (bisa disesuaikan berdasarkan kode juga)
        $kategoriPendeta = KategoriPelayanModel::where('kategoripelayan_nama', 'Pendeta')->first();

        if (!$kategoriPendeta) {
            return abort(404, 'Kategori Pendeta tidak ditemukan.');
        }

        // Ambil semua pelayan yang merupakan pendeta
        $pendetaList = PelayanModel::where('kategoripelayan_id', $kategoriPendeta->kategoripelayan_id)
            ->orderByDesc('masa_jabatan_mulai')
            ->get();

        return view('global.pendeta-kmj', compact('pendetaList'));
    }

    public function vikaris()
    {
        // Ambil kategori dengan nama "Vikaris"
        $kategoriVikaris = KategoriPelayanModel::where('kategoripelayan_nama', 'Vikaris')->first();
        
        if (!$kategoriVikaris) {
            return abort(404, 'Kategori Vikaris tidak ditemukan.');
        }

        // Ambil tahun ini
        $tahunSekarang = date('Y');

        // Ambil semua pelayan vikaris yang sedang bertugas tahun ini
        $vikarisList = PelayanModel::where('kategoripelayan_id', $kategoriVikaris->kategoripelayan_id)
            ->where(function ($query) use ($tahunSekarang) {
                $query->where('masa_jabatan_mulai', '<=', $tahunSekarang)
                    ->where(function ($subQuery) use ($tahunSekarang) {
                        $subQuery->where('masa_jabatan_selesai', '>=', $tahunSekarang)
                                ->orWhereNull('masa_jabatan_selesai');
                    });
            })
            ->orderByDesc('masa_jabatan_mulai')
            ->get();

        return view('global.vikaris', compact('vikarisList'));
    }

    public function phmj()
    {
        // Ambil ID kategori untuk Diaken dan Penatua
        $kategoriPendeta = KategoriPelayanModel::where('kategoripelayan_nama', 'Pendeta')->first();
        $kategoriDiaken = KategoriPelayanModel::where('kategoripelayan_nama', 'Diaken')->first();
        $kategoriPenatua = KategoriPelayanModel::where('kategoripelayan_nama', 'Penatua')->first();

        if (!$kategoriPendeta || !$kategoriDiaken || !$kategoriPenatua) {
            return abort(404, 'Kategori Pendeta, Diaken dan Penatua tidak ditemukan.');
        }

        $phmjList = PHMJModel::whereHas('pelayan', function ($query) use ($kategoriPendeta, $kategoriDiaken, $kategoriPenatua) {
                $query->whereIn('kategoripelayan_id', [$kategoriPendeta->kategoripelayan_id, $kategoriDiaken->kategoripelayan_id, $kategoriPenatua->kategoripelayan_id]);
            })
            ->with('pelayan.kategoripelayan')
            ->orderByDesc('periode_mulai')
            ->get();


        return view('global.phmj', compact('phmjList'));
    }

    public function majelisJemaat(Request $request)
    {
        // Ambil semua data Diaken & Penatua
        $allMajelis = PelayanModel::with('kategoripelayan')
            ->whereHas('kategoripelayan', function ($query) {
                $query->whereIn('kategoripelayan_nama', ['Diaken', 'Penatua']);
            })
            ->orderBy('masa_jabatan_mulai', 'desc')
            ->orderBy('nama')
            ->get();

        // Kelompokkan berdasarkan periode
        $groupedPeriode = $allMajelis->groupBy(function ($item) {
            return $item->masa_jabatan_mulai . ' - ' . $item->masa_jabatan_selesai;
        });

        // Cek apakah ada periode dipilih dari dropdown
        $selectedPeriode = $request->input('periode');

        if ($selectedPeriode) {
            [$mulai, $selesai] = explode(' - ', $selectedPeriode);
            $periode_terpilih = $allMajelis->where('masa_jabatan_mulai', $mulai)->where('masa_jabatan_selesai', $selesai);
        } else {
            // default: ambil periode terkini
            $selesai_tertinggi = $allMajelis->max('masa_jabatan_selesai');
            $periode_terpilih = $allMajelis->where('masa_jabatan_selesai', $selesai_tertinggi);
            $mulai = $periode_terpilih->min('masa_jabatan_mulai');
            $selesai = $selesai_tertinggi;
            $selectedPeriode = "$mulai - $selesai";
        }

        return view('global.majelis-jemaat', [
            'periode_terpilih' => $periode_terpilih,
            'groupedPeriode' => $groupedPeriode,
            'selectedPeriode' => $selectedPeriode
        ]);
    }

    public function pelkatpa(){
        $pelkat_pa = PelkatModel::whereRaw("LOWER(pelkat_nama) LIKE ?", ['%pa%'])->get();

        return view('global.pelkat-pa', compact('pelkat_pa'));
    }

    public function pelkatpt(){
        $pelkat_pt = PelkatModel::whereRaw("LOWER(pelkat_nama) LIKE ?", ['%pt%'])->get();

        return view('global.pelkat-pt', compact('pelkat_pt'));
    }

    public function pelkatgp(){
        $pelkat_gp = PelkatModel::whereRaw("LOWER(pelkat_nama) LIKE ?", ['%gp%'])->get();

        return view('global.pelkat-gp', compact('pelkat_gp'));
    }

    public function pelkatpkp(){
        $pelkat_pkp = PelkatModel::whereRaw("LOWER(pelkat_nama) LIKE ?", ['%pkp%'])->get();

        return view('global.pelkat-pkp', compact('pelkat_pkp'));
    }

    public function pelkatpkb(){
        $pelkat_pkb = PelkatModel::whereRaw("LOWER(pelkat_nama) LIKE ?", ['%pkb%'])->get();

        return view('global.pelkat-pkb', compact('pelkat_pkb'));
    }

    public function pelkatpklu(){
        $pelkat_pklu = PelkatModel::whereRaw("LOWER(pelkat_nama) LIKE ?", ['%pklu%'])->get();

        return view('global.pelkat-pklu', compact('pelkat_pklu'));
    }

    public function komisiteologi(){
        $komisi_teologi = KomisiModel::whereRaw("LOWER(komisi_nama) LIKE ?", ['%teologi%'])->get();

        return view('global.komisi-teologi', compact('komisi_teologi'));
    }

    public function komisipelkes(){
        $komisi_pelkes = KomisiModel::whereRaw("LOWER(komisi_nama) LIKE ?", ['%pelkes%'])->get();

        return view('global.komisi-pelkes', compact('komisi_pelkes'));
    }

    public function komisipeg(){
        $komisi_peg = KomisiModel::whereRaw("LOWER(komisi_nama) LIKE ?", ['%peg%'])->get();

        return view('global.komisi-peg', compact('komisi_peg'));
    }

    public function komisigermasa(){
        $komisi_germasa = KomisiModel::whereRaw("LOWER(komisi_nama) LIKE ?", ['%germasa%'])->get();

        return view('global.komisi-germasa', compact('komisi_germasa'));
    }

    public function komisippsdi(){
        $komisi_ppsdi = KomisiModel::whereRaw("LOWER(komisi_nama) LIKE ?", ['%ppsdi%'])->get();

        return view('global.komisi-ppsdi', compact('komisi_ppsdi'));
    }

    public function komisiinforkomlitbang(){
        $komisi_inforkomlitbang = KomisiModel::whereRaw("LOWER(komisi_nama) LIKE ?", ['%inforkom-litbang%'])->get();

        return view('global.komisi-inforkomlitbang', compact('komisi_inforkomlitbang'));
    }

    public function bppj(){
        $bppj = KomisiModel::whereRaw("LOWER(komisi_nama) LIKE ?", ['%bppj%'])->get();

        return view('global.bppj', compact('bppj'));
    }

    public function kantor(){
        $kantor = KomisiModel::whereRaw("LOWER(komisi_nama) LIKE ?", ['%kantor%'])->get();

        return view('global.kantor', compact('kantor'));
    }

    public function tataibadah(Request $request)
    {
        $tanggal = $request->input('tanggal');

        $tataIbadahList = TataIbadahModel::when($tanggal, function ($query, $tanggal) {
                                    return $query->whereDate('tanggal', $tanggal);
                                }, function ($query) {
                                    return $query->take(3); // ambil 3 teratas kalau tidak ada filter
                                })
                                ->orderBy('tanggal', 'desc')
                                ->get();

        return view('global.tata-ibadah', compact('tataIbadahList', 'tanggal'));
    }

    public function wartajemaat(Request $request)
    {
        $tanggal = $request->input('tanggal');

        $wartaJemaatList = WartaJemaatModel::when($tanggal, function ($query, $tanggal) {
                                    return $query->whereDate('tanggal', $tanggal);
                                }, function ($query) {
                                    return $query->take(3); // ambil 3 teratas kalau tidak ada filter
                                })
                                ->orderBy('tanggal', 'desc')
                                ->get();

        return view('global.warta-jemaat', compact('wartaJemaatList', 'tanggal'));
    }


    public function galeri()
    {
        // Ambil semua kategori galeri
        $kategoriList = KategoriGaleriModel::all();

        // Inisialisasi array hasil
        $galeriByKategori = [];

        foreach ($kategoriList as $kategori) {
            $galeriByKategori[$kategori->kategorigaleri_nama] = GaleriModel::where('kategorigaleri_id', $kategori->kategorigaleri_id)
                ->latest()
                ->take(3) // ambil 3 teratas seperti contoh halamanmu
                ->get();
        }

        return view('global.galeri', compact('galeriByKategori'));
    }

    public function galeriByKategori($id)
    {
        // Ambil data kategori
        $kategori = KategoriGaleriModel::findOrFail($id);

        // Ambil semua galeri dalam kategori ini
        $galeriList = GaleriModel::where('kategorigaleri_id', $id)
            ->latest()
            ->get();

        return view('global.galeri-kategori', compact('kategori', 'galeriList'));
    }





}