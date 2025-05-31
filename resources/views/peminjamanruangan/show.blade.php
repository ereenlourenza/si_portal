@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
            </div>
        </div>
        <div class="card-body">
            @if(session('success_peminjamanruangan'))
                <div class="alert alert-success">{{ session('success_peminjamanruangan') }}</div>
            @endif
            @if(session('error_peminjamanruangan'))
                <div class="alert alert-danger">{{ session('error_peminjamanruangan') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm">
                <tr>
                    <th>ID Peminjaman</th>
                    <td>{{ $peminjamanRuangan->peminjamanruangan_id }}</td>
                </tr>
                <tr>
                    <th>Nama Peminjam</th>
                    <td>{{ $peminjamanRuangan->peminjam_nama }}</td>
                </tr>
                <tr>
                    <th>Telepon Peminjam</th>
                    <td>{{ $peminjamanRuangan->peminjam_telepon }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ \Carbon\Carbon::parse($peminjamanRuangan->tanggal)->format('d M Y') }}</td>
                </tr>
                <tr>
                    <th>Waktu Mulai</th>
                    <td>{{ $peminjamanRuangan->waktu_mulai }}</td>
                </tr>
                <tr>
                    <th>Waktu Selesai</th>
                    <td>{{ $peminjamanRuangan->waktu_selesai }}</td>
                </tr>
                <tr>
                    <th>Ruangan</th>
                    <td>{{ $peminjamanRuangan->ruangan->ruangan_nama }}</td>
                </tr>
                <tr>
                    <th>Keperluan</th>
                    <td>{{ $peminjamanRuangan->keperluan }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($peminjamanRuangan->status == 0)
                            <span class="badge badge-warning">Menunggu Konfirmasi</span>
                        @elseif ($peminjamanRuangan->status == 1)
                            <span class="badge badge-success">Disetujui</span>
                        @elseif ($peminjamanRuangan->status == 2)
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </td>
                </tr>
                @if ($peminjamanRuangan->status == 2 && $peminjamanRuangan->alasan_penolakan)
                    <tr>
                        <th>Alasan Penolakan</th>
                        <td>{{ $peminjamanRuangan->alasan_penolakan }}</td>
                    </tr>
                @endif
                <tr>
                    <th>Diajukan Pada</th>
                    <td>{{ $peminjamanRuangan->created_at->format('d M Y H:i:s') }}</td>
                </tr>
                <tr>
                    <th>Terakhir Diperbarui</th>
                    <td>{{ $peminjamanRuangan->updated_at->format('d M Y H:i:s') }}</td>
                </tr>
            </table>
            <a href="{{ url('pengelolaan-informasi/peminjamanruangan') }}" class="btn btn-sm btn-default mt-1">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush
