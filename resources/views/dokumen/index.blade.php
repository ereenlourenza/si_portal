@extends('layouts.template')

@section('content')
    {{-- tanggal picker --}}
        {{-- <div class="col-md-4">
            <div class="form-group row d-flex align-items-center">
                <label for="filter_tanggal col-auto control-label col-form-label">Filter:</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="filter_tanggal" name="filter_tanggal" placeholder="Tanggal">
                    <div class="input-group-append">
                        <button class="btn btn-secondary" id="btn_filter">Filter</button>
                        <button class="btn btn-default ml-1" id="btn_reset">Reset</button>
                    </div>
                </div>
            </div>
        </div> 
    --}}

    {{-- bulan picker --}}
    <div class="col-md-12">
        <div class="form-group row d-flex align-items-center">
            <label for="filter_bulan" class="col-auto control-label col-form-label">Filter:</label>
            <div class="col-5">
                <div class="input-group">
                    <input type="text" class="form-control" id="filter_bulan" name="filter_bulan" style="font-size: 14px" placeholder="Bulan">
                    <div class="input-group-append">
                        <button class="btn btn-secondary" id="btn_filter" style="font-size: 14px">Filter</button>
                        <button class="btn btn-default ml-1" id="btn_reset" style="font-size: 14px">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TATA IBADAH --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $pageTataIbadah->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/tataibadah/create') }}">Tambah Tata Ibadah</a>
                {{-- <a href="{{ asset('storage/dokumen/' . $tataibadah->file) }}" target="_blank">Lihat Tata Ibadah {{ $tataibadah->judul }}</a> --}}
                
            </div>
        </div>
        <div class="card-body">
            @if(session('success_tataibadah'))
                <div class='alert alert-success'>{{ session('success_tataibadah' ) }}</div>
            @endif
            @if(session('error_tataibadah'))
                <div class='alert alert-danger'>{{ session('error_tataibadah' ) }}</div>
            @endif
            
            <table class="table table-bordered table-striped table-hover table-md" id="table_tataibadah">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>File</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- WARTA JEMAAT --}}
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $pageWartaJemaat->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/wartajemaat/create') }}">Tambah Warta Jemaat</a>
                {{-- <a href="{{ asset('storage/dokumen/' . $tataibadah->file) }}" target="_blank">Lihat Tata Ibadah {{ $tataibadah->judul }}</a> --}}
                
            </div>
        </div>
        <div class="card-body">
            @if(session('success_wartajemaat'))
                <div class='alert alert-success'>{{ session('success_wartajemaat' ) }}</div>
            @endif
            @if(session('error_wartajemaat'))
                <div class='alert alert-danger'>{{ session('error_wartajemaat' ) }}</div>
            @endif
            
            {{-- <div class="table-responsive"> --}}
                <table class="table table-bordered table-striped table-hover table-md" id="table_wartajemaat">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            {{-- </div> --}}
        </div>
    </div>
    
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function() {
            // Inisialisasi Datepicker
            // $('#filter_tanggal').datepicker({
            //     format: 'yyyy-mm-dd',  // Format tanggal sesuai database
            //     autoclose: true,
            //     todayHighlight: true
            // });

            // Inisialisasi Monthpicker (Tahun-Bulan)
            $('#filter_bulan').datepicker({
                format: 'yyyy-mm',
                viewMode: "months",
                minViewMode: "months",
                autoclose: true,
                todayHighlight: true
            }).on('show', function(e){
                $('.datepicker-dropdown').css({
                    'position': 'absolute',
                    'z-index': '1050', // Pastikan lebih tinggi dari modal atau elemen lain
                    'top': $(this).offset().top + $(this).outerHeight(),
                    'left': $(this).offset().left
                });
            });

            // Inisialisasi DataTable Tata Ibadah
            var dataTataIbadah = $('#table_tataibadah').DataTable({
                responsive: true, // Mengaktifkan fitur responsif
                // scrollX: true,
                // fixedHeader: true,
                lengthChange: true, 
                autoWidth: false,
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('pengelolaan-informasi/tataibadah/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        // d.tataibadah_id = $('#tataibadah_id').val();
                        // d.tanggal = $('#filter_tanggal').val(); // Kirim tanggal ke backend
                        d.tanggal = $('#filter_bulan').val(); // Kirim tanggal ke backend
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
                        data: "tanggal", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "judul", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "deskripsi", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true, // searchable: true, jika ingin kolom ini bisa dicari
                        render: function (data, type, row) {
                            if (!data || data.trim() === "") return "-"; // Jika null atau string kosong, tampilkan "-"
                            return data.length > 50 ? data.substring(0, 50) + "..." : data;
                        }
                    },
                    {
                        data: "file", 
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "aksi", 
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    }
                ]
            });

            // Inisialisasi DataTable Warta Jemaat
            var dataWartaJemaat = $('#table_wartajemaat').DataTable({
                responsive: true, // Mengaktifkan fitur responsif
                // scrollX: true,
                // fixedHeader: true,
                lengthChange: true, 
                autoWidth: false,
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('pengelolaan-informasi/wartajemaat/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        // d.tataibadah_id = $('#tataibadah_id').val();
                        // d.tanggal = $('#filter_tanggal').val(); // Kirim tanggal ke backend
                        d.tanggal = $('#filter_bulan').val(); // Kirim tanggal ke backend
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
                        data: "tanggal", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "judul", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "deskripsi", 
                        className: "",
                        orderable: true, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: true, // searchable: true, jika ingin kolom ini bisa dicari
                        render: function (data, type, row) {
                            if (!data || data.trim() === "") return "-"; // Jika null atau string kosong, tampilkan "-"
                            return data.length > 50 ? data.substring(0, 50) + "..." : data;
                        }
                    },
                    {
                        data: "file", 
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    },
                    {
                        data: "aksi", 
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    }
                ]
            });

            // Perbaiki lebar tabel setelah DataTables dimuat
            // dataWartaJemaat.columns.adjust().draw();
            // dataTataIbadah.columns.adjust().draw();

            // // Atur ulang lebar tabel ketika jendela diubah ukurannya
            // $(window).on('resize', function() {
            //     dataWartaJemaat.columns.adjust().draw();
            //     dataTataIbadah.columns.adjust().draw();
            // });

            // Event saat tombol filter ditekan
            $('#btn_filter').on('click', function() {
                dataTataIbadah.ajax.reload(),
                dataWartaJemaat.ajax.reload();
            });

            // Event saat tombol reset ditekan
            // $('#btn_reset').on('click', function() {
            //     $('#filter_tanggal').val('');
            //     dataTataIbadah.ajax.reload();
            // });

            // Event saat tanggal dipilih
            // $('#filter_tanggal').on('change', function(){
            //     dataTataIbadah.ajax.reload();
            // });

            // Event saat tombol reset ditekan
            $('#btn_reset').on('click', function() {
                $('#filter_bulan').val('');
                dataTataIbadah.ajax.reload();
                dataWartaJemaat.ajax.reload();
            });

            // Event saat bulan dipilih
            $('#filter_bulan').on('change', function(){
                dataTataIbadah.ajax.reload();
                dataWartaJemaat.ajax.reload();
            });

            // Data Tata Ibadah
            $('#tataibadah_id').on('change', function(){
                dataTataIbadah.ajax.reload();
            });

            // Data Warta Jemaat
            $('#wartajemaat_id').on('change', function(){
                dataWartaJemaat.ajax.reload();
            });
        });
    </script>
@endpush