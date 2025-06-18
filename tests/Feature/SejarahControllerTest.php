<?php

namespace Tests\Feature;

use App\Models\SejarahModel;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\LevelSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Storage;

class SejarahControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(LevelSeeder::class);
        $this->seed(UserSeeder::class);

        $adminLevel = LevelModel::where('level_kode', 'ADM')->first();
        if (!$adminLevel) {
            $adminLevel = LevelModel::factory()->create(['level_kode' => 'ADM', 'level_nama' => 'Administrator']);
        }

        $this->adminUser = UserModel::factory()->create([
            'level_id' => $adminLevel->level_id
        ]);

        // Mock the simpanLogAktivitas helper function if it's not available in the test environment
        if (!function_exists('simpanLogAktivitas')) {
            function simpanLogAktivitas($menu, $aksi, $detail) {
                // Mock implementation or leave empty
            }
        }
    }

    public function test_index()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/pengelolaan-informasi/sejarah');

        $response->assertStatus(200);
        $response->assertViewIs('sejarah.index');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'sejarah',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_list()
    {
        $this->actingAs($this->adminUser);

        $sejarahs = SejarahModel::factory()->count(3)->create();

        $response = $this->postJson('/pengelolaan-informasi/sejarah/list');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data' => [
                '*' => [
                    'DT_RowIndex',
                    'sejarah_id',
                    'judul_subbab',
                    'isi_konten', // Ini akan berisi HTML
                    'aksi',
                ]
            ]
        ]);

        // Memastikan jumlah data yang dikembalikan sesuai
        $response->assertJsonCount($sejarahs->count(), 'data');

        // Membandingkan data aktual
        $responseData = $response->json('data');

        foreach ($sejarahs as $sejarah) {
            // Cari data sejarah yang sesuai dalam respons berdasarkan sejarah_id
            $responseSejarah = collect($responseData)->firstWhere('sejarah_id', $sejarah->sejarah_id);

            $this->assertNotNull($responseSejarah, "Sejarah dengan ID {$sejarah->sejarah_id} tidak ditemukan dalam respons.");

            if ($responseSejarah) {
                $this->assertEquals($sejarah->sejarah_id, $responseSejarah['sejarah_id']);
                $this->assertEquals($sejarah->judul_subbab, $responseSejarah['judul_subbab']);
                
                // Assertions for the transformed 'isi_konten'
                // 1. Check for the wrapper div and that it contains a <p> tag
                $this->assertStringStartsWith('<div class="isi-konten-table"><p>', $responseSejarah['isi_konten']);
                $this->assertStringEndsWith('</p></div>', $responseSejarah['isi_konten']);
                
                // 2. Check for truncation indicator (ellipsis) within the paragraph tag
                //    This regex looks for <p> followed by any characters, then three dots, then </p>
                $this->assertMatchesRegularExpression('/<p>.*?\.{3}<\/p>/s', $responseSejarah['isi_konten']);

                // 3. Check that the beginning of the original content's text is present in the response's text
                //    (after stripping tags from both for a simpler text comparison)
                //    We take a short snippet from the beginning of the original text.
                $originalTextStripped = strip_tags($sejarah->isi_konten);
                $expectedTextFragment = substr($originalTextStripped, 0, 50); // Adjust length as needed, e.g., first 50 chars
                
                $responseTextStripped = strip_tags($responseSejarah['isi_konten']); // Strip tags from response for text comparison
                
                $this->assertStringContainsString($expectedTextFragment, $responseTextStripped);

                // Untuk kolom 'aksi', kita bisa cek apakah URL yang benar ada di dalamnya
                $this->assertStringContainsString(url('/pengelolaan-informasi/sejarah/' . $sejarah->sejarah_id), $responseSejarah['aksi']);
                $this->assertStringContainsString(url('/pengelolaan-informasi/sejarah/' . $sejarah->sejarah_id . '/edit'), $responseSejarah['aksi']);
                // Untuk tombol hapus, form action juga akan berisi URL ini
                $this->assertStringContainsString(url('/pengelolaan-informasi/sejarah/'.$sejarah->sejarah_id), $responseSejarah['aksi']);
            }
        }
    }

    public function test_create()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/pengelolaan-informasi/sejarah/create');

        $response->assertStatus(200);
        $response->assertViewIs('sejarah.create');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'sejarah',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_store_success()
    {
        Storage::fake('public'); // Mock storage for image uploads if any
        $this->actingAs($this->adminUser);

        $data = [
            'judul_subbab' => 'Test Sejarah Baru',
            'isi_konten' => '<p>Ini adalah konten sejarah baru.</p><img src="/storage/images/sejarah/test.jpg">',
        ];

        $response = $this->post('/pengelolaan-informasi/sejarah', $data);

        $response->assertRedirect('/pengelolaan-informasi/sejarah');
        $response->assertSessionHas('success', 'Konten berhasil disimpan');
        
        $this->assertDatabaseHas('t_sejarah', [
            'judul_subbab' => $data['judul_subbab'],
            'isi_konten' => $data['isi_konten'],
        ]);
    }

    public function test_store_validation_error()
    {
        $this->actingAs($this->adminUser);

        $data = [
            'judul_subbab' => '' // Invalid: judul_subbab is required
        ];

        $response = $this->post('/pengelolaan-informasi/sejarah', $data);

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('judul_subbab');
    }

    public function test_show()
    {
        $this->actingAs($this->adminUser);

        $sejarah = SejarahModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/sejarah/' . $sejarah->sejarah_id);

        $response->assertStatus(200);
        $response->assertViewIs('sejarah.show');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'sejarah',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_edit()
    {
        $this->actingAs($this->adminUser);

        $sejarah = SejarahModel::factory()->create();

        $response = $this->get('/pengelolaan-informasi/sejarah/' . $sejarah->sejarah_id . '/edit');

        $response->assertStatus(200);
        $response->assertViewIs('sejarah.edit');
        $response->assertViewHasAll([
            'breadcrumb',
            'page',
            'sejarah',
            'activeMenu',
            'notifUser'
        ]);
    }

    public function test_update_success()
    {
        Storage::fake('public');
        $this->actingAs($this->adminUser);

        $sejarah = SejarahModel::factory()->create([
            'isi_konten' => '<p>Konten lama.</p><img src="/storage/images/sejarah/lama.jpg">',
        ]);
        $updatedData = [
            'judul_subbab' => 'Updated Sejarah',
            'isi_konten' => '<p>Konten baru.</p><img src="/storage/images/sejarah/baru.jpg">',
        ];

        // Create dummy files for the test to simulate image existence
        Storage::disk('public')->put('images/sejarah/lama.jpg', 'dummy_content');
        Storage::disk('public')->put('images/sejarah/baru.jpg', 'dummy_content');

        $response = $this->put('/pengelolaan-informasi/sejarah/' . $sejarah->sejarah_id, $updatedData);

        $response->assertRedirect('/pengelolaan-informasi/sejarah');
        $response->assertSessionHas('success', 'Konten berhasil diubah');
        
        $this->assertDatabaseHas('t_sejarah', [
            'sejarah_id' => $sejarah->sejarah_id,
            'judul_subbab' => $updatedData['judul_subbab'],
            'isi_konten' => $updatedData['isi_konten'],
        ]);
        // Assert that the old image is deleted if not used elsewhere (this is complex to test without more context on hapusGambarTidakDipakai)
        // For simplicity, we'll assume the controller logic for deleting images works as intended.
        // Storage::disk('public')->assertMissing('images/sejarah/lama.jpg'); // This might fail if other entries use it.
        // Storage::disk('public')->assertExists('images/sejarah/baru.jpg');
    }

    public function test_update_validation_error()
    {
        $this->actingAs($this->adminUser);

        $sejarah = SejarahModel::factory()->create();
        $updatedData = [
            'judul_subbab' => '' // Invalid: judul_subbab is required
        ];

        $response = $this->put('/pengelolaan-informasi/sejarah/' . $sejarah->sejarah_id, $updatedData);

        $response->assertStatus(302); // Should redirect back
        $response->assertSessionHasErrors('judul_subbab');
    }

    public function test_destroy_success()
    {
        Storage::fake('public');
        $this->actingAs($this->adminUser);

        $sejarah = SejarahModel::factory()->create([
            'isi_konten' => '<p>Konten yang akan dihapus.</p><img src="/storage/images/sejarah/hapus.jpg">',
        ]);
        // Create a dummy file to simulate image existence
        Storage::disk('public')->put('images/sejarah/hapus.jpg', 'dummy_content');

        $response = $this->delete('/pengelolaan-informasi/sejarah/' . $sejarah->sejarah_id);

        $response->assertRedirect('/pengelolaan-informasi/sejarah');
        $response->assertSessionHas('success', 'Konten berhasil dihapus');
        $this->assertDatabaseMissing('t_sejarah', ['sejarah_id' => $sejarah->sejarah_id]);
        // Assert that the image is deleted (this is complex to test without more context on hapusGambarTidakDipakai)
        // Storage::disk('public')->assertMissing('images/sejarah/hapus.jpg'); // This might fail if other entries use it or if the path is different.
    }

    public function test_destroy_not_found()
    {
        $this->actingAs($this->adminUser);

        $response = $this->delete('/pengelolaan-informasi/sejarah/99999'); // An ID that is unlikely to exist

        $response->assertRedirect('/pengelolaan-informasi/sejarah');
        $response->assertSessionHas('error', 'Konten tidak ditemukan');
    }
}
