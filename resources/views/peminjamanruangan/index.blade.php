@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/peminjamanruangan/create') }}">Tambah Peminjaman</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success_peminjamanruangan'))
                <div class='alert alert-success'>{{ session('success_peminjamanruangan' ) }}</div>
            @endif
            @if(session('error_peminjamanruangan'))
                <div class='alert alert-danger'>{{ session('error_peminjamanruangan' ) }}</div>
            @endif

            <div class="row mb-3">
                <div class="input-group">
                    <label for="filter_tanggal" class="col-auto control-label col-form-label">Filter Tanggal:</label>
                    <input type="date" id="filter_tanggal" class="form-control">
                    <div class="input-group-append">
                        {{-- <label>&nbsp;</label><br> --}}
                        <button id="btnFilter" class="btn btn-primary">Filter</button>
                        <button id="btnReset" class="btn btn-secondary">Reset</button>
                    </div>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-md" id="table_peminjamanruangan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Peminjam Nama</th>
                        <th>Peminjam Telepon</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Ruangan</th>
                        <th>Keperluan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        
        $(document).ready(function() {

            var dataPeminjamanRuangan = $('#table_peminjamanruangan').DataTable({
                responsive: true, // Mengaktifkan fitur responsif
                // scrollX: true,
                // fixedHeader: true,
                lengthChange: true, 
                autoWidth: false,
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('pengelolaan-informasi/peminjamanruangan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.tanggal = $('#filter_tanggal').val();
                    }
                },
                columns: [
                    {
                        data: "DT_RowIndex", // nomor urut dari laravel datatable addIndexColumn()
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "peminjam_nama", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "peminjam_telepon", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "tanggal", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "waktu", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "ruangan.ruangan_nama", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "keperluan", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "status", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "aksi", 
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    },
                ]
            });
            
            // Event saat tombol "Terapkan" ditekan
            $('#btnFilter').on('click', function() {
                dataPeminjamanRuangan.ajax.reload(); // Refresh DataTable dengan filter
            });

            // Event saat tombol "Reset" ditekan
            $('#btnReset').on('click', function() {
                $('#filter_tanggal').val(''); // Kosongkan input
                dataPeminjamanRuangan.ajax.reload(); // Refresh tanpa filter
            });
            
        });
    </script>
@endpush