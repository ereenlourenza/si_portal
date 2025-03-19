@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($kategoriibadah)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $kategoriibadah->kategoriibadah_id }}</td>
                    </tr>
                    <tr>
                        <th>Kategori ibadah Kode</th>
                        <td>{{ $kategoriibadah->kategoriibadah_kode }}</td>
                    </tr>
                    <tr>
                        <th>Kategori ibadah Nama</th>
                        <td>{{ $kategoriibadah->kategoriibadah_nama }}</td>
                    </tr>
                </table>
            @endempty
            
            <a href="{{ url('pengelolaan-informasi/kategoriibadah') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush    