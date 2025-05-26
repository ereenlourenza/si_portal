<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\LevelModel;
use App\Models\BeritaAcaraIbadahModel;
use App\Models\IbadahModel;
use App\Models\BeritaAcaraPersembahanModel;
use App\Models\KategoriPersembahanModel;
use App\Models\UserModel;
use Database\Seeders\KategoriPelayanSeeder;
use Database\Seeders\LevelSeeder;
use Database\Seeders\PelayanSeeder;
use Database\Seeders\PelkatSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WelcomeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(LevelSeeder::class);
        $this->seed(UserSeeder::class);
        // Ensure correct seeding order for dependencies
        $this->seed(KategoriPelayanSeeder::class); 
        $this->seed(PelkatSeeder::class); // Run PelkatSeeder before PelayanSeeder
        $this->seed(PelayanSeeder::class); 

        $adminLevel = LevelModel::where('level_kode', 'ADM')->first();
        if (!$adminLevel) {
            $adminLevel = LevelModel::factory()->create(['level_kode' => 'ADM', 'level_nama' => 'Administrator']);
        }

        $this->adminUser = UserModel::factory()->create([
            'level_id' => $adminLevel->level_id
        ]);

        // Mock the simpanLogAktivitas helper function
        if (!function_exists('simpanLogAktivitas')) {
            function simpanLogAktivitas($menu, $aksi, $detail) {
                // Mock implementation or leave empty
            }
        }
    }

    public function testIndexWelcomeController()
    {
        // Bertindak sebagai user yang sudah login
        $this->actingAs($this->adminUser);

        // Buat data dummy untuk ibadah dan berita acara
        $ibadah = IbadahModel::factory()->create();
        $beritaAcaraIbadah = BeritaAcaraIbadahModel::factory()->create(['ibadah_id' => $ibadah->ibadah_id]);
        $kategoriPersembahan = KategoriPersembahanModel::factory()->create();
        BeritaAcaraPersembahanModel::factory()->create([
            'berita_acara_ibadah_id' => $beritaAcaraIbadah->berita_acara_ibadah_id,
            'kategori_persembahan_id' => $kategoriPersembahan->kategori_persembahan_id,
            'total' => 100000
        ]);

        // Panggil route atau action index
        $response = $this->get(route('beranda.index')); // Pastikan nama route sudah benar

        // Assertions
        $response->assertStatus(200);
        $response->assertViewIs('beranda.index');
        $response->assertViewHas('breadcrumb');
        $response->assertViewHas('activeMenu', 'dashboard');
        $response->assertViewHas('data');
        $response->assertViewHas('persembahanMinggu');
        $response->assertViewHas('hari');
        $response->assertViewHas('bulan');
        $response->assertViewHas('tahun');

        // Contoh assertion untuk data yang dikirim ke view
        $viewData = $response->viewData('data');
        $this->assertNotEmpty($viewData);
        $this->assertEquals($ibadah->tanggal, $viewData[0]['tanggal']);
        $this->assertEquals($beritaAcaraIbadah->jumlah_kehadiran, $viewData[0]['jumlah_kehadiran']);
        $this->assertEquals(100000, $viewData[0]['total_persembahan']);
    }

    public function testIndexWelcomeControllerWithDateFilters()
    {
        $this->actingAs($this->adminUser);

        $tahun = $this->faker->year;
        $bulan = $this->faker->month;
        $hari = $this->faker->dayOfMonth;

        // Buat data dummy spesifik untuk filter
        $ibadahFiltered = IbadahModel::factory()->create([
            'tanggal' => "{$tahun}-{$bulan}-{$hari} " . $this->faker->time()
        ]);
        $beritaAcaraIbadahFiltered = BeritaAcaraIbadahModel::factory()->create(['ibadah_id' => $ibadahFiltered->ibadah_id]);
        $kategoriPersembahan = KategoriPersembahanModel::factory()->create();
        BeritaAcaraPersembahanModel::factory()->create([
            'berita_acara_ibadah_id' => $beritaAcaraIbadahFiltered->berita_acara_ibadah_id,
            'kategori_persembahan_id' => $kategoriPersembahan->kategori_persembahan_id,
            'total' => 150000
        ]);

        // Buat data dummy lain yang tidak sesuai filter
        IbadahModel::factory()->count(2)->create([
            'tanggal' => $this->faker->dateTimeBetween('-1 year', '-1 month')->format('Y-m-d H:i:s') // Tanggal berbeda
        ])->each(function ($ibadah) use ($kategoriPersembahan) {
            $ba = BeritaAcaraIbadahModel::factory()->create(['ibadah_id' => $ibadah->ibadah_id]);
            BeritaAcaraPersembahanModel::factory()->create([
                'berita_acara_ibadah_id' => $ba->berita_acara_ibadah_id,
                'kategori_persembahan_id' => $kategoriPersembahan->kategori_persembahan_id,
            ]);
        });

        $response = $this->get(route('beranda.index', ['hari' => $hari, 'bulan' => $bulan, 'tahun' => $tahun]));

        $response->assertStatus(200);
        $response->assertViewIs('beranda.index');
        $response->assertViewHas('data');

        $viewData = $response->viewData('data');
        $this->assertCount(1, $viewData); // Hanya data yang terfilter yang muncul
        $this->assertEquals($ibadahFiltered->tanggal, $viewData[0]['tanggal']);
        $this->assertEquals(150000, $viewData[0]['total_persembahan']);
        $this->assertEquals($hari, $response->viewData('hari'));
        $this->assertEquals($bulan, $response->viewData('bulan'));
        $this->assertEquals($tahun, $response->viewData('tahun'));
    }

    public function testIndexWelcomeControllerAccessDeniedForInvalidLevel()
    {
        // Buat level yang tidak diizinkan
        $invalidLevel = LevelModel::factory()->create(['level_kode' => 'USR', 'level_nama' => 'User Biasa']);
        $testUser = User::factory()->create([
            'level_id' => $invalidLevel->level_id
        ]);

        $this->actingAs($testUser);

        $response = $this->get(route('beranda.index'));

        // Seharusnya redirect atau forbidden, tergantung implementasi middleware 'checklevel'
        // Jika middleware melakukan abort(403), maka statusnya 403
        // Jika middleware redirect ke login, maka statusnya 302
        // Sesuaikan assertion ini dengan perilaku middleware Anda
        $response->assertStatus(403); // Atau $response->assertRedirect('/login');
    }

    public function testIndexWelcomeControllerGuestRedirectsToLogin()
    {
        // Tidak ada user yang login (guest)
        $response = $this->get(route('beranda.index'));

        // Seharusnya redirect ke halaman login
        $response->assertRedirect('/login'); // Pastikan nama route login sudah benar
    }
}
