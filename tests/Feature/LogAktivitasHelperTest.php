<?php

namespace Tests\Feature;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogAktivitasHelperTest extends TestCase
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
    // public function simpan_log_aktivitas_creates_log_when_user_authenticated()
    // {
    //     $level = LevelModel::where('level_kode', 'ADM')->first();

    //     $user = UserModel::factory()->create([
    //         'level_id' => $level->level_id,
    //     ]);

    //     // Login user
    //     $this->actingAs($user);

    //     // Kirim request ke route dummy
    //     $this->get('/log-aktivitas-test', [
    //         'REMOTE_ADDR' => '127.0.0.1',
    //         'HTTP_USER_AGENT' => 'PHPUnit Test Agent',
    //     ]);

    //     $this->assertDatabaseHas('log_aktivitas', [
    //         'user_id'    => $user->id,
    //         'modul'      => 'ModulTest',
    //         'aksi'       => 'AksiTest',
    //         'aktivitas'  => 'AktivitasTest',
    //         'ip_address' => '127.0.0.1',
    //         'user_agent' => 'PHPUnit Test Agent',
    //     ]);
    // }



    /** @test */
    // public function simpan_log_aktivitas_does_nothing_if_user_not_authenticated()
    // {
    //     // Pastikan tidak ada user login
    //     $this->assertGuest();

    //     // Panggil helper
    //     simpanLogAktivitas('ModulTest', 'AksiTest', 'AktivitasTest');

    //     // Tidak ada log tersimpan
    //     $this->assertDatabaseCount('log_aktivitas', 0);
    // }
}
