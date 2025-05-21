<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat level super admin (kode: SAD)
        $level = LevelModel::factory()->create([
            'level_kode' => 'SAD',
            'level_nama' => 'Super Admin'
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

    public function test_index_returns_success()
    {
        $response = $this->get('/pengelolaan-pengguna/user');
        $response->assertStatus(200);
        $response->assertViewIs('user.index');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'level',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_list_returns_json()
    {
        $response = $this->postJson('/pengelolaan-pengguna/user/list');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_create_returns_success()
    {
        $response = $this->get('/pengelolaan-pengguna/user/create');
        $response->assertStatus(200);
        $response->assertViewIs('user.create');

        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'level',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_store_creates_new_user()
    {
        $level = LevelModel::factory()->create();

        $response = $this->post('/pengelolaan-pengguna/user', [
            'level_id' => $level->level_id,
            'username' => 'newuser',
            'name' => 'New User',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/pengelolaan-pengguna/user');
        $response->assertSessionHas('success', 'Data pengguna berhasil disimpan');
        $this->assertDatabaseHas('t_user', ['username' => 'newuser']);
    }

    public function test_update_modifies_user_data()
    {
        $level = LevelModel::factory()->create();
        $user = UserModel::factory()->create([
            'level_id' => $level->level_id,
            'username' => 'editme'
        ]);

        $response = $this->put("/pengelolaan-pengguna/user/{$user->user_id}", [
            'level_id' => $level->level_id,
            'username' => 'editeduser',
            'name' => 'Updated Name',
            'password' => '', // tidak diubah
        ]);

        $response->assertRedirect('/pengelolaan-pengguna/user');
        $response->assertSessionHas('success', 'Data user berhasil diubah');
        $this->assertDatabaseHas('t_user', ['username' => 'editeduser']);
    }

    public function test_destroy_deletes_user()
    {
        $user = UserModel::factory()->create();

        $response = $this->delete("/pengelolaan-pengguna/user/{$user->user_id}");

        $response->assertRedirect('/pengelolaan-pengguna/user');
        $response->assertSessionHas('success', 'Data pengguna berhasil dihapus');
        $this->assertDatabaseMissing('t_user', ['user_id' => $user->user_id]);
    }
}
