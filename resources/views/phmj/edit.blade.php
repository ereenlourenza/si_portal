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
                <form method="POST" action="{{ url('pengelolaan-informasi/phmj/'.$phmj->phmj_id) }}" class="form-horizontal" enctype='multipart/form-data'>
                    @csrf {!! method_field('PUT') !!} <!-- tambahkan baris ini untuk proses edit yang butuh method PUT -->

                    <div class="form-group row">
                        <label class="col-md-1 control-label col-form-label">Pelayan<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <select class="form-control" id="pelayan_id" name="pelayan_id_disabled" required disabled>
                                <option value="">Pilih Diaken/Penatua</option>
                                @foreach($pelayan as $item)
                                    <option value="{{ $item->pelayan_id }}" 
                                        @if($item->pelayan_id == $phmj->pelayan_id) selected 
                                        @endif>{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Input hidden untuk mengirimkan data ke backend -->
                            <input type="hidden" name="pelayan_id" value="{{ $phmj->pelayan_id }}">
                            
                            @error('pelayan_id')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Jabatan<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <select class="form-control" id="jabatan" name="jabatan">
                                <option value="" disabled {{ old('jabatan', $phmj->jabatan ?? '') == '' ? 'selected' : '' }}>Pilih Jabatan</option>
                                <option value="Ketua 1" {{ old('jabatan', $phmj->jabatan ?? '') == 'Ketua 1' ? 'selected' : '' }}>Ketua 1</option>
                                <option value="Ketua 2" {{ old('jabatan', $phmj->jabatan ?? '') == 'Ketua 2' ? 'selected' : '' }}>Ketua 2</option>
                                <option value="Ketua 3" {{ old('jabatan', $phmj->jabatan ?? '') == 'Ketua 3' ? 'selected' : '' }}>Ketua 3</option>
                                <option value="Ketua 4" {{ old('jabatan', $phmj->jabatan ?? '') == 'Ketua 4' ? 'selected' : '' }}>Ketua 4</option>
                                <option value="Ketua 5" {{ old('jabatan', $phmj->jabatan ?? '') == 'Ketua 5' ? 'selected' : '' }}>Ketua 5</option>
                                <option value="Sekretaris Umum" {{ old('jabatan', $phmj->jabatan ?? '') == 'Sekretaris Umum' ? 'selected' : '' }}>Sekretaris Umum</option>
                                <option value="Sekretaris 1" {{ old('jabatan', $phmj->jabatan ?? '') == 'Sekretaris 1' ? 'selected' : '' }}>Sekretaris 1</option>
                                <option value="Sekretaris 2" {{ old('jabatan', $phmj->jabatan ?? '') == 'Sekretaris 2' ? 'selected' : '' }}>Sekretaris 2</option>
                                <option value="Bendahara 1" {{ old('jabatan', $phmj->jabatan ?? '') == 'Bendahara 1' ? 'selected' : '' }}>Bendahara 1</option>
                                <option value="Bendahara 2" {{ old('jabatan', $phmj->jabatan ?? '') == 'Bendahara 2' ? 'selected' : '' }}>Bendahara 2</option>
                            </select>
                            
                            @error('jabatan')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Periode Mulai<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <select class="form-control" id="periode_mulai" name="periode_mulai" required>
                                <option value="" disabled>Pilih Tahun</option>
                                @for ($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}" 
                                        {{ (old('periode_mulai', $phmj->periode_mulai ?? '') == $year) ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                                                    
                            @error('periode_mulai')
                                <small class="form-text text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                        <label class="col-md-1 control-label col-form-label">Periode Selesai<span class="text-danger">*</span></label>
                        <div class="col-md-11">
                            <select class="form-control" id="periode_selesai" name="periode_selesai" required>
                                <option value="" disabled>Pilih Tahun</option>
                                @for ($year = date('Y'); $year >= 1900; $year--)
                                    <option value="{{ $year }}" 
                                        {{ (old('periode_selesai', $phmj->periode_selesai ?? '') == $year) ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
    
                            @error('periode_selesai')
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