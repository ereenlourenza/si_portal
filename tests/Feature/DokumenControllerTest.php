<?php

namespace Tests\Feature;

use App\Models\UserModel;
use App\Models\LevelModel;
use App\Models\TataIbadahModel;
use App\Models\WartaJemaatModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\LevelSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\TataIbadahSeeder; // Assuming you have this seeder
use Database\Seeders\WartaJemaatSeeder; // Assuming you have this seeder

class DokumenControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(LevelSeeder::class);
        $this->seed(UserSeeder::class);
        // Seed dependent data if necessary
        $this->seed(TataIbadahSeeder::class); 
        $this->seed(WartaJemaatSeeder::class); 

        $adminLevel = LevelModel::where('level_kode', 'ADM')->first();
        if (!$adminLevel) {
            $adminLevel = LevelModel::factory()->create(['level_kode' => 'ADM', 'level_nama' => 'Administrator']);
        }

        $this->adminUser = UserModel::factory()->create([
            'level_id' => $adminLevel->level_id
        ]);

        // Mock the simpanLogAktivitas helper function if it's used and not available in tests
        if (!function_exists('simpanLogAktivitas')) {
            function simpanLogAktivitas($menu, $aksi, $detail) {
                // Mock implementation or leave empty
            }
        }
    }

    public function test_index()
    {
        $this->actingAs($this->adminUser);

        // Create some dummy data for TataIbadahModel and WartaJemaatModel
        TataIbadahModel::factory()->count(3)->create();
        WartaJemaatModel::factory()->count(3)->create();

        $response = $this->get(route('dokumen.index')); // Using route name

        $response->assertStatus(200);
        $response->assertViewIs('dokumen.index');
        $response->assertViewHasAll([
            'breadcrumb',
            'pageTataIbadah',
            'pageWartaJemaat',
            'tataibadah',
            'wartajemaat',
            'activeMenu',
            'notifUser'
        ]);

        // Optionally, assert that the collections are not empty if data was created
        $response->assertViewHas('tataibadah', function ($tataibadah) {
            return $tataibadah->count() > 0;
        });
        $response->assertViewHas('wartajemaat', function ($wartajemaat) {
            return $wartajemaat->count() > 0;
        });
    }
}
