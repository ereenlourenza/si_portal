<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran Pernikahan - {{ $pendaftaran->nama_lengkap_pria }} & {{ $pendaftaran->nama_lengkap_wanita }}</title>
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
    <h2>Detail Pendaftaran Pernikahan</h2>

    <h3 class="section-title">Data Calon Mempelai Pria</h3>
    <table>
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>{{ $pendaftaran->nama_lengkap_pria }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td>{{ $pendaftaran->tempat_lahir_pria }}, {{ $pendaftaran->tanggal_lahir_pria ? \Carbon\Carbon::parse($pendaftaran->tanggal_lahir_pria)->format('d M Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td>{{ $pendaftaran->alamat_pria }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Telepon</td>
            <td>{{ $pendaftaran->nomor_telepon_pria }}</td>
        </tr>
        <tr>
            <td class="label">Pekerjaan</td>
            <td>{{ $pendaftaran->pekerjaan_pria }}</td>
        </tr>
        <tr>
            <td class="label">Nama Ayah</td>
            <td>{{ $pendaftaran->nama_ayah_pria }}</td>
        </tr>
        <tr>
            <td class="label">Nama Ibu</td>
            <td>{{ $pendaftaran->nama_ibu_pria }}</td>
        </tr>
    </table>

    <h3 class="section-title">Data Calon Mempelai Wanita</h3>
    <table>
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>{{ $pendaftaran->nama_lengkap_wanita }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td>{{ $pendaftaran->tempat_lahir_wanita }}, {{ $pendaftaran->tanggal_lahir_wanita ? \Carbon\Carbon::parse($pendaftaran->tanggal_lahir_wanita)->format('d M Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat</td>
            <td>{{ $pendaftaran->alamat_wanita }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Telepon</td>
            <td>{{ $pendaftaran->nomor_telepon_wanita }}</td>
        </tr>
        <tr>
            <td class="label">Pekerjaan</td>
            <td>{{ $pendaftaran->pekerjaan_wanita }}</td>
        </tr>
        <tr>
            <td class="label">Nama Ayah</td>
            <td>{{ $pendaftaran->nama_ayah_wanita }}</td>
        </tr>
        <tr>
            <td class="label">Nama Ibu</td>
            <td>{{ $pendaftaran->nama_ibu_wanita }}</td>
        </tr>
    </table>

    <h3 class="section-title">Data Pernikahan</h3>
    <table>
        <tr>
            <td class="label">Tanggal Pemberkatan</td>
            <td>{{ $pendaftaran->tanggal_pernikahan ? \Carbon\Carbon::parse($pendaftaran->tanggal_pernikahan)->format('d M Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Waktu Pemberkatan</td>
            <td>{{ \Carbon\Carbon::parse($pendaftaran->waktu_pernikahan)->format('H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Dilayani Oleh</td>
            <td>{{ $pendaftaran->dilayani }}</td>
        </tr>
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
        $path_prefix = 'images/pernikahan/'; // Assuming this is the correct path prefix

        // Define a map for model fields to their titles for the PDF
        $attachmentFields = [
            'ktp' => 'KTP',
            'kk' => 'Kartu Keluarga',
            'surat_sidi' => 'Surat Sidi', // From model, no Pria/Wanita distinction in field name
            'akta_kelahiran' => 'Akta Kelahiran', // From model, no Pria/Wanita distinction
            'sk_nikah' => 'Surat Keterangan Nikah dari Catatan Sipil',
            'sk_asalusul' => 'Surat Keterangan Asal Usul dari Lurah',
            'sp_mempelai' => 'Surat Pernyataan Mempelai',
            'sk_ortu' => 'Surat Keterangan/Izin Orang Tua',
            'akta_perceraian_kematian' => 'Akta Perceraian / Surat Kematian (bagi duda/janda)',
            'si_kawin_komandan' => 'Surat Izin Kawin dari Komandan (bagi TNI/POLRI)',
            'sp_gereja_asal' => 'Surat Pengantar dari Gereja Asal (jika dari luar jemaat)',
            'foto' => 'Pas Foto', // From model, no Pria/Wanita distinction in field name
            // Add any other relevant document fields from PernikahanModel here
        ];

        foreach ($attachmentFields as $field => $title) {
            if (isset($pendaftaran->$field) && !empty($pendaftaran->$field)) {
                $attachments[] = ['file' => $pendaftaran->$field, 'title' => $title, 'path_prefix' => $path_prefix];
            }
        }
    @endphp

    @if(count($attachments) > 0)
        {{-- Ensure the first attachment section doesn't cause a double page break if one was already added before Lampiran --}}
        {{-- <h3 class="lampiran-title">Lampiran</h3> --}}

        @foreach($attachments as $index => $attachment)

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
