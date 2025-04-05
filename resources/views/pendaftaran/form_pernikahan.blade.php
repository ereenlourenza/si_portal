@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pendaftaran.store', ['jenis' => 'pernikahan']) }}" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="form-group row align-items-center">
                    <b class="col-md-3">CALON MEMPELAI PRIA</b>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Nama Lengkap<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nama_lengkap_pria" name="nama_lengkap_pria" value="{{ old('nama_lengkap_pria') }}" required>
                        
                        @error('nama_lengkap_pria')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tempat Lahir<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="tempat_lahir_pria" name="tempat_lahir_pria" value="{{ old('tempat_lahir_pria') }}" required>
                        
                        @error('tempat_lahir_pria')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tanggal Lahir<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="date" class="form-control" id="tanggal_lahir_pria" name="tanggal_lahir_pria" value="{{ old('tanggal_lahir_pria') }}" required>
                        
                        @error('tanggal_lahir_pria')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tempat Sidi<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="tempat_sidi_pria" name="tempat_sidi_pria" value="{{ old('tempat_sidi_pria') }}" required>
                        
                        @error('tempat_sidi_pria')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tanggal Sidi<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="date" class="form-control" id="tanggal_sidi_pria" name="tanggal_sidi_pria" value="{{ old('tanggal_sidi_pria') }}" required>
                        
                        @error('tanggal_sidi_pria')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Pekerjaan<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="pekerjaan_pria" name="pekerjaan_pria" value="{{ old('pekerjaan_pria') }}" required>
                        
                        @error('pekerjaan_pria')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Alamat<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="alamat_pria" name="alamat_pria" value="{{ old('alamat_pria') }}" required>
                        
                        @error('alamat_pria')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Nomor Telepon<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nomor_telepon_pria" name="nomor_telepon_pria" value="{{ old('nomor_telepon_pria') }}" required>
                        
                        @error('nomor_telepon_pria')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Nama Ayah<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nama_ayah_pria" name="nama_ayah_pria" value="{{ old('nama_ayah_pria') }}" required>
                        
                        @error('nama_ayah_pria')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Nama Ibu<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nama_ibu_pria" name="nama_ibu_pria" value="{{ old('nama_ibu_pria') }}" required>
                        
                        @error('nama_ibu_pria')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <b class="col-md-3">CALON MEMPELAI WANITA</b>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Nama Lengkap<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nama_lengkap_wanita" name="nama_lengkap_wanita" value="{{ old('nama_lengkap_wanita') }}" required>
                        
                        @error('nama_lengkap_wanita')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tempat Lahir<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="tempat_lahir_wanita" name="tempat_lahir_wanita" value="{{ old('tempat_lahir_wanita') }}" required>
                        
                        @error('tempat_lahir_wanita')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tanggal Lahir<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="date" class="form-control" id="tanggal_lahir_wanita" name="tanggal_lahir_wanita" value="{{ old('tanggal_lahir_wanita') }}" required>
                        
                        @error('tanggal_lahir_wanita')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tempat Sidi<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="tempat_sidi_wanita" name="tempat_sidi_wanita" value="{{ old('tempat_sidi_wanita') }}" required>
                        
                        @error('tempat_sidi_wanita')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tanggal Sidi<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="date" class="form-control" id="tanggal_sidi_wanita" name="tanggal_sidi_wanita" value="{{ old('tanggal_sidi_wanita') }}" required>
                        
                        @error('tanggal_sidi_wanita')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Pekerjaan<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="pekerjaan_wanita" name="pekerjaan_wanita" value="{{ old('pekerjaan_wanita') }}" required>
                        
                        @error('pekerjaan_wanita')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Alamat<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="alamat_wanita" name="alamat_wanita" value="{{ old('alamat_wanita') }}" required>
                        
                        @error('alamat_wanita')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Nomor Telepon<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nomor_telepon_wanita" name="nomor_telepon_wanita" value="{{ old('nomor_telepon_wanita') }}" required>
                        
                        @error('nomor_telepon_wanita')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Nama Ayah<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nama_ayah_wanita" name="nama_ayah_wanita" value="{{ old('nama_ayah_wanita') }}" required>
                        
                        @error('nama_ayah_wanita')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Nama Ibu<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="nama_ibu_wanita" name="nama_ibu_wanita" value="{{ old('nama_ibu_wanita') }}" required>
                        
                        @error('nama_ibu_wanita')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <b class="col-md-5">PELAYANAN PEMBERKATAN NIKAH</b>
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
                    <label class="col-md-1 control-label col-form-label">Waktu Pernikahan<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="time" class="form-control" id="waktu_pernikahan" name="waktu_pernikahan" value="{{ old('waktu_pernikahan') }}" required>
                        
                        @error('waktu_pernikahan')
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
                    <label class="col-md-1 control-label col-form-label">Kartu Tanda Penduduk<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="ktp" name="ktp" accept=".jpg,.jpeg,.png" value="{{ old('ktp') }}" required>
                        
                        @error('ktp')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Kartu Keluarga<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="kk" name="kk" accept=".jpg,.jpeg,.png" value="{{ old('kk') }}" required>
                        
                        @error('kk')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Surat Sidi<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="surat_sidi" name="surat_sidi" accept=".jpg,.jpeg,.png" value="{{ old('surat_sidi') }}" required>
                        
                        @error('surat_sidi')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Akta Kelahiran<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="akta_kelahiran" name="akta_kelahiran" accept=".jpg,.jpeg,.png" value="{{ old('akta_kelahiran') }}" required>
                        
                        @error('akta_kelahiran')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Surat Keterangan Nikah (N1)<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="sk_nikah" name="sk_nikah" accept=".jpg,.jpeg,.png" value="{{ old('sk_nikah') }}" required>
                        
                        @error('sk_nikah')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Surat Keterangan Asal Usul (N2)<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="sk_asalusul" name="sk_asalusul" accept=".jpg,.jpeg,.png" value="{{ old('sk_asalusul') }}" required>
                        
                        @error('sk_asalusul')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Surat Persetujuan Mempelai (N3)<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="sp_mempelai" name="sp_mempelai" accept=".jpg,.jpeg,.png" value="{{ old('sp_mempelai') }}" required>
                        
                        @error('sp_mempelai')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Surat Keterangan Orang Tua (N4)<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="sk_ortu" name="sk_ortu" accept=".jpg,.jpeg,.png" value="{{ old('sk_ortu') }}" required>
                        
                        @error('sk_ortu')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Akta Perceraian/Kematian<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="akta_perceraian_kematian" name="akta_perceraian_kematian" accept=".jpg,.jpeg,.png" value="{{ old('akta_perceraian_kematian') }}" required>
                        
                        @error('akta_perceraian_kematian')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Surat Ijin Kawin Komandan/Kepala (TNI-Polri)<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="si_kawin_komandan" name="si_kawin_komandan" accept=".jpg,.jpeg,.png" value="{{ old('si_kawin_komandan') }}" required>
                        
                        @error('si_kawin_komandan')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Surat Pelimpahan Dari Gereja Asal<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="sp_gereja_asal" name="sp_gereja_asal" accept=".jpg,.jpeg,.png" value="{{ old('sp_gereja_asal') }}" required>
                        
                        @error('sp_gereja_asal')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Foto Berwarna Berdampingan 4x6<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="foto" name="foto" accept=".jpg,.jpeg,.png" value="{{ old('foto') }}" required>
                        
                        @error('foto')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Bukti Pembayaran Biaya Administrasi<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="file" class="form-control" id="biaya" name="biaya" accept=".jpg,.jpeg,.png" value="{{ old('biaya') }}" required>
                        
                        @error('biaya')
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
