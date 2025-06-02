@extends('layouts.template')

@section('content')
    @if(session('success_pendaftaran'))
        <div class='alert alert-success'>{{ session('success_pendaftaran') }}</div>
    @endif
    @if(session('error_pendaftaran'))
        <div class='alert alert-danger'>{{ session('error_pendaftaran') }}</div>
    @endif

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Pilih Jenis Pendaftaran</h3>
        </div>
        <div class="card-body">
            <ul>
                <li><a class="btn btn-sm btn-primary mt-1" href="{{ route('pendaftaran.create', ['jenis' => 'baptis']) }}">Pendaftaran Baptis</a></li>
                <li><a class="btn btn-sm btn-primary mt-1" href="{{ route('pendaftaran.create', ['jenis' => 'sidi']) }}">Pendaftaran Katekisasi (Sidi)</a></li>
                <li><a class="btn btn-sm btn-primary mt-1" href="{{ route('pendaftaran.create', ['jenis' => 'pernikahan']) }}">Pendaftaran Pernikahan</a></li>
            </ul>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
        </div>
        <div class="card-body">
            @csrf
            <div class="form-group row">
                <label class="col-md-1 col-form-label">Jenis<span class="text-danger">*</span></label>
                <div class="col-md-11">
                    <select id="jenis_pendaftaran" class="form-control">
                        <option value="baptis">Baptis</option>
                        <option value="sidi">Sidi</option>
                        <option value="pernikahan">Pernikahan</option>
                    </select>
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover table-md" id="table_pendaftaran">
                <thead id="thead_pendaftaran">
                    <!-- Header akan diganti dinamis oleh JS -->
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        // $(document).ready(function() {
        //     var dataPendaftaran = $('#table_pendaftaran').DataTable({
        //         responsive: true, // Mengaktifkan fitur responsif
        //         lengthChange: true, 
        //         autoWidth: false,
        //         serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
        //         ajax: {
        //             "url": "{{ url('pengelolaan-informasi/pendaftaran/list') }}",
        //             "dataType": "json",
        //             "headers": {
        //                 "X-CSRF-TOKEN": "{{ csrf_token() }}"
        //             },
        //             "type": "POST",
        //             "data": function (d) {
        //                 d.jenis = $('#jenis_pendaftaran').val();
        //             }
        //         },
        //         columns: [
        //             {
        //                 data: "DT_RowIndex", // nomor urut dari laravel datatable addIndexColumn()
        //                 className: "text-center",
        //                 orderable: false,
        //                 searchable: false
        //             },
        //             {
        //                 data: "tanggal_baptis", 
        //                 className: "",
        //                 orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                 searchable: true // searchable: true, jika ingin kolom ini bisa dicari
        //             },
        //             {
        //                 data: "nama_lengkap", 
        //                 className: "",
        //                 orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                 searchable: true // searchable: true, jika ingin kolom ini bisa dicari
        //             },
        //             {
        //                 data: "jenis_kelamin", 
        //                 className: "",
        //                 orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                 searchable: true // searchable: true, jika ingin kolom ini bisa dicari
        //             },
        //             {
        //                 data: "dilayani", 
        //                 className: "",
        //                 orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                 searchable: true // searchable: true, jika ingin kolom ini bisa dicari
        //             },
        //             {
        //                 data: "status", 
        //                 className: "",
        //                 orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                 searchable: true // searchable: true, jika ingin kolom ini bisa dicari
        //             },
        //             {
        //                 data: "aksi", 
        //                 className: "",
        //                 orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                 searchable: false // searchable: true, jika ingin kolom ini bisa dicari
        //             },
        //             {
        //                 data: "aksi_status", 
        //                 className: "",
        //                 orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
        //                 searchable: false // searchable: true, jika ingin kolom ini bisa dicari
        //             },
        //         ]
        //     });
            
        //     $('#jenis_pendaftaran').on('change', function() {
        //         dataPendaftaran.ajax.reload();
        //     });
        // });

    const table = $('#table_pendaftaran');

    function getColumnsAndHeaders(jenis) {
        let columns = [];
        let headers = '';

        if (jenis === 'baptis') {
            columns = [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "tanggal_baptis" },
                { data: "nama_lengkap" },
                { data: "jenis_kelamin" },
                { data: "dilayani" },
                { data: "status" },
                { data: "aksi" },
                { data: "aksi_status" },
                { 
                    data: "export_pdf",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<a href="${data}" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>`;
                    }
                }
            ];
            headers = `
                <tr>
                    <th>No</th>
                    <th>Tanggal Baptis</th>
                    <th>Nama Anak</th>
                    <th>Jenis Kelamin</th>
                    <th>Pelayan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                    <th>Aksi Status</th>
                    <th>Export</th>
                </tr>`;
        } else if (jenis === 'sidi') {
            columns = [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "tanggal_lahir" },
                { data: "nama_lengkap" },
                { data: "jenis_kelamin" },
                { data: "is_baptis" },
                { data: "status" },
                { data: "aksi" },
                { data: "aksi_status" },
                { 
                    data: "export_pdf",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<a href="${data}" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>`;
                    }
                }
            ];
            headers = `
                <tr>
                    <th>No</th>
                    <th>Tanggal Lahir</th>
                    <th>Nama Lengkap</th>
                    <th>Jenis Kelamin</th>
                    <th>Status Baptis</th>
                    <th>Status</th>
                    <th>Aksi</th>
                    <th>Aksi Status</th>
                    <th>Export</th>
                </tr>`;
        } else if (jenis === 'pernikahan') {
            columns = [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nama_lengkap_pria" },
                { data: "nama_lengkap_wanita" },
                { data: "tanggal_pernikahan" },
                { data: "waktu_pernikahan" },
                { data: "dilayani" },
                { data: "status" },
                { data: "aksi" },
                { data: "aksi_status" },
                { 
                    data: "export_pdf",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<a href="${data}" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>`;
                    }
                }
            ];
            headers = `
                <tr>
                    <th>No</th>
                    <th>Nama Mempelai Pria</th>
                    <th>Nama Mempelai Wanita</th>
                    <th>Tanggal Pernikahan</th>
                    <th>Waktu Pernikahan</th>
                    <th>Pelayan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                    <th>Aksi Status</th>
                    <th>Export</th>
                </tr>`;
        }

        return { columns, headers };
    }

    function loadDataTable(jenis) {
        const config = getColumnsAndHeaders(jenis);

        // Replace table headers
        $('#thead_pendaftaran').html(config.headers);

        // Destroy DataTable instance before re-init
        if ($.fn.DataTable.isDataTable(table)) {
            table.DataTable().clear().destroy();
        }

        table.DataTable({
            responsive: true,
            lengthChange: true,
            autoWidth: false,
            serverSide: true,
            ajax: {
                url: "{{ url('pengelolaan-informasi/pendaftaran/list') }}",
                dataType: "json",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                type: "POST",
                data: function(d) {
                    d.jenis = $('#jenis_pendaftaran').val();
                }
            },
            columns: config.columns
        });
    }

    $(document).ready(function() {
        const defaultJenis = $('#jenis_pendaftaran').val();
        loadDataTable(defaultJenis);

        $('#jenis_pendaftaran').on('change', function() {
            const selectedJenis = $(this).val();
            loadDataTable(selectedJenis);
        });
    });
</script>
@endpush
