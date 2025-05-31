@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('pengelolaan-informasi/peminjamanruangan/cetak-laporan') }}" method="GET" class="form-inline mb-3">
                <div class="form-group mr-2">
                    <label for="tanggal" class="mr-2">Tanggal:</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $request->tanggal ?? '' }}">
                </div>
                <div class="form-group mr-2">
                    <label for="status_filter" class="mr-2">Status:</label>
                    <select name="status_filter" id="status_filter" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="0" {{ ($request->status_filter ?? '') === '0' ? 'selected' : '' }}>Menunggu Disetujui</option>
                        <option value="1" {{ ($request->status_filter ?? '') === '1' ? 'selected' : '' }}>Disetujui</option>
                        <option value="2" {{ ($request->status_filter ?? '') === '2' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <div class="form-group mr-2">
                    <label for="ruangan_filter" class="mr-2">Ruangan:</label>
                    <select name="ruangan_filter" id="ruangan_filter" class="form-control">
                        <option value="">Semua Ruangan</option>
                        @foreach($ruangans as $ruangan)
                            <option value="{{ $ruangan->ruangan_id }}" {{ ($request->ruangan_filter ?? '') == $ruangan->ruangan_id ? 'selected' : '' }}>
                                {{ $ruangan->ruangan_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                <a href="{{ url('pengelolaan-informasi/peminjamanruangan/cetak-laporan') }}" class="btn btn-secondary">Reset</a>
                <button type="button" class="btn btn-success ml-2" onclick="printReport()">Cetak</button>
            </form>

            @if($peminjamanRuangans->isEmpty())
                <div class="alert alert-info">Tidak ada data peminjaman ruangan yang sesuai dengan filter.</div>
            @else
                <div id="report-area">
                    <h4 class="text-center mb-3">Laporan Peminjaman Ruangan</h4>
                    <p>Tanggal: {{ $request->tanggal ? \Carbon\Carbon::parse($request->tanggal)->format('d M Y') : 'Semua' }}</p>
                    @if($request->filled('status_filter') && $request->status_filter !== '')
                        <p>Status: 
                            @if($request->status_filter == '0') Menunggu 
                            @elseif($request->status_filter == '1') Disetujui 
                            @elseif($request->status_filter == '2') Ditolak 
                            @endif
                        </p>
                    @endif
                    @if($request->filled('ruangan_filter'))
                        @php
                            $selectedRuangan = $ruangans->firstWhere('ruangan_id', $request->ruangan_filter);
                        @endphp
                        <p>Ruangan: {{ $selectedRuangan ? $selectedRuangan->ruangan_nama : 'Semua Ruangan' }}</p>
                    @endif
                    <table class="table table-bordered table-striped" id="table_report_peminjamanruangan">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Peminjam</th>
                                <th>Telepon</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Ruangan</th>
                                <th>Keperluan</th>
                                <th>Status</th>
                                <th>Alasan Penolakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($peminjamanRuangans as $index => $peminjaman)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $peminjaman->peminjam_nama }}</td>
                                    <td>{{ $peminjaman->peminjam_telepon }}</td>
                                    <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal)->format('d M Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($peminjaman->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($peminjaman->waktu_selesai)->format('H:i') }}</td>
                                    <td>{{ $peminjaman->ruangan->ruangan_nama }}</td>
                                    <td>{{ $peminjaman->keperluan }}</td>
                                    <td>
                                        @if ($peminjaman->status == 0)
                                            <span class="badge badge-warning"><i class="fas fa-exclamation nav-icon"></i> Menunggu</span>
                                        @elseif ($peminjaman->status == 1)
                                            <span class="badge badge-success"><i class="fas fa-thumbs-up nav-icon"></i> Disetujui</span>
                                        @elseif ($peminjaman->status == 2)
                                            <span class="badge badge-danger"><i class="fas fa-ban nav-icon"></i> Ditolak</span>
                                        @endif
                                    </td>
                                    <td>{{ $peminjaman->alasan_penolakan }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('css')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #report-area, #report-area * {
            visibility: visible;
        }
        #report-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .form-inline, .card-header .btn, .btn-success.ml-2 {
            display: none !important;
        }
        .card-header h3 {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

@push('js')
<script>
    function printReport() {
        window.print();
    }

    $(document).ready(function() {
        $('#table_report_peminjamanruangan').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            // Anda bisa menambahkan opsi lain di sini jika diperlukan
            // Contoh: mengatur urutan default berdasarkan kolom tertentu
            // "order": [[ 1, "asc" ]], 
            // "language": {
            //     "search": "Cari:",
            //     "lengthMenu": "Tampilkan _MENU_ entri",
            //     "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            //     "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
            //     "infoFiltered": "(difilter dari _MAX_ total entri)",
            //     "zeroRecords": "Tidak ada data yang cocok",
            //     "paginate": {
            //         "first": "Pertama",
            //         "last": "Terakhir",
            //         "next": "Berikutnya",
            //         "previous": "Sebelumnya"
            //     }
            // }
        });
    });
</script>
@endpush
