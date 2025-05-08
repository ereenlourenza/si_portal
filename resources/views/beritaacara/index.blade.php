@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-berita-acara/berita-acara/create') }}">Tambah Berita Acara Ibadah</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class='alert alert-success'>{{ session('success' ) }}</div>
            @endif
            @if(session('error'))
                <div class='alert alert-danger'>{{ session('error' ) }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row d-flex align-items-center">
                        <label class="col-auto control-label col-form-label">Filter:</label>
            
                        {{-- Filter Tanggal --}}
                        <div class="col-md-3">
                            <input type="date" id="filterTanggal" name="tanggal" class="form-control">
                        </div>

                        @if (auth()->user()->level->level_kode == 'ADM') 
                            <div class="text-right">
                                <a href="{{ route('berita-acara.exportPdfAll') }}" class="btn btn-sm btn-danger" target="_blank">
                                    <i class="fas fa-file-pdf"></i> Export PDF All
                                </a>
                            </div>
                        @endif
            
                        {{-- Filter Tempat Ibadah --}}
                        {{-- <div class="col-md-3">
                            <select class="form-control" id="ibadah_id" name="ibadah_id">
                                <option value="">Semua Ibadah</option>
                                @foreach($ibadah as $item)
                                    <option value="{{ $item->ibadah_id }}">{{ $item->tempat }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                    </div>
                </div>
            </div>
            
            <table class="table table-bordered table-striped table-hover table-md" id="table_beritaacara">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Tempat</th>
                        <th>Pelayan Firman</th>
                        <th>Jumlah Kehadiran</th>
                        <th>Total Persembahan</th>
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
            var dataIbadah = $('#table_beritaacara').DataTable({
                responsive: true, // Mengaktifkan fitur responsif
                // scrollX: true,
                // fixedHeader: true,
                lengthChange: true, 
                autoWidth: false,
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('pengelolaan-berita-acara/berita-acara/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        d.ibadah_id = $('#ibadah_id').val();
                        d.tanggal = $('#filterTanggal').val(); // Mengambil nilai dari input tanggal
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
                        data: "ibadah.tanggal", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "ibadah.waktu", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "ibadah.tempat", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "ibadah.pelayan_firman", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "jumlah_kehadiran", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "total_persembahan", // Kolom baru untuk total persembahan
                        className: "text-right",
                        orderable: true,
                        searchable: false,
                        render: function(data, type, row) {
                            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(data);
                        }
                    },
                    {
                        data: "aksi", 
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    }
                ]
            });
            
            $('#filterTanggal, #ibadah_id').on('change', function() {
                dataIbadah.ajax.reload();
            });
        });
    </script>
@endpush