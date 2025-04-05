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
                <form method="POST" action="{{ url('pengelolaan-informasi/pendaftaran/'.$pendaftaran->katekisasi_id) }}" class="form-horizontal" enctype='multipart/form-data'>
                    @csrf {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh method PUT -->
                    <input type="hidden" name="jenis" value="sidi">
                    
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Nama Lengkap<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $pendaftaran->nama_lengkap) }}" required>
                            
                            @error('nama_lengkap')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tempat Lahir<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $pendaftaran->tempat_lahir) }}" required>
                            
                            @error('tempat_lahir')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Tanggal Lahir<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pendaftaran->tanggal_lahir) }}" required>
                            
                            @error('tanggal_lahir')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Jenis Kelamin<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="L" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ old('jenis_kelamin', $pendaftaran->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            
                            @error('jenis_kelamin')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Alamat<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="alamat_katekumen" name="alamat_katekumen" value="{{ old('alamat_katekumen', $pendaftaran->alamat_katekumen) }}" required>
                            
                            @error('alamat_katekumen')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">No Telp<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nomor_telepon_katekumen" name="nomor_telepon_katekumen" value="{{ old('nomor_telepon_katekumen', $pendaftaran->nomor_telepon_katekumen) }}" required>
                            
                            @error('nomor_telepon_katekumen')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Pendidikan Terakhir<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <select class="form-control" id="pendidikan_terakhir" name="pendidikan_terakhir" required>
                                <option value="SD" {{ old('pendidikan_terakhir', $pendaftaran->pendidikan_terakhir) == 'SD' ? 'selected' : '' }}>Sekolah Dasar (SD/Paket A)</option>
                                <option value="SMP" {{ old('pendidikan_terakhir', $pendaftaran->pendidikan_terakhir) == 'SMP' ? 'selected' : '' }}>Sekolah Menengah Pertama (SMP/SLTP/Paket B)</option>
                                <option value="SMA" {{ old('pendidikan_terakhir', $pendaftaran->pendidikan_terakhir) == 'SMA' ? 'selected' : '' }}>Sekolah Menengah Atas/Kejuruan (SMA/SMK/SLTA/Paket C)</option>
                                <option value="D1" {{ old('pendidikan_terakhir', $pendaftaran->pendidikan_terakhir) == 'D1' ? 'selected' : '' }}>Diploma 1 (D1)</option>
                                <option value="D2" {{ old('pendidikan_terakhir', $pendaftaran->pendidikan_terakhir) == 'D2' ? 'selected' : '' }}>Diploma 2 (D2)</option>
                                <option value="D3" {{ old('pendidikan_terakhir', $pendaftaran->pendidikan_terakhir) == 'D3' ? 'selected' : '' }}>Diploma 3 (D3)</option>
                                <option value="D4" {{ old('pendidikan_terakhir', $pendaftaran->pendidikan_terakhir) == 'D4' ? 'selected' : '' }}>Diploma 4 (D4)</option>
                                <option value="S1" {{ old('pendidikan_terakhir', $pendaftaran->pendidikan_terakhir) == 'S1' ? 'selected' : '' }}>Sarjana (S1)</option>
                                <option value="S2" {{ old('pendidikan_terakhir', $pendaftaran->pendidikan_terakhir) == 'S2' ? 'selected' : '' }}>Magister (S2)</option>
                                <option value="S3" {{ old('pendidikan_terakhir', $pendaftaran->pendidikan_terakhir) == 'S3' ? 'selected' : '' }}>Doktor (S3)</option>
                            </select>
                            
                            @error('pendidikan_terakhir')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Pekerjaan<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan', $pendaftaran->pekerjaan) }}" required>
                            
                            @error('pekerjaan')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Status Baptis<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <select class="form-control" id="is_baptis" name="is_baptis" required>
                                <option value="Sudah" {{ old('is_baptis', $pendaftaran->is_baptis) == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                                <option value="Belum" {{ old('is_baptis', $pendaftaran->is_baptis) == 'Belum' ? 'selected' : '' }}>Belum</option>
                                
                            </select>
                            
                            @error('is_baptis')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center group-sudah">
                        <label class="col-md-1 control-label col-form-label">Gereja Tempat Baptis<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="tempat_baptis" name="tempat_baptis" value="{{ old('tempat_baptis', $pendaftaran->tempat_baptis) }}" >
                            
                            @error('tempat_baptis')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center group-sudah">
                        <label class="col-md-1 control-label col-form-label">Nomor Surat Baptis<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="no_surat_baptis" name="no_surat_baptis" value="{{ old('no_surat_baptis', $pendaftaran->no_surat_baptis) }}" >
                            
                            @error('no_surat_baptis')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center group-sudah">
                        <label class="col-md-1 control-label col-form-label">Tanggal Surat Baptis<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="date" class="form-control" id="tanggal_surat_baptis" name="tanggal_surat_baptis" value="{{ old('tanggal_surat_baptis', $pendaftaran->tanggal_surat_baptis) }}" >
                            
                            @error('tanggal_surat_baptis')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center group-sudah">
                        <label class="col-md-1 control-label col-form-label">Dilayani Oleh<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="dilayani" name="dilayani" value="{{ old('dilayani', $pendaftaran->dilayani) }}">
                            
                            @error('dilayani')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center group-belum">
                        <label class="col-md-1 control-label col-form-label">Nama Ayah<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah', $pendaftaran->nama_ayah) }}">
                            
                            @error('nama_ayah')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center group-belum">
                        <label class="col-md-1 control-label col-form-label">Nama Ibu<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu', $pendaftaran->nama_ibu) }}">
                            
                            @error('nama_ibu')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center group-belum">
                        <label class="col-md-1 control-label col-form-label">Alamat Orang Tua<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="alamat_ortu" name="alamat_ortu" value="{{ old('alamat_ortu', $pendaftaran->alamat_ortu) }}" >
                            
                            @error('alamat_ortu')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center group-belum">
                        <label class="col-md-1 control-label col-form-label">No Telp Orang Tua<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nomor_telepon_ortu" name="nomor_telepon_ortu" value="{{ old('nomor_telepon_ortu', $pendaftaran->nomor_telepon_ortu) }}">
                            
                            @error('nomor_telepon_ortu')
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
                                    <a href="{{ Storage::url('images/sidi/' . $pendaftaran->akta_kelahiran) }}" target="_blank" class="btn btn-info btn-sm">
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
                    <div class="form-group row align-items-center group-sudah">
                        <label class="col-md-1 control-label col-form-label">Surat Baptis<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->surat_baptis)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/sidi/' . $pendaftaran->surat_baptis) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="surat_baptis" name="surat_baptis" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->surat_baptis)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('surat_baptis')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Pas Foto<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            @if($pendaftaran->pas_foto)
                                <div class="mb-2">
                                    <span class="text-danger">File sudah ada di sistem: </span>
                                    <a href="{{ Storage::url('images/sidi/' . $pendaftaran->pas_foto) }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="nav-icon far fa-eye"></i> Lihat File
                                    </a>
                                </div>
                            @endif

                            <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>  
                            <input type="file" class="form-control" id="pas_foto" name="pas_foto" accept=".jpg,.jpeg,.png">
                            @if($pendaftaran->pas_foto)
                                <small class="form-text text-muted">Abaikan (jangan diisi) jika tidak ingin mengganti foto</small>
                            @endif

                            @error('pas_foto')
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
    <style>
        .group-baptis, .group-orangtua {
            display: none;
        }
    </style>
@endpush

@push('js')
    <script>
        function toggleBaptisFields() {
            var isBaptis = $('#is_baptis').val();

            if (isBaptis === 'Sudah') {
                $('.group-sudah').show();
                $('.group-belum').hide();

                // Optional: hilangkan required agar validasi tidak error
                $('.group-belum input').prop('required', false);
                $('.group-sudah input').prop('required', true);

                // Pastikan surat baptis tidak required
                $('#surat_baptis').prop('required', false);
            } else {
                $('.group-sudah').hide();
                $('.group-belum').show();

                $('.group-sudah input').prop('required', false);
                $('.group-belum input').prop('required', true);
            }

        }

        $(document).ready(function () {
            toggleBaptisFields(); // initial on page load
            $('#is_baptis').change(toggleBaptisFields); // on change
        });
    </script>
@endpush