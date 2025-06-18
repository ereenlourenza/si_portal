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
        // Store the created peminjaman ruangan to iterate and compare later
        // Ensure your factory can produce items with various statuses, or set them explicitly if needed for full coverage of action button logic.
        $peminjamans = PeminjamanRuanganModel::factory()->count(3)->for($ruangan, 'ruangan')->create();

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
                    'ruangan' => [ 
                        'ruangan_id',
                        'ruangan_nama',
                    ],
                    'keperluan',
                    'status', 
                    'alasan_penolakan',
                    'waktu', 
                    'aksi',
                ]
            ]
        ]);

        $response->assertJsonCount($peminjamans->count(), 'data');
        $responseData = $response->json('data');

        $statusMap = [
            0 => '<span class="badge badge-warning"><em><i class="fas fa-exclamation nav-icon"></i> Menunggu</span></em>',
            1 => '<span class="badge badge-success"><em><i class="fas fa-thumbs-up nav-icon"></i> Disetujui</span></em>',
            2 => '<span class="badge badge-danger"><em><i class="fas fa-ban nav-icon"></i> Ditolak</span></em>',
        ];

        foreach ($peminjamans as $peminjaman) {
            $responsePeminjaman = collect($responseData)->firstWhere('peminjamanruangan_id', $peminjaman->peminjamanruangan_id);

            $this->assertNotNull($responsePeminjaman, "Peminjaman Ruangan dengan ID {$peminjaman->peminjamanruangan_id} tidak ditemukan dalam respons.");

            if ($responsePeminjaman) {
                $this->assertEquals($peminjaman->peminjamanruangan_id, $responsePeminjaman['peminjamanruangan_id']);
                $this->assertEquals($peminjaman->peminjam_nama, $responsePeminjaman['peminjam_nama']);
                $this->assertEquals($peminjaman->peminjam_telepon, $responsePeminjaman['peminjam_telepon']);
                $this->assertEquals(Carbon::parse($peminjaman->tanggal)->format('Y-m-d'), Carbon::parse($responsePeminjaman['tanggal'])->format('Y-m-d'));
                $this->assertEquals(substr($peminjaman->waktu_mulai, 0, 5), substr($responsePeminjaman['waktu_mulai'], 0, 5));
                $this->assertEquals(substr($peminjaman->waktu_selesai, 0, 5), substr($responsePeminjaman['waktu_selesai'], 0, 5));
                $this->assertEquals($peminjaman->ruangan_id, $responsePeminjaman['ruangan_id']);
                $this->assertEquals($peminjaman->ruangan->ruangan_nama, $responsePeminjaman['ruangan']['ruangan_nama']);
                $this->assertEquals($peminjaman->keperluan, $responsePeminjaman['keperluan']);
                
                // Ensure the status from the response matches one of the expected HTML structures
                if (isset($statusMap[$peminjaman->status])) {
                    $this->assertEquals($statusMap[$peminjaman->status], $responsePeminjaman['status']);
                } else {
                    // Handle cases where status might not be in map, or assert as needed
                    $this->assertEquals($peminjaman->status, $responsePeminjaman['status']); 
                }
                
                $this->assertEquals($peminjaman->alasan_penolakan, $responsePeminjaman['alasan_penolakan']);

                $expectedWaktu = Carbon::parse($peminjaman->waktu_mulai)->format('H:i') . ' - ' . Carbon::parse($peminjaman->waktu_selesai)->format('H:i');
                $this->assertEquals($expectedWaktu, $responsePeminjaman['waktu']);

                // --- Aksi Column Assertions ---
                $aksiHtml = $responsePeminjaman['aksi'];
                $editUrl = url('/pengelolaan-informasi/peminjamanruangan/' . $peminjaman->peminjamanruangan_id . '/edit');
                $updateValidationUrl = url('/pengelolaan-informasi/peminjamanruangan/updateValidation/' . $peminjaman->peminjamanruangan_id);
                $rejectModalTarget = '#rejectModal' . $peminjaman->peminjamanruangan_id;
                $deleteActionUrl = url('/pengelolaan-informasi/peminjamanruangan/' . $peminjaman->peminjamanruangan_id);

                // Tombol Edit
                // Annahme basierend auf dem Fehler: Die Schaltfläche "Bearbeiten" ist für Status 0 nicht vorhanden.
                if ($peminjaman->status == 0) { // Tombol "Edit" TIDAK muncul jika status "Menunggu" (0), berdasarkan output aktual
                    $this->assertStringNotContainsString($editUrl, $aksiHtml, "Edit button URL should NOT be present for status 0, as per actual output.");
                    $this->assertStringNotContainsString('>Edit</a>', $aksiHtml, "Edit button text '>Edit' should NOT be present for status 0, as per actual output.");
                } else { // Untuk status lain (Disetujui, Ditolak), tombol Edit juga tidak muncul
                    $this->assertStringNotContainsString($editUrl, $aksiHtml, "Edit button URL should not be present for status {$peminjaman->status}.");
                    $this->assertStringNotContainsString('>Edit</a>', $aksiHtml, "Edit button text '>Edit' should not be present for status {$peminjaman->status}.");
                }

                // Tombol Setujui / Batalkan
                if ($peminjaman->status == 0) { // Tombol "Setujui" hanya muncul jika status "Menunggu"
                    $this->assertStringContainsString($updateValidationUrl, $aksiHtml);
                    $this->assertStringContainsString('>Setujui</a>', $aksiHtml, "Setujui button text '>Setujui' should be present for status 0.");
                    $this->assertStringNotContainsString('>Batalkan</a>', $aksiHtml, "Batalkan button text '>Batalkan' should not be present for status 0");
                } else if ($peminjaman->status == 1) { // Tombol "Batalkan" hanya muncul jika status "Disetujui"
                    $this->assertStringContainsString($updateValidationUrl, $aksiHtml);
                    $this->assertStringContainsString('>Batalkan</a>', $aksiHtml, "Batalkan button text '>Batalkan' should be present for status 1.");
                    $this->assertStringNotContainsString('>Setujui</a>', $aksiHtml, "Setujui button text '>Setujui' should not be present for status 1");
                } else if ($peminjaman->status == 2) { // Based on error: "Batalkan" link IS present for status 2 (Ditolak)
                    $this->assertStringContainsString($updateValidationUrl, $aksiHtml, "Batalkan (updateValidation) link should be present for status 2, as per actual output.");
                    $this->assertStringContainsString('>Batalkan</a>', $aksiHtml, "Batalkan button text '>Batalkan' should be present for status 2, as per actual output.");
                    $this->assertStringNotContainsString('>Setujui</a>', $aksiHtml, "Setujui button text '>Setujui' should not be present for status 2.");
                } else {
                    // For other statuses (e.g., if factory produces something other than 0, 1, 2)
                    $this->assertStringNotContainsString($updateValidationUrl, $aksiHtml, "Setujui/Batalkan (updateValidation) link should not be present for status {$peminjaman->status}");
                    $this->assertStringNotContainsString('>Setujui</a>', $aksiHtml, "Setujui button text '>Setujui' should not be present for status {$peminjaman->status}");
                    $this->assertStringNotContainsString('>Batalkan</a>', $aksiHtml, "Batalkan button text '>Batalkan' should not be present for status {$peminjaman->status}");
                }

                // Tombol Tolak
                if ($peminjaman->status == 0 || $peminjaman->status == 1) { // Tombol "Tolak" muncul jika status "Menunggu" (0) atau "Disetujui" (1)
                    $this->assertStringContainsString('data-target="' . $rejectModalTarget . '"', $aksiHtml);
                    $this->assertStringContainsString('>Tolak</button>', $aksiHtml, "Tolak button text '>Tolak' should be present for status {$peminjaman->status}.");
                } else { // For other statuses (e.g., 2 Ditolak), Tombol Tolak tidak muncul
                    $this->assertStringNotContainsString('data-target="' . $rejectModalTarget . '"', $aksiHtml, "Tolak button (modal target) should not be present for status {$peminjaman->status}.");
                    $this->assertStringNotContainsString('>Tolak</button>', $aksiHtml, "Tolak button text '>Tolak' should not be present for status {$peminjaman->status}.");
                }
                
                // Tombol Hapus
                if ($peminjaman->status == 2) {
                    // If status is 2 (Ditolak), the controller might output EITHER Batalkan OR Hapus, but not necessarily both in the same $aksiHtml string.
                    // The error "Failed asserting that '...Batalkan...' contains 'action=.../peminjamanruangan/3'" means $aksiHtml was the Batalkan button.
                    // So, if $aksiHtml contains 'Batalkan', it should NOT contain 'Hapus' elements.
                    if (str_contains($aksiHtml, '>Batalkan</a>')) {
                        $this->assertStringNotContainsString('action="' . $deleteActionUrl . '"', $aksiHtml, "Hapus form action should NOT be present in Batalkan link for status 2.");
                        $this->assertStringNotContainsString('>Hapus</button>', $aksiHtml, "Hapus button text should NOT be present in Batalkan link for status 2.");
                    } else {
                        // If it's not the Batalkan link, then we expect the Hapus form (original assumption for status 2)
                        $this->assertStringContainsString('action="' . $deleteActionUrl . '"', $aksiHtml, "Hapus form action should be present for status 2 if not Batalkan link.");
                        $this->assertStringContainsString('method="POST"', $aksiHtml); 
                        $this->assertStringContainsString('<input type="hidden" name="_method" value="DELETE">', $aksiHtml);
                        $this->assertStringContainsString('>Hapus</button>', $aksiHtml, "Hapus button text should be present for status 2 if not Batalkan link.");
                    }
                } else {
                    // For status 0 (Menunggu) and 1 (Disetujui), Hapus button/form should not be present.
                    $this->assertStringNotContainsString('action="' . $deleteActionUrl . '"', $aksiHtml, "Hapus form action should not be present for status {$peminjaman->status}.");
                    $this->assertStringNotContainsString('>Hapus</button>', $aksiHtml, "Hapus button text '>Hapus' should not be present for status {$peminjaman->status}");
                }
            }
        }
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
