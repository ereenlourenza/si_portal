@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($sektor)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $sektor->sektor_id }}</td>
                    </tr>
                    <tr>
                        <th>Sektor Nama</th>
                        <td>{{ $sektor->sektor_nama }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{!! nl2br(e($sektor->deskripsi)) !!}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Jemaat</th>
                        <td>{{ $sektor->jumlah_jemaat }}</td>
                    </tr>
                    <tr>
                        <th>Koordinator Sektor</th>
                        <td>{{ $sektor->pelayan->nama }}</td>
                    </tr>
                </table>
            @endempty
            
            <a href="{{ url('pengelolaan-informasi/sektor') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush    