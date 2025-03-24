@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($persembahan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $persembahan->persembahan_id }}</td>
                    </tr>
                    <tr>
                        <th>Kategori Galeri Kode</th>
                        <td>{{ $persembahan->persembahan_nama }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Rekening</th>
                        <td>{{ $persembahan->nomor_rekening }}</td>
                    </tr>
                    <tr>
                        <th>Atas Nama</th>
                        <td>{{ $persembahan->atas_nama }}</td>
                    </tr>
                    <tr>
                        <th>Barcode</th>
                        <td>
                            @if (!empty($persembahan->barcode) && Storage::url('images/' . $persembahan->barcode))
                                <img src="{{ asset('storage/images/barcode/'.$persembahan->barcode) }}" class="" style="width: 20%">
                            @else
                                <span class="text-danger">Tidak ada barcode</span>
                            @endif   
                        </td>
                    </tr>
                </table>
            @endempty
            
            <a href="{{ url('pengelolaan-informasi/persembahan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush    