<?php

namespace Tests\Feature;

use App\Models\KategoriPersembahanModel;
use App\Models\UserModel;
use App\Models\LevelModel; // Import LevelModel
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\LevelSeeder;
use Database\Seeders\UserSeeder;

class KategoriPersembahanControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(LevelSeeder::class);
        $this->seed(UserSeeder::class); // Ensure this seeder doesn't conflict with manual user creation

        // Find or create the ADM level
        $adminLevel = LevelModel::where('level_kode', 'ADM')->first();
        if (!$adminLevel) {
            $adminLevel = LevelModel::factory()->create(['level_kode' => 'ADM', 'level_nama' => 'Administrator']);
        }

        // Create a user with the ADM level
        $this->adminUser = UserModel::factory()->create([
            'level_id' => $adminLevel->level_id
        ]);
    }

    public function test_index()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/pengelolaan-berita-acara/kategoripersembahan');

        $response->assertStatus(200);
        $response->assertViewIs('kategoripersembahan.index');
        $response->assertViewHas('breadcrumb');
        $response->assertViewHas('page');
        $response->assertViewHas('kategoripersembahan');
        $response->assertViewHas('activeMenu', 'beritaacara-kategoripersembahan');
        $response->assertViewHas('notifUser');
    }

    public function test_list()
    {
        $this->actingAs($this->adminUser);

        KategoriPersembahanModel::factory()->count(3)->create();

        $response = $this->postJson('/pengelolaan-berita-acara/kategoripersembahan/list');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'DT_RowIndex',
                    'kategori_persembahan_id',
                    'kategori_persembahan_nama',
                    'aksi',
                ]
            ]
        ]);
    }

    public function test_create()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/pengelolaan-berita-acara/kategoripersembahan/create');

        $response->assertStatus(200);
        $response->assertViewIs('kategoripersembahan.create');
        $response->assertViewHas('breadcrumb');
        $response->assertViewHas('page');
        // In the controller, \'kategori_persembahan\' is passed, not \'kategoripersembahan\' for create view
        $response->assertViewHas('kategori_persembahan'); 
        $response->assertViewHas('activeMenu', 'beritaacara-kategoripersembahan');
        $response->assertViewHas('notifUser');
    }

    public function test_store_success()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'kategori_persembahan_nama' => 'Test Kategori Persembahan Baru'
        ];

        $response = $this->post('/pengelolaan-berita-acara/kategoripersembahan', $data);

        $response->assertRedirect('/pengelolaan-berita-acara/kategoripersembahan');
        $response->assertSessionHas('success_kategoripersembahan', 'Data Kategori Persembahan berhasil disimpan');
        $this->assertDatabaseHas('t_kategori_persembahan', $data);
    }

    public function test_store_validation_error()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'kategori_persembahan_nama' => '' // Invalid: name is required
        ];

        $response = $this->post('/pengelolaan-berita-acara/kategoripersembahan', $data);

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('kategori_persembahan_nama');
    }

    public function test_show()
    {
        $this->actingAs($this->adminUser);

        $kategori = KategoriPersembahanModel::factory()->create();

        $response = $this->get('/pengelolaan-berita-acara/kategoripersembahan/' . $kategori->kategori_persembahan_id);

        $response->assertStatus(200);
        $response->assertViewIs('kategoripersembahan.show');
        $response->assertViewHas('breadcrumb');
        $response->assertViewHas('page');
        $response->assertViewHas('kategori_persembahan', $kategori);
        $response->assertViewHas('activeMenu', 'beritaacara-kategoripersembahan');
        $response->assertViewHas('notifUser');
    }

    public function test_edit()
    {
        $this->actingAs($this->adminUser);

        $kategori = KategoriPersembahanModel::factory()->create();

        $response = $this->get('/pengelolaan-berita-acara/kategoripersembahan/' . $kategori->kategori_persembahan_id . '/edit');

        $response->assertStatus(200);
        $response->assertViewIs('kategoripersembahan.edit');
        $response->assertViewHas('breadcrumb');
        $response->assertViewHas('page');
        $response->assertViewHas('kategori_persembahan', $kategori);
        $response->assertViewHas('activeMenu', 'beritaacara-kategoripersembahan');
        $response->assertViewHas('notifUser');
    }

    public function test_update_success()
    {
        $this->actingAs($this->adminUser);

        $kategori = KategoriPersembahanModel::factory()->create();
        $updatedData = [
            'kategori_persembahan_nama' => 'Updated Kategori Persembahan'
        ];

        $response = $this->put('/pengelolaan-berita-acara/kategoripersembahan/' . $kategori->kategori_persembahan_id, $updatedData);

        $response->assertRedirect('/pengelolaan-berita-acara/kategoripersembahan');
        $response->assertSessionHas('success_kategoripersembahan', 'Data Kategori Persembahan berhasil diubah');
        $this->assertDatabaseHas('t_kategori_persembahan', $updatedData + ['kategori_persembahan_id' => $kategori->kategori_persembahan_id]);
    }

    public function test_update_validation_error()
    {
        $this->actingAs($this->adminUser);

        $kategori = KategoriPersembahanModel::factory()->create();
        $updatedData = [
            'kategori_persembahan_nama' => '' // Invalid: name is required
        ];

        $response = $this->put('/pengelolaan-berita-acara/kategoripersembahan/' . $kategori->kategori_persembahan_id, $updatedData);

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('kategori_persembahan_nama');
    }

    public function test_destroy_success()
    {
        $this->actingAs($this->adminUser);

        $kategori = KategoriPersembahanModel::factory()->create();

        $response = $this->delete('/pengelolaan-berita-acara/kategoripersembahan/' . $kategori->kategori_persembahan_id);

        $response->assertRedirect('/pengelolaan-berita-acara/kategoripersembahan');
        $response->assertSessionHas('success_kategoripersembahan', 'Data Kategori Persembahan berhasil dihapus');
        $this->assertDatabaseMissing('t_kategori_persembahan', ['kategori_persembahan_id' => $kategori->kategori_persembahan_id]);
    }

    public function test_destroy_not_found()
    {
        $this->actingAs($this->adminUser);

        $response = $this->delete('/pengelolaan-berita-acara/kategoripersembahan/99999'); // An ID that is unlikely to exist

        $response->assertRedirect('/pengelolaan-berita-acara/kategoripersembahan');
        $response->assertSessionHas('error_kategoripersembahan', 'Data Kategori Persembahan tidak ditemukan');
    }
}
