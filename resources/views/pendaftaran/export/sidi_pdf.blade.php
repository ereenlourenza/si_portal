<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Sidi - {{ $pendaftaran->nama_lengkap }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 0; padding: 0; }
        h2 { text-align: center; margin-bottom: 20px; }
        h3 { text-align: center; margin-bottom: 10px; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; vertical-align: top; }
        th { background-color: #f2f2f2; }
        .label { font-weight: bold; width: 30%; }
        .page-break { page-break-after: always; }
        .image-container {
            margin-top: 20px;
            text-align: center;
            width: 100%; /* A4 width approx 210mm */
            height: 270mm; /* A4 height approx 297mm, leaving some margin for title */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            page-break-inside: avoid; /* Try to avoid breaking image container itself */
        }
        .image-container img {
            max-width: 100%;
            max-height: 250mm; /* Adjusted to fit title within 270mm container height */
            object-fit: contain;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .image-title {
            font-weight: bold;
            margin-bottom: 15px; /* Increased margin for better separation */
            text-align: center;
            font-size: 1.1em;
        }
        .no-attachments { text-align: center; margin-top: 20px; font-style: italic; }
        .lampiran-title {
            font-size: 1.5em;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            margin-top: 30px;
        }
        .section-title {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Detail Pendaftaran Katekisasi (Sidi)</h2>

    <table>
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>{{ $pendaftaran->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td>{{ $pendaftaran->tempat_lahir }}, {{ $pendaftaran->tanggal_lahir ? \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->format('d M Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td>{{ $pendaftaran->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Katekumen</td>
            <td>{{ $pendaftaran->alamat_katekumen }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Telepon Katekumen</td>
            <td>{{ $pendaftaran->nomor_telepon_katekumen }}</td>
        </tr>
        <tr>
            <td class="label">Pendidikan Terakhir</td>
            <td>{{ $pendaftaran->pendidikan_terakhir }}</td>
        </tr>
        <tr>
            <td class="label">Pekerjaan</td>
            <td>{{ $pendaftaran->pekerjaan }}</td>
        </tr>
        <tr>
            <td class="label">Status Baptis</td>
            <td>{{ $pendaftaran->is_baptis ? 'Sudah Baptis' : 'Belum Baptis' }}</td>
        </tr>
        @if($pendaftaran->is_baptis)
        <tr>
            <td class="label">Tempat Baptis</td>
            <td>{{ $pendaftaran->tempat_baptis }}</td>
        </tr>
        <tr>
            <td class="label">No. Surat Baptis</td>
            <td>{{ $pendaftaran->no_surat_baptis }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Surat Baptis</td>
            <td>{{ $pendaftaran->tanggal_surat_baptis ? \Carbon\Carbon::parse($pendaftaran->tanggal_surat_baptis)->format('d M Y') : '-' }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Dilayani Oleh</td>
            <td>{{ $pendaftaran->dilayani }}</td>
        </tr>
        @if(!$pendaftaran->is_baptis)
        <tr>
            <td class="label">Nama Ayah</td>
            <td>{{ $pendaftaran->nama_ayah }}</td>
        </tr>
        <tr>
            <td class="label">Nama Ibu</td>
            <td>{{ $pendaftaran->nama_ibu }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Ortu</td>
            <td>{{ $pendaftaran->alamat_ortu }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Telepon Ortu</td>
            <td>{{ $pendaftaran->nomor_telepon_ortu }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Status Pendaftaran</td>
            <td>
                @if($pendaftaran->status == 0)
                    Menunggu Persetujuan
                @elseif($pendaftaran->status == 1)
                    Disetujui
                @elseif($pendaftaran->status == 2)
                    Ditolak
                @else
                    Tidak diketahui
                @endif
            </td>
        </tr>
        @if($pendaftaran->status == 2 && $pendaftaran->alasan_penolakan)
        <tr>
            <td class="label">Alasan Penolakan</td>
            <td>{{ $pendaftaran->alasan_penolakan }}</td>
        </tr>
        @endif
    </table>

    @php
        $attachments = [];
        if ($pendaftaran->akta_kelahiran) {
            $attachments[] = ['file' => $pendaftaran->akta_kelahiran, 'title' => 'Akta Kelahiran', 'path_prefix' => 'images/sidi/'];
        }
        if ($pendaftaran->is_baptis && $pendaftaran->surat_baptis) {
            $attachments[] = ['file' => $pendaftaran->surat_baptis, 'title' => 'Surat Baptis', 'path_prefix' => 'images/sidi/'];
        }
        if ($pendaftaran->pas_foto) {
            $attachments[] = ['file' => $pendaftaran->pas_foto, 'title' => 'Pas Foto', 'path_prefix' => 'images/sidi/'];
        }
    @endphp

    @if(count($attachments) > 0)
        {{-- <div class="page-break"></div> --}}
        {{-- <h3 class="lampiran-title">Lampiran</h3> --}}

        @foreach($attachments as $index => $attachment)
            {{-- @if($index > 0)
                <div class="page-break"></div>
            @endif --}}
            <div class="image-container">
                <div class="image-title">{{ $attachment['title'] }}</div>
                <img src="{{ public_path('storage/' . $attachment['path_prefix'] . $attachment['file']) }}" alt="{{ $attachment['title'] }}">
            </div>
        @endforeach
    @else
        <p class="no-attachments">Tidak ada lampiran.</p>
    @endif

</body>
</html>
