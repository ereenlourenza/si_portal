@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/sejarah/create') }}">Tambah Sejarah</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class='alert alert-success'>{{ session('success' ) }}</div>
            @endif
            @if(session('error'))
                <div class='alert alert-danger'>{{ session('error' ) }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-md" id="table_sejarah">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Judul Sub Bab</th>
                        <th>Isi Konten</th>
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
            var dataSejarah = $('#table_sejarah').DataTable({
                responsive: true, // Mengaktifkan fitur responsif
                lengthChange: true, 
                autoWidth: false,
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('pengelolaan-informasi/sejarah/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.sejarah_id = $('#sejarah_id').val();
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
                        data: "judul_subbab", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "isi_konten", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true, // searchable: true, jika ingin kolom ini bisa dicari
                        
                    },
                    {
                        data: "aksi", 
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    }
                ]
            });
            
            $('#sejarah_id').on('change', function(){
                dataSejarah.ajax.reload();
            });
        });
    </script>
@endpush