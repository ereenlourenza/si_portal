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
                        <select class="form-control" id="pelayan_id" name="pelayan_id" required>
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
                            <option value="Ketua 1">Ketua 1</option>
                            <option value="Ketua 2">Ketua 2</option>
                            <option value="Ketua 3">Ketua 3</option>
                            <option value="Ketua 4">Ketua 4</option>
                            <option value="Ketua 5">Ketua 5</option>
                            <option value="Sekretaris Umum">Sekretaris Umum</option>
                            <option value="Sekretaris 1">Sekretaris 1</option>
                            <option value="Sekretaris 2">Sekretaris 2</option>
                            <option value="Bendahara 1">Bendahara 1</option>
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
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Periode Mulai<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <select class="form-control" id="periode_mulai" name="periode_mulai" required>
                            <option value="">Pilih Tahun</option>
                            @for ($year = date('Y'); $year >= 1900; $year--)
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
                            @for ($year = date('Y'); $year >= 1900; $year--)
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
@endpush

@push('js')
@endpush