<?php

namespace Tests\Feature;

use App\Models\LevelModel;
use App\Models\UserModel;
use App\Models\PelkatModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PelkatControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat level admin
        $level = LevelModel::factory()->create([
            'level_kode' => 'ADM',
            'level_nama' => 'Admin'
        ]);

        // Buat user admin
        $this->admin = UserModel::factory()->create([
            'level_id' => $level->level_id,
            'username' => 'adminuser',
            'password' => Hash::make('admin123')
        ]);

        // Login sebagai admin
        $this->actingAs($this->admin);
    }

    public function test_index_returns_view()
    {
        PelkatModel::factory()->count(3)->create();

        $response = $this->get('/pengelolaan-informasi/pelkat');

        $response->assertStatus(200);
        $response->assertViewIs('pelkat.index');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_create_returns_view()
    {
        $response = $this->get('/pengelolaan-informasi/pelkat/create');

        $response->assertStatus(200);
        $response->assertViewIs('pelkat.create');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_store_creates_data()
    {
        $data = [
            'pelkat_nama' => 'Pelkat Pemuda',
            'deskripsi' => '<p>Deskripsi singkat pelkat</p>'
        ];

        $response = $this->post('/pengelolaan-informasi/pelkat', $data);

        $response->assertRedirect('/pengelolaan-informasi/pelkat');
        $this->assertDatabaseHas('t_pelkat', [
            'pelkat_nama' => 'Pelkat Pemuda',
            'deskripsi' => $data['deskripsi']
        ]);
    }

    public function test_show_returns_view()
    {
        $pelkat = PelkatModel::factory()->create();

        $response = $this->get("/pengelolaan-informasi/pelkat/{$pelkat->pelkat_id}");

        $response->assertStatus(200);
        $response->assertViewIs('pelkat.show');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'pelkat',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_edit_returns_view()
    {
        $pelkat = PelkatModel::factory()->create();

        $response = $this->get("/pengelolaan-informasi/pelkat/{$pelkat->pelkat_id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('pelkat.edit');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'pelkat',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_update_modifies_existing_pelkat()
    {
        $pelkat = PelkatModel::factory()->create([
            'pelkat_nama' => 'Pelkat Lama',
            'deskripsi' => 'Deskripsi lama'
        ]);

        $data = [
            'pelkat_nama' => 'Pelkat Baru',
            'deskripsi' => 'Deskripsi baru'
        ];

        $response = $this->put("/pengelolaan-informasi/pelkat/{$pelkat->pelkat_id}", $data);

        $response->assertRedirect('/pengelolaan-informasi/pelkat');

        $this->assertDatabaseHas('t_pelkat', [
            'pelkat_id' => $pelkat->pelkat_id,
            'pelkat_nama' => 'Pelkat Baru',
            'deskripsi' => 'Deskripsi baru'
        ]);
    }

    public function test_destroy_deletes_data()
    {
        // Arrange
        $pelkat = PelkatModel::factory()->create([
            'pelkat_nama' => 'Pelkat Test',
            'deskripsi' => '<p>Deskripsi pelkat test</p>',
        ]);

        // Act
        $response = $this->delete('/pengelolaan-informasi/pelkat/' . $pelkat->pelkat_id);

        // Assert
        $response->assertRedirect('/pengelolaan-informasi/pelkat');
        $response->assertSessionHas('success', 'Data pelkat berhasil dihapus');
        $this->assertDatabaseMissing('t_pelkat', [
            'pelkat_id' => $pelkat->pelkat_id
        ]);
    }
}
