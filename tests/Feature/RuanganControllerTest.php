<?php

namespace Tests\Feature;

use App\Models\RuanganModel;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\LevelSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RuanganControllerTest extends TestCase
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

        $response = $this->get('/pengelolaan-informasi/ruangan');

        $response->assertStatus(200);
        $response->assertViewIs('ruangan.index');
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

        $ruangans = RuanganModel::factory()->count(3)->create();

        $response = $this->postJson('/pengelolaan-informasi/ruangan/list');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'DT_RowIndex',
                    'ruangan_id',
                    'ruangan_nama',
                    'deskripsi',
                    'foto',
                    'aksi',
                ]
            ]
        ]);

        // Memastikan jumlah data yang dikembalikan sesuai
        $response->assertJsonCount(3, 'data');

        // Membandingkan data aktual
        $responseData = $response->json('data');

        foreach ($ruangans as $index => $ruangan) {
            // Cari data ruangan yang sesuai dalam respons berdasarkan ruangan_id
            // Urutan data dari factory dan dari DataTables mungkin tidak selalu sama,
            // jadi lebih baik mencari berdasarkan ID unik jika memungkinkan,
            // atau jika urutannya dijamin sama, Anda bisa menggunakan $responseData[$index]
            $responseRuangan = collect($responseData)->firstWhere('ruangan_id', $ruangan->ruangan_id);

            $this->assertNotNull($responseRuangan, "Ruangan dengan ID {$ruangan->ruangan_id} tidak ditemukan dalam respons.");

            if ($responseRuangan) {
                $this->assertEquals($ruangan->ruangan_nama, $responseRuangan['ruangan_nama']);
                $this->assertEquals($ruangan->deskripsi, $responseRuangan['deskripsi']);
                $this->assertEquals($ruangan->ruangan_id, $responseRuangan['ruangan_id']);

                // Untuk kolom 'foto', karena berisi HTML, kita bisa cek apakah nama file foto ada di dalamnya
                if ($ruangan->foto) {
                    $this->assertStringContainsString(Storage::url('images/ruangan/' . $ruangan->foto), $responseRuangan['foto']);
                } else {
                    $this->assertStringContainsString('Tidak Ada Foto', $responseRuangan['foto']);
                }

                // Untuk kolom 'aksi', kita bisa cek apakah URL yang benar ada di dalamnya
                $this->assertStringContainsString(url('/pengelolaan-informasi/ruangan/' . $ruangan->ruangan_id), $responseRuangan['aksi']);
                $this->assertStringContainsString(url('/pengelolaan-informasi/ruangan/' . $ruangan->ruangan_id . '/edit'), $responseRuangan['aksi']);
                // Untuk tombol hapus, form action juga akan berisi URL ini
                $this->assertStringContainsString(url('/pengelolaan-informasi/ruangan/'.$ruangan->ruangan_id), $responseRuangan['aksi']);
            }
        }
    }

    public function test_create()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/pengelolaan-informasi/ruangan/create');

        $response->assertStatus(200);
        $response->assertViewIs('ruangan.create');
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
            'ruangan_nama' => 'Test Ruangan Baru',
            'deskripsi' => 'Deskripsi ruangan baru',
            'foto' => UploadedFile::fake()->image('ruangan.jpg'),
        ];

        $response = $this->post('/pengelolaan-informasi/ruangan', $data);

        $response->assertRedirect('/pengelolaan-informasi/ruangan');
        $response->assertSessionHas('success_ruangan', 'Data ruangan berhasil disimpan');
        
        $this->assertDatabaseHas('t_ruangan', [
            'ruangan_nama' => $data['ruangan_nama'],
            'deskripsi' => $data['deskripsi'],
        ]);
        $this->assertNotNull(RuanganModel::where('ruangan_nama', $data['ruangan_nama'])->first()->foto);
    }

    public function test_store_validation_error()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'ruangan_nama' => '' // Invalid: name is required
        ];

        $response = $this->post('/pengelolaan-informasi/ruangan', $data);

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('ruangan_nama');
    }

    public function test_show()
    {
        $this->actingAs($this->adminUser);

        $ruangan = RuanganModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/ruangan/' . $ruangan->ruangan_id);

        $response->assertStatus(200);
        $response->assertViewIs('ruangan.show');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'ruangan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_edit()
    {
        $this->actingAs($this->adminUser);

        $ruangan = RuanganModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/ruangan/' . $ruangan->ruangan_id . '/edit');

        $response->assertStatus(200);
        $response->assertViewIs('ruangan.edit');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'ruangan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_update_success()
    {
        Storage::fake('public');
        $this->actingAs($this->adminUser);

        $ruangan = RuanganModel::factory()->create();
        $updatedData = [
            'ruangan_nama' => 'Updated Ruangan',
            'deskripsi' => 'Updated deskripsi ruangan',
            'foto' => UploadedFile::fake()->image('new_ruangan.jpg'),
        ];

        $response = $this->put('/pengelolaan-informasi/ruangan/' . $ruangan->ruangan_id, $updatedData);

        $response->assertRedirect('/pengelolaan-informasi/ruangan');
        $response->assertSessionHas('success_ruangan', 'Data ruangan berhasil diubah');
        
        $this->assertDatabaseHas('t_ruangan', [
            'ruangan_id' => $ruangan->ruangan_id,
            'ruangan_nama' => $updatedData['ruangan_nama'],
            'deskripsi' => $updatedData['deskripsi'],
        ]);
        $this->assertNotNull(RuanganModel::find($ruangan->ruangan_id)->foto);
    }

    public function test_update_validation_error()
    {
        $this->actingAs($this->adminUser);

        $ruangan = RuanganModel::factory()->create();
        $updatedData = [
            'ruangan_nama' => '' // Invalid: name is required
        ];

        $response = $this->put('/pengelolaan-informasi/ruangan/' . $ruangan->ruangan_id, $updatedData);

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('ruangan_nama');
    }

    public function test_destroy_success()
    {
        $this->actingAs($this->adminUser);

        $ruangan = RuanganModel::factory()->create();

        $response = $this->delete('/pengelolaan-informasi/ruangan/' . $ruangan->ruangan_id);

        $response->assertRedirect('/pengelolaan-informasi/ruangan');
        $response->assertSessionHas('success_ruangan', 'Data ruangan berhasil dihapus');
        $this->assertDatabaseMissing('t_ruangan', ['ruangan_id' => $ruangan->ruangan_id]);
    }

    public function test_destroy_not_found()
    {
        $this->actingAs($this->adminUser);

        $response = $this->delete('/pengelolaan-informasi/ruangan/99999'); // An ID that is unlikely to exist

        $response->assertRedirect('/pengelolaan-informasi/ruangan');
        $response->assertSessionHas('error_ruangan', 'Data ruangan tidak ditemukan');
    }
}
