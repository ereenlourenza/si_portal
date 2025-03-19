@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($pelayan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
                <a href="{{ url('pengelolaan-informasi/pelayan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
            @else
                <form method="POST" action="{{ url('pengelolaan-informasi/pelayan/'.$pelayan->pelayan_id) }}" class="form-horizontal" enctype='multipart/form-data'>
                    @csrf {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh method PUT -->

                    <div class="form-group row">
                        <label class="col-md-1 control-label col-form-label">Kategori Pelayan<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <select class="form-control" id="kategoripelayan_id" name="kategoripelayan_id" required>
                                <option value="" disabled>Pilih Kategori</option>
                                    @foreach($kategoripelayan as $item)
                                        <option value="{{ $item->kategoripelayan_id }}" 
                                            @if($item->kategoripelayan_id == $pelayan->kategoripelayan_id) selected 
                                            @endif>{{ $item->kategoripelayan_nama }}
                                        </option>
                                    @endforeach
                            </select>

                            @error('kategoripelayan_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Nama<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $pelayan->nama) }}" required>
                            
                            @error('nama')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Masa Jabatan Mulai<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <select class="form-control" id="masa_jabatan_mulai" name="masa_jabatan_mulai" required>
                                <option value="" disabled>Pilih Tahun</option>
                                @for ($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}" 
                                        {{ (old('masa_jabatan_mulai', $pelayan->masa_jabatan_mulai ?? '') == $year) ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                                                    
                            @error('masa_jabatan_mulai')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Masa Jabatan Selesai<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <select class="form-control" id="masa_jabatan_selesai" name="masa_jabatan_selesai" required>
                                <option value="" disabled>Pilih Tahun</option>
                                @for ($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}"     
                                        {{ (old('masa_jabatan_selesai', $pelayan->masa_jabatan_selesai ?? '') == $year) ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                                                    
                            @error('masa_jabatan_selesai')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Keterangan</label>
                        <div class="col-md-11">
                            <input type="text" class="form-control" id="keterangan" name="keterangan" value="{{ old('keterangan', $pelayan->keterangan) }}">
                            
                            @error('keterangan')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Foto</label>
                        <div class="col-md-11">
                            
                                @if($pelayan->foto)
                                    <div class="mb-2">
                                        <span class="text-danger">Foto sudah ada di sistem: </span>
                                        <a href="{{ Storage::url('images/pelayan/' . $pelayan->foto) }}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="nav-icon far fa-eye"></i> Lihat Foto
                                        </a>
                                    </div>
                                @endif
                                <small class="form-text text-muted">.jpg,.jpeg,.png (max:2MB)</small>   
                                <input type="file" class="form-control" id="foto" name="foto" accept=".jpg,.jpeg,.png" value="{{ old('foto', $pelayan->foto) }}">
                                @if($pelayan->foto)
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
                            <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/pelayan') }}">Kembali</a>
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