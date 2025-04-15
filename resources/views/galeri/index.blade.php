@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/galeri/create') }}">Tambah Galeri</a>
                
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
    
                    });
                }
            });
        }
    
    
        $('#kategorigaleri_id').on('change', function() {
            loadGaleri();
        });
    
        // loadFilterTahun();
        loadGaleri();
    });
    
    </script>
@endpush