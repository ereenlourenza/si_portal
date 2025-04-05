@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($galeri)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $galeri->galeri_id }}</td>
                    </tr>
                    <tr>
                        <th>Kategori Galeri</th>
                        <td>{{ $galeri->kategorigaleri->kategorigaleri_nama }}</td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td>{{ $galeri->judul }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $galeri->deskripsi ? $galeri->deskripsi : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Foto</th>
                        <th>
                            @if (!empty($galeri->foto) && Storage::url('images/' . $galeri->foto))
                                <img src="{{ asset('storage/images/galeri/'.$galeri->foto) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif     
                        </th>
                    </tr>
                </table>
            @endempty
            
            <a href="{{ url('pengelolaan-informasi/galeri') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush    