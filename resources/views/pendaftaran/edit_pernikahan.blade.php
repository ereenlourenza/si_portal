@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($pendaftaran)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('pengelolaan-informasi/pendaftaran') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
            {{-- <pre>{{ dd($pendaftaran) }}</pre> --}}
                <form method="POST" action="{{ url('pengelolaan-informasi/pendaftaran/'.$pendaftaran->pernikahan_id) }}" class="form-horizontal" enctype='multipart/form-data'>
                    @csrf {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh method PUT -->
                    <input type="hidden" name="jenis" value="pernikahan">
                    
                    <div class="form-group row align-items-center">
                        <b class="col-md-3">CALON MEMPELAI PRIA</b>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Nama Lengkap<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nama_lengkap_pria" name="nama_lengkap_pria" value="{{ old('nama_lengkap_pria', $pendaftaran->nama_lengkap_pria) }}" required>
                            
                            @error('nama_lengkap_pria')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tempat Lahir<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="tempat_lahir_pria" name="tempat_lahir_pria" value="{{ old('tempat_lahir_pria', $pendaftaran->tempat_lahir_pria) }}" required>
                            
                            @error('tempat_lahir_pria')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tanggal Lahir<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="date" class="form-control" id="tanggal_lahir_pria" name="tanggal_lahir_pria" value="{{ old('tanggal_lahir_pria', $pendaftaran->tanggal_lahir_pria) }}" required>
                            
                            @error('tanggal_lahir_pria')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tempat Sidi<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="tempat_sidi_pria" name="tempat_sidi_pria" value="{{ old('tempat_sidi_pria', $pendaftaran->tempat_sidi_pria) }}" required>
                            
                            @error('tempat_sidi_pria')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tanggal Sidi<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="date" class="form-control" id="tanggal_sidi_pria" name="tanggal_sidi_pria" value="{{ old('tanggal_sidi_pria', $pendaftaran->tanggal_sidi_pria) }}" required>
                            
                            @error('tanggal_sidi_pria')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Pekerjaan<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="pekerjaan_pria" name="pekerjaan_pria" value="{{ old('pekerjaan_pria', $pendaftaran->pekerjaan_pria) }}" required>
                            
                            @error('pekerjaan_pria')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Alamat<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="alamat_pria" name="alamat_pria" value="{{ old('alamat_pria', $pendaftaran->alamat_pria) }}" required>
                            
                            @error('alamat_pria')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">No Telp<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nomor_telepon_pria" name="nomor_telepon_pria" value="{{ old('nomor_telepon_pria', $pendaftaran->nomor_telepon_pria) }}" required>
                            
                            @error('nomor_telepon_pria')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Nama Ayah<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nama_ayah_pria" name="nama_ayah_pria" value="{{ old('nama_ayah_pria', $pendaftaran->nama_ayah_pria) }}" required>
                            
                            @error('nama_ayah_pria')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Nama Ibu<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nama_ibu_pria" name="nama_ibu_pria" value="{{ old('nama_ibu_pria', $pendaftaran->nama_ibu_pria) }}" required>
                            
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
                            <input type="text" class="form-control" id="nama_lengkap_wanita" name="nama_lengkap_wanita" value="{{ old('nama_lengkap_wanita', $pendaftaran->nama_lengkap_wanita) }}" required>
                            
                            @error('nama_lengkap_wanita')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tempat Lahir<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="tempat_lahir_wanita" name="tempat_lahir_wanita" value="{{ old('tempat_lahir_wanita', $pendaftaran->tempat_lahir_wanita) }}" required>
                            
                            @error('tempat_lahir_wanita')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tanggal Lahir<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="date" class="form-control" id="tanggal_lahir_wanita" name="tanggal_lahir_wanita" value="{{ old('tanggal_lahir_wanita', $pendaftaran->tanggal_lahir_wanita) }}" required>
                            
                            @error('tanggal_lahir_wanita')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tempat Sidi<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="tempat_sidi_wanita" name="tempat_sidi_wanita" value="{{ old('tempat_sidi_wanita', $pendaftaran->tempat_sidi_wanita) }}" required>
                            
                            @error('tempat_sidi_wanita')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tanggal Sidi<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="date" class="form-control" id="tanggal_sidi_wanita" name="tanggal_sidi_wanita" value="{{ old('tanggal_sidi_wanita', $pendaftaran->tanggal_sidi_wanita) }}" required>
                            
                            @error('tanggal_sidi_wanita')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Pekerjaan<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="pekerjaan_wanita" name="pekerjaan_wanita" value="{{ old('pekerjaan_wanita', $pendaftaran->pekerjaan_wanita) }}" required>
                            
                            @error('pekerjaan_wanita')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Alamat<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="alamat_wanita" name="alamat_wanita" value="{{ old('alamat_wanita', $pendaftaran->alamat_wanita) }}" required>
                            
                            @error('alamat_wanita')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">No Telp<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nomor_telepon_wanita" name="nomor_telepon_wanita" value="{{ old('nomor_telepon_wanita', $pendaftaran->nomor_telepon_wanita) }}" required>
                            
                            @error('nomor_telepon_wanita')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Nama Ayah<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nama_ayah_wanita" name="nama_ayah_wanita" value="{{ old('nama_ayah_wanita', $pendaftaran->nama_ayah_wanita) }}" required>
                            
                            @error('nama_ayah_wanita')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Nama Ibu<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nama_ibu_wanita" name="nama_ibu_wanita" value="{{ old('nama_ibu_wanita', $pendaftaran->nama_ibu_wanita) }}" required>
                            
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
                            <input type="date" class="form-control" id="tanggal_pernikahan" name="tanggal_pernikahan" value="{{ old('tanggal_pernikahan', $pendaftaran->tanggal_pernikahan) }}" required>
                            
                            @error('tanggal_pernikahan')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Waktu Pernikahan<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="time" class="form-control" id="waktu_pernikahan" name="waktu_pernikahan" value="{{ old('waktu_pernikahan', \Carbon\Carbon::parse($pendaftaran->waktu_pernikahan)->format('H:i')) }}" required>
                            
                            @error('waktu_pernikahan')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Dilayani Oleh<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="dilayani" name="dilayani" value="{{ old('dilayani', $pendaftaran->dilayani) }}" required>
                            
                            @error('dilayani')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group row align-items-center">
                        <b class="col-md-5">LAMPIRAN</b>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">KARTU TANDA KEPENDUDUKAN<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->ktp)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->ktp) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="ktp" name="ktp" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->ktp)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('ktp')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Kartu Keluarga<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->kk)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->kk) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="kk" name="kk" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->kk)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('kk')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Surat Sidi<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->surat_sidi)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->surat_sidi) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="surat_sidi" name="surat_sidi" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->surat_sidi)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('surat_sidi')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Akta Kelahiran<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->akta_kelahiran)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->akta_kelahiran) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="akta_kelahiran" name="akta_kelahiran" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->akta_kelahiran)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('akta_kelahiran')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Surat Keterangan Nikah (N1)<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->sk_nikah)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->sk_nikah) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="sk_nikah" name="sk_nikah" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->sk_nikah)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('sk_nikah')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Surat Keterangan Asal Usul (N2)<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->sk_asalusul)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->sk_asalusul) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="sk_asalusul" name="sk_asalusul" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->sk_asalusul)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('sk_asalusul')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Surat Persetujuan Mempelai (N3)<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->sp_mempelai)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->sp_mempelai) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="sp_mempelai" name="sp_mempelai" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->sp_mempelai)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('sp_mempelai')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Surat Keterangan Orang Tua (N4)<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->sk_ortu)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->sk_ortu) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="sk_ortu" name="sk_ortu" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->sk_ortu)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('sk_ortu')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Akta Perceraian/Kematian<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->akta_perceraian_kematian)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->akta_perceraian_kematian) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="akta_perceraian_kematian" name="akta_perceraian_kematian" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->akta_perceraian_kematian)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('akta_perceraian_kematian')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Surat Ijin Kawin Komandan/Kepala (TNI-Polri)<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->si_kawin_komandan)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->si_kawin_komandan) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="si_kawin_komandan" name="si_kawin_komandan" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->si_kawin_komandan)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('si_kawin_komandan')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Surat Pelimpahan Dari Gereja Asal<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->sp_gereja_asal)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->sp_gereja_asal) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="sp_gereja_asal" name="sp_gereja_asal" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->sp_gereja_asal)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('sp_gereja_asal')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Foto Berwarna Berdampingan 4x6<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->foto)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->foto) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="foto" name="foto" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->foto)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('foto')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Bukti Pembayaran Biaya Administrasi<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->biaya)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/pernikahan/' . $pendaftaran->biaya) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="biaya" name="biaya" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->biaya)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

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
            @endempty
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush