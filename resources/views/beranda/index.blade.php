@extends('layouts.template')

@section('content')

    <div class="card p-4">
        <div class="d-flex align-items-center">
            {{-- <img src="{{ asset('images/users/avatar-2.png') }}" class="rounded-circle mr-3" width="80" alt="User"> --}}
            <img src="
                @if(auth()->user()->level->level_kode == 'SAD')
                    {{ asset('images/users/avatar-2.png') }}
                @elseif(auth()->user()->level->level_kode == 'ADM')
                    {{ asset('images/users/avatar-4.png') }}
                @elseif(auth()->user()->level->level_kode == 'MLJ')
                    {{ asset('images/users/avatar-1.png') }}
                @elseif(auth()->user()->level->level_kode == 'PHM')
                    {{ asset('images/users/avatar-3.png') }}
                @else
                    {{ asset('images/users/avatar.png') }}
                @endif
            " class="rounded-circle mr-3" width="80" alt="User">

            <div class="ml-3">
                <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                <p class="text-muted">Username : {{ Auth::user()->username }}</p>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Filter Data</h3>
            @if (auth()->user()->level->level_kode == 'ADM') 
                <!-- Tombol Export Semua Grafik -->
                <div class="text-right">
                    <button id="exportAllExcel" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Excel</button>
                    <button id="exportAllPNG" class="btn btn-sm btn-secondary"><i class="fas fa-image"></i> PNG</button>
                    <button id="exportAllPDF" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> PDF</button>
                </div>
            @endif
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('beranda.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="hari">Hari</label>
                        <select name="hari" id="hari" class="form-control">
                            <option value="">Semua Hari</option>
                            @foreach(range(1, 31) as $d)
                                <option value="{{ str_pad($d, 2, '0', STR_PAD_LEFT) }}" 
                                    {{ $hari == str_pad($d, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ $d }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="bulan">Bulan</label>
                        <select name="bulan" id="bulan" class="form-control">
                            <option value="">Semua Bulan</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" 
                                    {{ $bulan == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="tahun">Tahun</label>
                        <select name="tahun" id="tahun" class="form-control">
                            <option value="">Semua Tahun</option>
                            @foreach(range(date('Y') - 5, date('Y')) as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">Grafik Kehadiran, Persembahan, dan Kategori Persembahan</h3>
        </div>
        <div class="card-body"> --}}
            <div class="row">
                <!-- Grafik Kehadiran -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title text-center">Grafik Kehadiran</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="grafikKehadiran" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            
                <!-- Grafik Persembahan -->
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title text-center">Grafik Persembahan</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="grafikPersembahan" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Grafik Kategori Persembahan -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title text-center">Grafik Kategori Persembahan</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="grafikKategoriPersembahan" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            
                <!-- Grafik Distribusi Persembahan Berdasarkan Minggu -->
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title text-center">Distribusi Persembahan Berdasarkan Minggu</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="grafikPersembahanMinggu" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        {{-- </div>
    </div> --}}

@endsection

@push('css')
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script>
    // Langkah ke-2: Kirim data dari controller ke JavaScript
    var dataBeritaAcara = @json($data);
    var totalKategoriPersembahanData = @json($totalKategoriPersembahan); // Data agregat baru

    document.addEventListener('DOMContentLoaded', function () {
        const labels = dataBeritaAcara.map(item => item.tanggal);
        const dataKehadiran = dataBeritaAcara.map(item => item.jumlah_kehadiran);
        const dataPersembahan = dataBeritaAcara.map(item => item.total_persembahan);

        // Grafik Kehadiran
        const ctxKehadiran = document.getElementById('grafikKehadiran').getContext('2d');
        new Chart(ctxKehadiran, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Kehadiran',
                    data: dataKehadiran,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Tanggal' } },
                    y: { title: { display: true, text: 'Jumlah Kehadiran' }, beginAtZero: true }
                }
            }
        });

        // Grafik Persembahan
        const ctxPersembahan = document.getElementById('grafikPersembahan').getContext('2d');
        new Chart(ctxPersembahan, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Persembahan',
                    data: dataPersembahan,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: 'Tanggal' } },
                    y: { title: { display: true, text: 'Jumlah Persembahan' }, beginAtZero: true }
                }
            }
        });

        // Grafik Kategori Persembahan
        const kategoriLabels = Object.keys(totalKategoriPersembahanData || {}); // Gunakan data agregat
        const kategoriData = Object.values(totalKategoriPersembahanData || {}); // Gunakan data agregat

        const ctxKategoriPersembahan = document.getElementById('grafikKategoriPersembahan').getContext('2d');
        new Chart(ctxKategoriPersembahan, {
            type: 'bar', // Gunakan bar chart
            data: {
                labels: kategoriLabels, // Nama kategori
                datasets: [{
                    label: 'Total Persembahan per Kategori', // Label diperbarui
                    data: kategoriData, // Total persembahan per kategori
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y', // Membuat bar chart horizontal
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'nearest', // Tooltip muncul untuk data terdekat
                        intersect: false, // Tooltip muncul meskipun kursor tidak tepat di atas bar
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Total Persembahan'
                        },
                        beginAtZero: true
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Kategori'
                        }
                    }
                }
            }
        });

        // Data untuk Grafik Persembahan Berdasarkan Minggu
        const mingguLabels = @json($persembahanMinggu->keys());
        const mingguData = @json($persembahanMinggu->values());

        // Grafik Distribusi Persembahan Berdasarkan Minggu (Doughnut Chart)
        const ctxPersembahanMinggu = document.getElementById('grafikPersembahanMinggu').getContext('2d');
        new Chart(ctxPersembahanMinggu, {
            type: 'doughnut', // Ubah tipe menjadi doughnut
            data: {
                labels: mingguLabels, // Label Minggu
                datasets: [{
                    label: 'Total Persembahan',
                    data: mingguData, // Total persembahan per minggu
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top', // Posisi legenda di atas
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false, // Tooltip muncul untuk semua dataset
                    }
                }
            }
        });

        
    });

    // Fungsi untuk mengekspor semua grafik ke Excel
    document.getElementById('exportAllExcel').addEventListener('click', function () {
        const wb = XLSX.utils.book_new();

        // Grafik Kehadiran
        const dataKehadiranSheet = [['Tanggal', 'Jumlah Kehadiran']];
        dataBeritaAcara.forEach(item => {
            dataKehadiranSheet.push([item.tanggal, item.jumlah_kehadiran]);
        });
        const wsKehadiran = XLSX.utils.aoa_to_sheet(dataKehadiranSheet);
        XLSX.utils.book_append_sheet(wb, wsKehadiran, 'Grafik Kehadiran');

        // Grafik Total Persembahan
        const dataPersembahanSheet = [['Tanggal', 'Total Persembahan']];
        dataBeritaAcara.forEach(item => {
            dataPersembahanSheet.push([item.tanggal, item.total_persembahan]);
        });
        const wsPersembahan = XLSX.utils.aoa_to_sheet(dataPersembahanSheet);
        XLSX.utils.book_append_sheet(wb, wsPersembahan, 'Grafik Persembahan');

        // Grafik Kategori Persembahan
        const kategoriLabels = Object.keys(dataBeritaAcara[0]?.kategori_persembahan || {});
        const kategoriData = Object.values(dataBeritaAcara[0]?.kategori_persembahan || {});
        const dataKategoriSheet = [['Kategori', 'Jumlah']];
        kategoriLabels.forEach((label, index) => {
            dataKategoriSheet.push([label, kategoriData[index]]);
        });
        const wsKategori = XLSX.utils.aoa_to_sheet(dataKategoriSheet);
        XLSX.utils.book_append_sheet(wb, wsKategori, 'Kategori Persembahan');

        // Grafik Distribusi Berdasarkan Minggu
        const mingguLabels = Object.keys(@json($persembahanMinggu->toArray()));
        const mingguData = Object.values(@json($persembahanMinggu->toArray()));
        const dataMingguSheet = [['Minggu ke-', 'Jumlah']];
        mingguLabels.forEach((label, index) => {
            dataMingguSheet.push([label, mingguData[index]]);
        });
        const wsMinggu = XLSX.utils.aoa_to_sheet(dataMingguSheet);
        XLSX.utils.book_append_sheet(wb, wsMinggu, 'Persembahan per Minggu');

        // Unduh file Excel
        XLSX.writeFile(wb, 'semua_grafik.xlsx');
    });


    // Fungsi untuk mengekspor grafik ke PNG
    document.getElementById('exportAllPNG').addEventListener('click', function () {
        const grafikIds = ['grafikKehadiran', 'grafikPersembahan', 'grafikKategoriPersembahan', 'grafikPersembahanMinggu'];
        const zip = new JSZip(); // Inisialisasi objek JSZip
        const folder = zip.folder('grafik'); // Buat folder di dalam ZIP

        grafikIds.forEach((id) => {
            const canvas = document.getElementById(id);
            if (canvas) {
                const image = canvas.toDataURL('image/png', 1.0); // Konversi canvas ke data URL
                const base64Data = image.split(',')[1]; // Ambil data base64 dari data URL
                folder.file(`${id}.png`, base64Data, { base64: true }); // Tambahkan file PNG ke folder ZIP
            } else {
                console.error(`Canvas dengan ID ${id} tidak ditemukan.`);
            }
        });

        // Generate file ZIP dan unduh
        zip.generateAsync({ type: 'blob' }).then(function (content) {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(content);
            link.download = 'grafik.zip'; // Nama file ZIP
            link.click();
        });
    });

    // Fungsi untuk mengekspor semua grafik ke PDF
    document.getElementById('exportAllPDF').addEventListener('click', function () {
        const { jsPDF } = window.jspdf; // Pastikan jsPDF diakses dari namespace window.jspdf
        const pdf = new jsPDF('landscape'); // Buat PDF dalam orientasi landscape
        const grafikIds = ['grafikKehadiran', 'grafikPersembahan', 'grafikKategoriPersembahan', 'grafikPersembahanMinggu'];

        grafikIds.forEach((id, index) => {
            const canvas = document.getElementById(id);
            if (canvas) {
                console.log(`Canvas ditemukan: ${id}`);
                const image = canvas.toDataURL('image/png', 1.0);
                if (index > 0) pdf.addPage();
                pdf.addImage(image, 'PNG', 10, 10, 280, 150);
            } else {
                console.error(`Canvas dengan ID ${id} tidak ditemukan.`);
            }
        });

        pdf.save('semua_grafik.pdf'); // Unduh PDF
    });
</script>
@endpush