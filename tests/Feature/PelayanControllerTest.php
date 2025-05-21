<?php

namespace Tests\Feature;

use App\Models\GaleriModel;
use App\Models\UserModel;
use App\Models\LevelModel;
use App\Models\PelayanModel;
use App\Models\KategoriPelayanModel;
use App\Models\PelkatModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PelayanControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat level admin
        $level = LevelModel::factory()->create([
            'level_kode' => 'ADM',
            'level_nama' => 'Admin',
        ]);

        // Buat user admin
        $this->admin = UserModel::factory()->create([
            'level_id' => $level->level_id,
            'username' => 'adminuser',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($this->admin);
    }

    public function test_index_returns_view()
    {
        KategoriPelayanModel::factory()->count(2)->create();
        PelkatModel::factory()->count(2)->create();

        $response = $this->get('/pengelolaan-informasi/pelayan');
        $response->assertStatus(200);
        $response->assertViewIs('pelayan.index');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategoripelayan',
            'pelkat',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_list_returns_json_structure()
    {
        $kategori = KategoriPelayanModel::factory()->create();
        $pelkat = PelkatModel::factory()->create();

        PelayanModel::factory()->create([
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'pelkat_id' => $pelkat->pelkat_id,
            'masa_jabatan_mulai' => 2022,
            'masa_jabatan_selesai' => 2024
        ]);

        $response = $this->postJson('/pengelolaan-informasi/pelayan/list');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                ['pelayan_id', 'kategoripelayan', 'pelkat', 'masa_jabatan', 'aksi']
            ],
            'minYear',
            'maxYear'
        ]);
    }

    public function test_create_returns_view()
    {
        KategoriPelayanModel::factory()->count(2)->create();
        PelkatModel::factory()->count(2)->create();

        $response = $this->get('/pengelolaan-informasi/pelayan/create');
        $response->assertStatus(200);
        $response->assertViewIs('pelayan.create');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategoripelayan',
            'pelkat',
            'diakenPenatua',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_store_creates_data()
    {
        Storage::fake('public');

        $kategori = KategoriPelayanModel::factory()->create();
        $pelkat = PelkatModel::factory()->create();

        $foto = UploadedFile::fake()->image('foto.jpg');

        $data = [
            'nama' => 'Test Pelayan',
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'pelkat_id' => $pelkat->pelkat_id,
            'masa_jabatan_mulai' => 2022,
            'masa_jabatan_selesai' => 2024,
            'foto' => $foto,
            'keterangan' => 'Pengujian simpan data',
        ];

        $response = $this->post('/pengelolaan-informasi/pelayan', $data);

        $response->assertRedirect('/pengelolaan-informasi/pelayan');
        $response->assertSessionHas('success_pelayan', 'Data pelayan berhasil disimpan');
        $this->assertDatabaseHas('t_pelayan', [
            'nama' => 'Test Pelayan',
            'keterangan' => 'Pengujian simpan data',
        ]);

        // Storage::disk('public')->assertExists('images/pelayan/' . $foto->hashName());
    }

    public function test_store_fails_when_nama_duplicate_in_same_period()
    {
        $kategori = KategoriPelayanModel::factory()->create();
        $pelkat = PelkatModel::factory()->create();

        // Insert existing pelayan
        PelayanModel::factory()->create([
            'nama' => 'Duplikat Nama',
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'pelkat_id' => $pelkat->pelkat_id,
            'masa_jabatan_mulai' => 2022,
            'masa_jabatan_selesai' => 2024,
        ]);

        $data = [
            'nama' => 'Duplikat Nama',
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'pelkat_id' => $pelkat->pelkat_id,
            'masa_jabatan_mulai' => 2022,
            'masa_jabatan_selesai' => 2024,
        ];

        $response = $this->post('/pengelolaan-informasi/pelayan', $data);
        $response->assertSessionHasErrors(['nama']);
    }

    public function test_store_fails_when_masa_jabatan_invalid()
    {
        $kategori = KategoriPelayanModel::factory()->create();

        $data = [
            'nama' => 'Test Nama',
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'masa_jabatan_mulai' => 2025,
            'masa_jabatan_selesai' => 2024, // salah, selesai sebelum mulai
        ];

        $response = $this->post('/pengelolaan-informasi/pelayan', $data);
        $response->assertSessionHasErrors(['masa_jabatan_selesai']);
    }

    public function test_store_fails_when_required_fields_missing()
    {
        $response = $this->post('/pengelolaan-informasi/pelayan', []); // kirim kosong

        $response->assertSessionHasErrors([
            'nama',
            'kategoripelayan_id',
            'masa_jabatan_mulai',
            'masa_jabatan_selesai',
        ]);
    }

    public function test_show_returns_view()
    {
        $pelayan = PelayanModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/pelayan/' . $pelayan->pelayan_id);
        $response->assertStatus(200);
        $response->assertViewIs('pelayan.show');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'pelayan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_edit_returns_view()
    {
        $kategori = KategoriPelayanModel::factory()->create();
        $pelkat = PelkatModel::factory()->create();

        $pelayan = PelayanModel::factory()->create([
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'pelkat_id' => $pelkat->pelkat_id,
        ]);

        $response = $this->get('/pengelolaan-informasi/pelayan/' . $pelayan->pelayan_id . '/edit');

        $response->assertStatus(200);
        $response->assertViewIs('pelayan.edit');
        $response->assertViewHas('pelayan', function ($viewPelayan) use ($pelayan) {
            return $viewPelayan->pelayan_id === $pelayan->pelayan_id;
        });
    }

    public function test_update_changes_data()
    {
        $kategori = KategoriPelayanModel::factory()->create();
        $pelkat = PelkatModel::factory()->create();

        $pelayan = PelayanModel::factory()->create([
            'nama' => 'Lama Nama',
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'pelkat_id' => $pelkat->pelkat_id,
            'masa_jabatan_mulai' => 2022,
            'masa_jabatan_selesai' => 2024,
        ]);

        $updateData = [
            'nama' => 'Nama Baru',
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'pelkat_id' => $pelkat->pelkat_id,
            'masa_jabatan_mulai' => 2023,
            'masa_jabatan_selesai' => 2025,
        ];

        $response = $this->put('/pengelolaan-informasi/pelayan/' . $pelayan->pelayan_id, $updateData);

        $response->assertRedirect('/pengelolaan-informasi/pelayan');
        $response->assertSessionHas('success_pelayan', 'Data pelayan berhasil diubah');
        $this->assertDatabaseHas('t_pelayan', [
            'pelayan_id' => $pelayan->pelayan_id,
            'nama' => 'Nama Baru',
            'masa_jabatan_mulai' => 2023,
            'masa_jabatan_selesai' => 2025,
        ]);
    }

    public function test_update_fails_when_duplicate_in_same_period()
    {
        $kategori = KategoriPelayanModel::factory()->create();
        $pelkat = PelkatModel::factory()->create();

        // Pelayan pertama
        PelayanModel::factory()->create([
            'nama' => 'Pelayan Sama',
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'pelkat_id' => $pelkat->pelkat_id,
            'masa_jabatan_mulai' => 2022,
            'masa_jabatan_selesai' => 2024,
        ]);

        // Pelayan kedua yang akan diupdate menjadi duplikat
        $pelayan2 = PelayanModel::factory()->create([
            'nama' => 'Pelayan Kedua',
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'pelkat_id' => $pelkat->pelkat_id,
            'masa_jabatan_mulai' => 2021,
            'masa_jabatan_selesai' => 2023,
        ]);

        $updateData = [
            'nama' => 'Pelayan Sama',
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'pelkat_id' => $pelkat->pelkat_id,
            'masa_jabatan_mulai' => 2022,
            'masa_jabatan_selesai' => 2024,
        ];

        $response = $this->put('/pengelolaan-informasi/pelayan/' . $pelayan2->pelayan_id, $updateData);
        $response->assertSessionHasErrors(['nama']);
    }

    public function test_update_fails_when_required_fields_missing()
    {
        $kategori = KategoriPelayanModel::factory()->create();
        $pelkat = PelkatModel::factory()->create();

        // Pelayan pertama
        PelayanModel::factory()->create([
            'nama' => 'Pelayan Old',
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'pelkat_id' => $pelkat->pelkat_id,
            'masa_jabatan_mulai' => 2022,
            'masa_jabatan_selesai' => 2024,
        ]);

        $response = $this->post('/pengelolaan-informasi/pelayan', []); // kirim kosong

        $response->assertSessionHasErrors([
            'nama',
            'kategoripelayan_id',
            'masa_jabatan_mulai',
            'masa_jabatan_selesai',
        ]);
    }

    public function test_destroy_deletes_data()
    {
        $pelayan = PelayanModel::factory()->create();

        $response = $this->delete('/pengelolaan-informasi/pelayan/' . $pelayan->pelayan_id);
        
        $response->assertRedirect('/pengelolaan-informasi/pelayan');
        $response->assertSessionHas('success_pelayan', 'Data pelayan berhasil dihapus');
        $this->assertDatabaseMissing('t_pelayan', [
            'pelayan_id' => $pelayan->pelayan_id,
        ]);
        
    }

    public function test_destroy_fails_if_not_found()
    {
        $response = $this->delete('/pengelolaan-informasi/pelayan/9999');

        $response->assertRedirect('/pengelolaan-informasi/pelayan');
        $response->assertSessionHas('error_pelayan', 'Data pelayan tidak ditemukan');
    }

    



}
