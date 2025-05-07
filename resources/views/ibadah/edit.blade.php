@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($ibadah)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('pengelolaan-informasi/ibadah') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('pengelolaan-informasi/ibadah/'.$ibadah->ibadah_id) }}" class="form-horizontal" enctype='multipart/form-data'>
                    @csrf {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh method PUT -->
                    <div class="form-group row">
                        <label class="col-md-1 control-label col-form-label">Kategori Ibadah<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <select class="form-control" id="kategoriibadah_id" name="kategoriibadah_id" required>
                                <option value="" disabled>Pilih Kategori</option>
                                    @foreach($kategoriibadah as $item)
                                        <option value="{{ $item->kategoriibadah_id }}" 
                                            @if($item->kategoriibadah_id == $ibadah->kategoriibadah_id) selected 
                                            @endif>{{ $item->kategoriibadah_nama }}
                                        </option>
                                    @endforeach
                            </select>

                            @error('kategoriibadah_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tanggal<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', $ibadah->tanggal) }}" required>
                            
                            @error('tanggal')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Waktu<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="time" class="form-control" id="waktu" name="waktu" value="{{ old('waktu', \Carbon\Carbon::parse($ibadah->waktu)->format('H:i')) }}" required>
                            @error('waktu')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tempat</label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="tempat" name="tempat" value="{{ old('tempat', $ibadah->tempat) }}">
                            @error('tempat')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Lokasi</label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="lokasi" name="lokasi" value="{{ old('lokasi', $ibadah->lokasi) }}">
                            @error('lokasi')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Sektor</label>
                        <div class="col-md-11">
                            <input type="number" class="form-control" id="sektor" name="sektor" value="{{ old('sektor', $ibadah->sektor) }}">
                            @error('sektor')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Nama Pelkat</label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nama_pelkat" name="nama_pelkat" value="{{ old('nama_pelkat', $ibadah->nama_pelkat) }}">
                            @error('nama_pelkat')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Ruang</label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="ruang" name="ruang" value="{{ old('ruang', $ibadah->ruang) }}">
                            @error('ruang')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Pelayan Firman</label>
                        <div class="col-md-11">
                            <select class="form-control select2" id="pelayan_firman" name="pelayan_firman">
                                <option value="" disabled>Pilih atau Ketik Manual</option>
                                @foreach($pelayan as $item)
                                    <option value="{{ $item->nama }}" 
                                        @if(old('pelayan_firman', $ibadah->pelayan_firman) == $item->nama) selected @endif>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pelayan_firman')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label"></label>
                        <div class="col-md-11">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/ibadah') }}">Kembali</a>
                        </div>
                    </div>
                </form>
            @endempty
        </div>
    </div>
@endsection

@push('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
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
</style>
@endpush

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        function toggleFields() {
            var kategoriIbadah = $('#kategoriibadah_id').val();
            
            // Sembunyikan semua field yang nullable di awal
            $('#lokasi, #sektor, #nama_pelkat, #ruang').closest('.form-group').hide();

            // Tampilkan field sesuai dengan kategori ibadah yang dipilih
            if (kategoriIbadah == 1) { // Ibadah Minggu
                // $('#lokasi').closest('.form-group').show();
                // $('#sektor').closest('.form-group').show();
                // $('#nama_pelkat').closest('.form-group').show();
                // $('#ruang').closest('.form-group').show();
            } else if (kategoriIbadah == 2 || kategoriIbadah == 4 || kategoriIbadah == 5) { // Ibadah Keluarga
                $('#lokasi').closest('.form-group').show();
                $('#sektor').closest('.form-group').show();
            } else if (kategoriIbadah == 3) { // Ibadah Keluarga
                $('#nama_pelkat').closest('.form-group').show();
                $('#ruang').closest('.form-group').show();
            } 
        }

        // Panggil fungsi saat halaman dimuat (untuk handle old value jika ada error validation)
        toggleFields();

        // Panggil fungsi saat kategori ibadah berubah
        $('#kategoriibadah_id').change(function() {
            toggleFields();
        });

        $(document).ready(function() {
            $('#pelayan_firman').select2({
                tags: true, // Memungkinkan input manual
                placeholder: "Pilih atau Ketik Manual",
                allowClear: true, // Menambahkan tombol clear
                width: '100%' // Menyesuaikan lebar dropdown dengan elemen form
            });
        });

        

    });

</script>

@endpush