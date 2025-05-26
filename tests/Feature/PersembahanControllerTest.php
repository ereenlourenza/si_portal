<?php

namespace Tests\Feature;

use App\Models\PersembahanModel;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\LevelSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PersembahanControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(LevelSeeder::class);
        $this->seed(UserSeeder::class);

        $adminLevel = LevelModel::where('level_kode', 'ADM')->first();
        if (!$adminLevel) {
            $adminLevel = LevelModel::factory()->create(['level_kode' => 'ADM', 'level_nama' => 'Administrator']);
        }

        $this->adminUser = UserModel::factory()->create([
            'level_id' => $adminLevel->level_id
        ]);
    }

    public function test_index()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/pengelolaan-informasi/persembahan');

        $response->assertStatus(200);
        $response->assertViewIs('persembahan.index');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_list()
    {
        $this->actingAs($this->adminUser);

        PersembahanModel::factory()->count(3)->create();

        $response = $this->postJson('/pengelolaan-informasi/persembahan/list');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'DT_RowIndex',
                    'persembahan_id',
                    'persembahan_nama',
                    'nomor_rekening',
                    'atas_nama',
                    'barcode',
                    'aksi',
                ]
            ]
        ]);
    }

    public function test_create()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/pengelolaan-informasi/persembahan/create');

        $response->assertStatus(200);
        $response->assertViewIs('persembahan.create');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_store_success()
    {
        Storage::fake('public');
        $this->actingAs($this->adminUser);

        $data = [
            'persembahan_nama' => 'Test Persembahan Baru',
            'nomor_rekening' => '1234567890',
            'atas_nama' => 'Test Atas Nama',
            'barcode' => UploadedFile::fake()->image('persembahan.jpg'),
        ];

        $response = $this->post('/pengelolaan-informasi/persembahan', $data);

        $response->assertRedirect('/pengelolaan-informasi/persembahan');
        $response->assertSessionHas('success_persembahan', 'Data persembahan berhasil disimpan');
        
        // Exclude barcode from direct comparison, as filename might change
        $this->assertDatabaseHas('t_persembahan', [
            'persembahan_nama' => $data['persembahan_nama'],
            'nomor_rekening' => $data['nomor_rekening'],
            'atas_nama' => $data['atas_nama'],
        ]);
        // Check that barcode is stored (not null)
        $this->assertNotNull(PersembahanModel::where('persembahan_nama', $data['persembahan_nama'])->first()->barcode);
    }

    public function test_store_validation_error()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'persembahan_nama' => '' // Invalid: name is required
        ];

        $response = $this->post('/pengelolaan-informasi/persembahan', $data);

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('persembahan_nama');
    }

    public function test_show()
    {
        $this->actingAs($this->adminUser);

        $persembahan = PersembahanModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/persembahan/' . $persembahan->persembahan_id);

        $response->assertStatus(200);
        $response->assertViewIs('persembahan.show');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'persembahan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_edit()
    {
        $this->actingAs($this->adminUser);

        $persembahan = PersembahanModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/persembahan/' . $persembahan->persembahan_id . '/edit');

        $response->assertStatus(200);
        $response->assertViewIs('persembahan.edit');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'persembahan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_update_success()
    {
        Storage::fake('public');
        $this->actingAs($this->adminUser);

        $persembahan = PersembahanModel::factory()->create();
        $updatedData = [
            'persembahan_nama' => 'Updated Persembahan',
            'nomor_rekening' => '0987654321',
            'atas_nama' => 'Updated Atas Nama',
            'barcode' => UploadedFile::fake()->image('new.jpg'),
        ];

        $response = $this->put('/pengelolaan-informasi/persembahan/' . $persembahan->persembahan_id, $updatedData);

        $response->assertRedirect('/pengelolaan-informasi/persembahan');
        $response->assertSessionHas('success_persembahan', 'Data persembahan berhasil diubah');
        
        // Exclude barcode from direct comparison, as filename might change
        $this->assertDatabaseHas('t_persembahan', [
            'persembahan_id' => $persembahan->persembahan_id,
            'persembahan_nama' => $updatedData['persembahan_nama'],
            'nomor_rekening' => $updatedData['nomor_rekening'],
            'atas_nama' => $updatedData['atas_nama'],
        ]);
        // Check that barcode is updated and stored (not null)
        $this->assertNotNull(PersembahanModel::find($persembahan->persembahan_id)->barcode);
    }

    public function test_update_validation_error()
    {
        $this->actingAs($this->adminUser);

        $persembahan = PersembahanModel::factory()->create();
        $updatedData = [
            'persembahan_nama' => '' // Invalid: name is required
        ];

        $response = $this->put('/pengelolaan-informasi/persembahan/' . $persembahan->persembahan_id, $updatedData);

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('persembahan_nama');
    }

    public function test_destroy_success()
    {
        $this->actingAs($this->adminUser);

        $persembahan = PersembahanModel::factory()->create();

        $response = $this->delete('/pengelolaan-informasi/persembahan/' . $persembahan->persembahan_id);

        $response->assertRedirect('/pengelolaan-informasi/persembahan');
        $response->assertSessionHas('success_persembahan', 'Data persembahan berhasil dihapus');
        $this->assertDatabaseMissing('t_persembahan', ['persembahan_id' => $persembahan->persembahan_id]);
    }

    public function test_destroy_not_found()
    {
        $this->actingAs($this->adminUser);

        $response = $this->delete('/pengelolaan-informasi/persembahan/99999'); // An ID that is unlikely to exist

        $response->assertRedirect('/pengelolaan-informasi/persembahan');
        $response->assertSessionHas('error_persembahan', 'Data persembahan tidak ditemukan');
    }
}
