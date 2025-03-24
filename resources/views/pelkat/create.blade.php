@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ url('pengelolaan-informasi/pelkat') }}" class="form-horizontal" enctype='multipart/form-data'>
                @csrf
                
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Pelkat Nama<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="pelkat_nama" name="pelkat_nama" value="{{ old('pelkat_nama') }}" required>
                        
                        @error('pelkat_nama')
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
                <div class="form-group row">
                    <label class="col-md-1 control-label col-form-label"></label>
                    <div class="col-md-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/pelkat') }}">Kembali</a>
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