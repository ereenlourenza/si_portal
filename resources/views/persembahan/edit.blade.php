@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($persembahan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('pengelolaan-pengguna/persembahan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('pengelolaan-informasi/persembahan/'.$persembahan->persembahan_id) }}" class="form-horizontal">
                    @csrf {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh method PUT -->
                    
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Persembahan Nama<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="persembahan_nama" name="persembahan_nama" value="{{ old('persembahan_nama', $persembahan->persembahan_nama) }}" required>
                            @error('persembahan_nama')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Nomor Rekening<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nomor_rekening" name="nomor_rekening" value="{{ old('nomor_rekening', $persembahan->nomor_rekening) }}" required>
                            @error('nomor_rekening')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Atas Nama<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="atas_nama" name="atas_nama" value="{{ old('atas_nama', $persembahan->atas_nama) }}" required>
                            @error('atas_nama')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Barcode</label>
                        <div class="col-md-11">
                            
                                @if($persembahan->barcode)
                                    <div class="mb-2">
                                        <span class="text-danger">Barcode sudah ada di sistem: </span>
                                        <a href="{{ Storage::url('images/barcode/' . $persembahan->barcode) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="nav-icon far fa-eye"></i> Lihat Barcode
                                        </a>
                                    </div>
                                @endif
                                <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>   
                                <input type="file" class="form-control" id="barcode" name="barcode" accept=".jpg,.jpeg,.png" value="{{ old('barcode', $persembahan->barcode) }}">
                                @if($persembahan->barcode)
                                    <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti barcode</small>
                                @endif

                            @error('barcode')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label"></label>
                        <div class="col-md-11">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/persembahan') }}">Kembali</a>
                        </div>
                    </div>
                </form>
            @endempty
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush