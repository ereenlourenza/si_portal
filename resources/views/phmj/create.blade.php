@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('pengelolaan-informasi/phmj') }}" class="form-horizontal" enctype='multipart/form-data'>
                @csrf
                <div class="form-group row">
                    <label class="col-md-1 control-label col-form-label">Pelayan<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <select class="form-control select2" id="pelayan_id" name="pelayan_id" required>
                            <option value="">Pilih Diaken/Penatua</option>
                            {{-- @foreach($pelayan as $item)
                                <option value="{{ $item->pelayan_id }}">{{ $item->nama }}</option>
                            @endforeach --}}
                            @foreach($pelayan as $item)
                                <option value="{{ $item->pelayan_id }}" 
                                    {{ in_array($item->pelayan_id, $pemakaiPelayan) ? 'disabled' : '' }}>
                                    {{ $item->nama }}
                                </option>
                            @endforeach
                        </select>
                        
                        @error('pelayan_id')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Jabatan<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <select class="form-control" id="jabatan" name="jabatan">
                            <option value="" disabled selected>Pilih Jabatan</option>
                            <option value="Ketua Majelis Jemaat">Ketua Majelis Jemaat</option>
                            <option value="Ketua 1">Ketua 1</option>
                            <option value="Ketua 2">Ketua 2</option>
                            <option value="Ketua 3">Ketua 3</option>
                            <option value="Ketua 4">Ketua 4</option>
                            <option value="Ketua 5">Ketua 5</option>
                            <option value="Sekretaris Umum">Sekretaris</option>
                            <option value="Sekretaris 1">Sekretaris 1</option>
                            <option value="Sekretaris 2">Sekretaris 2</option>
                            <option value="Bendahara 1">Bendahara</option>
                            <option value="Bendahara 2">Bendahara 2</option>
                        </select>
                        @error('jabatan')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                {{-- <div class="form-group row">
                    <label class="col-md-1 control-label col-form-label">Periode Mulai<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="periode_mulai" periode_mulai="periode_mulai" value="{{ old('periode_mulai') }}" required>
                        
                        @error('periode_mulai')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-1 control-label col-form-label">Periode Selesai<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="periode_selesai" periode_selesai="periode_selesai" value="{{ old('periode_selesai') }}" required>
                        
                        @error('periode_selesai')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div> --}}
                @php
                    $currentYear = date('Y');
                    $futureYear = $currentYear + 5; // Bisa diganti sesuai kebutuhan
                @endphp
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Periode Mulai<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <select class="form-control" id="periode_mulai" name="periode_mulai" required>
                            <option value="">Pilih Tahun</option>
                            @for ($year = $futureYear; $year >= 1900; $year--)
                                <option value="{{ $year }}" {{ old('periode_mulai') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                                                
                        @error('periode_mulai')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Periode Selesai<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <select class="form-control" id="periode_selesai" name="periode_selesai" required>
                            <option value="">Pilih Tahun</option>
                            @for ($year = $futureYear; $year >= 1900; $year--)
                                <option value="{{ $year }}" {{ old('periode_selesai') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>

                        @error('periode_selesai')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-1 control-label col-form-label"></label>
                    <div class="col-md-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/phmj') }}">Kembali</a>
                    </div>
                </div>
            </form>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#pelayan_id').select2({
            tags: false, // Memungkinkan input manual
            placeholder: "Pilih Pelayan",
            allowClear: true, // Menambahkan tombol clear
            width: '100%' // Menyesuaikan lebar dropdown dengan elemen form
        });
    });
</script>
@endpush