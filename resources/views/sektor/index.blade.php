@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/sektor/create') }}">Tambah Pengguna</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class='alert alert-success'>{{ session('success' ) }}</div>
            @endif
            @if(session('error'))
                <div class='alert alert-danger'>{{ session('error' ) }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-md" id="table_sektor">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sektor Nama</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Jemaat</th>
                        <th>Koordinator Sektor</th>
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
            var dataSektor = $('#table_sektor').DataTable({
                responsive: true, // Mengaktifkan fitur responsif
                lengthChange: true, 
                autoWidth: false,
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('pengelolaan-informasi/sektor/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        // d.level_id = $('#level_id').val();
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
                        data: "sektor_nama", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "deskripsi", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true, // searchable: true, jika ingin kolom ini bisa dicari
                        render: function(data, type, row) {
                            return data
                                .replace(/(Sebelah Timur|Sebelah Utara|Sebelah Barat|Sebelah Selatan|\/Selatan)/g, '<b><i>$1</i></b>') // Menjadikan bold
                                .replace(/\n/g, '<br>'); // Mengubah newline ke <br> agar format tetap rapi
                        },
                    },
                    {
                        data: "jumlah_jemaat", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "pelayan.nama", 
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

            // $('#level_id').on('change', function(){
            //     dataSektor.ajax.reload();
            // });
        });
    </script>
@endpush