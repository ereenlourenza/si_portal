@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($berita)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('pengelolaan-berita-acara/berita-acara') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('pengelolaan-berita-acara/berita-acara/'.$berita->berita_acara_ibadah_id) }}" class="form-horizontal" enctype='multipart/form-data'>
                    @csrf {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh method PUT -->
                    {{-- STEP 1 --}}
                    <div class="step" id="step-1">
                        <h5>Step 1: Informasi Ibadah</h5>
                        
                        <!-- Pilih Ibadah -->
                        <div class="form-group">
                            <label for="ibadah_id">Ibadah</label>
                            <select name="ibadah_id" id="ibadah_id" class="form-control" required>
                                <option value="">-- Pilih Ibadah --</option>
                                @foreach($ibadah as $item)
                                    <option value="{{ $item->ibadah_id }}"
                                        data-pelayan-firman="{{ $item->pelayan_firman }}"
                                        {{ old('ibadah_id', $berita->ibadah_id) == $item->ibadah_id ? 'selected' : '' }}>
                                        {{ $item->tempat }} ({{ $item->tanggal }} pk.{{ \Carbon\Carbon::parse($item->waktu)->format('H:i') }})
                                    </option>
                                @endforeach
                            </select>

                            @error('ibadah_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Bacaan Alkitab -->
                        <div class="form-group">
                            <label for="bacaan_alkitab">Bacaan Alkitab</label>
                            <input type="text" name="bacaan_alkitab" class="form-control" value="{{ old('bacaan_alkitab', $berita->bacaan_alkitab) }}" required>

                            @error('bacaan_alkitab')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Pelayan Firman -->
                        <div class="form-group">
                            <label for="pelayan_firman">Pelayan Firman</label>
                            <input type="text" class="form-control" id="pelayan_firman" name="pelayan_firman" value="{{ old('pelayan_firman', $berita->pelayan_firman) }}" readonly 
                                value="" readonly>
                        </div>

                        <div class="form-group">
                            <label>Petugas Ibadah</label>
                            <div id="petugas-wrapper">
                                @foreach ($berita->petugas as $index => $petugas)
                                    <div class="form-row mb-2 align-items-center">
                                        <div class="col-3">
                                            <select name="petugas[{{ $index }}][peran]" class="form-control" required>
                                                <option value="">-- Pilih Peran --</option>
                                                @foreach ([
                                                    'Pelayan 1', 'Pelayan 2', 'Pelayan 3', 'Pelayan 4', 'Pelayan 5',
                                                    'Pelayan 6', 'Pelayan 7', 'Pelayan 8', 'Pelayan 9', 'Kolektan',
                                                    'Pemandu Lagu', 'Kantoria', 'Paduan Suara/VG', 'Organis/Pianis/Keyboardis',
                                                    'Operator LCD', 'Operator Sound', 'Operator CCTV'
                                                ] as $peran)
                                                    <option value="{{ $peran }}" {{ old("petugas[$index][peran]", $petugas->peran) == $peran ? 'selected' : '' }}>
                                                        {{ $peran }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <select name="petugas[{{ $index }}][pelayan_id_jadwal]" class="form-control select2" required>
                                                <option value="">-- Jadwal Petugas --</option>
                                                @foreach ($pelayan as $p)
                                                    <option value="{{ $p->pelayan_id }}" {{ old("petugas[$index][pelayan_id_jadwal]", $petugas->pelayan_id_jadwal) == $p->pelayan_id ? 'selected' : '' }}>
                                                        {{ $p->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <select name="petugas[{{ $index }}][pelayan_id_hadir]" class="form-control select2">
                                                <option value="">-- Petugas Hadir --</option>
                                                @foreach ($pelayan as $p)
                                                    <option value="{{ $p->pelayan_id }}" {{ old("petugas[$index][pelayan_id_hadir]", $petugas->pelayan_id_hadir) == $p->pelayan_id ? 'selected' : '' }}>
                                                        {{ $p->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-1 d-flex justify-content-center align-items-center mb-2">
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removePetugas(this)">✕</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-sm btn-success" onclick="addPetugas()">+ Tambah Petugas</button>
                            </div>
                        </div>

                        <!-- Jumlah Kehadiran -->
                        <div class="form-group">
                            <label for="jumlah_kehadiran">Jumlah Kehadiran Jemaat</label>
                            <div class="input-group">
                                <input type="number" name="jumlah_kehadiran" class="form-control" value="{{ old('jumlah_kehadiran', $berita->jumlah_kehadiran) }}" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Orang</span>
                                </div>

                                @error('jumlah_kehadiran')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        
                        {{-- <div class="form-group row align-items-center">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" onclick="nextStep()">Lanjut</button>
                                <a class="btn btn-default ml-1" href="{{ url('pengelolaan-berita-acara/berita-acara') }}">Kembali</a>
                            </div>
                        </div> --}}
                        <a type="button" class="btn btn-default mt-2" href="{{ url('pengelolaan-berita-acara/berita-acara') }}">Kembali</a>
                        <button type="button" class="btn btn-info" onclick="nextStep()">Lanjut</button>

                    </div>

                    {{-- STEP 2 --}}
                    <div class="step d-none" id="step-2">
                        <h5>Step 2: Input Persembahan</h5>
                        <div id="form-persembahan">
                            @foreach ($berita->persembahan as $index => $persembahan)
                                <div class="persembahan-item mb-3 border p-3">
                                    <!-- Tambahkan input hidden untuk ID -->
                                    <input type="hidden" name="persembahan[{{ $index }}][id]" value="{{ $persembahan->berita_acara_persembahan_id }}">
                                    <input type="hidden" name="persembahan[{{ $index }}][hapus]" value="false"> <!-- Defaultnya false -->
                                    
                                    <select name="persembahan[{{ $index }}][kategori_persembahan_id]" class="form-select">
                                        @foreach($kategoriPersembahan as $kategori)
                                            <option value="{{ $kategori->kategori_persembahan_id }}" {{ old("persembahan[$index][kategori_persembahan_id]", $persembahan->kategori_persembahan_id) == $kategori->kategori_persembahan_id ? 'selected' : '' }}>
                                                {{ $kategori->kategori_persembahan_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="persembahan[{{ $index }}][jenis_input]" class="form-select mt-2 jenis-input">
                                        <option value="langsung" {{ old("persembahan[$index][jenis_input]", $persembahan->jenis_input) == 'langsung' ? 'selected' : '' }}>Langsung</option>
                                        <option value="lembaran" {{ old("persembahan[$index][jenis_input]", $persembahan->jenis_input) == 'lembaran' ? 'selected' : '' }}>Lembaran</option>
                                        <option value="amplop" {{ old("persembahan[$index][jenis_input]", $persembahan->jenis_input) == 'amplop' ? 'selected' : '' }}>Amplop</option>
                                    </select>
                                    <div class="jenis-input-wrapper mt-2">
                                        @if ($persembahan->jenis_input === 'langsung')
                                            <div class="form-langsung">
                                                <input type="number" name="persembahan[{{ $index }}][total]" class="form-control" value="{{ old("persembahan[$index][total]", $persembahan->total) }}" placeholder="Total Persembahan">
                                            </div>
                                        @elseif ($persembahan->jenis_input === 'lembaran')
                                        {{-- <pre>{{ var_dump($persembahan) }}</pre> --}}
                                            <div class="form-lembaran">
                                                <div class="row">
                                                    @foreach([100, 200, 500, 1000, 2000, 5000, 10000, 20000, 50000, 100000] as $nom)
                                                        <div class="col-md-2">
                                                            <label>Rp{{ number_format($nom, 0, ',', '.') }}</label>
                                                            <input type="number" min="0" name="persembahan[{{ $index }}][lembaran][jumlah_{{ $nom }}]" 
                                                                class="form-control" 
                                                                value="{{ old("persembahan[$index][lembaran][jumlah_$nom]", $persembahan->lembaran1[0]["jumlah_$nom"] ?? 0) }}">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @elseif ($persembahan->jenis_input === 'amplop')
                                            <div class="form-amplop">
                                                <div class="row">
                                                    @foreach ($persembahan->amplop as $amplopIndex => $amplop)
                                                        <div class="col-md-4">
                                                            <input type="text" name="persembahan[{{ $index }}][amplop][{{ $amplopIndex }}][no_amplop]" class="form-control" value="{{ old("persembahan[$index][amplop][$amplopIndex][no_amplop]", $amplop['no_amplop']) }}" placeholder="No Amplop">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" name="persembahan[{{ $index }}][amplop][{{ $amplopIndex }}][nama_pengguna_amplop]" class="form-control" value="{{ old("persembahan[$index][amplop][$amplopIndex][nama_pengguna_amplop]", $amplop['nama_pengguna_amplop']) }}" placeholder="Nama Pengguna">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="number" name="persembahan[{{ $index }}][amplop][{{ $amplopIndex }}][jumlah]" class="form-control" value="{{ old("persembahan[$index][amplop][$amplopIndex][jumlah]", $amplop['jumlah']) }}" placeholder="Jumlah">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm mt-2 btn-hapus">Hapus</button>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-success mb-3" id="btn-tambah">+ Tambah Persembahan</button>
                        </div>                    
                        <br>
                        <button type="button" class="btn btn-default" onclick="prevStep()">Kembali</button>
                        <button type="button" class="btn btn-info" onclick="nextStep()">Lanjut</button>
                    </div>

                    {{-- STEP 3 --}}
                    <div class="step d-none" id="step-3">
                        <h5>Step 3: Tanda Tangan Pelayan</h5>
                        <!-- Catatan -->
                        <div class="form-group">
                            <label>Catatan Tambahan</label>
                            <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $berita->catatan) }}</textarea>

                            @error('catatan')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <input type="hidden" name="ttd_pelayan_1" id="ttd_pelayan_1">
                        <input type="hidden" name="ttd_pelayan_4" id="ttd_pelayan_4">      

                        <!-- Tanda Tangan Pelayan 1 -->
                        <div class="form-group">
                            <label for="ttd_pelayan_1_id">Tanda Tangan Digital Pelayan 1</label>
                            <select name="ttd_pelayan_1_id" class="form-control" required>
                                <option value="">-- Pilih Pelayan --</option>
                                @foreach($pelayan as $p)
                                    <option value="{{ $p->pelayan_id }}" {{ old('ttd_pelayan_1_id', $berita->ttd_pelayan_1_id) == $p->pelayan_id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>

                            @error('ttd_pelayan_1_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror

                            <!-- Canvas untuk tanda tangan digital -->
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <canvas id="ttd_canvas_1" width="300" height="100" style="border: 1px solid #ccc;"></canvas>

                                @error('ttd_canvas_1')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                                <button type="button" id="clear_ttd_1" class="btn btn-warning btn-sm mt-2">Clear</button>
                            </div>
                        </div>

                        <!-- Tanda Tangan Pelayan 4 -->
                        <div class="form-group">
                            <label for="ttd_pelayan_4_id">Tanda Tangan Digital Pelayan 4</label>
                            <select name="ttd_pelayan_4_id" class="form-control" required>
                                <option value="">-- Pilih Pelayan --</option>
                                @foreach($pelayan as $p)
                                    <option value="{{ $p->pelayan_id }}" {{ old('ttd_pelayan_4_id', $berita->ttd_pelayan_4_id) == $p->pelayan_id ? 'selected' : '' }}>{{ $p->nama }}</option>
                                @endforeach
                            </select>
                            @error('ttd_pelayan_4_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror

                            <!-- Canvas untuk tanda tangan digital -->
                            <div style="display: flex; flex-direction: column; align-items: center;">
                                <canvas id="ttd_canvas_4" width="300" height="100" style="border: 1px solid #ccc;"></canvas>
                                @error('ttd_canvas_4')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                                <button type="button" id="clear_ttd_4" class="btn btn-warning btn-sm mt-2">Clear</button>
                            </div>
                        </div>

        
                        <button type="button" class="btn btn-default" onclick="prevStep()">Kembali</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
        
                    </div>
                </form>
            @endempty
        </div>
    </div>
@endsection

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px !important; /* samakan dengan .form-control (default Bootstrap) */
        padding: 6px 3px;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }

    .select2-selection__rendered {
        line-height: 30px !important;
    }

    .select2-selection__arrow {
        height: 36px !important;
    }

    canvas {
        display: block;
        margin-top: 10px;
    }

    button {
        margin-top: 10px;
    }
</style>
@endpush

@push('js')

<script>
    // ========== Script Step Form ==========
    let currentStep = 1;
    function showStep(step) {
        document.querySelectorAll('.step').forEach((el) => el.classList.add('d-none'));
        document.getElementById('step-' + step).classList.remove('d-none');
        currentStep = step;
    }
    function nextStep() {
        if (currentStep < 3) showStep(currentStep + 1);
    }
    function prevStep() {
        if (currentStep > 1) showStep(currentStep - 1);
    }
    // End of Step Form  
</script>

<script>
    // ========== Script Select2 ==========
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Select2 untuk elemen dengan kelas 'select2'
        $('.select2').select2({
            placeholder: 'Pilih...',
            width: '100%' // Sesuaikan dengan lebar form
        });

        // Re-inisialisasi Select2 jika elemen baru ditambahkan secara dinamis
        function reinitializeSelect2() {
            $('.select2').select2({
                placeholder: 'Pilih...',
                width: '100%'
            });
        }

        // Tambahkan event listener untuk tombol "Tambah Petugas"
        document.querySelector('button[onclick="addPetugas()"]').addEventListener('click', function () {
            setTimeout(reinitializeSelect2, 100); // Tunggu elemen baru dirender sebelum inisialisasi ulang
        });
    });
    // End of Select2
</script>

<script>
    // ========== Script Dynamic Form Persembahan ==========
    let index = 1;
    document.getElementById('btn-tambah').addEventListener('click', function () {
        const container = document.getElementById('form-persembahan');
        const item = document.createElement('div');
        item.classList.add('persembahan-item', 'mb-3', 'border', 'p-3');
        item.innerHTML = `
            <!-- Tambahkan input hidden untuk ID kosong -->
            <input type="hidden" name="persembahan[${index}][id]" value="">
            <input type="hidden" name="persembahan[${index}][hapus]" value="false"> <!-- Menandakan apakah item ini dihapus -->

            <select name="persembahan[${index}][kategori_persembahan_id]" class="form-select">
                @foreach($kategoriPersembahan as $kategori)
                    <option value="{{ $kategori->kategori_persembahan_id }}">{{ $kategori->kategori_persembahan_nama }}</option>
                @endforeach
            </select>
            <select name="persembahan[${index}][jenis_input]" class="form-select mt-2 jenis-input">
                <option value="langsung">Langsung</option>
                <option value="lembaran">Lembaran</option>
                <option value="amplop">Amplop</option>
            </select>
            <div class="jenis-input-wrapper mt-2">
                <div class="form-langsung">
                    <input type="number" name="persembahan[${index}][total]" class="form-control" placeholder="Total Persembahan">
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm mt-2 btn-hapus">Hapus</button>
        `;
        container.appendChild(item);
        index++;
    });
    // End of Dynamic Form Persembahan

    // hapus item
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('btn-hapus')) {
            const item = e.target.closest('.persembahan-item');
            // Temukan input hidden yang menunjukkan apakah item ini dihapus
            const hiddenInput = item.querySelector('input[name$="[hapus]"]');
            if (hiddenInput) {
                hiddenInput.value = 'true'; // Tandai item ini sebagai dihapus
            }
            item.remove();  // Hapus elemen dari DOM
        }
    });

    // jenis input dinamis
    document.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('jenis-input')) {
            const jenis = e.target.value;
            const wrapper = e.target.closest('.persembahan-item').querySelector('.jenis-input-wrapper');
            const idx = [...document.querySelectorAll('.jenis-input')].indexOf(e.target);
            let html = '';

            if (jenis === 'langsung') {
                html = `<div class="form-langsung">
                    <input type="number" name="persembahan[${idx}][total]" class="form-control" placeholder="Total Persembahan">
                </div>`;
            } else if (jenis === 'lembaran') {
                html = `<div class="form-lembaran">
                    <div class="row">
                        @foreach([100, 200, 500, 1000, 2000, 5000, 10000, 20000, 50000, 100000] as $nom)
                        <div class="col-md-2">
                            <label>Rp{{ number_format($nom, 0, ',', '.') }}</label>
                            <input type="number" min="0" name="persembahan[${idx}][lembaran][jumlah_{{ $nom }}]" 
                                class="form-control" 
                                value="0">
                        </div>
                        @endforeach
                    </div>
                </div>`;
            } else if (jenis === 'amplop') {
                html = `<div class="form-amplop">
                    <div class="row amplop-item mb-1">
                        <div class="col-4">
                            <input type="text" name="persembahan[${idx}][amplop][0][no_amplop]" class="form-control" placeholder="No Amplop">
                        </div>
                        <div class="col-4">
                            <input type="text" name="persembahan[${idx}][amplop][0][nama_pengguna_amplop]" class="form-control" placeholder="Nama Pengguna">
                        </div>
                        <div class="col-3">
                            <input type="number" name="persembahan[${idx}][amplop][0][jumlah]" class="form-control" placeholder="Jumlah">
                        </div>
                        <div class="col-1 d-flex justify-content-center align-items-center mb-3">
                            <button type="button" class="btn btn-danger btn-sm btn-hapus-amplop">✕</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-success btn-tambah-amplop mt-2">+ Amplop</button>

                </div>`;
            }

            wrapper.innerHTML = html;
        }
    });

    // Tambah dan hapus amplop dinamis
    document.addEventListener('click', function (e) {
        // Tambah amplop
        if (e.target && e.target.classList.contains('btn-tambah-amplop')) {
            const amplopContainer = e.target.closest('.form-amplop');
            const amplopItems = amplopContainer.querySelectorAll('.amplop-item');
            const idx = amplopItems.length; // Index amplop baru
            const persembahanIdx = [...document.querySelectorAll('.jenis-input')].indexOf(
                amplopContainer.closest('.persembahan-item').querySelector('.jenis-input')
            );

            const amplopItem = document.createElement('div');
            amplopItem.classList.add('row', 'amplop-item', 'mb-1');
            amplopItem.innerHTML = `
                <div class="col-4">
                    <input type="text" name="persembahan[${persembahanIdx}][amplop][${idx}][no_amplop]" class="form-control" placeholder="No Amplop">
                </div>
                <div class="col-4">
                    <input type="text" name="persembahan[${persembahanIdx}][amplop][${idx}][nama_pengguna_amplop]" class="form-control" placeholder="Nama Pengguna">
                </div>
                <div class="col-3">
                    <input type="number" name="persembahan[${persembahanIdx}][amplop][${idx}][jumlah]" class="form-control" placeholder="Jumlah">
                </div>
                <div class="col-1 d-flex justify-content-center align-items-center mb-3">
                    <button type="button" class="btn btn-danger btn-sm btn-hapus-amplop">✕</button>
                </div>
            `;
            amplopContainer.insertBefore(amplopItem, e.target);
        }

        // Hapus amplop
        if (e.target && e.target.classList.contains('btn-hapus-amplop')) {
            e.target.closest('.amplop-item').remove();
        }
    });
    // End of amplop dinamis
</script>

{{-- Script Otomatis Nama Pelayan Firman --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ibadahSelect = document.getElementById('ibadah_id');
        const pelayanFirmanInput = document.getElementById('pelayan_firman');

        // Event listener untuk perubahan dropdown
        ibadahSelect.addEventListener('change', function () {
            // Ambil opsi yang dipilih
            const selectedOption = ibadahSelect.options[ibadahSelect.selectedIndex];
            // Ambil data-pelayan-firman dari opsi yang dipilih
            const pelayanFirman = selectedOption.getAttribute('data-pelayan-firman');
            // Set nilai input pelayan_firman
            pelayanFirmanInput.value = pelayanFirman || '';
        });

        // Set nilai awal jika ada data yang sudah dipilih
        const selectedOption = ibadahSelect.options[ibadahSelect.selectedIndex];
        if (selectedOption) {
            pelayanFirmanInput.value = selectedOption.getAttribute('data-pelayan-firman') || '';
        }
    });
</script>
{{-- End Script otomatis --}}


{{-- TTD --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    console.log('Script TTD dimulai!');
    document.addEventListener('DOMContentLoaded', function () {
        console.log("Page is loaded and script is running.");

        // Inisialisasi SignaturePad
        const canvas1 = document.getElementById('ttd_canvas_1');
        console.log('Canvas 1:', document.getElementById('ttd_canvas_1'));
        const signaturePad1 = new SignaturePad(canvas1);
        console.log('SignaturePad1 initialized:', signaturePad1);

        const canvas4 = document.getElementById('ttd_canvas_4');
        console.log('Canvas 4:', document.getElementById('ttd_canvas_4'));
        const signaturePad4 = new SignaturePad(canvas4);
        console.log('SignaturePad4 initialized:', signaturePad4);

        console.log('Hidden Input TTD Pelayan 1:', document.getElementById('ttd_pelayan_1'));
        console.log('Hidden Input TTD Pelayan 4:', document.getElementById('ttd_pelayan_4'));

        console.log('SignaturePad:', SignaturePad);

        // Tombol clear untuk kedua canvas
        document.getElementById('clear_ttd_1').addEventListener('click', () => {
            signaturePad1.clear();
        });
        document.getElementById('clear_ttd_4').addEventListener('click', () => {
            signaturePad4.clear();
        });

        // Cek form dan pastikan event listener submit terpasang
        const form = document.querySelector('form');
        if (form) {
            console.log("Form element found.");
            form.addEventListener('submit', function (e) {
                console.log("Form sedang disubmit");

                // Validasi tanda tangan kosong
                if (signaturePad1.isEmpty()) {
                    alert('Tanda tangan Pelayan 1 harus diisi.');
                    e.preventDefault();
                    return;
                }
                if (signaturePad4.isEmpty()) {
                    alert('Tanda tangan Pelayan 4 harus diisi.');
                    e.preventDefault();
                    return;
                }

                // Mengambil data URL tanda tangan
                const ttd1 = signaturePad1.toDataURL();
                const ttd4 = signaturePad4.toDataURL();

                // Validasi jika tanda tangan gagal diambil
                if (!ttd1 || !ttd4) {
                    console.error("Tanda tangan tidak dapat dikonversi menjadi data URL.");
                    e.preventDefault();
                    return;
                }

                // Debug log untuk memastikan data sudah siap
                console.log('TTD Pelayan 1 (Base64):', ttd1);
                console.log('TTD Pelayan 4 (Base64):', ttd4);

                // Set nilai pada field input tersembunyi
                document.getElementById('ttd_pelayan_1').value = ttd1;
                document.getElementById('ttd_pelayan_4').value = ttd4;

                // Debug log untuk memastikan hidden input diisi
                console.log('Hidden Input TTD Pelayan 1:', document.getElementById('ttd_pelayan_1').value);
                console.log('Hidden Input TTD Pelayan 4:', document.getElementById('ttd_pelayan_4').value);
            });
        } else {
            console.log("Form element NOT found.");
        }
    });

</script>
    
    
{{-- End Script TTD --}}


{{-- Pelayan Firman ketik & dropdown --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- <script>
    $(document).ready(function() {
        $('#pelayan_firman').select2({
            placeholder: 'Pilih pelayan firman...',
            width: '100%' // Sesuaikan dengan lebar form
        });
    });
</script> --}}
<script>
    let petugasIndex = {{ count($berita->petugas) }};
    function addPetugas() {
        const wrapper = document.getElementById('petugas-wrapper');
        const html = `
            <div class="form-row mb-2 align-items-center">
                <div class="col-3">
                    <select name="petugas[${petugasIndex}][peran]" class="form-control" required>
                        <option value="">-- Pilih Peran --</option>
                        <option value="Pelayan 1">Pelayan 1</option>
                        <option value="Pelayan 2">Pelayan 2</option>
                        <option value="Pelayan 3">Pelayan 3</option>
                    </select>
                </div>
                <div class="col-4">
                    <select name="petugas[${petugasIndex}][pelayan_id_jadwal]" class="form-control select2" required>
                        <option value="">-- Jadwal Petugas --</option>
                        @foreach($pelayan as $p)
                            <option value="{{ $p->pelayan_id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <select name="petugas[${petugasIndex}][pelayan_id_hadir]" class="form-control select2">
                        <option value="">-- Petugas Hadir --</option>
                        @foreach($pelayan as $p)
                            <option value="{{ $p->pelayan_id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-1 d-flex justify-content-center align-items-center mb-2">
                    <button type="button" class="btn btn-danger btn-sm" onclick="removePetugas(this)">✕</button>
                </div>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', html);
        petugasIndex++;
    }
    
    function removePetugas(button) {
        button.closest('.form-row').remove();
    }
    </script>
      
    
@endpush