<?php

namespace Tests\Feature;

use App\Models\SektorModel;
use App\Models\UserModel;
use App\Models\LevelModel;
use App\Models\PelayanModel;
use App\Models\KategoriPelayanModel; // Added import
use App\Models\PelkatModel;         // Added import
use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker; // Removed as fake() helper is used directly
use Tests\TestCase;
use Database\Seeders\LevelSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\KategoriPelayanSeeder;
use Database\Seeders\PelkatSeeder; // Added PelkatSeeder
use Database\Seeders\PelayanSeeder;

class SektorControllerTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_index()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/pengelolaan-informasi/sektor');

        $response->assertStatus(200);
        $response->assertViewIs('sektor.index');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'pelayan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_list()
    {
        $this->actingAs($this->adminUser);

        // Create SektorModels with associated PelayanModels that have all necessary attributes
        SektorModel::factory()->count(3)->for(
            PelayanModel::factory()->state(function (array $attributes) {
                // Ensure PelayanModel has all fields expected by the JSON structure
                return [
                    'nama' => fake()->name(), // Changed to use global fake() helper
                    'kategoripelayan_id' => KategoriPelayanModel::factory(), 
                    'pelkat_id' => PelkatModel::factory(), 
                    'masa_jabatan_mulai' => fake()->year(), // Changed to use global fake() helper
                    'masa_jabatan_selesai' => fake()->year(), // Changed to use global fake() helper
                    'foto' => 'test.jpg',
                    'keterangan' => fake()->sentence(), // Changed to use global fake() helper
                ];
            }),
            'pelayan' // This is the relationship name in SektorModel
        )->create();

        $response = $this->postJson('/pengelolaan-informasi/sektor/list');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'DT_RowIndex',
                    'sektor_id',
                    'sektor_nama',
                    'deskripsi',
                    'jumlah_jemaat',
                    'pelayan_id',
                    'pelayan' => [
                        'pelayan_id',
                        'kategoripelayan_id',
                        'pelkat_id',
                        'nama',
                        'foto',
                        'masa_jabatan_mulai',
                        'masa_jabatan_selesai',
                        'keterangan',
                        // If your PelayanModel implicitly includes timestamps in JSON:
                        // 'created_at',
                        // 'updated_at',
                    ],
                    'aksi',
                ]
            ]
        ]);
    }

    public function test_create()
    {
        $this->actingAs($this->adminUser);
        // Ensure at least one PelayanModel exists with a valid kategoripelayan_id and name
        // Assumes KategoriPelayanModel with ID 3 and 4 are created by KategoriPelayanSeeder
        PelayanModel::factory()->create([
            'kategoripelayan_id' => 3, 
            'nama' => fake()->name() // Changed to use global fake() helper
        ]);
        PelayanModel::factory()->create([
            'kategoripelayan_id' => 4, 
            'nama' => fake()->name() // Changed to use global fake() helper
        ]);

        $response = $this->get('/pengelolaan-informasi/sektor/create');

        $response->assertStatus(200);
        $response->assertViewIs('sektor.create');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'pelayan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_store_success()
    {
        $this->actingAs($this->adminUser);
        // Assumes KategoriPelayanModel with ID 3 is created by KategoriPelayanSeeder
        $pelayan = PelayanModel::factory()->create([
            'kategoripelayan_id' => 3, 
            'nama' => fake()->name() // Changed to use global fake() helper
        ]);

        $data = [
            'sektor_nama' => 'Sektor Test Baru',
            'deskripsi' => 'Deskripsi untuk sektor test baru',
            'jumlah_jemaat' => 100,
            'pelayan_id' => $pelayan->pelayan_id,
        ];

        $response = $this->post('/pengelolaan-informasi/sektor', $data);

        $response->assertRedirect('/pengelolaan-informasi/sektor');
        $response->assertSessionHas('success', 'Data sektor berhasil disimpan');
        
        $this->assertDatabaseHas('t_sektor', [
            'sektor_nama' => $data['sektor_nama'],
            'deskripsi' => $data['deskripsi'],
            'jumlah_jemaat' => $data['jumlah_jemaat'],
            'pelayan_id' => $data['pelayan_id'],
        ]);
    }

    public function test_store_validation_error()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'sektor_nama' => '' // Invalid: name is required
        ];

        $response = $this->post('/pengelolaan-informasi/sektor', $data);

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('sektor_nama');
    }

    public function test_show()
    {
        $this->actingAs($this->adminUser);

        // Assumes KategoriPelayanModel with ID 3 is created by KategoriPelayanSeeder
        $pelayan = PelayanModel::factory()->create([
            'nama' => fake()->name(), // Changed to use global fake() helper
            'kategoripelayan_id' => 3 
        ]);
        $sektor = SektorModel::factory()->create(['pelayan_id' => $pelayan->pelayan_id]);

        $response = $this->get('/pengelolaan-informasi/sektor/' . $sektor->sektor_id);

        $response->assertStatus(200);
        $response->assertViewIs('sektor.show');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'sektor',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_edit()
    {
        $this->actingAs($this->adminUser);

        // Assumes KategoriPelayanModel with ID 3 is created by KategoriPelayanSeeder
        $pelayanOwner = PelayanModel::factory()->create([
            'nama' => fake()->name(), // Changed to use global fake() helper
            'kategoripelayan_id' => 3 
        ]);
        $sektor = SektorModel::factory()->create(['pelayan_id' => $pelayanOwner->pelayan_id]);
        
        // Assumes KategoriPelayanModel with ID 4 is created by KategoriPelayanSeeder
        PelayanModel::factory()->create([
            'kategoripelayan_id' => 4, 
            'nama' => fake()->name() // Changed to use global fake() helper
        ]);

        $response = $this->get('/pengelolaan-informasi/sektor/' . $sektor->sektor_id . '/edit');

        $response->assertStatus(200);
        $response->assertViewIs('sektor.edit');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'sektor',
            'pelayan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_update_success()
    {
        $this->actingAs($this->adminUser);

        // Assumes KategoriPelayanModel with ID 3 is created by KategoriPelayanSeeder
        $pelayanOwner = PelayanModel::factory()->create([
            'nama' => fake()->name(), // Changed to use global fake() helper
            'kategoripelayan_id' => 3
        ]);
        $sektor = SektorModel::factory()->create(['pelayan_id' => $pelayanOwner->pelayan_id]);
        
        // Assumes KategoriPelayanModel with ID 4 is created by KategoriPelayanSeeder
        $newPelayan = PelayanModel::factory()->create([
            'kategoripelayan_id' => 4, 
            'nama' => fake()->name() // Changed to use global fake() helper
        ]);
        $updatedData = [
            'sektor_nama' => 'Updated Sektor',
            'deskripsi' => 'Updated deskripsi sektor',
            'jumlah_jemaat' => 150,
            'pelayan_id' => $newPelayan->pelayan_id,
        ];

        $response = $this->put('/pengelolaan-informasi/sektor/' . $sektor->sektor_id, $updatedData);

        $response->assertRedirect('/pengelolaan-informasi/sektor');
        $response->assertSessionHas('success', 'Data sektor berhasil diubah');
        
        $this->assertDatabaseHas('t_sektor', [
            'sektor_id' => $sektor->sektor_id,
            'sektor_nama' => $updatedData['sektor_nama'],
            'deskripsi' => $updatedData['deskripsi'],
            'jumlah_jemaat' => $updatedData['jumlah_jemaat'],
            'pelayan_id' => $updatedData['pelayan_id'],
        ]);
    }

    public function test_update_validation_error()
    {
        $this->actingAs($this->adminUser);

        $sektor = SektorModel::factory()->create();
        $updatedData = [
            'sektor_nama' => '' // Invalid: name is required
        ];

        $response = $this->put('/pengelolaan-informasi/sektor/' . $sektor->sektor_id, $updatedData);

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('sektor_nama');
    }

    public function test_destroy_success()
    {
        $this->actingAs($this->adminUser);

        // Assumes KategoriPelayanModel with ID 3 is created by KategoriPelayanSeeder
        $pelayan = PelayanModel::factory()->create([
            'nama' => fake()->name(), // Changed to use global fake() helper
            'kategoripelayan_id' => 3 
        ]);
        $sektor = SektorModel::factory()->create(['pelayan_id' => $pelayan->pelayan_id]);

        $response = $this->delete('/pengelolaan-informasi/sektor/' . $sektor->sektor_id);

        $response->assertRedirect('/pengelolaan-informasi/sektor');
        $response->assertSessionHas('success', 'Data sektor berhasil dihapus');
        $this->assertDatabaseMissing('t_sektor', ['sektor_id' => $sektor->sektor_id]);
    }

    public function test_destroy_not_found()
    {
        $this->actingAs($this->adminUser);

        $response = $this->delete('/pengelolaan-informasi/sektor/99999'); // An ID that is unlikely to exist

        $response->assertRedirect('/pengelolaan-informasi/sektor');
        $response->assertSessionHas('error', 'Data sektor tidak ditemukan');
    }
}
