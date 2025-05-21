<?php

namespace Tests\Feature;

use App\Models\KategoriGaleriModel;
use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class KategoriGaleriControllerTest extends TestCase
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
        KategoriGaleriModel::factory()->count(3)->create();

        $response = $this->get('/pengelolaan-informasi/kategorigaleri');
        $response->assertStatus(200);
        $response->assertViewIs('kategorigaleri.index');
        
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
        $kategori = KategoriGaleriModel::factory()->create([
            'kategorigaleri_kode' => 'FOT01',
            'kategorigaleri_nama' => 'Foto Baptis'
        ]);

        $response = $this->postJson('/pengelolaan-informasi/kategorigaleri/list');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'kategorigaleri_kode' => 'FOT01',
            'kategorigaleri_nama' => 'Foto Baptis',
        ]);
    }

    public function test_create_returns_view()
    {
        $response = $this->get('/pengelolaan-informasi/kategorigaleri/create');
        $response->assertStatus(200);
        $response->assertViewIs('kategorigaleri.create');

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
        $data = [
            'kategorigaleri_kode' => 'VID01',
            'kategorigaleri_nama' => 'Video Natal'
        ];

        $response = $this->post('/pengelolaan-informasi/kategorigaleri', $data);

        $response->assertRedirect('/pengelolaan-informasi/kategorigaleri');
        $response->assertSessionHas('success_kategorigaleri', 'Data kategori galeri berhasil disimpan');
        $this->assertDatabaseHas('t_kategorigaleri', $data);
    }

    public function test_store_fails_validation()
    {
        // Data kosong
        $data = [
            'kategorigaleri_kode' => '',
            'kategorigaleri_nama' => ''
        ];

        $response = $this->post('/pengelolaan-informasi/kategorigaleri', $data);

        $response->assertSessionHasErrors(['kategorigaleri_kode', 'kategorigaleri_nama']);
        $this->assertDatabaseMissing('t_kategorigaleri', $data);
    }


    public function test_show_returns_view()
    {
        $kategori = KategoriGaleriModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/kategorigaleri/' . $kategori->kategorigaleri_id);
        $response->assertStatus(200);
        $response->assertViewIs('kategorigaleri.show');
        
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategorigaleri',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_edit_returns_view()
    {
        $kategori = KategoriGaleriModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/kategorigaleri/' . $kategori->kategorigaleri_id . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('kategorigaleri.edit');
        
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
        $kategori = KategoriGaleriModel::factory()->create([
            'kategorigaleri_kode' => 'OLD',
            'kategorigaleri_nama' => 'Lama'
        ]);

        $response = $this->put('/pengelolaan-informasi/kategorigaleri/' . $kategori->kategorigaleri_id, [
            'kategorigaleri_kode' => 'NEW',
            'kategorigaleri_nama' => 'Baru'
        ]);

        $response->assertRedirect('/pengelolaan-informasi/kategorigaleri');
        $response->assertSessionHas('success_kategorigaleri', 'Data Kategori galeri berhasil diubah');
        $this->assertDatabaseHas('t_kategorigaleri', [
            'kategorigaleri_kode' => 'NEW',
            'kategorigaleri_nama' => 'Baru',
        ]);
    }

    public function test_update_fails_validation()
    {
        $kategori = KategoriGaleriModel::factory()->create();

        // Coba update dengan data kosong
        $response = $this->put('/pengelolaan-informasi/kategorigaleri/' . $kategori->kategorigaleri_id, [
            'kategorigaleri_kode' => '',
            'kategorigaleri_nama' => ''
        ]);

        $response->assertSessionHasErrors(['kategorigaleri_kode', 'kategorigaleri_nama']);
        
        // Pastikan data lama tetap ada
        $this->assertDatabaseHas('t_kategorigaleri', [
            'kategorigaleri_id' => $kategori->kategorigaleri_id,
            'kategorigaleri_kode' => $kategori->kategorigaleri_kode,
            'kategorigaleri_nama' => $kategori->kategorigaleri_nama
        ]);
    }

    public function test_update_fails_if_not_found()
    {
        $response = $this->put('/pengelolaan-informasi/kategorigaleri/9999', [
            'kategorigaleri_kode' => 'ABC',
            'kategorigaleri_nama' => 'Tidak Ada'
        ]);

        $response->assertRedirect('/pengelolaan-informasi/kategorigaleri');
        $response->assertSessionHas('error_kategorigaleri', 'Data Kategori galeri tidak ditemukan');
    }

    public function test_destroy_deletes_data()
    {
        $kategori = KategoriGaleriModel::factory()->create();

        $response = $this->delete('/pengelolaan-informasi/kategorigaleri/' . $kategori->kategorigaleri_id);

        $response->assertRedirect('/pengelolaan-informasi/kategorigaleri');
        $response->assertSessionHas('success_kategorigaleri', 'Data Kategori galeri berhasil dihapus');
        $this->assertDatabaseMissing('t_kategorigaleri', [
            'kategorigaleri_id' => $kategori->kategorigaleri_id
        ]);
    }

    public function test_destroy_fails_if_not_found()
    {
        $response = $this->delete('/pengelolaan-informasi/kategorigaleri/9999');

        $response->assertRedirect('/pengelolaan-informasi/kategorigaleri');
        $response->assertSessionHas('error_kategorigaleri', 'Data Kategori galeri tidak ditemukan');
    }
}
