@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title"><i class="fas fa-history mr-2"></i>{{ $page->title }}</h3>
            <div class="card-tools">
                {{-- Tambahan fitur kalau mau, contoh: filter atau export --}}
            </div>
        </div>

        <div class="card-body">
            @if ($logs->isEmpty())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Tidak Ada Data!</h5>
                    Belum ada aktivitas pengguna yang terekam.
                </div>
            @else
                <div class="table-responsive">
                    <table id="logTable" class="table table-striped table-hover table-sm align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th style="width: 150px;">Waktu</th>
                                <th>User</th>
                                <th>Modul</th>
                                <th>Aksi</th>
                                <th>Aktivitas</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                                    <td>
                                        <strong>{{ $log->user->name ?? 'User tidak ditemukan' }}</strong><br>
                                        <small class="text-muted">{{ $log->user->username ?? '-' }}</small>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ ucfirst($log->modul) }}</span></td>
                                    <td>
                                        @php
                                            $aksiClass = match(strtolower($log->aksi)) {
                                                'store' => 'success',
                                                'update' => 'warning',
                                                'destroy' => 'danger',
                                                'validation' => 'primary',
                                                'cancel validation' => 'info',
                                                'reject' => 'danger',
                                                default => 'light',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $aksiClass }}">{{ ucfirst($log->aksi) }}</span>
                                    </td>
                                    <td>{!! nl2br(e($log->aktivitas)) !!}</td>
                                    <td><code>{{ $log->ip_address }}</code></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                {{-- <div class="mt-3">
                    {{ $logs->links() }}
                </div> --}}
            @endif

            <a href="{{ url('pengelolaan-pengguna/log') }}" class="btn btn-sm btn-outline-secondary mt-3">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('js')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#logTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [5, 10, 25, 50, 100],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "order": [[0, "desc"]]
        });
    });
</script>
@endpush

