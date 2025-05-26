<?php

namespace Tests\Feature;

use App\Models\TataIbadahModel; // Changed from PersembahanModel
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\LevelSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class TataIbadahControllerTest extends TestCase // Changed class name
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

    public function test_list()
    {
        $this->actingAs($this->adminUser);

        TataIbadahModel::factory()->count(3)->create(); // Changed model

        $response = $this->postJson('/pengelolaan-informasi/tataibadah/list'); // Changed route

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'DT_RowIndex',
                    'tataibadah_id', // Changed field
                    'tanggal',       // Changed field
                    'judul',         // Changed field
                    'deskripsi',     // Changed field
                    'file',          // Changed field
                    'aksi',
                ]
            ]
        ]);
    }

    public function test_create()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/pengelolaan-informasi/tataibadah/create'); // Changed route

        $response->assertStatus(200);
        $response->assertViewIs('tataibadah.create'); // Changed view
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'tataibadah',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_store_success()
    {
        Storage::fake('public');
        $this->actingAs($this->adminUser);

        $data = [
            'tanggal' => now()->format('Y-m-d'), // Changed field and data
            'judul' => 'Test Tata Ibadah Baru',  // Changed field
            'deskripsi' => 'Deskripsi untuk tata ibadah baru.', // Changed field
            'file' => UploadedFile::fake()->create('tataibadah.pdf', 100, 'application/pdf'), // Changed field and file type
        ];

        $response = $this->post('/pengelolaan-informasi/tataibadah', $data); // Changed route

        $response->assertRedirect('/pengelolaan-informasi/tataibadah'); // Changed route
        $response->assertSessionHas('success_tataibadah', 'Data tata ibadah berhasil disimpan'); // Changed session key
        
        $this->assertDatabaseHas('t_tataibadah', [ // Changed table and fields
            'tanggal' => $data['tanggal'],
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
        ]);
        // Check that file is stored (not null)
        $this->assertNotNull(TataIbadahModel::where('judul', $data['judul'])->first()->file); // Changed model and field
    }

    public function test_store_validation_error()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'judul' => '' // Invalid: judul is required
        ];

        $response = $this->post('/pengelolaan-informasi/tataibadah', $data); // Changed route

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('judul'); // Changed field key
    }

    public function test_show()
    {
        $this->actingAs($this->adminUser);

        $tataibadah = TataIbadahModel::factory()->create(); // Changed model

        $response = $this->get('/pengelolaan-informasi/tataibadah/' . $tataibadah->tataibadah_id); // Changed route and ID

        $response->assertStatus(200);
        $response->assertViewIs('tataibadah.show'); // Changed view
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'tataibadah', // Changed view variable
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_edit()
    {
        $this->actingAs($this->adminUser);

        $tataibadah = TataIbadahModel::factory()->create(); // Changed model

        $response = $this->get('/pengelolaan-informasi/tataibadah/' . $tataibadah->tataibadah_id . '/edit'); // Changed route and ID

        $response->assertStatus(200);
        $response->assertViewIs('tataibadah.edit'); // Changed view
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'tataibadah', // Changed view variable
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_update_success()
    {
        Storage::fake('public');
        $this->actingAs($this->adminUser);

        $tataibadah = TataIbadahModel::factory()->create(); // Changed model
        $updatedData = [
            'tanggal' => now()->addDay()->format('Y-m-d'), // Changed field and data
            'judul' => 'Updated Tata Ibadah',           // Changed field
            'deskripsi' => 'Updated deskripsi.',          // Changed field
            'file' => UploadedFile::fake()->create('new_tataibadah.pdf', 150, 'application/pdf'), // Changed field and file type
        ];

        $response = $this->put('/pengelolaan-informasi/tataibadah/' . $tataibadah->tataibadah_id, $updatedData); // Changed route and ID

        $response->assertRedirect('/pengelolaan-informasi/tataibadah'); // Changed route
        $response->assertSessionHas('success_tataibadah', 'Data tata ibadah berhasil diubah'); // Changed session key
        
        $this->assertDatabaseHas('t_tataibadah', [ // Changed table and fields
            'tataibadah_id' => $tataibadah->tataibadah_id,
            'tanggal' => $updatedData['tanggal'],
            'judul' => $updatedData['judul'],
            'deskripsi' => $updatedData['deskripsi'],
        ]);
        $this->assertNotNull(TataIbadahModel::find($tataibadah->tataibadah_id)->file); // Changed model and field
    }

    public function test_update_validation_error()
    {
        $this->actingAs($this->adminUser);

        $tataibadah = TataIbadahModel::factory()->create(); // Changed model
        $updatedData = [
            'judul' => '' // Invalid: judul is required
        ];

        $response = $this->put('/pengelolaan-informasi/tataibadah/' . $tataibadah->tataibadah_id, $updatedData); // Changed route and ID

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('judul'); // Changed field key
    }

    public function test_destroy_success()
    {
        $this->actingAs($this->adminUser);

        $tataibadah = TataIbadahModel::factory()->create(); // Changed model

        // Simulate file storage
        Storage::fake('public');
        if ($tataibadah->file) { // Ensure there is a file to store
            $fakeFile = UploadedFile::fake()->create($tataibadah->file, 100, 'application/pdf');
            // Store the file in the expected path for the controller to delete
            $path = Storage::putFileAs('public/dokumen/tataibadah', $fakeFile, $tataibadah->file);
        }

        $response = $this->delete('/pengelolaan-informasi/tataibadah/' . $tataibadah->tataibadah_id); // Changed route and ID

        $response->assertRedirect('/pengelolaan-informasi/tataibadah'); // Changed route
        $response->assertSessionHas('success_tataibadah', 'Data tata ibadah berhasil dihapus'); // Changed session key
        $this->assertDatabaseMissing('t_tataibadah', ['tataibadah_id' => $tataibadah->tataibadah_id]); // Changed table and ID
        if ($tataibadah->file) {
            // Assert that the file no longer exists after deletion
            $this->assertFalse(Storage::disk('public')->exists('dokumen/tataibadah/' . $tataibadah->file));
        }
    }

    public function test_destroy_not_found()
    {
        $this->actingAs($this->adminUser);

        $response = $this->delete('/pengelolaan-informasi/tataibadah/99999'); // Changed route, An ID that is unlikely to exist

        $response->assertRedirect('/pengelolaan-informasi/tataibadah'); // Changed route
        $response->assertSessionHas('error_tataibadah', 'Data tata ibadah tidak ditemukan'); // Changed session key
    }
}
