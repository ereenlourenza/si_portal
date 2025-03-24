@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/persembahan/create') }}">Tambah Persembahan</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success_persembahan'))
                <div class='alert alert-success'>{{ session('success_persembahan' ) }}</div>
            @endif
            @if(session('error_persembahan'))
                <div class='alert alert-danger'>{{ session('error_persembahan' ) }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-md" id="table_persembahan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Persembahan Nama</th>
                        <th>Nomor Rekening</th>
                        <th>Atas Nama</th>
                        <th>Barcode</th>
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
            var dataPersembahan = $('#table_persembahan').DataTable({
                responsive: true, // Mengaktifkan fitur responsif
                // scrollX: true,
                // fixedHeader: true,
                lengthChange: true, 
                autoWidth: false,
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('pengelolaan-informasi/persembahan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.persembahan_id = $('#persembahan_id').val();
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
                        data: "persembahan_nama", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "nomor_rekening", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "atas_nama", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "barcode", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "aksi", 
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    }
                ]
            });
            
            $('#persembahan_id').on('change', function(){
                dataPersembahan.ajax.reload();
            });
        });
    </script>
@endpush