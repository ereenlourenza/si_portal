<?php

namespace Tests\Feature;

use App\Models\KategoriPelayanModel;
use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class KategoriPelayanControllerTest extends TestCase
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
        KategoriPelayanModel::factory()->count(3)->create();

        $response = $this->get('/pengelolaan-informasi/kategoripelayan');
        $response->assertStatus(200);
        $response->assertViewIs('kategoripelayan.index');
        
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategoripelayan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_list_returns_json()
    {
        $kategori = KategoriPelayanModel::factory()->create([
            'kategoripelayan_kode' => 'PEL01',
            'kategoripelayan_nama' => 'Diaken'
        ]);

        $response = $this->postJson('/pengelolaan-informasi/kategoripelayan/list');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'kategoripelayan_kode' => 'PEL01',
            'kategoripelayan_nama' => 'Diaken',
        ]);
    }

    public function test_create_returns_view()
    {
        $response = $this->get('/pengelolaan-informasi/kategoripelayan/create');
        $response->assertStatus(200);
        $response->assertViewIs('kategoripelayan.create');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategoripelayan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_store_creates_data()
    {
        $data = [
            'kategoripelayan_kode' => 'PLN01',
            'kategoripelayan_nama' => 'Pelayan 1'
        ];

        $response = $this->post('/pengelolaan-informasi/kategoripelayan', $data);

        $response->assertRedirect('/pengelolaan-informasi/kategoripelayan');
        $response->assertSessionHas('success_kategoripelayan', 'Data kategori pelayan berhasil disimpan');
        $this->assertDatabaseHas('t_kategoripelayan', $data);
    }

    public function test_store_fails_validation()
    {
        // Data kosong
        $data = [
            'kategoripelayan_kode' => '',
            'kategoripelayan_nama' => ''
        ];

        $response = $this->post('/pengelolaan-informasi/kategoripelayan', $data);

        $response->assertSessionHasErrors(['kategoripelayan_kode', 'kategoripelayan_nama']);
        $this->assertDatabaseMissing('t_kategoripelayan', $data);
    }

    public function test_show_returns_view()
    {
        $kategori = kategoripelayanModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/kategoripelayan/' . $kategori->kategoripelayan_id);
        $response->assertStatus(200);
        $response->assertViewIs('kategoripelayan.show');
        
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategoripelayan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_edit_returns_view()
    {
        $kategori = kategoripelayanModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/kategoripelayan/' . $kategori->kategoripelayan_id . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('kategoripelayan.edit');
        
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategoripelayan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_update_changes_data()
    {
        $kategori = kategoripelayanModel::factory()->create([
            'kategoripelayan_kode' => 'OLD',
            'kategoripelayan_nama' => 'Lama'
        ]);

        $response = $this->put('/pengelolaan-informasi/kategoripelayan/' . $kategori->kategoripelayan_id, [
            'kategoripelayan_kode' => 'NEW',
            'kategoripelayan_nama' => 'Baru'
        ]);

        $response->assertRedirect('/pengelolaan-informasi/kategoripelayan');
        $response->assertSessionHas('success_kategoripelayan', 'Data Kategori Pelayan berhasil diubah');
        $this->assertDatabaseHas('t_kategoripelayan', [
            'kategoripelayan_kode' => 'NEW',
            'kategoripelayan_nama' => 'Baru',
        ]);
    }

    public function test_update_fails_validation()
    {
        $kategori = kategoripelayanModel::factory()->create();

        // Coba update dengan data kosong
        $response = $this->put('/pengelolaan-informasi/kategoripelayan/' . $kategori->kategoripelayan_id, [
            'kategoripelayan_kode' => '',
            'kategoripelayan_nama' => ''
        ]);

        $response->assertSessionHasErrors(['kategoripelayan_kode', 'kategoripelayan_nama']);
        
        // Pastikan data lama tetap ada
        $this->assertDatabaseHas('t_kategoripelayan', [
            'kategoripelayan_id' => $kategori->kategoripelayan_id,
            'kategoripelayan_kode' => $kategori->kategoripelayan_kode,
            'kategoripelayan_nama' => $kategori->kategoripelayan_nama
        ]);
    }

    public function test_update_fails_if_not_found()
    {
        $response = $this->put('/pengelolaan-informasi/kategoripelayan/9999', [
            'kategoripelayan_kode' => 'ABC',
            'kategoripelayan_nama' => 'Tidak Ada'
        ]);

        $response->assertRedirect('/pengelolaan-informasi/kategoripelayan');
        $response->assertSessionHas('error_kategoripelayan', 'Data Kategori Pelayan tidak ditemukan');
    }

    public function test_destroy_deletes_data()
    {
        $kategori = kategoripelayanModel::factory()->create();

        $response = $this->delete('/pengelolaan-informasi/kategoripelayan/' . $kategori->kategoripelayan_id);

        $response->assertRedirect('/pengelolaan-informasi/kategoripelayan');
        $response->assertSessionHas('success_kategoripelayan', 'Data Kategori Pelayan berhasil dihapus');
        $this->assertDatabaseMissing('t_kategoripelayan', [
            'kategoripelayan_id' => $kategori->kategoripelayan_id
        ]);
    }

    public function test_destroy_fails_if_not_found()
    {
        $response = $this->delete('/pengelolaan-informasi/kategoripelayan/9999');

        $response->assertRedirect('/pengelolaan-informasi/kategoripelayan');
        $response->assertSessionHas('error_kategoripelayan', 'Data Kategori Pelayan tidak ditemukan');
    }
}
