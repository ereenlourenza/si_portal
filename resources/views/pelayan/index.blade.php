@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/pelayan/create') }}">Tambah Pelayan</a>
                <a class="btn btn-sm btn-secondary mt-1" href="{{ url('pengelolaan-informasi/phmj/create') }}">Tambah PHMJ</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success_pelayan'))
                <div class='alert alert-success'>{{ session('success_pelayan' ) }}</div>
            @endif
            @if(session('error_pelayan'))
                <div class='alert alert-danger'>{{ session('error_pelayan' ) }}</div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row d-flex align-items-center">
                        <label class="col-auto control-label col-form-label">Filter:</label>
                        <div class="col-3">
                            <select class="form-control" id="filter-tahun" name="filter-tahun">
                                <option value="">Semua Tahun</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div id="pelayan-container" class="row">
                <!-- Card untuk setiap kategori akan di-generate di sini -->
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        function loadPelayan() {
            $.ajax({
                url: "{{ url('pengelolaan-informasi/pelayan/list') }}",
                type: "POST",
                data: { tahun: $('#filter-tahun').val() },
                success: function(response) {
                    let container = $('#pelayan-container');
                    container.empty();

                    let pelayanData = response.data;
                    let groupedByKategori = {};

                    // Kelompokkan berdasarkan kategori
                    pelayanData.forEach(function(item) {
                        let kategori = item.kategoripelayan ? item.kategoripelayan.kategoripelayan_nama : 'Lainnya';

                        // Jika pelayan memiliki data PHMJ, kategorikan sebagai "PHMJ"

                        // // Jika pelayan memiliki data PHMJ, tambahkan ke kategori "PHMJ" 
                        // if (item.phmj) {
                        //     if (!groupedByKategori["PHMJ"]) {
                        //         groupedByKategori["PHMJ"] = [];
                        //     }
                        //     groupedByKategori["PHMJ"].push(item);
                        // }

                        // Tambahkan juga ke kategori aslinya (Diaken/Penatua)
                        if (!groupedByKategori[kategori]) {
                            groupedByKategori[kategori] = [];
                        }
                        groupedByKategori[kategori].push(item);
                        
                        if (item.phmj) {
                            if (!groupedByKategori["PHMJ"]) {
                                groupedByKategori["PHMJ"] = [];
                            }
                            groupedByKategori["PHMJ"].push(item);
                        }
                    });

                    // Urutkan agar kategori "PHMJ" muncul pertama
                    let orderedCategories = Object.keys(groupedByKategori);

                    // Generate tabel berdasarkan kategori
                    orderedCategories.forEach(function(kategori) {
                        let tableId = `table_${kategori.replace(/\s+/g, '_')}`;
                        let isPHMJ = kategori === "PHMJ"; // Cek apakah kategori ini PHMJ

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
                                                            <th>Periode Jabatan</th>
                                                            <th>Nama</th>`;
                                                            // ${kategori.startsWith('Pengurus') || kategori.startsWith('Pelayan') ? '<th>Pelkat</th>' : ''}
                                                            // Tambahkan kolom "Jabatan & Periode" jika kategori PHMJ
                                                            if (isPHMJ) {
                                                                card += `<th>Jabatan</th>
                                                                         <th>Periode PHMJ</th>`;
                                                            }
                                                            card += `
                                                            <th>Keterangan</th>
                                                            <th>Foto</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>`;

                        groupedByKategori[kategori].forEach(function(pelayan, index) {
                            let fotoUrl = pelayan.foto ? `{{ asset('storage/images/pelayan/') }}/${pelayan.foto}` : `{{ asset('storage/images/pelayan/avatar.png') }}`;
                            card += `<tr>
                                        <td>${index + 1}</td>
                                        <td>${pelayan.masa_jabatan}</td>
                                        <td>${pelayan.nama}</td>`;
                                        // ${kategori.startsWith('Pengurus') || kategori.startsWith('Pelayan') ? `<td>${pelayan.pelkat ? pelayan.pelkat.pelkat_nama : '-'}</td>` : ''}
                                        // Tambahkan data "Jabatan & Periode" jika kategori PHMJ
                                            if (isPHMJ) {
                                                let jabatan = pelayan.phmj ? pelayan.phmj.jabatan : '-';
                                                let periode = pelayan.masa_jabatan ? pelayan.masa_jabatan : '-';
                                                let periodeMulai = pelayan.phmj ? pelayan.phmj.periode_mulai : '-';
                                                let periodeSelesai = pelayan.phmj ? pelayan.phmj.periode_selesai : '-';
                                                let periodeJabatan = (periodeMulai !== '-' && periodeSelesai !== '-') ? `${periodeMulai} - ${periodeSelesai}` : '-';
                                                card += `<td>${jabatan}</td>
                                                <td>${periodeJabatan}</td>`;                                        
                                            }
                                        card += `
                                        <td>${pelayan.keterangan ? pelayan.keterangan : '-'}</td>
                                        <td><img src="${fotoUrl}" class="img-thumbnail" style="max-width: 100px; height: auto;"></td>
                                        <td>${pelayan.aksi}</td>
                                    </tr>`;
                        });

                        card += `</tbody>
                                </table>
                            </div>
                        </div>
                    </div>`;

                        container.append(card);

                        // Inisialisasi DataTables setelah elemen tabel ditambahkan ke DOM
                        $(`#${tableId}`).DataTable({
                            responsive: true,
                            lengthChange: true, 
                            autoWidth: false,
                            searching: true,
                            ordered: true,
                        });
                    });
                }
            });
        }

        function loadFilterTahun() {
        $.ajax({
            url: "{{ url('pengelolaan-informasi/pelayan/list') }}",
            type: "POST",
            success: function(response) {
                let minYear = response.minYear ? parseInt(response.minYear) : new Date().getFullYear();
                let maxYear = response.maxYear ? parseInt(response.maxYear) : new Date().getFullYear();
                let currentYear = new Date().getFullYear(); // Tahun saat ini
                let futureYear = currentYear + 5; // Bisa diubah sesuai kebutuhan

                let dropdown = $('#filter-tahun');
                dropdown.empty();
                dropdown.append('<option value="">Semua Tahun</option>');

                for (let year = maxYear; year <= futureYear; year++) {
                    dropdown.append('<option value="' + year + '">' + year + '</option>');
                }
            }
        });
    }


        $('#filter-tahun').on('change', function() {
            loadPelayan();
        });

        loadFilterTahun();
        loadPelayan();
    });
</script>
@endpush
