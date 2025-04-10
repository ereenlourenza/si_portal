<?php

namespace App\Http\Controllers;

use App\Models\IbadahModel;
use App\Models\PersembahanModel;
use App\Models\SejarahModel;
use App\Models\SektorModel;
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

}