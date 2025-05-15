@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('pengelolaan-informasi/sektor') }}" class="form-horizontal" enctype='multipart/form-data'>
                @csrf
                
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Sektor Nama<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="sektor_nama" name="sektor_nama" value="{{ old('sektor_nama') }}" required>
                        
                        @error('sektor_nama')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-1 control-label col-form-label">Deskripsi<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <textarea type="text" class="form-control" id="deskripsi" name="deskripsi" value="{{ old('deskripsi') }}" required></textarea>
                        
                        @error('deskripsi')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Jumlah Jemaat<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="number" class="form-control" id="jumlah_jemaat" name="jumlah_jemaat" required>
                        
                        @error('jumlah_jemaat')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-1 control-label col-form-label">Koordinator Sektor<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <select class="form-control" id="pelayan_id" name="pelayan_id" required>
                            <option value="" selected>Pilih Koordinator Sektor</option>
                            @foreach($pelayan as $item)
                                <option value="{{ $item->pelayan_id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        
                        @error('pelayan_id')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-1 control-label col-form-label"></label>
                    <div class="col-md-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/sektor') }}">Kembali</a>
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