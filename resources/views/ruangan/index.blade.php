@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/ruangan/create') }}">Tambah Ruangan</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success_ruangan'))
                <div class='alert alert-success'>{{ session('success_ruangan' ) }}</div>
            @endif
            @if(session('error_ruangan'))
                <div class='alert alert-danger'>{{ session('error_ruangan' ) }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-md" id="table_ruangan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ruangan Nama</th>
                        <th>Deskripsi</th>
                        <th>Foto</th>
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
            var dataRuangan = $('#table_ruangan').DataTable({
                responsive: true, // Mengaktifkan fitur responsif
                // scrollX: true,
                // fixedHeader: true,
                lengthChange: true, 
                autoWidth: false,
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('pengelolaan-informasi/ruangan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.ruangan_id = $('#ruangan_id').val();
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
                        data: "ruangan_nama", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "deskripsi", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "foto", 
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
            
            $('#ruangan_id').on('change', function(){
                dataRuangan.ajax.reload();
            });
        });
    </script>
@endpush