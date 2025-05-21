<?php

namespace Tests\Feature;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LevelControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_returns_correct_view_with_data()
    {
        // Setup data dummy langsung (tanpa factory)
        LevelModel::insert([
            ['level_kode' => 'MLJ', 'level_nama' => 'Majelis Jemaat'],
            ['level_kode' => 'PHM', 'level_nama' => 'PHMJ'],
            ['level_kode' => 'ADM', 'level_nama' => 'Admin'],
            ['level_kode' => 'SAD', 'level_nama' => 'Super Admin'],
        ]);

        UserModel::insert([
            ['user_id' => 1, 'name' => 'Majelis Jemaat Official', 'username' => 'majelisjemaat', 'password' => bcrypt('majelisjemaat12345'), 'level_id' => 1],
            ['user_id' => 2, 'name' => 'PHMJ Official', 'username' => 'phmj', 'password' => bcrypt('phmj12345'), 'level_id' => 2],
            ['user_id' => 3, 'name' => 'Admin Official', 'username' => 'admin', 'password' => bcrypt('admin12345'), 'level_id' => 3],
            ['user_id' => 4, 'name' => 'Super Admin Official', 'username' => 'superadmin', 'password' => bcrypt('superadmin12345'), 'level_id' => 4]
        ]);

        // Simulasi login sebagai super admin
        $superAdmin = UserModel::find(4); // id sesuai data di atas
        $this->actingAs($superAdmin);

        // Panggil route
        $response = $this->get('/pengelolaan-pengguna/level'); // sesuaikan dengan routing kamu

        // Cek status dan view
        $response->assertStatus(200);
        $response->assertViewIs('level.index');

        // Cek apakah variabel dikirim ke view
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'level',
            'activeMenu',
            'notifUser'
        ]);
    }
    
    /** @test */
    public function create_returns_correct_view_with_data()
    {
        // Insert levels dulu
        $levels = [
            ['level_kode' => 'MLJ', 'level_nama' => 'Majelis Jemaat'],
            ['level_kode' => 'PHM', 'level_nama' => 'PHMJ'],
            ['level_kode' => 'ADM', 'level_nama' => 'Admin'],
            ['level_kode' => 'SAD', 'level_nama' => 'Super Admin'],
        ];

        foreach ($levels as $levelData) {
            LevelModel::create($levelData);
        }

        // Ambil level super admin
        $superAdminLevel = LevelModel::where('level_kode', 'SAD')->first();

        // Insert user super admin dengan level_id yang valid
        $superAdminUser = UserModel::create([
            'name' => 'Super Admin Official',
            'username' => 'superadmin',
            'password' => bcrypt('superadmin12345'),
            'level_id' => $superAdminLevel->level_id,
        ]);

        // Acting as super admin
        $this->actingAs($superAdminUser);

        // Test route create
        $response = $this->get('/pengelolaan-pengguna/level/create');

        $response->assertStatus(200);
        $response->assertViewIs('level.create');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'level',
            'activeMenu',
            'notifUser'
        ]);
    }

    // /** @test */
    public function test_store_saves_new_level_and_redirects()
    {
        // Insert level super admin dulu (tanpa set id)
        $level = LevelModel::create([
            'level_kode' => 'SAD',
            'level_nama' => 'Super Admin',
        ]);

        // Buat user super admin dengan level_id sesuai yang dibuat
        $superAdmin = UserModel::factory()->create(['level_id' => $level->level_id]);
        $this->actingAs($superAdmin);

        $postData = [
            'level_kode' => 'TST',
            'level_nama' => 'Testing Level',
        ];

        $response = $this->post('/pengelolaan-pengguna/level', $postData);

        $response->assertRedirect('/pengelolaan-pengguna/level');
        $response->assertSessionHas('success', 'Data level berhasil disimpan');
        $this->assertDatabaseHas('t_level', [
            'level_kode' => 'TST',
            'level_nama' => 'Testing Level',
        ]);
    }

    /** @test */
    public function update_edits_existing_level_and_redirects()
    {
        // Buat dulu level super admin yang konsisten kode-nya 'SAD'
        $superAdminLevel = LevelModel::create([
            'level_kode' => 'SAD',
            'level_nama' => 'Super Admin',
        ]);

        // Buat level yang akan diupdate
        $level = LevelModel::create([
            'level_kode' => 'UPD',
            'level_nama' => 'Level Lama',
        ]);

        // Buat user super admin dengan level super admin yang benar
        $superAdmin = UserModel::factory()->create([
            'level_id' => $superAdminLevel->level_id
        ]);

        $this->actingAs($superAdmin);

        $updateData = [
            'level_kode' => 'UPD',
            'level_nama' => 'Level Baru',
        ];

        $response = $this->put("/pengelolaan-pengguna/level/{$level->level_id}", $updateData);

        $response->assertRedirect('/pengelolaan-pengguna/level');
        $response->assertSessionHas('success', 'Data level berhasil diubah');

        $this->assertDatabaseHas('t_level', [
            'level_id' => $level->level_id,
            'level_nama' => 'Level Baru',
        ]);

        
    }

    /** @test */
    public function destroy_deletes_level_and_redirects()
    {
        // Level super admin yang valid (kode 'SAD')
        $superAdminLevel = LevelModel::create([
            'level_kode' => 'SAD',
            'level_nama' => 'Super Admin',
        ]);

        // Level yang akan dihapus
        $level = LevelModel::create([
            'level_kode' => 'DEL',
            'level_nama' => 'To be deleted',
        ]);

        // User super admin yang valid
        $superAdmin = UserModel::factory()->create([
            'level_id' => $superAdminLevel->level_id,
        ]);

        $this->actingAs($superAdmin);

        $response = $this->delete("/pengelolaan-pengguna/level/{$level->level_id}");

        $response->assertRedirect('/pengelolaan-pengguna/level');
        $response->assertSessionHas('success', 'Data level berhasil dihapus');

        $this->assertDatabaseMissing('t_level', [
            'level_id' => $level->level_id,
        ]);
    }



    /** @test */
    public function store_fails_with_missing_required_fields()
    {
        $level = LevelModel::create([
            'level_kode' => 'SAD',
            'level_nama' => 'Super Admin',
        ]);

        $superAdmin = UserModel::factory()->create(['level_id' => $level->level_id]);
        $this->actingAs($superAdmin);

        $response = $this->post('/pengelolaan-pengguna/level', []); // kosong

        $response->assertSessionHasErrors(['level_kode', 'level_nama']);
    }

    /** @test */
    public function store_fails_if_level_kode_is_not_unique()
    {
        LevelModel::create([
            'level_kode' => 'ADM',
            'level_nama' => 'Admin',
        ]);

        $superAdmin = UserModel::factory()->create([
            'level_id' => LevelModel::create([
                'level_kode' => 'SAD',
                'level_nama' => 'Super Admin',
            ])->level_id
        ]);

        $this->actingAs($superAdmin);

        $response = $this->post('/pengelolaan-pengguna/level', [
            'level_kode' => 'ADM',
            'level_nama' => 'Administrator Duplikat',
        ]);

        $response->assertSessionHasErrors(['level_kode']);
    }




}
