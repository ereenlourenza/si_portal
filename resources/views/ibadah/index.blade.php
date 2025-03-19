@extends('layouts.template')

@section('content')

    {{-- Bulan Picker --}}
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

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <a class="btn btn-sm btn-primary mt-1" href="{{ url('pengelolaan-informasi/ibadah/create') }}">Tambah Ibadah</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success_ibadah'))
                <div class='alert alert-success'>{{ session('success_ibadah' ) }}</div>
            @endif
            @if(session('error_ibadah'))
                <div class='alert alert-danger'>{{ session('error_ibadah' ) }}</div>
            @endif

            <div id="ibadah-container" class="row">
                <!-- Card untuk setiap kategori akan di-generate di sini -->
            </div>
            
        </div>
    </div>
    
@endsection

@push('css')
@endpush

@push('js')
    <script>
        $(document).ready(function() {

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

            function loadIbadahData() {
                let bulanFilter = $('#filter_bulan').val();

                $.ajax({
                    url: "{{ url('pengelolaan-informasi/ibadah/list') }}",
                    type: "POST",
                    dataType: "json",
                    data: { tanggal: bulanFilter },
                    success: function(response) {
                        let container = $('#ibadah-container');
                        container.empty();

                        let ibadahData = response.data;
                        let groupedByKategori = {};
                        let kategoriKolom = {
                            "Ibadah Keluarga": ["ID", "Tanggal", "Waktu",  "Sektor","Tempat", "Lokasi", "Pelayan Firman", "Aksi"],
                            "Ibadah Pengucapan Syukur": ["ID", "Tanggal", "Waktu",  "Sektor","Tempat", "Lokasi", "Pelayan Firman", "Aksi"],
                            "Ibadah Diakonia": ["ID", "Tanggal", "Waktu",  "Sektor","Tempat", "Lokasi", "Pelayan Firman", "Aksi"],
                            "Ibadah Pelkat": ["ID", "Tanggal", "Waktu", "Nama Pelkat", "Tempat",  "Ruang", "Pelayan Firman", "Aksi"]
                        };

                        // Kelompokkan berdasarkan kategori
                        ibadahData.forEach(function(item) {
                            let kategoriID = item.kategoriibadah.kategoriibadah_id; // Pastikan ID ada
                            let kategoriNama = item.kategoriibadah.kategoriibadah_nama; // Pastikan Nama ada

                            if (!groupedByKategori[kategoriID]) {
                                groupedByKategori[kategoriID] = {
                                    nama: kategoriNama,  // Simpan nama kategori
                                    data: []
                                };
                            }
                            groupedByKategori[kategoriID].data.push(item);
                        });

                        // Object.keys(groupedByKategori).forEach(function(kategori) {
                        //     let tableId = `table_${kategori.replace(/\s+/g, '_')}`;

                        //     let card = `<div class="col-md-12">
                        //                     <div class="card card-dark">
                        //                         <div class="card-header">
                        //                             <h5 class="card-title">${kategori}</h5>
                        //                         </div>
                        //                         <div class="card-body">
                        //                             <table class="table table-bordered table-striped table-hover table-md" id="${tableId}">
                        //                                 <thead>
                        //                                     <tr>
                        //                                         <th>ID</th>
                        //                                         <th>Tanggal</th>
                        //                                         <th>Waktu</th>
                        //                                         <th>Tempat</th>
                        //                                         <th>Pelayan Firman</th>
                        //                                         <th>Aksi</th>
                        //                                     </tr>
                        //                                 </thead>
                        //                                 <tbody>`;

                        //     if (groupedByKategori[kategori].length > 0) {
                        //         groupedByKategori[kategori].forEach(function(ibadah, index) {
                        //             card += `<tr>
                        //                         <td>${index + 1}</td>
                        //                         <td>${ibadah.tanggal}</td>
                        //                         <td>${ibadah.waktu}</td>
                        //                         <td>${ibadah.tempat}</td>
                        //                         <td>${ibadah.pelayan_firman}</td>
                        //                         <td>${ibadah.aksi}</td>
                        //                     </tr>`;
                        //         });
                        //     }

                        //     card += `</tbody>
                        //                             </table>
                        //                         </div>
                        //                     </div>
                        //                 </div>`;

                        //     container.append(card);

                        //     // Inisialisasi DataTables agar tetap muncul walaupun tidak ada data
                        //     $(`#${tableId}`).DataTable({
                        //         responsive: true,
                        //         lengthChange: true, 
                        //         autoWidth: false,
                        //         searching: true,
                        //         ordered: true,
                        //         language: {
                        //             emptyTable: "No data available"
                        //         }
                        //     });

                        // });
                        if (Object.keys(groupedByKategori).length === 0) {
                            container.append(`<div class="col-md-12 text-center">
                                <p class="text-muted">Tidak ada data ibadah</p>
                            </div>`);
                        }
                        
                        Object.keys(groupedByKategori).forEach(function(kategoriID) {
                            let kategoriNama = groupedByKategori[kategoriID].nama;
                            let tableId = `table_${(kategoriNama || 'Unknown').replace(/\s+/g, '_')}`;

                            // Jika kategori ada di kategoriKolom, gunakan strukturnya. Kalau tidak, pakai default
                            let kolom = kategoriKolom[kategoriNama] || ["ID", "Tanggal", "Waktu", "Tempat", "Pelayan Firman", "Aksi"];

                            let card = `<div class="col-md-12">
                                            <div class="card card-dark">
                                                <div class="card-header">
                                                    <h5 class="card-title">${kategoriNama}</h5>
                                                </div>
                                                <div class="card-body">
                                                    <table class="table table-bordered table-striped table-hover table-md" id="${tableId}">
                                                        <thead>
                                                            <tr>`;

                                                            kolom.forEach(col => {
                                                                card += `<th>${col}</th>`;
                                                            });

                                                            card += 
                                                            
                                                            `</tr>
                                                        </thead>
                                                        <tbody>`;

                                                            groupedByKategori[kategoriID].data.forEach((ibadah, index) => {
                                                                card += `<tr>`;
                                                                kolom.forEach(col => {
                                                                    if (col === "ID") card += `<td>${index + 1}</td>`;
                                                                    else if (col === "Tanggal") card += `<td>${ibadah.tanggal}</td>`;
                                                                    else if (col === "Waktu") card += `<td>${ibadah.waktu || '-'}</td>`;
                                                                    else if (col === "Tempat") card += `<td>${ibadah.tempat || '-'}</td>`;
                                                                    else if (col === "Pelayan Firman") card += `<td>${ibadah.pelayan_firman || '-'}</td>`;
                                                                    else if (col === "Lokasi") card += `<td>${ibadah.lokasi || '-'}</td>`; // Hanya untuk Ibadah Keluarga
                                                                    else if (col === "Sektor") card += `<td>${ibadah.sektor || '-'}</td>`; // Hanya untuk Ibadah Keluarga
                                                                    else if (col === "Nama Pelkat") card += `<td>${ibadah.nama_pelkat || '-'}</td>`; // Hanya untuk Ibadah Keluarga
                                                                    else if (col === "Ruang") card += `<td>${ibadah.ruang || '-'}</td>`; // Hanya untuk Ibadah Keluarga
                                                                    else if (col === "Aksi") card += `<td>${ibadah.aksi || '-'}</td>`;
                                                                    else card += `<td>-</td>`; // Jika ada kolom lain di masa depan
                                                                });
                                                                card += `</tr>`;
                                                            });

                                                            card += 

                                                        `</tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>`;

                            container.append(card);

                            // Inisialisasi DataTables
                            if ($.fn.DataTable.isDataTable(`#${tableId}`)) {
                                $(`#${tableId}`).DataTable().destroy();
                            }
                            $(`#${tableId}`).DataTable({
                                responsive: true,
                                lengthChange: true, 
                                autoWidth: false,
                                searching: true,
                                ordered: true,
                                language: {
                                    emptyTable: "No data available"
                                }
                            });
                        });

                    },
                    error: function() {
                        console.error("Gagal mengambil data ibadah.");
                    }
                });
            }


            // Panggil fungsi saat halaman dimuat pertama kali
            loadIbadahData();

            // Event saat tombol filter ditekan
            $('#btn_filter').on('click', function() {
                loadIbadahData();
            });

            // Event saat tombol reset ditekan
            $('#btn_reset').on('click', function() {
                $('#filter_bulan').val('');
                loadIbadahData();
            });

            // Event saat bulan dipilih
            $('#filter_bulan').on('change', function(){
                loadIbadahData();
            });

        });
    </script>
@endpush
