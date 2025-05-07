@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-berita-acara/kategoripersembahan/create') }}">Tambah Kategori Persembahan</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success_kategoripersembahan'))
                <div class='alert alert-success'>{{ session('success_kategoripersembahan' ) }}</div>
            @endif
            @if(session('error_kategoripersembahan'))
                <div class='alert alert-danger'>{{ session('error_kategoripersembahan' ) }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row d-flex align-items-center">
                        <label class="col-auto control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="kategori_persembahan_id" name="kategori_persembahan_id" required>
                                <option value="">Semua Kategori</option>
                                @foreach($kategoripersembahan as $item)
                                    <option value="{{ $item->kategori_persembahan_id }}">{{ $item->kategori_persembahan_nama }}</option>
                                @endforeach
                            </select>
                            {{-- <small class="form-text text-muted">kategoripersembahan Nama</small> --}}
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-striped table-hover table-md" id="table_kategoripersembahan">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori Persembahan Nama</th>
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
            var dataKategoriPersembahan = $('#table_kategoripersembahan').DataTable({
                responsive: true, // Mengaktifkan fitur responsif
                // scrollX: true,
                // fixedHeader: true,
                lengthChange: true, 
                autoWidth: false,
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('pengelolaan-berita-acara/kategoripersembahan/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.kategori_persembahan_id = $('#kategori_persembahan_id').val();
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
                        data: "kategori_persembahan_nama", 
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
            
            $('#kategori_persembahan_id').on('change', function(){
                dataKategoriPersembahan.ajax.reload();
            });
        });
    </script>
@endpush