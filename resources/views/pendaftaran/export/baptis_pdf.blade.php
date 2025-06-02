<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Baptis - {{ $pendaftaran->nama_lengkap }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        h4 { /* For "Lampiran" title and other h4 if any */
            text-align: center;
            margin-top: 20px;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: bold;
        }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .image-container {
            margin-top: 10px; /* Space between H4 and first image title, or between page-break and image title */
            text-align: center;
            width: 100%;
            page-break-inside: avoid; /* Attempt to keep image title and image on the same page */
        }
        .image-container .label { /* Specifically for the title of the image */
            font-weight: bold;
            text-align: center;
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .image-container img {
            max-width: 100%;
            max-height: 250mm; /* Adjusted for A4 portrait (297mm height), leaving space for its title & page margins */
            border: 1px solid #ddd;
            object-fit: contain;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .label { font-weight: bold; } /* General label for table cells, kept for compatibility */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <h2>Detail Pendaftaran Baptis</h2>

    <table>
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>{{ $pendaftaran->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td>{{ $pendaftaran->tempat_lahir }}, {{ \Carbon\Carbon::parse($pendaftaran->tanggal_lahir)->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td>{{ $pendaftaran->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Ayah</td>
            <td>{{ $pendaftaran->nama_ayah }}</td>
        </tr>
        <tr>
            <td class="label">Nama Ibu</td>
            <td>{{ $pendaftaran->nama_ibu }}</td>
        </tr>
        <tr>
            <td class="label">Tempat & Tanggal Pernikahan Ortu</td>
            <td>{{ $pendaftaran->tempat_pernikahan }}, {{ $pendaftaran->tanggal_pernikahan ? \Carbon\Carbon::parse($pendaftaran->tanggal_pernikahan)->format('d M Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tempat & Tanggal Sidi Ayah</td>
            <td>{{ $pendaftaran->tempat_sidi_ayah ?? '-' }}, {{ $pendaftaran->tanggal_sidi_ayah ? \Carbon\Carbon::parse($pendaftaran->tanggal_sidi_ayah)->format('d M Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tempat & Tanggal Sidi Ibu</td>
            <td>{{ $pendaftaran->tempat_sidi_ibu ?? '-' }}, {{ $pendaftaran->tanggal_sidi_ibu ? \Carbon\Carbon::parse($pendaftaran->tanggal_sidi_ibu)->format('d M Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td>{{ $pendaftaran->alamat }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Telepon</td>
            <td>{{ $pendaftaran->nomor_telepon }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Baptis</td>
            <td>{{ $pendaftaran->tanggal_baptis ? \Carbon\Carbon::parse($pendaftaran->tanggal_baptis)->format('d M Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Dilayani Oleh</td>
            <td>{{ $pendaftaran->dilayani }}</td>
        </tr>
        <tr>
            <td class="label">Status Pendaftaran</td>
            <td>
                @if($pendaftaran->status == 0)
                    <span class="badge badge-warning">Menunggu Persetujuan</span>
                @elseif($pendaftaran->status == 1)
                    <span class="badge badge-success">Disetujui</span>
                @elseif($pendaftaran->status == 2)
                    <span class="badge badge-danger">Ditolak</span>
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

    @if($pendaftaran->surat_nikah_ortu || $pendaftaran->akta_kelahiran_anak)
        <div class="page-break"></div> <!-- Start attachments on a new page -->
        {{-- <h4>Lampiran</h4> --}}

        @if($pendaftaran->surat_nikah_ortu)
            <div class="image-container">
                <p class="label">Surat Nikah Ortu:</p>
                <img src="{{ public_path('storage/images/baptis/' . $pendaftaran->surat_nikah_ortu) }}" alt="Surat Nikah Ortu">
            </div>
        @endif

        @if($pendaftaran->akta_kelahiran_anak)
            @if($pendaftaran->surat_nikah_ortu) <!-- If surat_nikah_ortu was printed, this is not the first attachment on this page block -->
                <div class="page-break"></div>
            @endif
            <div class="image-container">
                <p class="label">Akta Kelahiran Anak:</p>
                <img src="{{ public_path('storage/images/baptis/' . $pendaftaran->akta_kelahiran_anak) }}" alt="Akta Kelahiran Anak">
            </div>
        @endif
    @endif

</body>
</html>
