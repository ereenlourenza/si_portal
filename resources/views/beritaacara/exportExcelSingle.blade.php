<!DOCTYPE html>
<html>
<head>
    <style>
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-bottom: 20px; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 6px; 
            text-align: left; 
        }
        .section-title { 
            background: #f0f0f0; 
            font-weight: bold; 
            padding: 6px; 
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">Berita Acara Ibadah</h2>    
    <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($berita->ibadah->tanggal)->format('d M Y') }}</p>
    <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($berita->ibadah->waktu)->format('H:i') }}</p>
    <p><strong>Tempat:</strong> {{ $berita->ibadah->tempat ?? '-' }}</p>
    <p><strong>Bacaan Alkitab:</strong> {{ $berita->bacaan_alkitab }}</p>
    <p><strong>Pelayan Firman:</strong> {{ $berita->ibadah->pelayan_firman }}</p>

    <h4 class="section-title">Petugas</h4>
    <table>
        <thead>
            <tr>
                <th>Peran</th>
                <th>Jadwal</th>
                <th>Hadir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($berita->petugas as $petugas)
            <tr>
                <td>{{ $petugas->peran ?? '-' }}</td>
                <td>{{ $petugas->pelayanJadwal ? $petugas->pelayanJadwal->nama : '-' }}</td>
                <td>{{ $petugas->pelayanHadir ? $petugas->pelayanHadir->nama : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4 class="section-title">Kehadiran Jemaat</h4>
    <p><strong>Jumlah Hadir:</strong> {{ $berita->jumlah_kehadiran ?? 0 }} orang</p>

    <h4 class="section-title">Persembahan</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Kategori</th>
                <th>Jenis Input</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($berita->persembahan as $index => $persembahan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $persembahan->kategori->kategori_persembahan_nama ?? '-' }}</td>
                <td>{{ ucfirst($persembahan->jenis_input) }}</td>
                <td>Rp {{ number_format($persembahan->total, 0, ',', '.') }}</td>
            </tr>

            @if ($persembahan->jenis_input == 'amplop' && $persembahan->amplop->count())
            <tr>
                <td colspan="4">
                    <strong>Detail Amplop:</strong>
                    <table>
                        <thead>
                            <tr>
                                <th>No Amplop</th>
                                <th>Nama</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; @endphp
                            @foreach ($persembahan->amplop as $amplop)
                            @php $total += $amplop->jumlah; @endphp
                            <tr>
                                <td>{{ $amplop->no_amplop }}</td>
                                <td>{{ $amplop->nama_pengguna_amplop }}</td>
                                <td>Rp {{ number_format($amplop->jumlah, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"><strong>Total</strong></td>
                                <td><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @elseif ($persembahan->jenis_input == 'lembaran' && $persembahan->lembaran->count())
            <tr>
                <td colspan="4">
                    <strong>Detail Lembaran:</strong>
                    <table>
                        <thead>
                            <tr>
                                <th>Nominal</th>
                                <th>Jumlah Lembar</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $grandTotal = 0; @endphp
                            @foreach ($persembahan->lembaran as $lembar)
                            @php $total = $lembar->jumlah * $lembar->nominal; $grandTotal += $total; @endphp
                            <tr>
                                <td>Rp {{ number_format($lembar->nominal, 0, ',', '.') }}</td>
                                <td>{{ $lembar->jumlah }}</td>
                                <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"><strong>Total</strong></td>
                                <td><strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <h4 class="section-title">Tanda Tangan</h4>
    <table style="border: none;">
        <tr>
            <td style="border: none; width: 50%;"></td>
            <td style="border: none; width: 50%;"></td>
        </tr>
    </table>

</body>
</html>
