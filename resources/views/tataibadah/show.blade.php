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
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $tataibadah->tataibadah_id }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $tataibadah->tanggal }}</td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td>{{ $tataibadah->judul }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $tataibadah->deskripsi ? $tataibadah->deskripsi : '-' }}</td>
                    </tr>
                    <tr>
                        <th>File</th>
                        <th>
                            @if (!empty($tataibadah->file) && Storage::url('dokumen/' . $tataibadah->file))
                                <a href="{{ Storage::url('dokumen/tataibadah/' . $tataibadah->file) }}" target="_blank" class="btn btn-info btn-sm">
                                    <i class="nav-icon far fa-eye"></i> Lihat File
                                </a>
                            @else
                                <span class="text-danger">Tidak ada file</span>
                            @endif   
                        </th>
                    </tr>
                </table>
            @endempty
            
            <a href="{{ url('pengelolaan-informasi/tataibadah') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush    