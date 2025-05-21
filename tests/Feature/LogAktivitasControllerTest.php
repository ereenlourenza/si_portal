<?php

namespace Tests\Feature;

use App\Models\LevelModel;
use App\Models\LogAktivitasModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LogAktivitasControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // buat data level pengguna via model
        LevelModel::create([
            'level_kode' => 'MLJ',
            'level_nama' => 'Majelis Jemaat',
        ]);
        LevelModel::create([
            'level_kode' => 'PHM',
            'level_nama' => 'PHMJ',
        ]);
        LevelModel::create([
            'level_kode' => 'ADM',
            'level_nama' => 'Admin',
        ]);

        LevelModel::create([
            'level_kode' => 'SAD',
            'level_nama' => 'Super Admin',
        ]);

    }

    /** @test */
    public function index_shows_log_list_view_with_data_for_sad_user()
    {
        // buat user yang level_kode 'SAD'
        $level = LevelModel::where('level_kode', 'SAD')->first();

        $user = UserModel::factory()->create([
            'level_id' => $level->level_id,
        ]);

        // Login sebagai user SAD
        $this->actingAs($user);

        // Akses halaman index log aktivitas
        $response = $this->get(route('log.index'));

        $response->assertStatus(200);
        $response->assertViewIs('log.index');
        
        $response->assertViewHas('logs');
        $response->assertViewHas('breadcrumb');
        $response->assertViewHas('page');
        $response->assertViewHas('activeMenu', 'log');
    }

    /** @test */
    public function index_is_forbidden_for_non_sad_user()
    {
        $level = LevelModel::where('level_kode', 'ADM')->first();

        $user = UserModel::factory()->create([
            'level_id' => $level->level_id,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('log.index'));

        // Asumsikan middleware mengembalikan status 403 jika bukan SAD
        $response->assertStatus(403);
    }
}
