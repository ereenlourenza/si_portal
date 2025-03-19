@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/kategoripelayan/create') }}">Tambah Kategori Pelayan</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success_kategoripelayan'))
                <div class='alert alert-success'>{{ session('success_kategoripelayan' ) }}</div>
            @endif
            @if(session('error_kategoripelayan'))
                <div class='alert alert-danger'>{{ session('error_kategoripelayan' ) }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row d-flex align-items-center">
                        <label class="col-auto control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="kategoripelayan_id" name="kategoripelayan_id" required>
                                <option value="">Semua Kategori</option>
                                @foreach($kategoripelayan as $item)
                                    <option value="{{ $item->kategoripelayan_id }}">{{ $item->kategoripelayan_nama }}</option>
                                @endforeach
                            </select>
                            {{-- <small class="form-text text-muted">kategoripelayan Nama</small> --}}
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-md" id="table_kategoripelayan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori Pelayan Kode</th>
                        <th>Kategori Pelayan Nama</th>
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
            var dataKategoriPelayan = $('#table_kategoripelayan').DataTable({
                responsive: true, // Mengaktifkan fitur responsif
                // scrollX: true,
                // fixedHeader: true,
                lengthChange: true, 
                autoWidth: false,
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('pengelolaan-informasi/kategoripelayan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.kategoripelayan_id = $('#kategoripelayan_id').val();
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
                        data: "kategoripelayan_kode", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "kategoripelayan_nama", 
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
            
            $('#kategoripelayan_id').on('change', function(){
                dataKategoriPelayan.ajax.reload();
            });
        });
    </script>
@endpush