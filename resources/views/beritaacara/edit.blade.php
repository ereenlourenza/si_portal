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
                            <select name="ibadah_id" id="ibadah_id" class="form-control select2" required>
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
                            <!-- Header Row -->
                            <div class="form-row mb-1">
                                <div class="col-3"><label>Peran</label></div>
                                <div class="col-4"><label>Petugas Jadwal</label></div>
                                <div class="col-4"><label>Petugas Hadir</label></div>
                                <div class="col-1" style="text-align: center;"><label>Aksi</label></div>
                            </div>
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
                                                <input type="number" name="persembahan[{{ $index }}][total]" class="form-control" value="{{ old("persembahan.$index.total", $persembahan->total) }}" placeholder="Total Persembahan">
                                            </div>
                                        @elseif ($persembahan->jenis_input === 'lembaran')
                                        {{-- <pre>{{ var_dump($persembahan) }}</pre> --}}
                                            <div class="form-lembaran">
                                                <div class="row">
                                                    {{-- Koin --}}
                                                    @foreach([100, 200, 500] as $nom)
                                                        <div class="col-md-2">
                                                            <label>Rp{{ number_format($nom, 0, ',', '.') }} (Koin)</label>
                                                            <input type="number" min="0" name="persembahan[{{ $index }}][lembaran][jumlah_{{ $nom }}]" 
                                                                class="form-control" 
                                                                value="{{ old("persembahan.$index.lembaran.jumlah_$nom", $persembahan->lembaran->first()->{'jumlah_'.$nom} ?? 0) }}">
                                                        </div>
                                                    @endforeach
                                                    <div class="col-md-2">
                                                        <label>Rp1.000 (Koin)</label>
                                                        <input type="number" min="0" name="persembahan[{{ $index }}][lembaran][jumlah_1000_koin]" 
                                                            class="form-control" 
                                                            value="{{ old("persembahan.$index.lembaran.jumlah_1000_koin", $persembahan->lembaran->first()->jumlah_1000_koin ?? 0) }}">
                                                    </div>

                                                    {{-- Kertas --}}
                                                    <div class="col-md-2">
                                                        <label>Rp1.000 (Kertas)</label>
                                                        <input type="number" min="0" name="persembahan[{{ $index }}][lembaran][jumlah_1000_kertas]" 
                                                            class="form-control" 
                                                            value="{{ old("persembahan.$index.lembaran.jumlah_1000_kertas", $persembahan->lembaran->first()->jumlah_1000_kertas ?? 0) }}">
                                                    </div>
                                                    @foreach([2000, 5000, 10000, 20000, 50000, 100000] as $nom)
                                                        <div class="col-md-2">
                                                            <label>Rp{{ number_format($nom, 0, ',', '.') }} (Kertas)</label>
                                                            <input type="number" min="0" name="persembahan[{{ $index }}][lembaran][jumlah_{{ $nom }}]" 
                                                                class="form-control" 
                                                                value="{{ old("persembahan.$index.lembaran.jumlah_$nom", $persembahan->lembaran->first()->{'jumlah_'.$nom} ?? 0) }}">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @elseif ($persembahan->jenis_input === 'amplop')
                                            <div class="form-amplop">
                                                @foreach ($persembahan->amplop as $amplopIndex => $amplop)
                                                    <div class="amplop-item row mb-2">
                                                        <input type="hidden" name="persembahan[{{ $index }}][amplop][{{ $amplopIndex }}][id]" value="{{ $amplop->berita_acara_persembahan_amplop_id }}">
                                                        <input type="hidden" name="persembahan[{{ $index }}][amplop][{{ $amplopIndex }}][hapus]" value="false" class="input-amplop-hapus">
                                                        <div class="col-md-3">
                                                            <input type="text" name="persembahan[{{ $index }}][amplop][{{ $amplopIndex }}][no_amplop]" class="form-control" value="{{ old("persembahan.$index.amplop.$amplopIndex.no_amplop", $amplop->no_amplop) }}" placeholder="No Amplop">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" name="persembahan[{{ $index }}][amplop][{{ $amplopIndex }}][nama_pengguna_amplop]" class="form-control" value="{{ old("persembahan.$index.amplop.$amplopIndex.nama_pengguna_amplop", $amplop->nama_pengguna_amplop) }}" placeholder="Nama Pengguna">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="number" name="persembahan[{{ $index }}][amplop][{{ $amplopIndex }}][jumlah]" class="form-control" value="{{ old("persembahan.$index.amplop.$amplopIndex.jumlah", $amplop->jumlah) }}" placeholder="Jumlah">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="btn btn-danger btn-sm btn-hapus-amplop">✕</button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <button type="button" class="btn btn-success btn-sm mt-2 btn-tambah-amplop">+ Amplop</button>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm mt-2 btn-hapus">Hapus Persembahan</button>
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

                        <!-- Tanda Tangan Pelayan 1 -->
                        <div class="form-group">
                            <label>Pelayan 1 (Penanda Tangan)</label>
                            <input type="text" class="form-control" value="{{ $berita->pelayan1->nama ?? 'Tidak ada data' }}" readonly>
                            <input type="hidden" name="ttd_pelayan_1_id" value="{{ $berita->ttd_pelayan_1_id }}">

                            @error('ttd_pelayan_1_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Tanda Tangan Pelayan 4 -->
                        <div class="form-group">
                            <label>Pelayan 4 (Penanda Tangan)</label>
                            <input type="text" class="form-control" value="{{ $berita->pelayan4->nama ?? 'Tidak ada data' }}" readonly>
                            <input type="hidden" name="ttd_pelayan_4_id" value="{{ $berita->ttd_pelayan_4_id }}">

                            @error('ttd_pelayan_4_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
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
            tags: true,
            width: '100%' // Sesuaikan dengan lebar form
        });

        // Re-inisialisasi Select2 jika elemen baru ditambahkan secara dinamis
        function reinitializeSelect2() {
            $('.select2').select2({
                placeholder: 'Pilih...',
                tags: true,
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
    document.addEventListener('DOMContentLoaded', function () {
        let persembahanNextIndex = {{ $berita->persembahan->count() }};
        const formPersembahan = document.getElementById('form-persembahan');

        function getPersembahanIndex(element) {
            const persembahanItem = element.closest('.persembahan-item');
            if (!persembahanItem) return null;

            const nameInput = persembahanItem.querySelector('[name^="persembahan["]');
            if (nameInput) {
                const match = nameInput.name.match(/persembahan\\[(\d+)\\]/);
                if (match && match[1] !== undefined) return parseInt(match[1]);
            }
            // For newly added items that might not have inputs yet or if parsing fails
            if (persembahanItem.dataset.index !== undefined) {
                return parseInt(persembahanItem.dataset.index);
            }
            return null;
        }

        function getNextAmplopIndex(persembahanItem) {
            return persembahanItem.querySelectorAll('.amplop-item').length;
        }

        function addAmplopRow(formAmplopContainer, pIndex, amplopData = null) {
            const aIndex = getNextAmplopIndex(formAmplopContainer.closest('.persembahan-item'));
            const amplopId = amplopData ? amplopData.id : '';
            const noAmplop = amplopData ? amplopData.no_amplop : '';
            const namaPengguna = amplopData ? amplopData.nama_pengguna_amplop : '';
            const jumlah = amplopData ? amplopData.jumlah : '';

            const amplopItem = document.createElement('div');
            amplopItem.classList.add('amplop-item', 'row', 'mb-2');
            amplopItem.innerHTML = `
                <input type="hidden" name="persembahan[${pIndex}][amplop][${aIndex}][id]" value="${amplopId}">
                <input type="hidden" name="persembahan[${pIndex}][amplop][${aIndex}][hapus]" value="false" class="input-amplop-hapus">
                <div class="col-md-3">
                    <input type="text" name="persembahan[${pIndex}][amplop][${aIndex}][no_amplop]" class="form-control" placeholder="No Amplop" value="${noAmplop}">
                </div>
                <div class="col-md-4">
                    <input type="text" name="persembahan[${pIndex}][amplop][${aIndex}][nama_pengguna_amplop]" class="form-control" placeholder="Nama Pengguna" value="${namaPengguna}">
                </div>
                <div class="col-md-3">
                    <input type="number" name="persembahan[${pIndex}][amplop][${aIndex}][jumlah]" class="form-control" placeholder="Jumlah" value="${jumlah}">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm btn-hapus-amplop">✕</button>
                </div>
            `;
            // If there's a "+ Amplop" button, insert before it, otherwise append to container
            const tambahAmplopButton = formAmplopContainer.parentElement.querySelector('.btn-tambah-amplop');
            if(tambahAmplopButton){
                formAmplopContainer.insertBefore(amplopItem, null); // insert at the end of .form-amplop div
            } else {
                formAmplopContainer.appendChild(amplopItem);
            }
        }

        function renderJenisInputFields(jenisInputSelect) {
            const selectedJenis = jenisInputSelect.value;
            const persembahanItem = jenisInputSelect.closest('.persembahan-item');
            const wrapper = persembahanItem.querySelector('.jenis-input-wrapper');
            const pIndex = getPersembahanIndex(jenisInputSelect);

            if (pIndex === null) {
                console.error('Could not determine persembahan index for', jenisInputSelect);
                return;
            }

            wrapper.innerHTML = ''; // Clear previous inputs

            if (selectedJenis === 'langsung') {
                wrapper.innerHTML = `
                    <div class="form-langsung">
                        <input type="number" name="persembahan[${pIndex}][total]" class="form-control" placeholder="Total Persembahan">
                    </div>`;
            } else if (selectedJenis === 'lembaran') {
                let lembaranHtml = '<div class="form-lembaran"><div class="row">';
                
                // Koin
                [100, 200, 500].forEach(nom => {
                    lembaranHtml += `
                        <div class="col-md-2">
                            <label>Rp${nom.toLocaleString('id-ID')} (Koin)</label>
                            <input type="number" min="0" name="persembahan[${pIndex}][lembaran][jumlah_${nom}]" class="form-control" value="0">
                        </div>`;
                });
                lembaranHtml += `
                        <div class="col-md-2">
                            <label>Rp1.000 (Koin)</label>
                            <input type="number" min="0" name="persembahan[${pIndex}][lembaran][jumlah_1000_koin]" class="form-control" value="0">
                        </div>`;

                // Kertas
                lembaranHtml += `
                        <div class="col-md-2">
                            <label>Rp1.000 (Kertas)</label>
                            <input type="number" min="0" name="persembahan[${pIndex}][lembaran][jumlah_1000_kertas]" class="form-control" value="0">
                        </div>`;
                [2000, 5000, 10000, 20000, 50000, 100000].forEach(nom => {
                    lembaranHtml += `
                        <div class="col-md-2">
                            <label>Rp${nom.toLocaleString('id-ID')} (Kertas)</label>
                            <input type="number" min="0" name="persembahan[${pIndex}][lembaran][jumlah_${nom}]" class="form-control" value="0">
                        </div>`;
                });
                lembaranHtml += '</div></div>';
                wrapper.innerHTML = lembaranHtml;
            } else if (selectedJenis === 'amplop') {
                wrapper.innerHTML = `
                    <div class="form-amplop">
                        <!-- Amplop items will be added here -->
                    </div>
                    <button type="button" class="btn btn-success btn-sm mt-2 btn-tambah-amplop">+ Amplop</button>
                `;
                addAmplopRow(wrapper.querySelector('.form-amplop'), pIndex); // Add the first amplop row
            }
        }

        if (formPersembahan) {
            formPersembahan.addEventListener('click', function(e) {
                // Hapus Persembahan Item
                if (e.target.classList.contains('btn-hapus')) { // Assuming 'btn-hapus' is for persembahan item
                    const persembahanItem = e.target.closest('.persembahan-item');
                    if (!persembahanItem) return;
                    const idInput = persembahanItem.querySelector('input[name$="[id]"]');
                    const hapusInput = persembahanItem.querySelector('input[name$="[hapus]"]'); // Should be input-persembahan-hapus

                    if (hapusInput) {
                        hapusInput.value = 'true';
                    }
                    if (idInput && idInput.value) {
                        persembahanItem.style.display = 'none';
                    } else {
                        persembahanItem.remove();
                    }
                }

                // Tambah Amplop Item
                if (e.target.classList.contains('btn-tambah-amplop')) {
                    const persembahanItem = e.target.closest('.persembahan-item');
                    const pIndex = getPersembahanIndex(e.target);
                    const formAmplopContainer = persembahanItem.querySelector('.form-amplop');
                    if (pIndex !== null && formAmplopContainer) {
                        addAmplopRow(formAmplopContainer, pIndex);
                    }
                }

                // Hapus Amplop Item
                if (e.target.classList.contains('btn-hapus-amplop')) {
                    const amplopItem = e.target.closest('.amplop-item');
                    if (!amplopItem) return;
                    const idInput = amplopItem.querySelector('input[name$="[id]"]');
                    const hapusInput = amplopItem.querySelector('.input-amplop-hapus');

                    if (hapusInput) {
                        hapusInput.value = 'true';
                    }

                    if (idInput && idInput.value) {
                        amplopItem.style.display = 'none';
                    } else {
                        amplopItem.remove();
                    }
                }
            });

            formPersembahan.addEventListener('change', function(e) {
                if (e.target.classList.contains('jenis-input')) {
                    renderJenisInputFields(e.target);
                }
            });
        }

        const btnTambahPersembahan = document.getElementById('btn-tambah');
        if (btnTambahPersembahan) {
            btnTambahPersembahan.addEventListener('click', function () {
                const container = formPersembahan;
                const newItem = document.createElement('div');
                newItem.classList.add('persembahan-item', 'mb-3', 'border', 'p-3');
                newItem.dataset.index = persembahanNextIndex; 

                let kategoriOptions = '';
                @foreach($kategoriPersembahan as $kategori)
                    kategoriOptions += `<option value="{{ $kategori->kategori_persembahan_id }}">{{ $kategori->kategori_persembahan_nama }}</option>`;
                @endforeach

                newItem.innerHTML = `
                    <input type="hidden" name="persembahan[${persembahanNextIndex}][id]" value="">
                    <input type="hidden" name="persembahan[${persembahanNextIndex}][hapus]" value="false" class="input-persembahan-hapus">
                    
                    <select name="persembahan[${persembahanNextIndex}][kategori_persembahan_id]" class="form-select">
                        ${kategoriOptions}
                    </select>
                    <select name="persembahan[${persembahanNextIndex}][jenis_input]" class="form-select mt-2 jenis-input">
                        <option value="langsung" selected>Langsung</option>
                        <option value="lembaran">Lembaran</option>
                        <option value="amplop">Amplop</option>
                    </select>
                    <div class="jenis-input-wrapper mt-2">
                        <!-- Fields will be rendered here -->
                    </div>
                    <button type="button" class="btn btn-danger btn-sm mt-2 btn-hapus">Hapus Persembahan</button>
                `;
                if (container) {
                    container.appendChild(newItem);
                }
                
                const newJenisInputSelect = newItem.querySelector('.jenis-input');
                if (newJenisInputSelect) {
                    renderJenisInputFields(newJenisInputSelect);
                }
                
                persembahanNextIndex++;
            });
        }
    });
    // End of Script Dynamic Form Persembahan
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


{{-- Pelayan Firman ketik & dropdown --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
                        <option value="Pelayan 4">Pelayan 4</option>
                        <option value="Pelayan 5">Pelayan 5</option>
                        <option value="Pelayan 6">Pelayan 6</option>
                        <option value="Pelayan 7">Pelayan 7</option>
                        <option value="Pelayan 8">Pelayan 8</option>
                        <option value="Pelayan 9">Pelayan 9</option>
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