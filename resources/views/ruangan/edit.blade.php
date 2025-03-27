@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($ruangan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('pengelolaan-pengguna/ruangan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('pengelolaan-informasi/ruangan/'.$ruangan->ruangan_id) }}" class="form-horizontal">
                    @csrf {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh method PUT -->
                    
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Ruangan Nama<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="ruangan_nama" name="ruangan_nama" value="{{ old('ruangan_nama', $ruangan->ruangan_nama) }}" required>
                            @error('ruangan_nama')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Deskripsi<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi" value="{{ old('deskripsi', $ruangan->deskripsi) }}" required>
                            @error('deskripsi')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Foto</label>
                        <div class="col-md-11">
                            
                                @if($ruangan->foto)
                                    <div class="mb-2">
                                        <span class="text-danger">Foto sudah ada di sistem: </span>
                                        <a href="{{ Storage::url('images/ruangan/' . $ruangan->foto) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="nav-icon far fa-eye"></i> Lihat Foto
                                        </a>
                                    </div>
                                @endif
                                <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>   
                                <input type="file" class="form-control" id="foto" name="foto" accept=".jpg,.jpeg,.png" value="{{ old('foto', $ruangan->foto) }}">
                                @if($ruangan->foto)
                                    <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                                @endif

                            @error('foto')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label"></label>
                        <div class="col-md-11">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/ruangan') }}">Kembali</a>
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