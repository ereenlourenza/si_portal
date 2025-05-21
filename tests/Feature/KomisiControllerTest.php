<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\UserModel;
use App\Models\LevelModel;
use App\Models\KomisiModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class KomisiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $level = LevelModel::factory()->create([
            'level_kode' => 'ADM',
            'level_nama' => 'Admin'
        ]);

        $this->admin = UserModel::factory()->create([
            'level_id' => $level->level_id,
            'username' => 'adminuser',
            'password' => Hash::make('admin123')
        ]);

        $this->actingAs($this->admin);
    }

    public function test_index_returns_view()
    {
        KomisiModel::factory()->count(3)->create();

        $response = $this->get('/pengelolaan-informasi/komisi');

        $response->assertStatus(200);
        $response->assertViewIs('komisi.index');
        $response->assertViewHasAll(['breadcrumb', 'page', 'activeMenu', 'notifUser']);
    }

    public function test_create_returns_view()
    {
        $response = $this->get('/pengelolaan-informasi/komisi/create');

        $response->assertStatus(200);
        $response->assertViewIs('komisi.create');
        $response->assertViewHasAll(['breadcrumb', 'page', 'activeMenu', 'notifUser']);
    }

    public function test_store_creates_data()
    {
        $data = [
            'komisi_nama' => 'Komisi Doa',
            'deskripsi' => '<p>Ini deskripsi komisi doa</p>'
        ];

        $response = $this->post('/pengelolaan-informasi/komisi', $data);

        $response->assertRedirect('/pengelolaan-informasi/komisi');
        $this->assertDatabaseHas('t_komisi', [
            'komisi_nama' => 'Komisi Doa'
        ]);
    }

    public function test_show_returns_view()
    {
        $komisi = KomisiModel::factory()->create();

        $response = $this->get("/pengelolaan-informasi/komisi/{$komisi->komisi_id}");

        $response->assertStatus(200);
        $response->assertViewIs('komisi.show');
        $response->assertViewHasAll(['breadcrumb', 'page', 'komisi', 'activeMenu', 'notifUser']);
    }

    public function test_edit_returns_view()
    {
        $komisi = KomisiModel::factory()->create();

        $response = $this->get("/pengelolaan-informasi/komisi/{$komisi->komisi_id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('komisi.edit');
        $response->assertViewHasAll(['breadcrumb', 'page', 'komisi', 'activeMenu', 'notifUser']);
    }

    public function test_update_modifies_existing_komisi()
    {
        $komisi = KomisiModel::factory()->create();

        $response = $this->put("/pengelolaan-informasi/komisi/{$komisi->komisi_id}", [
            'komisi_nama' => 'Komisi Updated',
            'deskripsi' => '<p>Updated description</p>'
        ]);

        $response->assertRedirect('/pengelolaan-informasi/komisi');
        $this->assertDatabaseHas('t_komisi', [
            'komisi_id' => $komisi->komisi_id,
            'komisi_nama' => 'Komisi Updated',
        ]);
    }

    public function test_destroy_deletes_data()
    {
        $komisi = KomisiModel::factory()->create();

        $response = $this->delete("/pengelolaan-informasi/komisi/{$komisi->komisi_id}");

        $response->assertRedirect('/pengelolaan-informasi/komisi');
        $this->assertDatabaseMissing('t_komisi', [
            'komisi_id' => $komisi->komisi_id,
        ]);
    }
}
