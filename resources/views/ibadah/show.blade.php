@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($ibadah)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $ibadah->ibadah_id }}</td>
                    </tr>
                    <tr>
                        <th>Kategori Ibadah</th>
                        <td>{{ $ibadah->kategoriibadah->kategoriibadah_nama }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $ibadah->tanggal }}</td>
                    </tr>
                    <tr>
                        <th>Waktu</th>
                        <td>{{ \Carbon\Carbon::parse($ibadah->waktu)->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Tempat</th>
                        <td>{{ $ibadah->tempat }}</td>
                    </tr>
                    @if (in_array($ibadah->kategoriibadah->kategoriibadah_nama, ['Ibadah Keluarga', 'Ibadah Pengucapan Syukur', 'Ibadah Diakonia']))
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $ibadah->lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Sektor</th>
                        <td>{{ $ibadah->sektor }}</td>
                    </tr>
                    @endif
                    @if ($ibadah->kategoriibadah->kategoriibadah_nama == 'Ibadah Pelkat')
                    <tr>
                        <th>Nama Pelkat</th>
                        <td>{{ $ibadah->nama_pelkat }}</td>
                    </tr>
                    <tr>
                        <th>Ruang</th>
                        <td>{{ $ibadah->ruang }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Pelayan Firman</th>
                        <td>{{ $ibadah->pelayan_firman }}</td>
                    </tr>
                </table>
            @endempty
            
            <a href="{{ url('pengelolaan-informasi/ibadah') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush    