<?php

namespace Tests\Feature;

use App\Models\IbadahModel;
use App\Models\KategoriIbadahModel;
use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IbadahControllerTest extends TestCase
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
        IbadahModel::factory()->count(3)->create();

        $response = $this->get('/pengelolaan-informasi/ibadah');
        $response->assertStatus(200);
        $response->assertViewIs('ibadah.index');
        
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategoriibadah',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_list_returns_json()
    {
        $kategori = KategoriIbadahModel::factory()->create();
        
        $ibadah = IbadahModel::factory()->create([
            'kategoriibadah_id' => $kategori->kategoriibadah_id,
            'tanggal' => '2025-05-20',
            'waktu' => '10:00',
            'tempat' => 'Gereja Utama',
            'lokasi' => 'Jl. Mawar 1',
            'sektor' => 1,
            'nama_pelkat' => 'Pemuda',
            'ruang' => 'Aula',
            'pelayan_firman' => 'Pdt. Yohanes'
        ]);

        $response = $this->postJson('/pengelolaan-informasi/ibadah/list');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'tanggal' => '2025-05-20',
            'waktu' => '10:00',
            'tempat' => 'Gereja Utama',
            'lokasi' => 'Jl. Mawar 1',
            'pelayan_firman' => 'Pdt. Yohanes',
            'nama_pelkat' => 'Pemuda',
        ]);
    }

    public function test_create_returns_view()
    {
        $response = $this->get('/pengelolaan-informasi/ibadah/create');
        $response->assertStatus(200);
        $response->assertViewIs('ibadah.create');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategoriibadah',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_store_creates_data()
    {
        $kategori = KategoriIbadahModel::factory()->create();

        $data = [
            'kategoriibadah_id' => $kategori->kategoriibadah_id,
            'tanggal' => '2025-05-25',
            'waktu' => '08:30',
            'tempat' => 'Gereja Pusat',
            'lokasi' => 'Jl. Kemenangan No.1',
            'sektor' => 3,
            'nama_pelkat' => 'Remaja',
            'ruang' => 'Ruang Serbaguna',
            'pelayan_firman' => 'Pdt. Lukas'
        ];

        $response = $this->post('/pengelolaan-informasi/ibadah', $data);

        $response->assertRedirect('/pengelolaan-informasi/ibadah');
        $response->assertSessionHas('success_ibadah', 'Data ibadah berhasil disimpan');

        $this->assertDatabaseHas('t_ibadah', [
            'kategoriibadah_id' => $kategori->kategoriibadah_id,
            'tanggal' => '2025-05-25',
            'tempat' => 'Gereja Pusat',
            'pelayan_firman' => 'Pdt. Lukas',
        ]);
    }

    public function test_store_fails_validation()
    {
        // Data kosong
        $data = [
            'kategoriibadah_id' => '',
            'tanggal' => '',
            'waktu' => '',
            'tempat' => '',
            'lokasi' => '',
            'sektor' => '',
            'nama_pelkat' => '',
            'ruang' => '',
            'pelayan_firman' => ''
        ];

        $response = $this->post('/pengelolaan-informasi/ibadah', $data);

        $response->assertSessionHasErrors(['kategoriibadah_id', 'tanggal', 'waktu', 'tempat', 'pelayan_firman']);
        $this->assertDatabaseMissing('t_ibadah', $data);
    }

    public function test_show_returns_view()
    {
        $ibadah = IbadahModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/ibadah/' . $ibadah->ibadah_id);
        $response->assertStatus(200);
        $response->assertViewIs('ibadah.show');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'ibadah',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_edit_returns_view()
    {
        $ibadah = IbadahModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/ibadah/' . $ibadah->ibadah_id . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('ibadah.edit');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategoriibadah',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_update_changes_data()
    {
        // Buat kategori dan data ibadah awal
        $kategoriAwal = KategoriIbadahModel::factory()->create();
        $kategoriBaru = KategoriIbadahModel::factory()->create();

        $ibadah = IbadahModel::factory()->create([
            'kategoriibadah_id' => $kategoriAwal->kategoriibadah_id,
            'tanggal' => '2025-05-01',
            'waktu' => '09:00',
            'tempat' => 'Gedung Lama',
            'lokasi' => 'Jl. Mawar',
            'sektor' => 1,
            'nama_pelkat' => 'Pemuda',
            'ruang' => 'Aula',
            'pelayan_firman' => 'Pdt. Lama'
        ]);

        // Data perubahan
        $data = [
            'kategoriibadah_id' => $kategoriBaru->kategoriibadah_id,
            'tanggal' => '2025-06-01',
            'waktu' => '11:00',
            'tempat' => 'Gedung Baru',
            'lokasi' => 'Jl. Melati',
            'sektor' => 2,
            'nama_pelkat' => 'Remaja',
            'ruang' => 'Ruang Serbaguna',
            'pelayan_firman' => 'Pdt. Baru'
        ];

        $response = $this->put('/pengelolaan-informasi/ibadah/' . $ibadah->ibadah_id, $data);

        $response->assertRedirect('/pengelolaan-informasi/ibadah');
        $response->assertSessionHas('success_ibadah', 'Data ibadah berhasil diubah');

        $this->assertDatabaseHas('t_ibadah', [
            'ibadah_id' => $ibadah->ibadah_id,
            'kategoriibadah_id' => $kategoriBaru->kategoriibadah_id,
            'tempat' => 'Gedung Baru',
            'pelayan_firman' => 'Pdt. Baru',
        ]);
    }
    
    public function test_update_fails_validation()
    {
        $kategori = KategoriIbadahModel::factory()->create();

        $ibadah = IbadahModel::factory()->create([
            'kategoriibadah_id' => $kategori->kategoriibadah_id,
            'tanggal' => '2025-05-01',
            'waktu' => '09:00',
            'tempat' => 'Gedung Lama',
            'lokasi' => 'Jl. Mawar',
            'sektor' => 1,
            'nama_pelkat' => 'Pemuda',
            'ruang' => 'Aula',
            'pelayan_firman' => 'Pdt. Lama'
        ]);

        $data = [
            'kategoriibadah_id' => '',
            'tanggal' => '',
            'waktu' => '',
            'tempat' => '',
            'lokasi' => '',
            'sektor' => '',
            'nama_pelkat' => '',
            'ruang' => '',
            'pelayan_firman' => ''
        ];

        $response = $this->put('/pengelolaan-informasi/ibadah/' . $ibadah->ibadah_id, $data);

        $response->assertSessionHasErrors(['kategoriibadah_id', 'tanggal', 'waktu', 'tempat', 'pelayan_firman']);
        $this->assertDatabaseMissing('t_ibadah', $data);
    }

    public function test_update_fails_if_not_found()
    {
        $kategori = KategoriIbadahModel::factory()->create();

        $response = $this->put('/pengelolaan-informasi/ibadah/9999',[
            'kategoriibadah_id' => $kategori->kategoriibadah_id,
            'tanggal' => '2025-05-01',
            'waktu' => '09:00',
            'tempat' => 'Gedung Satunya',
            'lokasi' => 'Jl. Melati',
            'sektor' => 1,
            'nama_pelkat' => 'PA',
            'ruang' => 'Serbaguna',
            'pelayan_firman' => 'Pdt. Baru'
        ]);

        $response->assertRedirect('/pengelolaan-informasi/ibadah');
        $response->assertSessionHas('error_ibadah', 'Terjadi kesalahan saat mengubah data: ');
    }

    public function test_destroy_deletes_data()
    {
        $ibadah = IbadahModel::factory()->create();

        $response = $this->delete('/pengelolaan-informasi/ibadah/' . $ibadah->ibadah_id);

        $response->assertRedirect('/pengelolaan-informasi/ibadah');
        $response->assertSessionHas('success_ibadah', 'Data ibadah berhasil dihapus');
        $this->assertDatabaseMissing('t_ibadah', [
            'ibadah_id' => $ibadah->ibadah_id,
        ]);
    }

    public function test_destroy_fails_if_not_found()
    {
        $response = $this->delete('/pengelolaan-informasi/ibadah/9999');

        $response->assertRedirect('/pengelolaan-informasi/ibadah');
        $response->assertSessionHas('error_ibadah', 'Data ibadah tidak ditemukan');
    }

}
