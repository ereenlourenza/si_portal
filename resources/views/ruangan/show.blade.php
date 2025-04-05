@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($ruangan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $ruangan->ruangan_id }}</td>
                    </tr>
                    <tr>
                        <th>Ruangan Nama</th>
                        <td>{{ $ruangan->ruangan_nama }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $ruangan->deskripsi }}</td>
                    </tr>
                    <tr>
                        <th>Foto</th>
                        <td>
                            @if (!empty($ruangan->foto) && Storage::url('images/' . $ruangan->foto))
                                <img src="{{ asset('storage/images/ruangan/'.$ruangan->foto) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                </table>
            @endempty
            
            <a href="{{ url('pengelolaan-informasi/ruangan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush    