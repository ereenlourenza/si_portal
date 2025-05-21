<?php

namespace Tests\Feature;

use App\Models\GaleriModel;
use App\Models\KategoriGaleriModel;
use App\Models\LevelModel;
use App\Models\User;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Filesystem\FilesystemAdapter;

class GaleriControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat level super admin (kode: SAD)
        $level = LevelModel::factory()->create([
            'level_kode' => 'ADM',
            'level_nama' => 'Admin'
        ]);

        // Buat user super admin untuk login
        $this->admin = UserModel::factory()->create([
            'level_id' => $level->level_id,
            'username' => 'superadmin',
            'password' => Hash::make('superadmin123'), // Password untuk login
        ]);

        // Login sebagai admin
        $this->actingAs($this->admin);
    }

    public function test_index_returns_view()
    {
        GaleriModel::factory()->count(3)->create();

        $response = $this->get('/pengelolaan-informasi/galeri');
        $response->assertStatus(200);
        $response->assertViewIs('galeri.index');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategorigaleri',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_list_returns_json()
    {
        $kategori = KategoriGaleriModel::factory()->create();

        $galeri = GaleriModel::factory()->create([
            'judul' => 'Galeri Baru',
            'deskripsi' => 'Deskripsi singkat',
            'foto' => 'galeri.jpg',
            'kategorigaleri_id' => $kategori->kategorigaleri_id,
        ]);

        $response = $this->postJson('/pengelolaan-informasi/galeri/list');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'judul' => 'Galeri Baru',
            'deskripsi' => 'Deskripsi singkat',
            'foto' => 'galeri.jpg',
            'kategorigaleri_id' => $kategori->kategorigaleri_id,
        ]);
    }

    public function test_create_returns_view()
    {
        $response = $this->get('/pengelolaan-informasi/galeri/create');
        $response->assertStatus(200);
        $response->assertViewIs('galeri.create');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategorigaleri',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_store_creates_data()
    {
        Storage::fake('public');
        $kategori = KategoriGaleriModel::factory()->create();

        $data = [
            'judul' => 'Galeri Baru',
            'deskripsi' => 'Deskripsi singkat',
            'foto' => UploadedFile::fake()->image('galeri.jpg'),
            'kategorigaleri_id' => $kategori->kategorigaleri_id,
        ];

        $response = $this->post('/pengelolaan-informasi/galeri', $data);

        $response->assertRedirect('/pengelolaan-informasi/galeri');
        $response->assertSessionHas('success_galeri', 'Data galeri berhasil disimpan');
        
        // Cek database (tanpa cek kolom foto karena dinamis)
        $this->assertDatabaseHas('t_galeri', [
            'judul' => 'Galeri Baru',
            'deskripsi' => 'Deskripsi singkat',
            'kategorigaleri_id' => $kategori->kategorigaleri_id,
        ]);
    }

    public function test_store_fails_validation()
    {
        // Data kosong
        $data = [
            'judul' => '',
            'deskripsi' => '',
            'foto' => '',
            'kategorigaleri_id' => '',
        ];

        $response = $this->post('/pengelolaan-informasi/galeri', $data);

        $response->assertSessionHasErrors(['judul', 'foto', 'kategorigaleri_id']);
        $this->assertDatabaseMissing('t_galeri', $data);
    }

    public function test_show_returns_view()
    {
        $galeri = GaleriModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/galeri/' . $galeri->galeri_id);
        $response->assertStatus(200);
        $response->assertViewIs('galeri.show');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'galeri',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_edit_returns_view()
    {
        $galeri = GaleriModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/galeri/' . $galeri->galeri_id . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('galeri.edit');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategorigaleri',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_update_changes_data()
    {
        Storage::fake('public');
        $galeri = GaleriModel::factory()->create([
            'foto' => 'old.jpg'
        ]);

        $data = [
            'judul' => 'Judul Baru',
            'deskripsi' => 'Deskripsi Baru',
            'foto' => UploadedFile::fake()->image('new.jpg'),
            'kategorigaleri_id' => $galeri->kategorigaleri_id,
        ];

        $response = $this->put('/pengelolaan-informasi/galeri/' . $galeri->galeri_id, $data);

        $response->assertRedirect('/pengelolaan-informasi/galeri');
        $response->assertSessionHas('success_galeri', 'Data galeri berhasil diubah');
        $this->assertDatabaseHas('t_galeri', [
            'judul' => 'Judul Baru',
            'deskripsi' => 'Deskripsi Baru',
            'kategorigaleri_id' => $galeri->kategorigaleri_id,
        ]);
    }

    public function test_update_fails_validation()
    {
        Storage::fake('public');
        $kategori = KategoriGaleriModel::factory()->create();

        $galeri = GaleriModel::factory()->create([
            'judul' => 'old',
            'deskripsi' => 'old',
            'foto' => 'old.jpg',
            'kategorigaleri_id' => $kategori->kategorigaleri_id,
        ]);

        $data = [
            'judul' => '',
            'deskripsi' => '',
            'foto' => '',
            'kategorigaleri_id' => '',
        ];

        $response = $this->put('/pengelolaan-informasi/galeri/' . $galeri->galeri_id, $data);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['judul', 'kategorigaleri_id']);
        
        $this->assertDatabaseHas('t_galeri', [
            'galeri_id' => $galeri->galeri_id,
            'judul' => 'old',
            'deskripsi' => 'old',
            'foto' => 'old.jpg',
            'kategorigaleri_id' => $galeri->kategorigaleri_id,
        ]);

    }

    public function test_update_fails_if_not_found()
    {
        Storage::fake('public');
        $kategori = KategoriGaleriModel::factory()->create();

        $response = $this->put('/pengelolaan-informasi/galeri/9999',[
            'judul' => 'ABC',
            'deskripsi' => 'Tidak Ada',
            'foto' => UploadedFile::fake()->image('pepaya.jpg'),
            'kategorigaleri_id' => $kategori->kategorigaleri_id,
        ]);

        $response->assertRedirect('/pengelolaan-informasi/galeri');
        $response->assertSessionHas('error_galeri', 'Terjadi kesalahan saat mengubah data: ');
    }

    public function test_destroy_deletes_data()
    {
        Storage::fake('public');
        $galeri = GaleriModel::factory()->create([
            'foto' => 'foto.jpg'
        ]);
        Storage::put('public/images/galeri/foto.jpg', 'dummy');

        $response = $this->delete('/pengelolaan-informasi/galeri/' . $galeri->galeri_id);

        $response->assertRedirect('/pengelolaan-informasi/galeri');
        $response->assertSessionHas('success_galeri', 'Data galeri berhasil dihapus');
        $this->assertDatabaseMissing('t_galeri', ['galeri_id' => $galeri->galeri_id]);
    }

    public function test_destroy_fails_if_not_found()
    {
        $response = $this->delete('/pengelolaan-informasi/galeri/9999');

        $response->assertRedirect('/pengelolaan-informasi/galeri');
        $response->assertSessionHas('error_galeri', 'Data galeri tidak ditemukan');
    }
}
