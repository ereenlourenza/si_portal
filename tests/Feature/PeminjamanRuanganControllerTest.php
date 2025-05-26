<?php

namespace Tests\Feature;

use App\Models\PeminjamanRuanganModel;
use App\Models\RuanganModel;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\LevelSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\RuanganSeeder; // Tambahkan seeder untuk RuanganModel jika ada, atau buat factory
use Carbon\Carbon;

class PeminjamanRuanganControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(LevelSeeder::class);
        $this->seed(UserSeeder::class);
        // Seed RuanganSeeder jika ada, atau buat data ruangan secara manual/factory
        // Contoh: $this->seed(RuanganSeeder::class); 
        // Atau buat factory untuk RuanganModel dan gunakan di sini jika diperlukan

        $adminLevel = LevelModel::where('level_kode', 'ADM')->first();
        if (!$adminLevel) {
            $adminLevel = LevelModel::factory()->create(['level_kode' => 'ADM', 'level_nama' => 'Administrator']);
        }

        $this->adminUser = UserModel::factory()->create([
            'level_id' => $adminLevel->level_id
        ]);

        // Membuat data Ruangan default jika belum ada
        if (RuanganModel::count() == 0) {
            RuanganModel::factory()->create(['ruangan_nama' => 'Ruang Serbaguna']); // Ensure this is the only name attribute
        }
    }

    public function test_index()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/pengelolaan-informasi/peminjamanruangan');

        $response->assertStatus(200);
        $response->assertViewIs('peminjamanruangan.index');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'ruangan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_list()
    {
        $this->actingAs($this->adminUser);
        $ruangan = RuanganModel::first();
        PeminjamanRuanganModel::factory()->count(3)->for($ruangan, 'ruangan')->create();

        $response = $this->postJson('/pengelolaan-informasi/peminjamanruangan/list');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'DT_RowIndex',
                    'peminjamanruangan_id',
                    'peminjam_nama',
                    'peminjam_telepon',
                    'tanggal',
                    'waktu_mulai',
                    'waktu_selesai',
                    'ruangan_id',
                    'keperluan',
                    'status',
                    'alasan_penolakan',
                    'waktu',
                    'aksi',
                ]
            ]
        ]);
    }

    public function test_create()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/pengelolaan-informasi/peminjamanruangan/create');

        $response->assertStatus(200);
        $response->assertViewIs('peminjamanruangan.create');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'ruangan',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_store_success()
    {
        $this->actingAs($this->adminUser);
        $ruangan = RuanganModel::first();

        $data = [
            'peminjam_nama' => 'Test Peminjam',
            'peminjam_telepon' => '08123456789',
            'tanggal' => Carbon::now()->format('Y-m-d'),
            'waktu_mulai' => '10:00',
            'waktu_selesai' => '12:00',
            'ruangan_id' => $ruangan->ruangan_id,
            'keperluan' => 'Rapat Internal',
            'status' => 0, // Menunggu Konfirmasi
        ];

        $response = $this->post('/pengelolaan-informasi/peminjamanruangan', $data);

        $response->assertRedirect('/pengelolaan-informasi/peminjamanruangan');
        $response->assertSessionHas('success_peminjamanruangan', 'Data peminjaman ruangan berhasil disimpan');
        $this->assertDatabaseHas('t_peminjamanruangan', [
            'peminjam_nama' => $data['peminjam_nama'],
            'ruangan_id' => $data['ruangan_id'],
            'keperluan' => $data['keperluan'],
        ]);
    }

    public function test_store_validation_error()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'peminjam_nama' => '' // Invalid: nama peminjam is required
        ];

        $response = $this->post('/pengelolaan-informasi/peminjamanruangan', $data);

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('peminjam_nama');
    }

    public function test_update_validation_success_approve()
    {
        $this->actingAs($this->adminUser);
        $ruangan = RuanganModel::first();
        $peminjaman = PeminjamanRuanganModel::factory()->for($ruangan, 'ruangan')->create(['status' => 0]); // Status Menunggu

        $response = $this->get('/pengelolaan-informasi/peminjamanruangan/updateValidation/' . $peminjaman->peminjamanruangan_id);

        $response->assertRedirect('/pengelolaan-informasi/peminjamanruangan');
        $response->assertSessionHas('success_peminjamanruangan', 'Peminjaman ruangan berhasil disetujui dan peminjaman lain yang bentrok telah ditolak.');
        $this->assertDatabaseHas('t_peminjamanruangan', [
            'peminjamanruangan_id' => $peminjaman->peminjamanruangan_id,
            'status' => 1 // Disetujui
        ]);
    }
    
    public function test_update_validation_success_approve_and_reject_others()
    {
        $this->actingAs($this->adminUser);
        $ruangan = RuanganModel::first();
        $peminjamanToApprove = PeminjamanRuanganModel::factory()->for($ruangan, 'ruangan')->create([
            'status' => 0, // Menunggu
            'tanggal' => '2025-06-01',
            'waktu_mulai' => '10:00:00',
            'waktu_selesai' => '12:00:00',
        ]);
    
        // Peminjaman lain yang bentrok (sama tanggal, ruangan, dan waktu)
        $peminjamanToReject = PeminjamanRuanganModel::factory()->for($ruangan, 'ruangan')->create([
            'status' => 0, // Menunggu
            'tanggal' => '2025-06-01',
            'waktu_mulai' => '11:00:00', // Bentrok
            'waktu_selesai' => '13:00:00',
        ]);
    
        // Peminjaman lain yang tidak bentrok (beda tanggal)
        $peminjamanNotConflicting = PeminjamanRuanganModel::factory()->for($ruangan, 'ruangan')->create([
            'status' => 0, // Menunggu
            'tanggal' => '2025-06-02',
            'waktu_mulai' => '10:00:00',
            'waktu_selesai' => '12:00:00',
        ]);
    
        $response = $this->get('/pengelolaan-informasi/peminjamanruangan/updateValidation/' . $peminjamanToApprove->peminjamanruangan_id);
    
        $response->assertRedirect('/pengelolaan-informasi/peminjamanruangan');
        $response->assertSessionHas('success_peminjamanruangan', 'Peminjaman ruangan berhasil disetujui dan peminjaman lain yang bentrok telah ditolak.');
        
        $this->assertDatabaseHas('t_peminjamanruangan', [
            'peminjamanruangan_id' => $peminjamanToApprove->peminjamanruangan_id,
            'status' => 1 // Disetujui
        ]);
        $this->assertDatabaseHas('t_peminjamanruangan', [
            'peminjamanruangan_id' => $peminjamanToReject->peminjamanruangan_id,
            'status' => 2, // Ditolak karena bentrok
            'alasan_penolakan' => 'Bentrok dengan peminjaman yang telah disetujui'
        ]);
        $this->assertDatabaseHas('t_peminjamanruangan', [
            'peminjamanruangan_id' => $peminjamanNotConflicting->peminjamanruangan_id,
            'status' => 0 // Tetap menunggu, tidak terpengaruh
        ]);
    }

    public function test_update_validation_cancel_approval()
    {
        $this->actingAs($this->adminUser);
        $ruangan = RuanganModel::first();
        $peminjaman = PeminjamanRuanganModel::factory()->for($ruangan, 'ruangan')->create(['status' => 1]); // Status Disetujui

        $response = $this->get('/pengelolaan-informasi/peminjamanruangan/updateValidation/' . $peminjaman->peminjamanruangan_id);

        $response->assertRedirect('/pengelolaan-informasi/peminjamanruangan');
        $response->assertSessionHas('success_peminjamanruangan', 'Persetujuan peminjaman ruangan telah dibatalkan.');
        $this->assertDatabaseHas('t_peminjamanruangan', [
            'peminjamanruangan_id' => $peminjaman->peminjamanruangan_id,
            'status' => 0 // Kembali ke Menunggu
        ]);
    }

    public function test_update_validation_not_found()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/pengelolaan-informasi/peminjamanruangan/updateValidation/99999'); // ID tidak ada

        $response->assertRedirect('/pengelolaan-informasi/peminjamanruangan');
        $response->assertSessionHas('error_peminjamanruangan', 'Data tidak ditemukan');
    }

    public function test_reject_peminjaman_success()
    {
        $this->actingAs($this->adminUser);
        $ruangan = RuanganModel::first();
        $peminjaman = PeminjamanRuanganModel::factory()->for($ruangan, 'ruangan')->create(['status' => 0]); // Status Menunggu

        $alasan = 'Ruangan tidak tersedia pada jam tersebut.';
        $response = $this->post('/pengelolaan-informasi/peminjamanruangan/rejectPeminjaman/' . $peminjaman->peminjamanruangan_id, [
            'alasan_penolakan' => $alasan
        ]);

        $response->assertRedirect('/pengelolaan-informasi/peminjamanruangan');
        $response->assertSessionHas('success_peminjamanruangan', 'Peminjaman ruangan berhasil ditolak dengan alasan: ' . $alasan);
        $this->assertDatabaseHas('t_peminjamanruangan', [
            'peminjamanruangan_id' => $peminjaman->peminjamanruangan_id,
            'status' => 2, // Ditolak
            'alasan_penolakan' => $alasan
        ]);
    }

    public function test_reject_peminjaman_not_found()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post('/pengelolaan-informasi/peminjamanruangan/rejectPeminjaman/99999', [
            'alasan_penolakan' => 'Alasan tes.'
        ]);

        $response->assertRedirect('/pengelolaan-informasi/peminjamanruangan');
        $response->assertSessionHas('error_peminjamanruangan', 'Data tidak ditemukan');
    }

    public function test_reject_peminjaman_validation_error()
    {
        $this->actingAs($this->adminUser);
        $ruangan = RuanganModel::first();
        $peminjaman = PeminjamanRuanganModel::factory()->for($ruangan, 'ruangan')->create(['status' => 0]);

        $response = $this->post('/pengelolaan-informasi/peminjamanruangan/rejectPeminjaman/' . $peminjaman->peminjamanruangan_id, [
            'alasan_penolakan' => '' // Alasan kosong
        ]);

        $response->assertStatus(302); // Redirect back
        $response->assertSessionHasErrors('alasan_penolakan');
    }
    
    public function test_destroy_success()
    {
        $this->actingAs($this->adminUser);
        $ruangan = RuanganModel::first();
        $peminjaman = PeminjamanRuanganModel::factory()->for($ruangan, 'ruangan')->create();

        $response = $this->delete('/pengelolaan-informasi/peminjamanruangan/' . $peminjaman->peminjamanruangan_id);

        $response->assertRedirect('/pengelolaan-informasi/peminjamanruangan');
        $response->assertSessionHas('success_peminjamanruangan', 'Data peminjaman ruangan berhasil dihapus');
        $this->assertDatabaseMissing('t_peminjamanruangan', ['peminjamanruangan_id' => $peminjaman->peminjamanruangan_id]);
    }

    public function test_destroy_not_found()
    {
        $this->actingAs($this->adminUser);

        $response = $this->delete('/pengelolaan-informasi/peminjamanruangan/99999'); // ID tidak ada

        $response->assertRedirect('/pengelolaan-informasi/peminjamanruangan');
        $response->assertSessionHas('error_peminjamanruangan', 'Data peminjaman ruangan tidak ditemukan');
    }
}
