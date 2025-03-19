@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/galeri/create') }}">Tambah Tata Ibadah</a>
                
            </div>
        </div>
        <div class="card-body">
            @if(session('success_galeri'))
                <div class='alert alert-success'>{{ session('success_galeri' ) }}</div>
            @endif
            @if(session('error_galeri'))
                <div class='alert alert-danger'>{{ session('error_galeri' ) }}</div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row d-flex align-items-center">
                        <label class="col-auto control-lavel col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="kategorigaleri_id" name="kategorigaleri_id" required>
                                <option value="">Semua Kategori</option>
                                @foreach($kategorigaleri as $item)
                                    <option value="{{ $item->kategorigaleri_id }}">{{ $item->kategorigaleri_nama }}</option>
                                @endforeach
                            </select>
                            {{-- <small class="form-text text-muted">Level Pengguna</small> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div id="galeri-container" class="row">
                <!-- Card untuk setiap kategori akan di-generate di sini -->
            </div>
            
            {{-- <table class="table table-bordered table-striped table-hover table-md" id="table_galeri">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori Galeri</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table> --}}
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
    {{-- <script>
        $(document).ready(function() {

            // Inisialisasi DataTable Tata Ibadah
            var dataGaleri = $('#table_galeri').DataTable({
                responsive: true, // Mengaktifkan fitur responsif
                // scrollX: true,
                // fixedHeader: true,
                lengthChange: true, 
                autoWidth: false,
                serverSide: true, // serverSide: true, jika ingin menggunakan server side processing
                ajax: {
                    "url": "{{ url('pengelolaan-informasi/galeri/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": function (d) {
                        // d.galeri_id = $('#galeri_id').val();
                        // d.tanggal = $('#filter_tanggal').val(); // Kirim tanggal ke backend
                        d.kategorigaleri_id = $('#kategorigaleri_id').val(); // Kirim tanggal ke backend
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
                        data: "kategorigaleri.kategorigaleri_nama", 
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
                        data: "foto", 
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false, // searchable: true, jika ingin kolom ini bisa dicari
                                      
                    },
                    {
                        data: "aksi", 
                        className: "",
                        orderable: false, // orderable: true, jika ingin kolom ini bisa diurutkan
                        searchable: false // searchable: true, jika ingin kolom ini bisa dicari
                    }
                ]
            });

            // Data Galeri
            $('#kategorigaleri_id').on('change', function(){
                dataGaleri.ajax.reload();
            });

        });
    </script> --}}

    <script>
        $(document).ready(function() {
        function loadGaleri() {
            $.ajax({
                url: "{{ url('pengelolaan-informasi/galeri/list') }}",
                type: "POST",
                data: { kategorigaleri_id: $('#kategorigaleri_id').val() },
                success: function(response) {
                    let container = $('#galeri-container');
                    container.empty();
                    
                    let galeriData = response.data;
                    let groupedByKategori = {};
    
                    // Kelompokkan berdasarkan kategori
                    galeriData.forEach(function(item) {
                        let kategori = item.kategorigaleri.kategorigaleri_nama;
                        if (!groupedByKategori[kategori]) {
                            groupedByKategori[kategori] = [];
                        }
                        groupedByKategori[kategori].push(item);
                    });
    
                    // Generate tabel berdasarkan kategori
                    Object.keys(groupedByKategori).forEach(function(kategori) {
                        let tableId = `table_${kategori.replace(/\s+/g, '_')}`;
                        
                        let card = `<div class="col-md-12">
                                        <div class="card card-dark">
                                            <div class="card-header">
                                                <h5 class="card-title">${kategori}</h5>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-bordered table-striped table-hover table-md" id="${tableId}">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Kategori Galeri</th>
                                                            <th>Judul</th>
                                                            <th>Deskripsi</th>
                                                            <th>Foto</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>`;
                                                        groupedByKategori[kategori].forEach(function(galeri, index) {
                                                            let fotoUrl = galeri.foto ? `{{ asset('storage/images/galeri/') }}/${galeri.foto}` : `{{ asset('storage/images/galeri/avatar.png') }}`;
                                                            card += `<tr>
                                                                        <td>${index + 1}</td>
                                                                        <td>${galeri.kategorigaleri.kategorigaleri_nama}</td>
                                                                        <td>${galeri.judul}</td>
                                                                        <td>${galeri.deskripsi ? galeri.deskripsi : '-'}</td>
                                                                        <td><img src="${fotoUrl}" class="img-thumbnail" style="max-width: 100px; height: auto;"></td>
                                                                        <td>${galeri.aksi}</td>
                                                                    </tr>`;
                                                        });
                                                        card += 
                                                    `</tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>`;
                        container.append(card);
    
                        // Inisialisasi DataTables setelah elemen tabel ditambahkan ke DOM
                        let dataTable = $(`#${tableId}`).DataTable({
                            responsive: true,
                            // scrollX: true,
                            // fixedHeader: true,
                            lengthChange: true, 
                            autoWidth: false,
                            searching: true,
                            ordered: true,
                        });
    
                        // Perbaiki lebar tabel setelah DataTables dimuat
                        // dataTable.columns.adjust().draw();
    
                        // // Atur ulang lebar tabel ketika jendela diubah ukurannya
                        // $(window).on('resize', function() {
                        //     dataTable.columns.adjust().draw();
                        // });
                    });
                }
            });
        }
    
        // function loadFilterTahun() {
        //     $.ajax({
        //         url: "{{ url('pengelolaan-informasi/pelayan/list') }}",
        //         type: "POST",
        //         success: function(response) {
        //             let minYear = response.minYear ? parseInt(response.minYear) : new Date().getFullYear();
        //             let maxYear = response.maxYear ? parseInt(response.maxYear) : new Date().getFullYear();
        //             let dropdown = $('#kategorigaleri_id');
        //             dropdown.empty();
        //             dropdown.append('<option value="">Semua Tahun</option>');
        //             for (let year = maxYear; year >= minYear; year--) {
        //                 dropdown.append('<option value="' + year + '">' + year + '</option>');
        //             }
        //         }
        //     });
        // }
    
        $('#kategorigaleri_id').on('change', function() {
            loadGaleri();
        });
    
        // loadFilterTahun();
        loadGaleri();
    });
    
    </script>
@endpush