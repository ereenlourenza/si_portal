@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($user)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('pengelolaan-pengguna/user') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('pengelolaan-pengguna/user/'.$user->user_id) }}" class="form-horizontal" enctype='multipart/form-data'>
                    @csrf {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh method PUT -->
                    <div class="form-group row">
                        <label class="col-md-1 control-label col-form-label">Level<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <select class="form-control" id="level_id" name="level_id" required>
                                <option value="" disabled>Pilih Level</option>
                                    @foreach($level as $item)
                                        <option value="{{ $item->level_id }}" 
                                            @if($item->level_id == $user->level_id) selected 
                                            @endif>{{ $item->level_nama }}
                                        </option>
                                    @endforeach
                            </select>
                            @error('level_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-1 control-label col-form-label">Username<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Nama<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Password<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="password" class="form-control" id="password" name="password">
                            @error('password')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @else
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti password user.</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label"></label>
                        <div class="col-md-11">
                            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                            <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-pengguna/user') }}">Kembali</a>
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