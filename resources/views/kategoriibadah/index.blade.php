@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/kategoriibadah/create') }}">Tambah Kategori ibadah</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success_kategoriibadah'))
                <div class='alert alert-success'>{{ session('success_kategoriibadah' ) }}</div>
            @endif
            @if(session('error_kategoriibadah'))
                <div class='alert alert-danger'>{{ session('error_kategoriibadah' ) }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row d-flex align-items-center">
                        <label class="col-auto control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="kategoriibadah_id" name="kategoriibadah_id" required>
                                <option value="">Semua Kategori</option>
                                @foreach($kategoriibadah as $item)
                                    <option value="{{ $item->kategoriibadah_id }}">{{ $item->kategoriibadah_nama }}</option>
                                @endforeach
                            </select>
                            {{-- <small class="form-text text-muted">kategoriibadah Nama</small> --}}
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-md" id="table_kategoriibadah">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori Ibadah Kode</th>
                        <th>Kategori Ibadah Nama</th>
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
            var dataKategoriIbadah = $('#table_kategoriibadah').DataTable({
                responsive: true, // Mengaktifkan fitur responsif
                // scrollX: true,
                // fixedHeader: true,
                lengthChange: true, 
                autoWidth: false,
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('pengelolaan-informasi/kategoriibadah/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.kategoriibadah_id = $('#kategoriibadah_id').val();
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
                        data: "kategoriibadah_kode", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "kategoriibadah_nama", 
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
            
            $('#kategoriibadah_id').on('change', function(){
                dataKategoriIbadah.ajax.reload();
            });
        });
    </script>
@endpush