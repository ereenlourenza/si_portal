<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaAcaraIbadahController;
use App\Http\Controllers\BeritaAcaraPersembahan;
use App\Http\Controllers\BeritaAcaraWizardController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IbadahController;
use App\Http\Controllers\IbadahMingguController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JemaatController;
use App\Http\Controllers\KategoriGaleriController;
use App\Http\Controllers\KategoriIbadahController;
use App\Http\Controllers\KategoriPelayanController;
use App\Http\Controllers\KategoriPersembahan;
use App\Http\Controllers\KategoriPersembahanController;
use App\Http\Controllers\KomisiController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\PelayanController;
use App\Http\Controllers\PelkatController;
use App\Http\Controllers\PelkatPengurusController;
use App\Http\Controllers\PeminjamanRuanganController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PendaftaranSakramenController;
use App\Http\Controllers\PersembahanController;
use App\Http\Controllers\PHMJController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\SejarahController;
use App\Http\Controllers\SektorController;
use App\Http\Controllers\TataIbadahController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WartaJemaatController;
use App\Http\Controllers\WelcomeController;
use App\Models\BeritaAcaraIbadahModel;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// =================
// INFORMASI PUBLIK
// =================

Route::get('/', [HomeController::class, 'index']);
Route::get('/beranda', [HomeController::class, 'index'])->name('beranda');

Route::prefix('profil')->group(function () {
    Route::prefix('gpib')->group(function () {
        Route::get('/tentang-gpib', function () {return view('global.tentang-gpib');})->name('tentang-gpib');
        Route::get('/visi-misi', function () {return view('global.visi-misi');})->name('visi-misi');
        Route::get('/pemahaman-iman', function () {return view('global.pemahaman-iman');})->name('pemahaman-iman');
        Route::get('/simbol-tahun-gereja', function () { return view('global.simbol-tahun-gereja');})->name('simbol-tahun-gereja');
    });
    Route::prefix('immanuel-malang')->group(function () {
        Route::get('/sejarah-gereja', [HomeController::class, 'sejarah'])->name('sejarah-gereja');
        Route::get('/wilayah-pelayanan', [HomeController::class, 'sektor'])->name('wilayah-pelayanan');
    });
});

Route::prefix('pelayanan')->group(function () {
    Route::prefix('kegiatan-ibadah')->group(function () {
        Route::get('/ibadah-rutin', [HomeController::class, 'ibadah'])->name('ibadah-rutin');
        Route::get('/persembahan', [HomeController::class, 'persembahan'])->name('persembahan');
        Route::get('/kanal-youtube', [HomeController::class, 'showLatestVideo'])->name('kanal-youtube');
    });
    Route::prefix('pelayanan-jemaat')->group(function () {
        Route::prefix('baptisan')->group(function () {
            Route::get('/', function () { return view('global.baptisan');})->name('baptisan');
            Route::get('/form', [HomeController::class, 'baptisCreate'])->name('baptis.create');
            Route::post('/', [HomeController::class, 'baptisStore'])->name('baptis.store');
            Route::get('/status', [HomeController::class, 'baptisStatus'])->name('baptis.status');
        });
        Route::prefix('katekisasi')->group(function () {
            Route::get('/', function () { return view('global.katekisasi');})->name('katekisasi');
            Route::get('/form', [HomeController::class, 'katekisasiCreate'])->name('katekisasi.create');
            Route::post('/', [HomeController::class, 'katekisasiStore'])->name('katekisasi.store');
            Route::get('/status', [HomeController::class, 'katekisasiStatus'])->name('katekisasi.status');
        });
        Route::prefix('pemberkatan-nikah')->group(function () {
            Route::get('/', function () { return view('global.pernikahan');})->name('pemberkatan-nikah');
            Route::get('/form', [HomeController::class, 'pernikahanCreate'])->name('pemberkatan-nikah.create');
            Route::post('/', [HomeController::class, 'pernikahanStore'])->name('pemberkatan-nikah.store');
            Route::get('/status', [HomeController::class, 'pernikahanStatus'])->name('pemberkatan-nikah.status');
        });
        Route::prefix('peminjaman-ruangan')->group(function () {
            Route::get('/', function () { return view('global.ruangan');})->name('peminjaman-ruangan');
            Route::get('/form', [HomeController::class, 'ruanganCreate'])->name('peminjaman-ruangan.create');
            Route::post('/', [HomeController::class, 'ruanganStore'])->name('peminjaman-ruangan.store');
            Route::get('/status', [HomeController::class, 'ruanganStatus'])->name('peminjaman-ruangan.status');
        });
    });
});

Route::prefix('fungsionaris')->group(function () {
    Route::prefix('fungsionaris')->group(function () {
        Route::get('/pendeta-kmj', [HomeController::class, 'pendeta'])->name('pendeta-kmj');
        Route::get('/vikaris', [HomeController::class, 'vikaris'])->name('vikaris');
        Route::get('/phmj', [HomeController::class, 'phmj'])->name('phmj');
        Route::get('/majelis-jemaat', [HomeController::class, 'majelisJemaat'])->name('majelis-jemaat');
    });

    Route::prefix('pelkat')->group(function () {
        Route::get('/pa', [HomeController::class, 'pelkatpa'])->name('pa');
        Route::get('/pt', [HomeController::class, 'pelkatpt'])->name('pt');
        Route::get('/gp', [HomeController::class, 'pelkatgp'])->name('gp');
        Route::get('/pkp', [HomeController::class, 'pelkatpkp'])->name('pkp');
        Route::get('/pkb', [HomeController::class, 'pelkatpkb'])->name('pkb');
        Route::get('/pklu', [HomeController::class, 'pelkatpklu'])->name('pklu');
    });

    Route::prefix('komisi')->group(function () {
        Route::get('/teologi', [HomeController::class, 'komisiteologi'])->name('teologi');
        Route::get('/pelkes', [HomeController::class, 'komisipelkes'])->name('pelkes');
        Route::get('/peg', [HomeController::class, 'komisipeg'])->name('peg');
        Route::get('/germasa', [HomeController::class, 'komisigermasa'])->name('germasa');
        Route::get('/ppsdi-ppk', [HomeController::class, 'komisippsdi'])->name('ppsdi-ppk');
        Route::get('/inforkom-litbang', [HomeController::class, 'komisiinforkomlitbang'])->name('inforkom-litbang');
    });

    Route::prefix('lain-lain')->group(function () {
        Route::get('/bppj', [HomeController::class, 'bppj'])->name('bppj');
        Route::get('/kantor-sekretariat', [HomeController::class, 'kantor'])->name('kantor-sekretariat');
    });
});

Route::prefix('dokumen')->group(function () {
    Route::get('/tata-ibadah', [HomeController::class, 'tataibadah'])->name('tata-ibadah');
    Route::get('/warta-jemaat', [HomeController::class, 'wartajemaat'])->name('warta-jemaat');
});

Route::get('/galeri', [HomeController::class, 'galeri'])->name('galeri');
Route::get('/galeri/kategori/{id}', [HomeController::class, 'galeriByKategori'])->name('galeri-kategori');
Route::get('/kontak', function () { return view('global.kontak');})->name('kontak');

if (app()->environment('testing')) {
    Route::get('/log-aktivitas-test', function () {
        simpanLogAktivitas('ModulTest', 'AksiTest', 'AktivitasTest');
        return response()->json(['message' => 'OK']);
    });
};

// =================
// ADMIN
// =================
    
Route::get('/login', [AuthController::class, 'index'])->name('login.index');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [WelcomeController::class, 'index'])->name('beranda.index');
    // Route::get('/portal', [WelcomeController::class, 'index'])->name('portal.index');

    // Modul Pengelolaan Pengguna - Hanya Super Admin
    Route::middleware(['checklevel:SAD'])->prefix('pengelolaan-pengguna')->group(function () {

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('user.index');
            Route::post('/list', [UserController::class, 'list']);
            Route::get('/create', [UserController::class, 'create']);
            Route::post('/', [UserController::class, 'store']);
            Route::get('/{id}', [UserController::class, 'show']);
            Route::get('/{id}/edit', [UserController::class, 'edit']);
            Route::put('/{id}', [UserController::class, 'update']);
            Route::delete('/{id}', [UserController::class, 'destroy']);
        });

        Route::prefix('level')->group(function () {
            Route::get('/', [LevelController::class, 'index'])->name('level.index');
            Route::post('/list', [LevelController::class, 'list']);
            Route::get('/create', [LevelController::class, 'create']);
            Route::post('/', [LevelController::class, 'store']);
            Route::get('/{id}', [LevelController::class, 'show']);
            Route::get('/{id}/edit', [LevelController::class, 'edit']);
            Route::put('/{id}', [LevelController::class, 'update']);
            Route::delete('/{id}', [LevelController::class, 'destroy']);
        });

        Route::prefix('log')->group(function () {
            Route::get('/', [LogAktivitasController::class, 'index'])->name('log.index');
        });
        

    });

    // Modul Pengelolaan Informasi - Hanya Admin
    Route::middleware(['checklevel:ADM'])->prefix('pengelolaan-informasi')->group(function () {
        
        Route::prefix('tataibadah')->group(function () {
            Route::get('/', [DokumenController::class, 'index'])->name('dokumen.index');
            Route::post('/list', [TataIbadahController::class, 'list']);
            Route::get('/create', [TataIbadahController::class, 'create']);
            Route::post('/', [TataIbadahController::class, 'store']);
            Route::get('/{id}', [TataIbadahController::class, 'show']);
            Route::get('/{id}/edit', [TataIbadahController::class, 'edit']);
            Route::put('/{id}', [TataIbadahController::class, 'update']);
            Route::delete('/{id}', [TataIbadahController::class, 'destroy']);
        });
        
        Route::prefix('wartajemaat')->group(function () {
            Route::get('/', [DokumenController::class, 'index'])->name('dokumen.index');
            Route::post('/list', [WartaJemaatController::class, 'list']);
            Route::get('/create', [WartaJemaatController::class, 'create']);
            Route::post('/', [WartaJemaatController::class, 'store']);
            Route::get('/{id}', [WartaJemaatController::class, 'show']);
            Route::get('/{id}/edit', [WartaJemaatController::class, 'edit']);
            Route::put('/{id}', [WartaJemaatController::class, 'update']);
            Route::delete('/{id}', [WartaJemaatController::class, 'destroy']);
        });

        Route::prefix('kategoripelayan')->group(function () {
            Route::get('/', [KategoriPelayanController::class, 'index'])->name('kategoripelayan.index');
            Route::post('/list', [KategoriPelayanController::class, 'list']);
            Route::get('/create', [KategoriPelayanController::class, 'create']);
            Route::post('/', [KategoriPelayanController::class, 'store']);
            Route::get('/{id}', [KategoriPelayanController::class, 'show']);
            Route::get('/{id}/edit', [KategoriPelayanController::class, 'edit']);
            Route::put('/{id}', [KategoriPelayanController::class, 'update']);
            Route::delete('/{id}', [KategoriPelayanController::class, 'destroy']);
        });

        Route::prefix('pelayan')->group(function () {
            Route::get('/', [PelayanController::class, 'index'])->name('pelayan.index');
            Route::post('/list', [PelayanController::class, 'list']);
            Route::get('/create', [PelayanController::class, 'create']);
            Route::post('/', [PelayanController::class, 'store']);
            Route::get('/{id}', [PelayanController::class, 'show']);
            Route::get('/{id}/edit', [PelayanController::class, 'edit']);
            Route::put('/{id}', [PelayanController::class, 'update']);
            Route::delete('/{id}', [PelayanController::class, 'destroy']);
        });

        Route::prefix('phmj')->group(function () {
            Route::get('/', [PelayanController::class, 'index'])->name('phmj.index');
            Route::post('/list', [PelayanController::class, 'list']);
            Route::get('/create', [PHMJController::class, 'create']);
            Route::post('/', [PHMJController::class, 'store']);
            Route::get('/{id}', [PHMJController::class, 'show']);
            Route::get('/{id}/edit', [PHMJController::class, 'edit']);
            Route::put('/{id}', [PHMJController::class, 'update']);
            Route::delete('/{id}', [PHMJController::class, 'destroy']);
        });

        Route::prefix('kategoriibadah')->group(function () {
            Route::get('/', [KategoriIbadahController::class, 'index'])->name('kategoriibadah.index');
            Route::post('/list', [KategoriIbadahController::class, 'list']);
            Route::get('/create', [KategoriIbadahController::class, 'create']);
            Route::post('/', [KategoriIbadahController::class, 'store']);
            Route::get('/{id}', [KategoriIbadahController::class, 'show']);
            Route::get('/{id}/edit', [KategoriIbadahController::class, 'edit']);
            Route::put('/{id}', [KategoriIbadahController::class, 'update']);
            Route::delete('/{id}', [KategoriIbadahController::class, 'destroy']);
        });

        Route::prefix('ibadah')->group(function () {
            Route::get('/', [IbadahController::class, 'index'])->name('ibadah.index');
            Route::post('/list', [IbadahController::class, 'list']);
            Route::get('/create', [IbadahController::class, 'create']);
            Route::post('/', [IbadahController::class, 'store']);
            Route::get('/{id}', [IbadahController::class, 'show']);
            Route::get('/{id}/edit', [IbadahController::class, 'edit']);
            Route::put('/{id}', [IbadahController::class, 'update']);
            Route::delete('/{id}', [IbadahController::class, 'destroy']);
        });
        
        Route::prefix('kategorigaleri')->group(function () {
            Route::get('/', [KategoriGaleriController::class, 'index'])->name('kategorigaleri.index');
            Route::post('/list', [KategoriGaleriController::class, 'list']);
            Route::get('/create', [KategoriGaleriController::class, 'create']);
            Route::post('/', [KategoriGaleriController::class, 'store']);
            Route::get('/{id}', [KategoriGaleriController::class, 'show']);
            Route::get('/{id}/edit', [KategoriGaleriController::class, 'edit']);
            Route::put('/{id}', [KategoriGaleriController::class, 'update']);
            Route::delete('/{id}', [KategoriGaleriController::class, 'destroy']);
        });
        
        Route::prefix('galeri')->group(function () {
            Route::get('/', [GaleriController::class, 'index'])->name('galeri.index');
            Route::post('/list', [GaleriController::class, 'list']);
            Route::get('/create', [GaleriController::class, 'create']);
            Route::post('/', [GaleriController::class, 'store']);
            Route::get('/{id}', [GaleriController::class, 'show']);
            Route::get('/{id}/edit', [GaleriController::class, 'edit']);
            Route::put('/{id}', [GaleriController::class, 'update']);
            Route::delete('/{id}', [GaleriController::class, 'destroy']);
        });
        
        Route::prefix('sektor')->group(function () {
            Route::get('/', [SektorController::class, 'index'])->name('sektor.index');
            Route::post('/list', [SektorController::class, 'list']);
            Route::get('/create', [SektorController::class, 'create']);
            Route::post('/', [SektorController::class, 'store']);
            Route::get('/{id}', [SektorController::class, 'show']);
            Route::get('/{id}/edit', [SektorController::class, 'edit']);
            Route::put('/{id}', [SektorController::class, 'update']);
            Route::delete('/{id}', [SektorController::class, 'destroy']);
        });
        
        Route::prefix('sejarah')->group(function () {
            Route::get('/', [SejarahController::class, 'index'])->name('sejarah.index');
            Route::post('/list', [SejarahController::class, 'list']);
            Route::get('/create', [SejarahController::class, 'create']);
            Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');
            Route::post('/', [SejarahController::class, 'store']);
            Route::get('/{id}', [SejarahController::class, 'show']);
            Route::get('/{id}/edit', [SejarahController::class, 'edit']);
            Route::put('/{id}', [SejarahController::class, 'update']);
            Route::delete('/{id}', [SejarahController::class, 'destroy']);
        });
        
        Route::prefix('pelkat')->group(function () {
            Route::get('/', [PelkatController::class, 'index'])->name('pelkat.index');
            Route::post('/list', [PelkatController::class, 'list']);
            Route::get('/create', [PelkatController::class, 'create']);
            Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor-pa.upload');
            Route::post('/', [PelkatController::class, 'store']);
            Route::get('/{id}', [PelkatController::class, 'show']);
            Route::get('/{id}/edit', [PelkatController::class, 'edit']);
            Route::put('/{id}', [PelkatController::class, 'update']);
            Route::delete('/{id}', [PelkatController::class, 'destroy']);
        });
        
        Route::prefix('komisi')->group(function () {
            Route::get('/', [KomisiController::class, 'index'])->name('komisi.index');
            Route::post('/list', [KomisiController::class, 'list']);
            Route::get('/create', [KomisiController::class, 'create']);
            Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor-komisi.upload');
            Route::post('/', [KomisiController::class, 'store']);
            Route::get('/{id}', [KomisiController::class, 'show']);
            Route::get('/{id}/edit', [KomisiController::class, 'edit']);
            Route::put('/{id}', [KomisiController::class, 'update']);
            Route::delete('/{id}', [KomisiController::class, 'destroy']);
        });
        
        Route::prefix('persembahan')->group(function () {
            Route::get('/', [PersembahanController::class, 'index'])->name('persembahan.index');
            Route::post('/list', [PersembahanController::class, 'list']);
            Route::get('/create', [PersembahanController::class, 'create']);
            Route::post('/', [PersembahanController::class, 'store']);
            Route::get('/{id}', [PersembahanController::class, 'show']);
            Route::get('/{id}/edit', [PersembahanController::class, 'edit']);
            Route::put('/{id}', [PersembahanController::class, 'update']);
            Route::delete('/{id}', [PersembahanController::class, 'destroy']);
        });
        
        Route::prefix('ruangan')->group(function () {
            Route::get('/', [RuanganController::class, 'index'])->name('ruangan.index');
            Route::post('/list', [RuanganController::class, 'list']);
            Route::get('/create', [RuanganController::class, 'create']);
            Route::post('/', [RuanganController::class, 'store']);
            Route::get('/{id}', [RuanganController::class, 'show']);
            Route::get('/{id}/edit', [RuanganController::class, 'edit']);
            Route::put('/{id}', [RuanganController::class, 'update']);
            Route::delete('/{id}', [RuanganController::class, 'destroy']);
        });
        
        Route::prefix('peminjamanruangan')->group(function () {
            Route::get('/', [PeminjamanRuanganController::class, 'index'])->name('peminjamanruangan.index');
            Route::post('/list', [PeminjamanRuanganController::class, 'list']);
            Route::get('/cetak-laporan', [PeminjamanRuanganController::class, 'cetakLaporan'])->name('peminjamanruangan.cetakLaporan'); // Moved and ensured this is before /{id}
            Route::get('/create', [PeminjamanRuanganController::class, 'create']);
            Route::post('/', [PeminjamanRuanganController::class, 'store']);
            Route::get('/updateValidation/{id}', [PeminjamanRuanganController::class, 'updateValidation']);
            Route::match(['get', 'post'],'/rejectPeminjaman/{id}', [PeminjamanRuanganController::class, 'rejectPeminjaman']);
            Route::get('/{id}', [PeminjamanRuanganController::class, 'show']);
            Route::get('/{id}/edit', [PeminjamanRuanganController::class, 'edit']);
            Route::put('/{id}', [PeminjamanRuanganController::class, 'update']);
            Route::delete('/{id}', [PeminjamanRuanganController::class, 'destroy']);
        });        

        Route::prefix('pendaftaran')->group(function () {
            Route::get('/', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
            Route::post('/list', [PendaftaranController::class, 'list']);
            Route::get('/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');
            Route::post('/', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
            Route::get('/updateValidation/{id}', [PendaftaranController::class, 'updateValidation']);
            Route::match(['get', 'post'],'/rejectPendaftaran/{id}', [PendaftaranController::class, 'rejectPendaftaran']);
            Route::get('/{id}', [PendaftaranController::class, 'show']);
            Route::get('/{id}/export-pdf', [PendaftaranController::class, 'exportPdf'])->name('berita-acara.exportPdf');
            Route::get('/{id}/edit', [PendaftaranController::class, 'edit']);
            Route::put('/{id}', [PendaftaranController::class, 'update'])->name('pendaftaran.update');
            Route::delete('/{id}', [PendaftaranController::class, 'destroy']);
        });

    });

    Route::middleware(['checklevel:MLJ,ADM,PHM'])->prefix('pengelolaan-berita-acara')->group(function () {

        Route::prefix('berita-acara')->group(function () {
            
            Route::get('/', [BeritaAcaraIbadahController::class, 'index'])->name('berita-acara.index');
            Route::post('/list', [BeritaAcaraIbadahController::class, 'list']);
            Route::get('/export-pdf-all', [BeritaAcaraIbadahController::class, 'exportPdfAll'])->name('berita-acara.exportPdfAll');
            Route::get('/create', [BeritaAcaraIbadahController::class, 'create']);
            Route::post('/', [BeritaAcaraIbadahController::class, 'store']);
            Route::get('/{id}/edit', [BeritaAcaraIbadahController::class, 'edit']);
            Route::put('/{id}', [BeritaAcaraIbadahController::class, 'update']);
            Route::get('/{id}', [BeritaAcaraIbadahController::class, 'show']);
            Route::get('/{id}/export-pdf', [BeritaAcaraIbadahController::class, 'exportPdf'])->name('berita-acara.exportPdf');
            Route::get('/export-persembahan/{id}', [BeritaAcaraIbadahController::class, 'exportPersembahan'])->name('berita-acara.exportPersembahan');
            Route::delete('/{id}', [BeritaAcaraIbadahController::class, 'destroy']);
        });
        
        Route::prefix('kategoripersembahan')->group(function () {
            Route::get('/', [KategoriPersembahanController::class, 'index'])->name('kategoripersembahan.index');
            Route::post('/list', [KategoriPersembahanController::class, 'list']);
            Route::get('/create', [KategoriPersembahanController::class, 'create']);
            Route::post('/', [KategoriPersembahanController::class, 'store']);
            Route::get('/{id}', [KategoriPersembahanController::class, 'show']);
            Route::get('/{id}/edit', [KategoriPersembahanController::class, 'edit']);
            Route::put('/{id}', [KategoriPersembahanController::class, 'update']);
            Route::delete('/{id}', [KategoriPersembahanController::class, 'destroy']);
        });
    
    });
    
});