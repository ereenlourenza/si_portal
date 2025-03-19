@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($tataibadah)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('pengelolaan-informasi/tataibadah') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('pengelolaan-informasi/tataibadah/'.$tataibadah->tataibadah_id) }}" class="form-horizontal" enctype='multipart/form-data'>
                    @csrf {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh method PUT -->
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tanggal<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal', $tataibadah->tanggal) }}" required>
                            
                            @error('tanggal')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Judul<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="judul" name="judul" value="{{ old('judul', $tataibadah->judul) }}" required>
                            @error('judul')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Deskripsi</label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi" value="{{ old('deskripsi', $tataibadah->deskripsi) }}">
                            @error('deskripsi')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">File</label>
                        <div class="col-md-11">
                            
                                @if($tataibadah->file)
                                    <div class="mb-2">
                                        <span class="text-danger">File sudah ada di sistem: </span>
                                        <a href="{{ Storage::url('dokumen/tataibadah/' . $tataibadah->file) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="nav-icon far fa-eye"></i> Lihat File
                                        </a>
                                    </div>
                                @endif   
                                <input type="file" class="form-control" id="file" name="file" accept=".pdf" value="{{ old('file', $tataibadah->file) }}">
                                
                                <small class="form-text text-muted">.pdf (max:5MB)</small>

                                @if($tataibadah->file)
                                    <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti file</small>
                                @endif

                            @error('file')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label"></label>
                        <div class="col-md-11">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/tataibadah') }}">Kembali</a>
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