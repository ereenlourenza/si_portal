@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($sejarah)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $sejarah->sejarah_id }}</td>
                    </tr>
                    <tr>
                        <th>Judul Sub Bab</th>
                        <td>{{ $sejarah->judul_subbab }}</td>
                    </tr>
                    <tr>
                        <th>Isi Konten</th>
                        <td class="isi-konten">{!! $sejarah->isi_konten !!}</td>
                    </tr>
                </table>
            @endempty
            
            <a href="{{ url('pengelolaan-informasi/sejarah') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
<style>
    .isi-konten img {
        max-width: 50%;
        height: auto;
        /* display: block;
        margin: 10px auto; */
    }
</style>
@endpush

@push('js')
@endpush    