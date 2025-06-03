@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('pengelolaan-berita-acara/berita-acara') }}" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                {{-- STEP 1 --}}
                <div class="step" id="step-1">
                    <h5>Step 1: Informasi Ibadah</h5>
                    
                    <!-- Pilih Ibadah -->
                    <div class="form-group">
                        <label for="ibadah_id">Ibadah</label>
                        <select name="ibadah_id" id="ibadah_id" class="form-control select2" required>
                            <option value="">-- Pilih Ibadah --</option>
                            @foreach($ibadah as $item)
                                <option value="{{ $item->ibadah_id }}" data-pelayan-firman="{{ $item->pelayan_firman }}">
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
                        <input type="text" name="bacaan_alkitab" class="form-control" required>

                        @error('bacaan_alkitab')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Pelayan Firman -->
                    <div class="form-group">
                        <label for="pelayan_firman">Pelayan Firman</label>
                        <input type="text" class="form-control" id="pelayan_firman" name="pelayan_firman" 
                            value="" readonly>
                    </div>

                    {{-- Petugas Ibadah --}}
                    <div class="form-group">
                        <label>Petugas Ibadah</label>
                        <!-- Header Row -->
                        <div class="form-row mb-1">
                            <div class="col-3"><label>Peran</label></div>
                            <div class="col-4"><label>Petugas Jadwal</label></div>
                            <div class="col-4"><label>Petugas Hadir</label></div>
                            <div class="col-1" style="text-align: center;"><label>Aksi</label></div>
                        </div>
                        <!-- Wrapper for dynamic rows -->
                        <div id="petugas-wrapper">
                            {{-- Dynamic petugas rows will be added here by JavaScript --}}
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-sm btn-success" onclick="addPetugas()">+ Tambah Petugas</button>
                        </div>
                    </div>

                    <!-- Jumlah Kehadiran -->
                    <div class="form-group">
                        <label for="jumlah_kehadiran">Jumlah Kehadiran Jemaat</label>
                        <div class="input-group">
                            <input type="number" name="jumlah_kehadiran" class="form-control" required>
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
                        <div class="persembahan-item mb-3 border p-3">
                            <select name="persembahan[0][kategori_persembahan_id]" class="form-select">
                                @foreach($kategoriPersembahan as $kategori)
                                    <option value="{{ $kategori->kategori_persembahan_id }}">{{ $kategori->kategori_persembahan_nama }}</option>
                                @endforeach
                            </select>
                            @error('kategori_persembahan_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                            <select name="persembahan[0][jenis_input]" class="form-select mt-2 jenis-input">
                                <option value="langsung">Langsung</option>
                                <option value="lembaran">Lembaran</option>
                                <option value="amplop">Amplop</option>
                            </select>
                            @error('jenis_input')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                            <div class="jenis-input-wrapper mt-2">
                                <div class="form-langsung">
                                    <input type="number" name="persembahan[0][total]" class="form-control" placeholder="Total Persembahan">

                                    @error('total')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm mt-2 btn-hapus">Hapus</button>
                        </div>
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
                        <textarea name="catatan" class="form-control" rows="3"></textarea>

                        @error('catatan')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <input type="hidden" name="ttd_pelayan_1" id="ttd_pelayan_1">
                    <input type="hidden" name="ttd_pelayan_4" id="ttd_pelayan_4">      

                    <!-- Tanda Tangan Pelayan 1 -->
                    <div class="form-group">
                        <label for="ttd_pelayan_1_id">Tanda Tangan Digital Pelayan 1</label>
                        <select name="ttd_pelayan_1_id" id="ttd_pelayan_1_id" class="form-control select2" required>
                            <option value="">-- Pilih Pelayan --</option>
                            @foreach($pelayan as $p)
                                <option value="{{ $p->pelayan_id }}">{{ $p->nama }}</option>
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
                        <select name="ttd_pelayan_4_id" id="ttd_pelayan_4_id" class="form-control select2" required>
                            <option value="">-- Pilih Pelayan --</option>
                            @foreach($pelayan as $p)
                                <option value="{{ $p->pelayan_id }}">{{ $p->nama }}</option>
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
    // ========== Script Dynamic Form Persembahan ==========
    let index = 1;
    document.getElementById('btn-tambah').addEventListener('click', function () {
        const container = document.getElementById('form-persembahan');
        const item = document.createElement('div');
        item.classList.add('persembahan-item', 'mb-3', 'border', 'p-3');
        item.innerHTML = `
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
            e.target.closest('.persembahan-item').remove();
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
                        {{-- Koin --}}
                        @foreach([100, 200, 500] as $nom)
                        <div class="col-md-2">
                            <label>Rp{{ number_format($nom, 0, ',', '.') }} (Koin)</label>
                            <input type="number" min="0" name="persembahan[${idx}][lembaran][jumlah_{{ $nom }}]" class="form-control" value="0">
                        </div>
                        @endforeach
                        <div class="col-md-2">
                            <label>Rp1.000 (Koin)</label>
                            <input type="number" min="0" name="persembahan[${idx}][lembaran][jumlah_1000_koin]" class="form-control" value="0">
                        </div>

                        {{-- Kertas --}}
                        <div class="col-md-2">
                            <label>Rp1.000 (Kertas)</label>
                            <input type="number" min="0" name="persembahan[${idx}][lembaran][jumlah_1000_kertas]" class="form-control" value="0">
                        </div>
                        @foreach([2000, 5000, 10000, 20000, 50000, 100000] as $nom)
                        <div class="col-md-2">
                            <label>Rp{{ number_format($nom, 0, ',', '.') }} (Kertas)</label>
                            <input type="number" min="0" name="persembahan[${idx}][lembaran][jumlah_{{ $nom }}]" class="form-control" value="0">
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
    });
</script>
{{-- End Script otomatis --}}

<script>
    // ========== Script Dropdown Otomatis ID Pelayan 1 Pelayan 4 ==========
    document.addEventListener('DOMContentLoaded', function () {
        $('#petugas-wrapper').on('change', '.peran-dropdown', function () {
            const selectedPeran = this.value;
            const $row = $(this).closest('.petugas-row');
            const pelayanHadirSelect = $row.find('.petugas-hadir-select'); // Target by class

            if (!pelayanHadirSelect.length) {
                console.warn('Dropdown Petugas Hadir tidak ditemukan dalam baris ini.');
                return;
            }

            const pelayanHadirValue = pelayanHadirSelect.val(); // This will be the text or ID

            // If Petugas Hadir is not selected, or if it's a new tag (not an ID from a pre-loaded option),
            // the backend will handle creation. This script primarily links existing ones if IDs were used.
            // For tags:true, pelayanHadirValue will be the string name.
            // We need to decide if TTD should be linked if it's a new name or only if an existing ID was somehow selected.
            // For now, assume any value means we try to link, but if it's not an ID, TTD won't find it.
            // Or, more safely, only link if it's numeric (an ID).

            let pelayanIdToLink = null;
            if (pelayanHadirValue && !isNaN(parseInt(pelayanHadirValue))) { // Check if it's a numeric ID
                pelayanIdToLink = parseInt(pelayanHadirValue);
            }

            if (selectedPeran === 'Pelayan 1') {
                $('#ttd_pelayan_1_id').val(pelayanIdToLink).trigger('change');
            } else {
                // If this row was previously Pelayan 1 and set ttd_pelayan_1_id,
                // and now it's not Pelayan 1, we might need to clear ttd_pelayan_1_id
                // if ($('#ttd_pelayan_1_id').val() was set by this specific row.
                // This requires more complex state tracking. For now, we only set.
            }

            if (selectedPeran === 'Pelayan 4') {
                $('#ttd_pelayan_4_id').val(pelayanIdToLink).trigger('change');
            } else {
                // Similar logic for Pelayan 4 if needed.
            }

            // If pelayanHadirValue is a new tag (string), pelayanIdToLink will be null, effectively clearing TTD.
            if (!pelayanIdToLink) {
                 if (selectedPeran === 'Pelayan 1') {
                    $('#ttd_pelayan_1_id').val(null).trigger('change');
                }
                if (selectedPeran === 'Pelayan 4') {
                    $('#ttd_pelayan_4_id').val(null).trigger('change');
                }
            }
        });

        // Also listen to changes on Petugas Hadir directly, as it might change without Peran changing.
        $('#petugas-wrapper').on('change', '.petugas-hadir-select', function() {
            const $row = $(this).closest('.petugas-row');
            const selectedPeran = $row.find('.peran-dropdown').val();
            // Trigger a change on the peran dropdown to re-evaluate TTD linking
            if (selectedPeran) {
                 $row.find('.peran-dropdown').trigger('change');
            }
        });
    });
</script>


{{-- TTD --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@5.0.7/dist/signature_pad.umd.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const canvas1 = document.getElementById('ttd_canvas_1');
        const canvas4 = document.getElementById('ttd_canvas_4');

        // Pastikan canvas1 dan canvas4 ada sebelum melanjutkan
        if (!canvas1 || !canvas4) {
            console.error("Canvas tidak ditemukan!");
            return;
        }

        // Versi UMD SignaturePad
        const signaturePad1 = new window.SignaturePad(canvas1);
        const signaturePad4 = new window.SignaturePad(canvas4);
        
        signaturePad1.addEventListener('endStroke', function () {
            document.getElementById('ttd_pelayan_1').value = signaturePad1.toDataURL();
            console.log("TTD Pelayan 1 (updated):", signaturePad1.toDataURL());
        });
        
        signaturePad4.addEventListener('endStroke', function () {
            document.getElementById('ttd_pelayan_4').value = signaturePad4.toDataURL();
            console.log("TTD Pelayan 4 (updated):", signaturePad4.toDataURL());
        });

        console.log("TTD Pelayan 1 (initial):", signaturePad1.toDataURL());
        console.log("TTD Pelayan 4 (initial):", signaturePad4.toDataURL());

        // Clear buttons
        document.getElementById('clear_ttd_1').addEventListener('click', () => signaturePad1.clear());
        document.getElementById('clear_ttd_4').addEventListener('click', () => signaturePad4.clear());
    });
</script>
{{-- End Script TTD --}}


{{-- Pelayan Firman ketik & dropdown --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize standard Select2 elements (non-tags)
    $('#ibadah_id, #ttd_pelayan_1_id, #ttd_pelayan_4_id').select2({
        placeholder: 'Pilih...',
        width: '100%'
    });
    // Add any other non-tag Select2 initializations here if needed.

    let petugasCounter = 0; // Counter for unique IDs for new rows

    // Function to add a new petugas row
    window.addPetugas = function() {
        const wrapper = $('#petugas-wrapper');
        const currentRowId = petugasCounter; // Use counter for unique name attributes

        const newRowHtml = `
            <div class="form-row mb-2 align-items-center petugas-row" data-row-id="${currentRowId}">
                <div class="col-3">
                    <select name="petugas[${currentRowId}][peran]" class="form-control peran-dropdown" required>
                        <option value="">-- Pilih Peran --</option>
                        <option value="Pelayan 1">Pelayan 1</option>
                        <option value="Pelayan 2">Pelayan 2</option>
                        <option value="Pelayan 3">Pelayan 3</option>
                        <option value="Pelayan 4">Pelayan 4</option>
                        <option value="Pelayan 5">Pelayan 5</option>
                        <option value="Pelayan 6">Pelayan 6</option>
                        <option value="Pelayan 7">Pelayan 7</option>
                        <option value="Pelayan 8">Pelayan 8</option>
                        <option value="Pelayan 9">Pelayan 9</option>
                        <option value="Penerima Tamu">Penerima Tamu</option>
                        <option value="Kolektan">Kolektan</option>
                        <option value="Pemandu Lagu">Pemandu Lagu</option>
                        <option value="Paduan Suara/VG">Paduan Suara/VG</option>
                        <option value="Organis/Pianis/Keyboardis">Organis/Pianis/Keyboardis</option>
                        <option value="Operator LCD">Operator LCD</option>
                        <option value="Operator Sound">Operator Sound</option>
                        <option value="Operator CCTV">Operator CCTV</option>
                    </select>
                </div>
                <div class="col-4">
                    <select name="petugas[${currentRowId}][pelayan_id_jadwal]" class="form-control petugas-jadwal-select" required>
                        <option value="">-- Jadwal Petugas --</option>
                        @foreach($pelayan as $p)
                            <option value="{{ $p->pelayan_id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <select name="petugas[${currentRowId}][pelayan_id_hadir]" class="form-control petugas-hadir-select">
                        <option value="">-- Petugas Hadir --</option>
                        @foreach($pelayan as $p)
                            <option value="{{ $p->pelayan_id }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-1 d-flex justify-content-center align-items-center">
                    <button type="button" class="btn btn-danger btn-sm btn-remove-petugas">✕</button>
                </div>
            </div>
        `;
        wrapper.append(newRowHtml);

        const newRow = wrapper.find(`.petugas-row[data-row-id="${currentRowId}"]`);

        newRow.find('.petugas-jadwal-select').select2({
            tags: true,
            placeholder: 'Ketik atau pilih nama',
            width: '100%',
            language: {
                noResults: function () {
                    return "Ketik nama untuk menambah baru";
                }
            }
        });

        newRow.find('.petugas-hadir-select').select2({
            tags: true,
            placeholder: 'Ketik atau pilih nama',
            width: '100%',
            language: {
                noResults: function () {
                    return "Ketik nama untuk menambah baru";
                }
            }
        });

        petugasCounter++; // Increment counter for the next row
    }

    // Event delegation for removing a petugas row
    $('#petugas-wrapper').on('click', '.btn-remove-petugas', function() {
        const row = $(this).closest('.petugas-row');
        // Destroy Select2 instances before removing the row to prevent memory leaks
        row.find('.petugas-jadwal-select, .petugas-hadir-select').each(function() {
            if ($(this).data('select2')) {
                $(this).select2('destroy');
            }
        });
        row.remove();
        // Note: petugasCounter is not decremented. Indices sent to backend will be based on
        // the order of rows, not necessarily sequential if rows are deleted from the middle.
        // Laravel handles associative arrays from form data well, so gapped indices are usually fine.
    });

    // Add one petugas row by default when the page loads
    addPetugas();

});
</script>

@endpush