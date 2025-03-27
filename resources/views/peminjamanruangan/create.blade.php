@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @if(session('success_peminjamanruangan'))
                <div class='alert alert-success'>{{ session('success_peminjamanruangan' ) }}</div>
            @endif
            @if(session('error_peminjamanruangan'))
                <div class='alert alert-danger'>{{ session('error_peminjamanruangan' ) }}</div>
            @endif
            <form method="POST" action="{{ url('pengelolaan-informasi/peminjamanruangan') }}" class="form-horizontal">
                @csrf
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Peminjam Nama<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="peminjam_nama" name="peminjam_nama" value="{{ old('peminjam_nama') }}" required>
                        
                        @error('peminjam_nama')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Peminjam Telepon<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="peminjam_telepon" name="peminjam_telepon" value="{{ old('peminjam_telepon') }}" required>
                        
                        @error('peminjam_telepon')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Tanggal<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ old('tanggal') }}" required>
                        
                        @error('tanggal')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Waktu Mulai<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required>
                        
                        @error('waktu_mulai')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Waktu Selesai<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required>
                        
                        @error('waktu_selesai')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Ruangan<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <select class="form-control" id="ruangan_id" name="ruangan_id" required>
                            <option value="" disabled selected>Pilih Ruangan</option>
                            @foreach($ruangan as $item)
                                <option value="{{ $item->ruangan_id }}">{{ $item->ruangan_nama }}</option>
                            @endforeach
                        </select>
                        
                        @error('ruangan_id')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label">Keperluan<span class="text-danger">*</span></label>
                    <div class="col-md-11">
                        <input type="text" class="form-control" id="keperluan" name="keperluan" value="{{ old('keperluan') }}" required>
                        
                        @error('keperluan')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                {{-- <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Status</label>
                    <div class="col-11">
                        <input type="number" class="form-control" id="status" name="status" min="0" max="1" value="{{ old('status') }}" required>
                        <small class="form-text text-muted">Member: 0 | Other: 1</small>

                        @error('status')
                            <small class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div> --}}
                <div class="form-group row align-items-center">
                    <label class="col-md-1 control-label col-form-label"></label>
                    <div class="col-md-11">
                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                        <a class="btn btn-sm btn-default ml-1" href="{{ url('pengelolaan-informasi/kategorigaleri') }}">Kembali</a>
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