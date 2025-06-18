<?php

namespace Tests\Feature;

use App\Models\KategoriIbadahModel;
use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class KategoriIbadahControllerTest extends TestCase
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
        KategoriIbadahModel::factory()->count(3)->create();

        $response = $this->get('/pengelolaan-informasi/kategoriibadah');
        $response->assertStatus(200);
        $response->assertViewIs('kategoriibadah.index');
        
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
        // Buat beberapa data kategori untuk pengujian
        $kategoris = KategoriIbadahModel::factory()->count(3)->create();

        $response = $this->postJson('/pengelolaan-informasi/kategoriibadah/list');

        $response->assertStatus(200);
        // Perbarui struktur JSON untuk mencerminkan output DataTables yang sebenarnya
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'DT_RowIndex',
                    'kategoriibadah_id',
                    'kategoriibadah_kode',
                    'kategoriibadah_nama',
                    'aksi', // Kolom aksi yang ditambahkan oleh DataTables
                ]
            ]
        ]);

        // Memastikan jumlah data yang dikembalikan sesuai
        $response->assertJsonCount($kategoris->count(), 'data');

        // Membandingkan data aktual
        $responseData = $response->json('data');

        foreach ($kategoris as $kategori) {
            // Cari data kategori yang sesuai dalam respons berdasarkan kategoriibadah_id
            $responseKategori = collect($responseData)->firstWhere('kategoriibadah_id', $kategori->kategoriibadah_id);

            $this->assertNotNull($responseKategori, "Kategori Ibadah dengan ID {$kategori->kategoriibadah_id} tidak ditemukan dalam respons.");

            if ($responseKategori) {
                $this->assertEquals($kategori->kategoriibadah_id, $responseKategori['kategoriibadah_id']);
                $this->assertEquals($kategori->kategoriibadah_kode, $responseKategori['kategoriibadah_kode']);
                $this->assertEquals($kategori->kategoriibadah_nama, $responseKategori['kategoriibadah_nama']);

                // Untuk kolom 'aksi', kita bisa cek apakah URL yang benar ada di dalamnya
                $this->assertStringContainsString(url('/pengelolaan-informasi/kategoriibadah/' . $kategori->kategoriibadah_id), $responseKategori['aksi']);
                $this->assertStringContainsString(url('/pengelolaan-informasi/kategoriibadah/' . $kategori->kategoriibadah_id . '/edit'), $responseKategori['aksi']);
                // Untuk tombol hapus, form action juga akan berisi URL ini
                $this->assertStringContainsString(url('/pengelolaan-informasi/kategoriibadah/'.$kategori->kategoriibadah_id), $responseKategori['aksi']);
            }
        }
    }

    public function test_create_returns_view()
    {
        $response = $this->get('/pengelolaan-informasi/kategoriibadah/create');
        $response->assertStatus(200);
        $response->assertViewIs('kategoriibadah.create');

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
        $data = [
            'kategoriibadah_kode' => 'TST01',
            'kategoriibadah_nama' => 'Ibadah Pagi'
        ];

        $response = $this->post('/pengelolaan-informasi/kategoriibadah', $data);

        $response->assertRedirect('/pengelolaan-informasi/kategoriibadah');
        $response->assertSessionHas('success_kategoriibadah', 'Data Kategori Ibadah berhasil disimpan');
        $this->assertDatabaseHas('t_kategoriibadah', $data);
    }

    public function test_store_fails_validation()
    {
        // Data kosong
        $data = [
            'kategoriibadah_kode' => '',
            'kategoriibadah_nama' => ''
        ];

        $response = $this->post('/pengelolaan-informasi/kategoriibadah', $data);

        $response->assertSessionHasErrors(['kategoriibadah_kode', 'kategoriibadah_nama']);
        $this->assertDatabaseMissing('t_kategoriibadah', $data);
    }

    public function test_show_returns_view()
    {
        $kategori = KategoriIbadahModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/kategoriibadah/' . $kategori->kategoriibadah_id);
        $response->assertStatus(200);
        $response->assertViewIs('kategoriibadah.show');
        
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'kategoriibadah',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_edit_returns_view()
    {
        $kategori = KategoriIbadahModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/kategoriibadah/' . $kategori->kategoriibadah_id . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('kategoriibadah.edit');
        
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
        $kategori = KategoriIbadahModel::factory()->create([
            'kategoriibadah_kode' => 'OLD',
            'kategoriibadah_nama' => 'Lama'
        ]);

        $response = $this->put('/pengelolaan-informasi/kategoriibadah/' . $kategori->kategoriibadah_id, [
            'kategoriibadah_kode' => 'NEW',
            'kategoriibadah_nama' => 'Baru'
        ]);

        $response->assertRedirect('/pengelolaan-informasi/kategoriibadah');
        $response->assertSessionHas('success_kategoriibadah', 'Data Kategori Ibadah berhasil diubah');
        $this->assertDatabaseHas('t_kategoriibadah', [
            'kategoriibadah_kode' => 'NEW',
            'kategoriibadah_nama' => 'Baru',
        ]);
    }

    public function test_update_fails_validation()
    {
        $kategori = KategoriIbadahModel::factory()->create();

        // Coba update dengan data kosong
        $response = $this->put('/pengelolaan-informasi/kategoriibadah/' . $kategori->kategoriibadah_id, [
            'kategoriibadah_kode' => '',
            'kategoriibadah_nama' => ''
        ]);

        $response->assertSessionHasErrors(['kategoriibadah_kode', 'kategoriibadah_nama']);
        
        // Pastikan data lama tetap ada
        $this->assertDatabaseHas('t_kategoriibadah', [
            'kategoriibadah_id' => $kategori->kategoriibadah_id,
            'kategoriibadah_kode' => $kategori->kategoriibadah_kode,
            'kategoriibadah_nama' => $kategori->kategoriibadah_nama
        ]);
    }

    public function test_update_fails_if_not_found()
    {
        $response = $this->put('/pengelolaan-informasi/kategoriibadah/9999', [
            'kategoriibadah_kode' => 'ABC',
            'kategoriibadah_nama' => 'Tidak Ada'
        ]);

        $response->assertRedirect('/pengelolaan-informasi/kategoriibadah');
        $response->assertSessionHas('error_kategoriibadah', 'Data Kategori Ibadah tidak ditemukan');
    }

    public function test_destroy_deletes_data()
    {
        $kategori = KategoriIbadahModel::factory()->create();

        $response = $this->delete('/pengelolaan-informasi/kategoriibadah/' . $kategori->kategoriibadah_id);

        $response->assertRedirect('/pengelolaan-informasi/kategoriibadah');
        $response->assertSessionHas('success_kategoriibadah', 'Data Kategori Ibadah berhasil dihapus');
        $this->assertDatabaseMissing('t_kategoriibadah', [
            'kategoriibadah_id' => $kategori->kategoriibadah_id
        ]);
    }

    public function test_destroy_fails_if_not_found()
    {
        $response = $this->delete('/pengelolaan-informasi/kategoriibadah/9999');

        $response->assertRedirect('/pengelolaan-informasi/kategoriibadah');
        $response->assertSessionHas('error_kategoriibadah', 'Data Kategori Ibadah tidak ditemukan');
    }
}
