@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pendaftaran.store', ['jenis' => 'baptis']) }}" class="form-horizontal" enctype='multipart/form-data'>
                @csrf
                <div class="form-group row align-items-center">
                    <b class="col-md-3">DATA ANAK</b>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Nama Lengkap<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                        
                        @error('nama_lengkap')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tempat Lahir<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                        
                        @error('tempat_lahir')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tanggal Lahir<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                        
                        @error('tanggal_lahir')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Jenis Kelamin<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L" selected>Laki-Laki</option>
                            <option value="P" >Perempuan</option>
                            
                        </select>
                        
                        @error('jenis_kelamin')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <b class="col-md-3">DATA ORANG TUA</b>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Nama Ayah<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah') }}" required>
                        
                        @error('nama_ayah')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Nama Ibu<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu') }}" required>
                        
                        @error('nama_ibu')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tempat Pernikahan<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="tempat_pernikahan" name="tempat_pernikahan" value="{{ old('tempat_pernikahan') }}" required>
                        
                        @error('tempat_pernikahan')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tanggal Pernikahan<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="date" class="form-control" id="tanggal_pernikahan" name="tanggal_pernikahan" value="{{ old('tanggal_pernikahan') }}" required>
                        
                        @error('tanggal_pernikahan')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tempat Sidi Ayah<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="tempat_sidi_ayah" name="tempat_sidi_ayah" value="{{ old('tempat_sidi_ayah') }}" required>
                        
                        @error('tempat_sidi_ayah')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tanggal Sidi Ayah<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="date" class="form-control" id="tanggal_sidi_ayah" name="tanggal_sidi_ayah" value="{{ old('tanggal_sidi_ayah') }}" required>
                        
                        @error('tanggal_sidi_ayah')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tempat Sidi Ibu<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="tempat_sidi_ibu" name="tempat_sidi_ibu" value="{{ old('tempat_sidi_ibu') }}" required>
                        
                        @error('tempat_sidi_ibu')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tanggal Sidi Ibu<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="date" class="form-control" id="tanggal_sidi_ibu" name="tanggal_sidi_ibu" value="{{ old('tanggal_sidi_ibu') }}" required>
                        
                        @error('tanggal_sidi_ibu')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Alamat<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat') }}" required>
                        
                        @error('alamat')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Nomor Telepon<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required>
                        
                        @error('nomor_telepon')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <b class="col-md-3">PELAYANAN BAPTISAN</b>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tanggal Baptis<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="date" class="form-control" id="tanggal_baptis" name="tanggal_baptis" value="{{ old('tanggal_baptis') }}" required>
                        
                        @error('tanggal_baptis')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Dilayani Oleh<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="dilayani" name="dilayani" value="{{ old('dilayani') }}" required>
                        
                        @error('dilayani')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <b class="col-md-3">LAMPIRAN</b>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Surat Nikah Orang Tua<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="surat_nikah_ortu" name="surat_nikah_ortu" accept=".jpg,.jpeg,.png" value="{{ old('surat_nikah_ortu') }}" required>
                        
                        @error('surat_nikah_ortu')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Akta Kelahiran Anak<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="akta_kelahiran_anak" name="akta_kelahiran_anak" accept=".jpg,.jpeg,.png" value="{{ old('akta_kelahiran_anak') }}" required>
                        
                        @error('akta_kelahiran_anak')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label"></label>
                    <div class="col-md-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/pendaftaran') }}">Kembali</a>
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
