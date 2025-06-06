@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('pengelolaan-informasi/pelayan') }}" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                
                <!-- Dropdown Kategori Pelayan -->
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Kategori Pelayan<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <select class="form-control" id="kategoripelayan_id" name="kategoripelayan_id" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach($kategoripelayan->where('kategoripelayan_id', '!=', 5) as $item)
                                <option value="{{ $item->kategoripelayan_id }}">{{ $item->kategoripelayan_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row align-items-center" id="pelkat_container" style="display: none;">
                    <label class="col-md-1 control-label col-form-label">Pelkat Nama<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <select class="form-control" id="pelkat_id" name="pelkat_id" required disabled>
                            <option value="" disabled selected>Pilih Pelkat</option>
                            @foreach($pelkat as $item)
                                <option value="{{ $item->pelkat_id }}">{{ $item->pelkat_nama }}</option>
                            @endforeach
                            <input type="hidden" name="pelkat_id" id="hidden_pelkat_id">
                        </select>
                    </div>
                </div>

                <!-- Input teks nama (default) -->
                <div class="form-group row align-items-center" id="nama_text">
                    <label class="col-md-1 control-label col-form-label">Nama<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nama_input" name="nama" value="{{ old('nama') }}" required>
                        @error('nama')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                @php
                    $currentYear = date('Y');
                    $futureYear = $currentYear + 5; // Bisa diganti sesuai kebutuhan
                @endphp
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Masa Jabatan Mulai<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <select class="form-control" id="masa_jabatan_mulai" name="masa_jabatan_mulai" required>
                            <option value="" disabled selected>Pilih Tahun</option>
                            @for ($year = $futureYear; $year >= 1900; $year--)
                                <option value="{{ $year }}" {{ old('masa_jabatan_mulai') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                                                
                        {{-- @error('masa_jabatan_mulai')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror --}}
                        @if ($errors->has('masa_jabatan_mulai'))
                            <div class="text-danger">{{ $errors->first('masa_jabatan_mulai') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Masa Jabatan Selesai<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <select class="form-control" id="masa_jabatan_selesai" name="masa_jabatan_selesai" required>
                            <option value="" disabled selected>Pilih Tahun</option>
                            @for ($year = $futureYear; $year >= 1900; $year--)
                                <option value="{{ $year }}" {{ old('masa_jabatan_selesai') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>

                        @error('masa_jabatan_selesai')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Keterangan</label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ old('keterangan') }}">
                        
                        @error('keterangan')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Foto</label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="foto" name="foto" accept=".jpg,.jpeg,.png" value="{{ old('foto') }}">
                        
                        @error('foto')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror

                        <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>

                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label"></label>
                    <div class="col-md-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/pelayan') }}">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
<script>
    document.getElementById("pelkat_id").addEventListener("change", function() {
        document.getElementById("hidden_pelkat_id").value = this.value;
    });

    document.addEventListener("DOMContentLoaded", function() {
    var kategoriSelect = document.getElementById("kategoripelayan_id");
    var pelkatSelect = document.getElementById("pelkat_id");
    var pelkatContainer = document.getElementById("pelkat_container"); // Kontainer dropdown pelkat

    kategoriSelect.addEventListener("change", function() {
        var selectedValue = kategoriSelect.value; // Ambil value kategori yang dipilih

        // Mapping kategori ke pelkat
        var kategoriPelkatMap = {
            "6": "1", // Pengurus PA -> Pelkat PA
            "7": "2", // Pengurus PT -> Pelkat PT
            "8": "3", // Pengurus GP -> Pelkat GP
            "9": "4", // Pengurus PKP -> Pelkat PKP
            "10": "5", // Pengurus PKB -> Pelkat PKB
            "11": "6",  // Pengurus PKLU -> Pelkat PKLU
            "12": "1",  // Pelayan PA -> Pelkat PA
            "13": "2",  // Pelayan PT -> Pelkat PT
        };

        if (kategoriPelkatMap[selectedValue]) {
            pelkatSelect.value = kategoriPelkatMap[selectedValue]; // Atur pilihan otomatis
            pelkatContainer.style.display = "flex";
            pelkatSelect.setAttribute("required", "required");
        } else {
            pelkatContainer.style.display = "none";
            pelkatSelect.value = ""; // Set nilai kosong
            pelkatSelect.removeAttribute("required");
        }
    });
});

//     document.addEventListener("DOMContentLoaded", function() {
//         var kategoriSelect = document.getElementById("kategoripelayan_id");
//         var phmjSection = document.getElementById("phmj_section");
    
//         kategoriSelect.addEventListener("change", function() {
//             var selectedValue = kategoriSelect.options[kategoriSelect.selectedIndex].text; // Ambil teks kategori
            
//             if (selectedValue === "PHMJ") { 
//                 phmjSection.style.display = "flex"; // Munculkan dropdown Jabatan PHMJ
//                 document.getElementById("phmj_id").setAttribute("required", "required");
//             } else {
//                 phmjSection.style.display = "none"; // Sembunyikan dropdown Jabatan PHMJ
//                 document.getElementById("phmj_id").removeAttribute("required");
//             }
//         });
//     });

//     document.addEventListener("DOMContentLoaded", function() {
//     var kategoriSelect = document.getElementById("kategoripelayan_id");
//     var phmjSection = document.getElementById("phmj_section");
//     var isPhmjCheckbox = document.getElementById("is_phmj_section"); // Pastikan elemen checkbox ada di HTML

//     kategoriSelect.addEventListener("change", function() {
//         var selectedValue = kategoriSelect.options[kategoriSelect.selectedIndex].text;

//         // Jika kategori yang dipilih adalah PHMJ, tampilkan dropdown Jabatan PHMJ
//         if (selectedValue === "PHMJ") {
//             phmjSection.style.display = "flex";
//             document.getElementById("phmj_id").setAttribute("required", "required");
//         } else {
//             phmjSection.style.display = "none";
//             document.getElementById("phmj_id").removeAttribute("required");
//         }

//         // Jika kategori yang dipilih adalah Diaken atau Penatua, tampilkan checkbox is_phmj
//         if (selectedValue === "Diaken" || selectedValue === "Penatua") {
//             isPhmjCheckbox.style.display = "flex";
//         } else {
//             isPhmjCheckbox.style.display = "none";
//         }
//     });
// });


//     document.addEventListener("DOMContentLoaded", function() {
//     var kategoriSelect = document.getElementById("kategoripelayan_id");
//     var phmjSection = document.getElementById("phmj_section");
//     var namaText = document.getElementById("nama_text");
//     var namaDropdown = document.getElementById("nama_dropdown");
//     var namaInput = document.getElementById("nama_input");
//     var namaSelect = document.getElementById("nama_select");

//     kategoriSelect.addEventListener("change", function() {
//         var selectedValue = kategoriSelect.options[kategoriSelect.selectedIndex].text;

//         if (selectedValue === "PHMJ") {
//             phmjSection.style.display = "flex"; // Tampilkan dropdown Jabatan PHMJ
//             document.getElementById("phmj_id").setAttribute("required", "required");

//             // Ubah kolom Nama menjadi dropdown
//             namaText.style.display = "none";
//             namaDropdown.style.display = "flex";
//             namaInput.removeAttribute("required");
//             namaSelect.setAttribute("required", "required");
//         } else {
//             phmjSection.style.display = "none"; // Sembunyikan dropdown Jabatan PHMJ
//             document.getElementById("phmj_id").removeAttribute("required");

//             // Kembalikan kolom Nama ke input teks
//             namaText.style.display = "flex";
//             namaDropdown.style.display = "none";
//             namaInput.setAttribute("required", "required");
//             namaSelect.removeAttribute("required");
//         }
//     });
// });

    </script>
    @endpush