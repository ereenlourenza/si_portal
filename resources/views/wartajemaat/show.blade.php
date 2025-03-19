@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($wartajemaat)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $wartajemaat->wartajemaat_id }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $wartajemaat->tanggal }}</td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td>{{ $wartajemaat->judul }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $wartajemaat->deskripsi ? $wartajemaat->deskripsi : '-' }}</td>
                    </tr>
                    <tr>
                        <th>File</th>
                        <th>
                            @if (!empty($wartajemaat->file) && Storage::url('dokumen/' . $wartajemaat->file))
                                <a href="{{ Storage::url('dokumen/wartajemaat/' . $wartajemaat->file) }}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="nav-icon far fa-eye"></i> Lihat File
                                </a>
                            @else
                                <span class="text-danger">Tidak ada file</span>
                            @endif   
                        </th>
                    </tr>
                </table>
            @endempty
            
            <a href="{{ url('pengelolaan-informasi/wartajemaat') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush    