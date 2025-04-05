@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($pendaftaran)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $pendaftaran->baptis_id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $pendaftaran->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <th>Tempat Lahir</th>
                        <td>{{ $pendaftaran->tempat_lahir }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>{{ $pendaftaran->tanggal_lahir }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ $pendaftaran->jenis_kelamin }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ayah</th>
                        <td>{{ $pendaftaran->nama_ayah }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ibu</th>
                        <td>{{ $pendaftaran->nama_ibu }}</td>
                    </tr>
                    <tr>
                        <th>Tempat Pernikahan</th>
                        <td>{{ $pendaftaran->tempat_pernikahan }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pernikahan</th>
                        <td>{{ $pendaftaran->tanggal_pernikahan }}</td>
                    </tr>
                    <tr>
                        <th>Tempat Sidi Ayah</th>
                        <td>{{ $pendaftaran->tempat_sidi_ayah }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Sidi Ayah</th>
                        <td>{{ $pendaftaran->tanggal_sidi_ayah }}</td>
                    </tr>
                    <tr>
                        <th>Tempat Sidi Ibu</th>
                        <td>{{ $pendaftaran->tempat_sidi_ibu }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Sidi Ibu</th>
                        <td>{{ $pendaftaran->tanggal_sidi_ibu }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $pendaftaran->alamat }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Telepon</th>
                        <td>{{ $pendaftaran->nomor_telepon }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Baptis</th>
                        <td>{{ $pendaftaran->tanggal_baptis }}</td>
                    </tr>
                    <tr>
                        <th>Dilayani</th>
                        <td>{{ $pendaftaran->dilayani }}</td>
                    </tr>
                    <tr>
                        <th>Surat Nikah Orang Tua</th>
                        <td>
                            @if (!empty($pendaftaran->surat_nikah_ortu) && Storage::url('images/' . $pendaftaran->surat_nikah_ortu))
                                <img src="{{ asset('storage/images/baptis/'.$pendaftaran->surat_nikah_ortu) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada gambar</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Akta Kelahiran Anak</th>
                        <td>
                            @if (!empty($pendaftaran->akta_kelahiran_anak) && Storage::url('images/' . $pendaftaran->akta_kelahiran_anak))
                                <img src="{{ asset('storage/images/baptis/'.$pendaftaran->akta_kelahiran_anak) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                </table>
            @endempty
            
            <a href="{{ url('pengelolaan-informasi/pendaftaran') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush    