<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\SektorModel;
use App\Models\SejarahModel;
use App\Models\IbadahModel;
use App\Models\KategoriIbadahModel;
use App\Models\BaptisModel;
use App\Models\KatekisasiModel;
use App\Models\PernikahanModel;
use App\Models\PeminjamanRuanganModel;
use App\Models\RuanganModel;
use App\Models\KategoriPelayanModel;
use App\Models\PelayanModel;
use App\Models\PHMJModel;
use App\Models\PelkatModel;
use App\Models\KomisiModel;
use App\Models\TataIbadahModel;
use App\Models\WartaJemaatModel;
use App\Models\KategoriGaleriModel;
use App\Models\GaleriModel;
use App\Models\PersembahanModel;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase; // Use this trait to reset the database for each test

    /**
     * Test the index method.
     *
     * @return void
     */
    public function test_index_method()
    {
        // Mock SektorModel
        SektorModel::factory()->count(3)->create(['jumlah_jemaat' => 10]);
        SektorModel::factory()->count(2)->create(['jumlah_jemaat' => 5]);

        $response = $this->get(url('/beranda')); // Assuming 'home' is the route name for HomeController@index

        $response->assertStatus(200);
        $response->assertViewIs('global.home');
        $response->assertViewHas('jumlah_keluarga', 40); // (3*10) + (2*5)
        $response->assertViewHas('jumlah_sektor', 5);
    }

    /**
     * Test the sejarah method.
     *
     * @return void
     */
    public function test_sejarah_method()
    {
        // Mock SejarahModel
        $sejarahData = SejarahModel::factory()->count(3)->create();

        $response = $this->get(route('sejarah-gereja')); // Assuming 'sejarah' is the route name

        $response->assertStatus(200);
        $response->assertViewIs('global.sejarah-gereja');
        $response->assertViewHas('sejarah', function ($viewSejarah) use ($sejarahData) {
            return $viewSejarah->count() === $sejarahData->count();
        });
    }

    /**
     * Test the sektor method.
     *
     * @return void
     */
    public function test_sektor_method()
    {
        // Mock SektorModel
        $sektorData = SektorModel::factory()->count(5)->create();

        $response = $this->get(route('wilayah-pelayanan')); // Assuming 'sektor' is the route name

        $response->assertStatus(200);
        $response->assertViewIs('global.wilayah-pelayanan');
        $response->assertViewHas('sektor', function ($viewSektor) use ($sektorData) {
            return $viewSektor->count() === $sektorData->count();
        });
    }

    /**
     * Test the ibadah method.
     *
     * @return void
     */
    public function test_ibadah_method()
    {
        // Mock KategoriIbadahModel
        $kategoriMinggu = KategoriIbadahModel::factory()->create(['kategoriibadah_nama' => 'Ibadah Minggu']);
        $kategoriRabu = KategoriIbadahModel::factory()->create(['kategoriibadah_nama' => 'Ibadah Keluarga']);

        // Mock IbadahModel
        // Ibadah Minggu for the upcoming Sunday
        $tanggal_minggu = Carbon::now();
        if ($tanggal_minggu->dayOfWeek !== Carbon::SUNDAY) {
            $tanggal_minggu->next(Carbon::SUNDAY);
        }
        IbadahModel::factory()->create([
            'kategoriibadah_id' => $kategoriMinggu->kategoriibadah_id,
            'tanggal' => $tanggal_minggu->toDateString(),
            'tempat' => 'Gereja Immanuel',
            'waktu' => '08:00:00'
        ]);
        IbadahModel::factory()->create([
            'kategoriibadah_id' => $kategoriMinggu->kategoriibadah_id,
            'tanggal' => $tanggal_minggu->toDateString(),
            'tempat' => 'Gereja Ebed',
            'waktu' => '06:00:00'
        ]);
         IbadahModel::factory()->create([
            'kategoriibadah_id' => $kategoriMinggu->kategoriibadah_id,
            'tanggal' => $tanggal_minggu->toDateString(),
            'tempat' => 'Gereja Pakisaji',
            'waktu' => '09:00:00'
        ]);
        IbadahModel::factory()->create([
            'kategoriibadah_id' => $kategoriMinggu->kategoriibadah_id,
            'tanggal' => $tanggal_minggu->toDateString(),
            'tempat' => 'Gereja Immanuel (Sore)',
            'waktu' => '17:00:00'
        ]);


        // Ibadah Keluarga
        IbadahModel::factory()->count(2)->create([
            'kategoriibadah_id' => $kategoriRabu->kategoriibadah_id
        ]);

        $response = $this->get(route('ibadah-rutin')); // Assuming 'ibadah.rutin' is the route name

        $response->assertStatus(200);
        $response->assertViewIs('global.ibadah-rutin');
        $response->assertViewHas('jadwalMinggu');
        $response->assertViewHas('jadwalRabu');
        $response->assertViewHas('ebed_pagi');
        $response->assertViewHas('immanuel_pagi');
        $response->assertViewHas('pakisaji');
        $response->assertViewHas('immanuel_sore');

        $this->assertNotNull($response->viewData('ebed_pagi'));
        $this->assertNotNull($response->viewData('immanuel_pagi'));
        $this->assertNotNull($response->viewData('pakisaji'));
        $this->assertNotNull($response->viewData('immanuel_sore'));
        $this->assertEquals('06:00', Carbon::parse($response->viewData('ebed_pagi')->waktu)->format('H:i'));
        $this->assertEquals('08:00', Carbon::parse($response->viewData('immanuel_pagi')->waktu)->format('H:i'));
        $this->assertEquals('09:00', Carbon::parse($response->viewData('pakisaji')->waktu)->format('H:i'));
        $this->assertEquals('17:00', Carbon::parse($response->viewData('immanuel_sore')->waktu)->format('H:i'));
    }

    /**
     * Test the persembahan method.
     *
     * @return void
     */
    public function test_persembahan_method()
    {
        PersembahanModel::factory()->create(['persembahan_nama' => 'Pengucapan Syukur']);
        // Create unique names for "Persembahan Lain"
        PersembahanModel::factory()->create(['persembahan_nama' => 'Persembahan Lain 1']);
        PersembahanModel::factory()->create(['persembahan_nama' => 'Persembahan Lain 2']);
        PersembahanModel::factory()->create(['persembahan_nama' => 'Persembahan Lain 3']);

        $response = $this->get(route('persembahan'));

        $response->assertStatus(200);
        $response->assertViewIs('global.persembahan');
        $response->assertViewHas('pengucapan_syukur');
        $response->assertViewHas('persembahan_lain');
        $this->assertNotNull($response->viewData('pengucapan_syukur'));
        $this->assertCount(3, $response->viewData('persembahan_lain'));
    }

    /**
     * Test the showLatestVideo method.
     *
     * @return void
     */
    public function test_show_latest_video_method()
    {
        // This test mainly checks if the view is returned correctly.
        // Mocking the simplexml_load_file call can be complex and might be overkill
        // unless specific videoId assertions are critical.
        $response = $this->get(route('kanal-youtube'));

        $response->assertStatus(200);
        $response->assertViewIs('global.kanal-youtube');
        $response->assertViewHas('videoId'); // Asserts that videoId is passed, even if null
    }

    /**
     * Test the pendeta method.
     *
     * @return void
     */
    public function test_pendeta_method()
    {
        $kategoriPendeta = KategoriPelayanModel::factory()->create(['kategoripelayan_nama' => 'Pendeta']);
        PelayanModel::factory()->count(3)->create(['kategoripelayan_id' => $kategoriPendeta->kategoripelayan_id]);

        $response = $this->get(route('pendeta-kmj'));

        $response->assertStatus(200);
        $response->assertViewIs('global.pendeta-kmj');
        $response->assertViewHas('pendetaList', function ($pendetaList) {
            return $pendetaList->count() === 3;
        });
    }

    /**
     * Test the vikaris method.
     *
     * @return void
     */
    public function test_vikaris_method()
    {
        $kategoriVikaris = KategoriPelayanModel::factory()->create(['kategoripelayan_nama' => 'Vikaris']);
        // $tahunSekarang = date('Y');
        // PelayanModel::factory()->create([
        //     'kategoripelayan_id' => $kategoriVikaris->kategoripelayan_id,
        //     'masa_jabatan_mulai' => $tahunSekarang . '-01-01',
        //     'masa_jabatan_selesai' => $tahunSekarang . '-12-31',
        // ]);
        PelayanModel::factory()->create([
            'kategoripelayan_id' => $kategoriVikaris->kategoripelayan_id,
            // 'masa_jabatan_mulai' => ($tahunSekarang - 1) . '-01-01',
            // 'masa_jabatan_selesai' => ($tahunSekarang - 1) . '-12-31', // Not current year
        ]);

        $response = $this->get(route('vikaris'));

        $response->assertStatus(200);
        $response->assertViewIs('global.vikaris');
        $response->assertViewHas('vikarisList', function ($vikarisList) {
            return $vikarisList->count() === 1;
        });
    }

    /**
     * Test the phmj method.
     *
     * @return void
     */
    public function test_phmj_method()
    {
        $kategoriPendeta = KategoriPelayanModel::factory()->create(['kategoripelayan_nama' => 'Pendeta']);
        $kategoriDiaken = KategoriPelayanModel::factory()->create(['kategoripelayan_nama' => 'Diaken']);
        $kategoriPenatua = KategoriPelayanModel::factory()->create(['kategoripelayan_nama' => 'Penatua']);

        $pelayan1 = PelayanModel::factory()->create(['kategoripelayan_id' => $kategoriPendeta->kategoripelayan_id]);
        $pelayan2 = PelayanModel::factory()->create(['kategoripelayan_id' => $kategoriDiaken->kategoripelayan_id]);

        PHMJModel::factory()->create(['pelayan_id' => $pelayan1->pelayan_id]);
        PHMJModel::factory()->create(['pelayan_id' => $pelayan2->pelayan_id]);

        $response = $this->get(route('phmj'));

        $response->assertStatus(200);
        $response->assertViewIs('global.phmj');
        $response->assertViewHas('phmjList', function ($phmjList) {
            return $phmjList->count() === 2;
        });
    }

    /**
     * Test the majelisJemaat method.
     *
     * @return void
     */
    public function test_majelis_jemaat_method()
    {
        $kategoriDiaken = KategoriPelayanModel::factory()->create(['kategoripelayan_nama' => 'Diaken']);
        $kategoriPenatua = KategoriPelayanModel::factory()->create(['kategoripelayan_nama' => 'Penatua']);

        PelayanModel::factory()->count(2)->create([
            'kategoripelayan_id' => $kategoriDiaken->kategoripelayan_id,
            'masa_jabatan_mulai' => '2023',
            'masa_jabatan_selesai' => '2025'
        ]);
        PelayanModel::factory()->count(3)->create([
            'kategoripelayan_id' => $kategoriPenatua->kategoripelayan_id,
            'masa_jabatan_mulai' => '2023',
            'masa_jabatan_selesai' => '2025'
        ]);
        PelayanModel::factory()->count(1)->create([
            'kategoripelayan_id' => $kategoriDiaken->kategoripelayan_id,
            'masa_jabatan_mulai' => '2020',
            'masa_jabatan_selesai' => '2022'
        ]);

        // Test default (latest period)
        $response = $this->get(route('majelis-jemaat'));
        $response->assertStatus(200);
        $response->assertViewIs('global.majelis-jemaat');
        $response->assertViewHas('periode_terpilih', function ($periode) {
            return $periode->count() === 5; // 2 Diaken + 3 Penatua in 2023-2025
        });
        $response->assertViewHas('selectedPeriode', '2023 - 2025');

        // Test with specific period
        $response = $this->get(route('majelis-jemaat', ['periode' => '2020 - 2022']));
        $response->assertStatus(200);
        $response->assertViewHas('periode_terpilih', function ($periode) {
            return $periode->count() === 1; // 1 Diaken in 2020-2022
        });
        $response->assertViewHas('selectedPeriode', '2020 - 2022');
    }

    /**
     * Test the pelkat pa method.
     *
     * @return void
     */
    public function test_pelkat_pa_method()
    {
        PelkatModel::factory()->create(['pelkat_nama' => 'Pelkat PA Test']);
        PelkatModel::factory()->create(['pelkat_nama' => 'Pelkat PT Other']); // Should not be fetched

        $response = $this->get(route('pa'));

        $response->assertStatus(200);
        $response->assertViewIs('global.pelkat-pa');
        $response->assertViewHas('pelkat_pa', function ($pelkat_pa) {
            return $pelkat_pa->count() === 1 && Str::contains(Str::lower($pelkat_pa->first()->pelkat_nama), 'pa');
        });
    }

    /**
     * Test the pelkat pt method.
     *
     * @return void
     */
    public function test_pelkat_pt_method()
    {
        PelkatModel::factory()->create(['pelkat_nama' => 'Pelkat PT Test']);
        PelkatModel::factory()->create(['pelkat_nama' => 'Pelkat GP Other']); 

        $response = $this->get(route('pt'));

        $response->assertStatus(200);
        $response->assertViewIs('global.pelkat-pt');
        $response->assertViewHas('pelkat_pt', function ($pelkat_pt) {
            return $pelkat_pt->count() === 1 && Str::contains(Str::lower($pelkat_pt->first()->pelkat_nama), 'pt');
        });
    }

    /**
     * Test the pelkat gp method.
     *
     * @return void
     */
    public function test_pelkat_gp_method()
    {
        PelkatModel::factory()->create(['pelkat_nama' => 'Pelkat GP Test']);
        $response = $this->get(route('gp'));
        $response->assertStatus(200);
        $response->assertViewIs('global.pelkat-gp');
        $response->assertViewHas('pelkat_gp', function ($data) {
            return $data->count() === 1 && Str::contains(Str::lower($data->first()->pelkat_nama), 'gp');
        });
    }

    /**
     * Test the pelkat pkp method.
     *
     * @return void
     */
    public function test_pelkat_pkp_method()
    {
        PelkatModel::factory()->create(['pelkat_nama' => 'Pelkat PKP Test']);
        $response = $this->get(route('pkp'));
        $response->assertStatus(200);
        $response->assertViewIs('global.pelkat-pkp');
        $response->assertViewHas('pelkat_pkp', function ($data) {
            return $data->count() === 1 && Str::contains(Str::lower($data->first()->pelkat_nama), 'pkp');
        });
    }

    /**
     * Test the pelkat pkb method.
     *
     * @return void
     */
    public function test_pelkat_pkb_method()
    {
        PelkatModel::factory()->create(['pelkat_nama' => 'Pelkat PKB Test']);
        $response = $this->get(route('pkb'));
        $response->assertStatus(200);
        $response->assertViewIs('global.pelkat-pkb');
        $response->assertViewHas('pelkat_pkb', function ($data) {
            return $data->count() === 1 && Str::contains(Str::lower($data->first()->pelkat_nama), 'pkb');
        });
    }

    /**
     * Test the pelkat pklu method.
     *
     * @return void
     */
    public function test_pelkat_pklu_method()
    {
        PelkatModel::factory()->create(['pelkat_nama' => 'Pelkat PKLU Test']);
        $response = $this->get(route('pklu'));
        $response->assertStatus(200);
        $response->assertViewIs('global.pelkat-pklu');
        $response->assertViewHas('pelkat_pklu', function ($data) {
            return $data->count() === 1 && Str::contains(Str::lower($data->first()->pelkat_nama), 'pklu');
        });
    }

    /**
     * Test the komisiteologi method.
     *
     * @return void
     */
    public function test_komisi_teologi_method()
    {
        KomisiModel::factory()->create(['komisi_nama' => 'Komisi Teologi Test']);
        KomisiModel::factory()->create(['komisi_nama' => 'Komisi Pelkes Other']);

        $response = $this->get(route('teologi'));

        $response->assertStatus(200);
        $response->assertViewIs('global.komisi-teologi');
        $response->assertViewHas('komisi_teologi', function ($data) {
            return $data->count() === 1 && Str::contains(Str::lower($data->first()->komisi_nama), 'teologi');
        });
    }

    /**
     * Test the komisipelkes method.
     *
     * @return void
     */
    public function test_komisi_pelkes_method()
    {
        KomisiModel::factory()->create(['komisi_nama' => 'Komisi Pelkes Test']);
        $response = $this->get(route('pelkes'));
        $response->assertStatus(200);
        $response->assertViewIs('global.komisi-pelkes');
        $response->assertViewHas('komisi_pelkes', function ($data) {
            return $data->count() === 1 && Str::contains(Str::lower($data->first()->komisi_nama), 'pelkes');
        });
    }

    /**
     * Test the komisipeg method.
     *
     * @return void
     */
    public function test_komisi_peg_method()
    {
        KomisiModel::factory()->create(['komisi_nama' => 'Komisi PEG Test']);
        $response = $this->get(route('peg'));
        $response->assertStatus(200);
        $response->assertViewIs('global.komisi-peg');
        $response->assertViewHas('komisi_peg', function ($data) {
            return $data->count() === 1 && Str::contains(Str::lower($data->first()->komisi_nama), 'peg');
        });
    }

    /**
     * Test the komisigermasa method.
     *
     * @return void
     */
    public function test_komisi_germasa_method()
    {
        KomisiModel::factory()->create(['komisi_nama' => 'Komisi Germasa Test']);
        $response = $this->get(route('germasa'));
        $response->assertStatus(200);
        $response->assertViewIs('global.komisi-germasa');
        $response->assertViewHas('komisi_germasa', function ($data) {
            return $data->count() === 1 && Str::contains(Str::lower($data->first()->komisi_nama), 'germasa');
        });
    }

    /**
     * Test the komisippsdi method.
     *
     * @return void
     */
    public function test_komisi_ppsdi_method()
    {
        KomisiModel::factory()->create(['komisi_nama' => 'Komisi PPSDI Test']);
        $response = $this->get(route('ppsdi-ppk'));
        $response->assertStatus(200);
        $response->assertViewIs('global.komisi-ppsdi');
        $response->assertViewHas('komisi_ppsdi', function ($data) {
            return $data->count() === 1 && Str::contains(Str::lower($data->first()->komisi_nama), 'ppsdi');
        });
    }

    /**
     * Test the komisiinforkomlitbang method.
     *
     * @return void
     */
    public function test_komisi_inforkomlitbang_method()
    {
        KomisiModel::factory()->create(['komisi_nama' => 'Komisi Inforkom-Litbang Test']);
        $response = $this->get(route('inforkom-litbang'));
        $response->assertStatus(200);
        $response->assertViewIs('global.komisi-inforkomlitbang');
        $response->assertViewHas('komisi_inforkomlitbang', function ($data) {
            return $data->count() === 1 && Str::contains(Str::lower($data->first()->komisi_nama), 'inforkom-litbang');
        });
    }

    /**
     * Test the bppj method.
     *
     * @return void
     */
    public function test_bppj_method()
    {
        KomisiModel::factory()->create(['komisi_nama' => 'BPPJ Test']);
        $response = $this->get(route('bppj'));
        $response->assertStatus(200);
        $response->assertViewIs('global.bppj');
        $response->assertViewHas('bppj', function ($data) {
            return $data->count() === 1 && Str::contains(Str::lower($data->first()->komisi_nama), 'bppj');
        });
    }

    /**
     * Test the kantor method.
     *
     * @return void
     */
    public function test_kantor_method()
    {
        KomisiModel::factory()->create(['komisi_nama' => 'Kantor Test']);
        $response = $this->get(route('kantor-sekretariat'));
        $response->assertStatus(200);
        $response->assertViewIs('global.kantor');
        $response->assertViewHas('kantor', function ($data) {
            return $data->count() === 1 && Str::contains(Str::lower($data->first()->komisi_nama), 'kantor');
        });
    }

    /**
     * Test the tataibadah method.
     *
     * @return void
     */
    public function test_tata_ibadah_method()
    {
        TataIbadahModel::factory()->count(5)->create();
        $specificDate = Carbon::now()->subDays(3)->toDateString();
        TataIbadahModel::factory()->create(['tanggal' => $specificDate]);

        // Test without date filter (should take 3 latest)
        $response = $this->get(route('tata-ibadah'));
        $response->assertStatus(200);
        $response->assertViewIs('global.tata-ibadah');
        $response->assertViewHas('tataIbadahList', function ($list) {
            return $list->count() === 3;
        });
        $response->assertViewHas('tanggal', null);

        // Test with date filter
        $response = $this->get(route('tata-ibadah', ['tanggal' => $specificDate]));
        $response->assertStatus(200);
        $response->assertViewHas('tataIbadahList', function ($list) use ($specificDate) {
            return $list->count() === 1 && Carbon::parse($list->first()->tanggal)->toDateString() === $specificDate;
        });
        $response->assertViewHas('tanggal', $specificDate);
    }

    /**
     * Test the wartajemaat method.
     *
     * @return void
     */
    public function test_warta_jemaat_method()
    {
        WartaJemaatModel::factory()->count(5)->create();
        $specificDate = Carbon::now()->subDays(2)->toDateString();
        WartaJemaatModel::factory()->create(['tanggal' => $specificDate]);

        // Test without date filter (should take 3 latest)
        $response = $this->get(route('warta-jemaat'));
        $response->assertStatus(200);
        $response->assertViewIs('global.warta-jemaat');
        $response->assertViewHas('wartaJemaatList', function ($list) {
            return $list->count() === 3;
        });
        $response->assertViewHas('tanggal', null);

        // Test with date filter
        $response = $this->get(route('warta-jemaat', ['tanggal' => $specificDate]));
        $response->assertStatus(200);
        $response->assertViewHas('wartaJemaatList', function ($list) use ($specificDate) {
            return $list->count() === 1 && Carbon::parse($list->first()->tanggal)->toDateString() === $specificDate;
        });
        $response->assertViewHas('tanggal', $specificDate);
    }

    /**
     * Test the galeri method.
     *
     * @return void
     */
    public function test_galeri_method()
    {
        $kategori1 = KategoriGaleriModel::factory()->create(['kategorigaleri_nama' => 'Kegiatan Gereja']);
        $kategori2 = KategoriGaleriModel::factory()->create(['kategorigaleri_nama' => 'Pembangunan']);

        GaleriModel::factory()->count(5)->create(['kategorigaleri_id' => $kategori1->kategorigaleri_id]);
        GaleriModel::factory()->count(2)->create(['kategorigaleri_id' => $kategori2->kategorigaleri_id]);

        $response = $this->get(route('galeri'));

        $response->assertStatus(200);
        $response->assertViewIs('global.galeri');
        $response->assertViewHas('galeriByKategori');

        $galeriByKategori = $response->viewData('galeriByKategori');
        $this->assertArrayHasKey('Kegiatan Gereja', $galeriByKategori);
        $this->assertArrayHasKey('Pembangunan', $galeriByKategori);
        $this->assertCount(3, $galeriByKategori['Kegiatan Gereja']); // Should take 3 latest
        $this->assertCount(2, $galeriByKategori['Pembangunan']);
    }

    /**
     * Test the galeriByKategori method.
     *
     * @return void
     */
    public function test_galeri_by_kategori_method()
    {
        $kategori = KategoriGaleriModel::factory()->create();
        GaleriModel::factory()->count(4)->create(['kategorigaleri_id' => $kategori->kategorigaleri_id]);

        $response = $this->get(route('galeri-kategori', ['id' => $kategori->kategorigaleri_id]));

        $response->assertStatus(200);
        $response->assertViewIs('global.galeri-kategori');
        $response->assertViewHas('kategori', $kategori);
        $response->assertViewHas('galeriList', function ($list) {
            return $list->count() === 4;
        });
    }

    // Add more test methods here for other functions in HomeController
    // e.g., test_katekisasi_create_method, test_katekisasi_store_success, etc.

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // It's good practice to define routes for testing if they are not globally available
        // or if you want to be explicit.
        // This requires your routes/web.php or api.php to have named routes.
        // Example:
        if (!\Illuminate\Support\Facades\Route::has('home')) {
            \Illuminate\Support\Facades\Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
        }
        if (!\Illuminate\Support\Facades\Route::has('sejarah')) {
            \Illuminate\Support\Facades\Route::get('/sejarah-gereja', [\App\Http\Controllers\HomeController::class, 'sejarah'])->name('sejarah');
        }
        if (!\Illuminate\Support\Facades\Route::has('sektor')) {
            \Illuminate\Support\Facades\Route::get('/wilayah-pelayanan', [\App\Http\Controllers\HomeController::class, 'sektor'])->name('sektor');
        }
        if (!\Illuminate\Support\Facades\Route::has('ibadah.rutin')) {
            \Illuminate\Support\Facades\Route::get('/ibadah-rutin', [\App\Http\Controllers\HomeController::class, 'ibadah'])->name('ibadah.rutin');
        }
         if (!\Illuminate\Support\Facades\Route::has('persembahan')) {
            \Illuminate\Support\Facades\Route::get('/persembahan', [\App\Http\Controllers\HomeController::class, 'persembahan'])->name('persembahan');
        }
        if (!\Illuminate\Support\Facades\Route::has('kanal.youtube')) {
            \Illuminate\Support\Facades\Route::get('/kanal-youtube', [\App\Http\Controllers\HomeController::class, 'showLatestVideo'])->name('kanal.youtube');
        }
        if (!\Illuminate\Support\Facades\Route::has('pendeta.kmj')) {
            \Illuminate\Support\Facades\Route::get('/pendeta-kmj', [\App\Http\Controllers\HomeController::class, 'pendeta'])->name('pendeta.kmj');
        }
        if (!\Illuminate\Support\Facades\Route::has('vikaris')) {
            \Illuminate\Support\Facades\Route::get('/vikaris', [\App\Http\Controllers\HomeController::class, 'vikaris'])->name('vikaris');
        }
        if (!\Illuminate\Support\Facades\Route::has('phmj')) {
            \Illuminate\Support\Facades\Route::get('/phmj', [\App\Http\Controllers\HomeController::class, 'phmj'])->name('phmj');
        }
        if (!\Illuminate\Support\Facades\Route::has('majelis.jemaat')) {
            \Illuminate\Support\Facades\Route::get('/majelis-jemaat', [\App\Http\Controllers\HomeController::class, 'majelisJemaat'])->name('majelis.jemaat');
        }
        if (!\Illuminate\Support\Facades\Route::has('pelkat.pa')) {
            \Illuminate\Support\Facades\Route::get('/pelkat-pa', [\App\Http\Controllers\HomeController::class, 'pelkatpa'])->name('pelkat.pa');
        }
        if (!\Illuminate\Support\Facades\Route::has('pelkat.pt')) {
            \Illuminate\Support\Facades\Route::get('/pelkat-pt', [\App\Http\Controllers\HomeController::class, 'pelkatpt'])->name('pelkat.pt');
        }
        if (!\Illuminate\Support\Facades\Route::has('pelkat.gp')) {
            \Illuminate\Support\Facades\Route::get('/pelkat-gp', [\App\Http\Controllers\HomeController::class, 'pelkatgp'])->name('pelkat.gp');
        }
        if (!\Illuminate\Support\Facades\Route::has('pelkat.pkp')) {
            \Illuminate\Support\Facades\Route::get('/pelkat-pkp', [\App\Http\Controllers\HomeController::class, 'pelkatpkp'])->name('pelkat.pkp');
        }
        if (!\Illuminate\Support\Facades\Route::has('pelkat.pkb')) {
            \Illuminate\Support\Facades\Route::get('/pelkat-pkb', [\App\Http\Controllers\HomeController::class, 'pelkatpkb'])->name('pelkat.pkb');
        }
        if (!\Illuminate\Support\Facades\Route::has('pelkat.pklu')) {
            \Illuminate\Support\Facades\Route::get('/pelkat-pklu', [\App\Http\Controllers\HomeController::class, 'pelkatpklu'])->name('pelkat.pklu');
        }
        if (!\Illuminate\Support\Facades\Route::has('komisi.teologi')) {
            \Illuminate\Support\Facades\Route::get('/komisi-teologi', [\App\Http\Controllers\HomeController::class, 'komisiteologi'])->name('komisi.teologi');
        }
        if (!\Illuminate\Support\Facades\Route::has('komisi.pelkes')) {
            \Illuminate\Support\Facades\Route::get('/komisi-pelkes', [\App\Http\Controllers\HomeController::class, 'komisipelkes'])->name('komisi.pelkes');
        }
        if (!\Illuminate\Support\Facades\Route::has('komisi.peg')) {
            \Illuminate\Support\Facades\Route::get('/komisi-peg', [\App\Http\Controllers\HomeController::class, 'komisipeg'])->name('komisi.peg');
        }
        if (!\Illuminate\Support\Facades\Route::has('komisi.germasa')) {
            \Illuminate\Support\Facades\Route::get('/komisi-germasa', [\App\Http\Controllers\HomeController::class, 'komisigermasa'])->name('komisi.germasa');
        }
        if (!\Illuminate\Support\Facades\Route::has('komisi.ppsdi')) {
            \Illuminate\Support\Facades\Route::get('/komisi-ppsdi', [\App\Http\Controllers\HomeController::class, 'komisippsdi'])->name('komisi.ppsdi');
        }
        if (!\Illuminate\Support\Facades\Route::has('komisi.inforkomlitbang')) {
            \Illuminate\Support\Facades\Route::get('/komisi-inforkomlitbang', [\App\Http\Controllers\HomeController::class, 'komisiinforkomlitbang'])->name('komisi.inforkomlitbang');
        }
        if (!\Illuminate\Support\Facades\Route::has('bppj')) {
            \Illuminate\Support\Facades\Route::get('/bppj', [\App\Http\Controllers\HomeController::class, 'bppj'])->name('bppj');
        }
        if (!\Illuminate\Support\Facades\Route::has('kantor')) {
            \Illuminate\Support\Facades\Route::get('/kantor', [\App\Http\Controllers\HomeController::class, 'kantor'])->name('kantor');
        }
        if (!\Illuminate\Support\Facades\Route::has('tata.ibadah')) {
            \Illuminate\Support\Facades\Route::get('/tata-ibadah', [\App\Http\Controllers\HomeController::class, 'tataibadah'])->name('tata.ibadah');
        }
        if (!\Illuminate\Support\Facades\Route::has('warta.jemaat')) {
            \Illuminate\Support\Facades\Route::get('/warta-jemaat', [\App\Http\Controllers\HomeController::class, 'wartajemaat'])->name('warta.jemaat');
        }
        if (!\Illuminate\Support\Facades\Route::has('galeri')) {
            \Illuminate\Support\Facades\Route::get('/galeri', [\App\Http\Controllers\HomeController::class, 'galeri'])->name('galeri');
        }
        if (!\Illuminate\Support\Facades\Route::has('galeri.kategori')) {
            \Illuminate\Support\Facades\Route::get('/galeri/kategori/{id}', [\App\Http\Controllers\HomeController::class, 'galeriByKategori'])->name('galeri.kategori');
        }
        // Define other routes used by your controller actions here for robust testing
        // This is crucial if your main route files are complex or not easily parsable for test setup.
        // For a real application, ensure your routes in routes/web.php are named.
    }
}
