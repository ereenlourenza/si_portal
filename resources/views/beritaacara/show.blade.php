@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="text-right">
                @if (auth()->user()->level->level_kode == 'ADM' || auth()->user()->level->level_kode == 'PHM')
                    <a href="{{ route('berita-acara.exportPdf', $berita->berita_acara_ibadah_id) }}" class="btn btn-sm btn-danger mb-3" target="_blank">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                    <a href="{{ route('berita-acara.exportPersembahan', ['id' => $berita->berita_acara_ibadah_id]) }}" class="btn btn-sm btn-success mb-3">
                        <i class="fas fa-file-excel"></i> Export Persembahan (Excel)
                    </a>
                @endif
            </div>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($berita)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-md">
                    <tr>
                        <th>ID</th>
                        <td>{{ $berita->berita_acara_ibadah_id }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $berita->ibadah->tanggal }}</td>
                    </tr>
                    <tr>
                        <th>Waktu</th>
                        <td>{{ \Carbon\Carbon::parse($berita->ibadah->waktu)->format('H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Tempat</th>
                        <td>{{ $berita->ibadah->tempat }}</td>
                    </tr>
                    <tr>
                        <th>Bacaan Alkitab</th>
                        <td>{{ $berita->bacaan_alkitab }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Kehadiran</th>
                        <td>{{ $berita->jumlah_kehadiran }} orang</td>
                    </tr>
                </table>

                <h5 class="mt-4">Daftar Pelayan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-md">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Peran Pelayan</th>
                                <th>Pelayan Jadwal</th>
                                <th>Pelayan Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($berita->petugas as $index => $petugas)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $petugas->peran }}</td>
                                    <td>{{ $petugas->pelayanJadwal ? $petugas->pelayanJadwal->nama : '-' }}</td>
                                    <td>{{ $petugas->pelayanHadir ? $petugas->pelayanHadir->nama : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h5 class="mt-4">Daftar Persembahan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover table-md">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Kategori Persembahan</th>
                                <th>Jenis Input</th>
                                <th>Total</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($berita->persembahan as $index => $persembahan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $persembahan->kategori ? $persembahan->kategori->kategori_persembahan_nama : '-' }}</td>
                                    <td>{{ ucfirst($persembahan->jenis_input) }}</td>
                                    <td class="text-right" style="currency" currency="IDR">Rp {{ number_format($persembahan->total, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        @if ($persembahan->jenis_input !== 'langsung')  <!-- Mengecek apakah jenis_input bukan langsung -->
                                            <button class="btn btn-sm btn-primary" type="button" data-toggle="collapse" data-target="#detail-{{ $index }}" aria-expanded="false" aria-controls="detail-{{ $index }}">
                                                Lihat Detail
                                            </button>
                                        @else
                                            Tidak ada detail.
                                        @endif
                                    </td>
                                </tr>
                                <tr class="collapse" id="detail-{{ $index }}">
                                    <td colspan="5">
                                        @if ($persembahan->jenis_input === 'amplop')
                                            <div class="text-center mt-4 mb-4">
                                                <h6 class="mb-3">Detail Amplop</h6>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-md text-center w-auto mx-auto">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>No Amplop</th>
                                                                <th>Nama</th>
                                                                <th>Jumlah (Rp)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $totalAmplop = 0; // Variabel untuk menyimpan total jumlah amplop
                                                            @endphp
                                                            @foreach ($persembahan->amplop as $amplop)
                                                                <tr>
                                                                    <td>{{ $amplop->no_amplop }}</td>
                                                                    <td>{{ $amplop->nama_pengguna_amplop }}</td>
                                                                    <td>{{ number_format($amplop->jumlah, 0, ',', '.') }}</td>
                                                                </tr>
                                                                @php
                                                                    $totalAmplop += $amplop->jumlah; // Tambahkan jumlah amplop ke total
                                                                @endphp
                                                            @endforeach
                                                            <tr class="table-success">
                                                                <td colspan="2" class="text-end"><strong>Total Semua Amplop</strong></td>
                                                                <td><strong>{{ number_format($totalAmplop, 0, ',', '.') }}</strong></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @elseif ($persembahan->jenis_input === 'lembaran')
                                            <h6 class="mt-4 mb-4 text-center">Detail Lembaran</h6>
                                            @foreach ($persembahan->lembaran as $lembaran)
                                                @php
                                                    $total_100 = 100 * $lembaran->jumlah_100;
                                                    $total_200 = 200 * $lembaran->jumlah_200;
                                                    $total_500 = 500 * $lembaran->jumlah_500;
                                                    $total_1000_koin = 1000 * $lembaran->jumlah_1000_koin;
                                                    $total_1000_kertas = 1000 * $lembaran->jumlah_1000_kertas;
                                                    $total_2000 = 2000 * $lembaran->jumlah_2000;
                                                    $total_5000 = 5000 * $lembaran->jumlah_5000;
                                                    $total_10000 = 10000 * $lembaran->jumlah_10000;
                                                    $total_20000 = 20000 * $lembaran->jumlah_20000;
                                                    $total_50000 = 50000 * $lembaran->jumlah_50000;
                                                    $total_100000 = 100000 * $lembaran->jumlah_100000;

                                                    $total_persembahan = $total_100 + $total_200 + $total_500 + $total_1000_koin + $total_1000_kertas + $total_2000 +
                                                                        $total_5000 + $total_10000 + $total_20000 + $total_50000 + $total_100000;
                                                @endphp

                                                <div class="table-responsive mb-4 d-flex justify-content-center">
                                                    <table class="table table-bordered table-striped table-md text-center w-auto">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Pecahan</th>
                                                                <th>Jumlah</th>
                                                                <th>Nilai (Rp)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr><td>100</td><td>{{ $lembaran->jumlah_100 }}</td><td class="text-right">{{ number_format($total_100, 0, ',', '.') }}</td></tr>
                                                            <tr><td>200</td><td>{{ $lembaran->jumlah_200 }}</td><td class="text-right">{{ number_format($total_200, 0, ',', '.') }}</td></tr>
                                                            <tr><td>500</td><td>{{ $lembaran->jumlah_500 }}</td><td class="text-right">{{ number_format($total_500, 0, ',', '.') }}</td></tr>
                                                            <tr><td>1.000 (koin)</td><td>{{ $lembaran->jumlah_1000_koin }}</td><td class="text-right">{{ number_format($total_1000_koin, 0, ',', '.') }}</td></tr>
                                                            <tr><td>1.000 (kertas)</td><td>{{ $lembaran->jumlah_1000_kertas }}</td><td class="text-right">{{ number_format($total_1000_kertas, 0, ',', '.') }}</td></tr>
                                                            <tr><td>2.000</td><td>{{ $lembaran->jumlah_2000 }}</td><td class="text-right">{{ number_format($total_2000, 0, ',', '.') }}</td></tr>
                                                            <tr><td>5.000</td><td>{{ $lembaran->jumlah_5000 }}</td><td class="text-right">{{ number_format($total_5000, 0, ',', '.') }}</td></tr>
                                                            <tr><td>10.000</td><td>{{ $lembaran->jumlah_10000 }}</td><td class="text-right">{{ number_format($total_10000, 0, ',', '.') }}</td></tr>
                                                            <tr><td>20.000</td><td>{{ $lembaran->jumlah_20000 }}</td><td class="text-right">{{ number_format($total_20000, 0, ',', '.') }}</td></tr>
                                                            <tr><td>50.000</td><td>{{ $lembaran->jumlah_50000 }}</td><td class="text-right">{{ number_format($total_50000, 0, ',', '.') }}</td></tr>
                                                            <tr><td>100.000</td><td>{{ $lembaran->jumlah_100000 }}</td><td class="text-right">{{ number_format($total_100000, 0, ',', '.') }}</td></tr>
                                                            <tr class="table-success fw-bold">
                                                                <td colspan="2" class="text-end text-bold">Total Semua Lembaran</td>
                                                                <td class="text-right">{{ number_format($total_persembahan, 0, ',', '.') }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="mt-4 mb-4 text-center">
                                                Tidak ada detail.
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endempty

            {{-- Tanda Tangan Pelayan --}}
            @if(!empty($berita))
            <div class="row mt-5 mb-5">
                <div class="col-md-6 text-center">
                    <p>Pelayan 1,</p>
                    @if ($berita->ttd_pelayan_1_img)
                        <img src="{{ asset($berita->ttd_pelayan_1_img) }}" alt="TTD Pelayan 1" style="width: 150px; height: auto;">
                    @else
                        <br><br>
                        <p>____________________</p>
                    @endif
                    <br>
                    <p>
                        <strong style="margin-top: 5px;text-decoration: underline;">
                            ({{ $berita->pelayan1 ? $berita->pelayan1->nama : 'Nama Pelayan 1 Tidak Tersedia' }})
                        </strong>
                    </p>
                </div>
                <div class="col-md-6 text-center">
                    <p>Pelayan 4,</p>
                    @if ($berita->ttd_pelayan_4_img)
                        <img src="{{ asset($berita->ttd_pelayan_4_img) }}" alt="TTD Pelayan 4" style="width: 150px; height: auto;">
                    @else
                        <br><br>
                        <p>____________________</p>
                    @endif
                    <br>
                    <p>
                        <strong style="margin-top: 5px;text-decoration: underline;">
                            ({{ $berita->pelayan4 ? $berita->pelayan4->nama : 'Nama Pelayan 4 Tidak Tersedia' }})
                        </strong>
                    </p>
                </div>
            </div>
            @endif
            
            <a href="{{ url('pengelolaan-berita-acara/berita-acara') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush    