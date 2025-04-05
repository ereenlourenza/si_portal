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
                        <td>{{ $pendaftaran->katekisasi_id }}</td>
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
                        <th>Alamat Katekumen</th>
                        <td>{{ $pendaftaran->alamat_katekumen }}</td>
                    </tr>
                    <tr>
                        <th>No Telp Katekumen</th>
                        <td>{{ $pendaftaran->nomor_telepon_katekumen }}</td>
                    </tr>
                    <tr>
                        <th>Pendidikan Terakhir</th>
                        <td>{{ $pendaftaran->pendidikan_terakhir }}</td>
                    </tr>
                    <tr>
                        <th>Pekerjaan</th>
                        <td>{{ $pendaftaran->pekerjaan }}</td>
                    </tr>
                    <tr>
                        <th>Status Baptis</th>
                        <td>{{ $pendaftaran->is_baptis }}</td>
                    </tr>
                    @if ($pendaftaran->is_baptis == 'Sudah')
                        <tr>
                            <th>Gereja Tempat Baptis</th>
                            <td>{{ $pendaftaran->tempat_baptis }}</td>
                        </tr>
                        <tr>
                            <th>Nomor Surat Baptis</th>
                            <td>{{ $pendaftaran->no_surat_baptis }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Surat Baptis</th>
                            <td>{{ $pendaftaran->tanggal_surat_baptis }}</td>
                        </tr>
                        <tr>
                            <th>Dilayani Oleh</th>
                            <td>{{ $pendaftaran->dilayani }}</td>
                        </tr>
                    @endif
                    @if ($pendaftaran->is_baptis == 'Belum')
                        <tr>
                            <th>Nama Ayah</th>
                            <td>{{ $pendaftaran->nama_ayah }}</td>
                        </tr>
                        <tr>
                            <th>Nama Ibu</th>
                            <td>{{ $pendaftaran->nama_ibu }}</td>
                        </tr>
                        <tr>
                            <th>Alamat Orang Tua</th>
                            <td>{{ $pendaftaran->alamat_ortu }}</td>
                        </tr>
                        <tr>
                            <th>No Telp Orang Tua</th>
                            <td>{{ $pendaftaran->nomor_telepon_ortu }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Akta Kelahiran</th>
                        <td>
                            @if (!empty($pendaftaran->akta_kelahiran) && Storage::url('images/' . $pendaftaran->akta_kelahiran))
                                <img src="{{ asset('storage/images/sidi/'.$pendaftaran->akta_kelahiran) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada gambar</span>
                            @endif   
                        </td>
                    </tr>
                    @if ($pendaftaran->is_baptis == 'Sudah')
                        <tr>
                            <th>Surat Baptis</th>
                            <td>
                                @if (!empty($pendaftaran->surat_baptis) && Storage::url('images/' . $pendaftaran->surat_baptis))
                                    <img src="{{ asset('storage/images/sidi/'.$pendaftaran->surat_baptis) }}" class="" style="width: 50%">
                                @else
                                    <span class="text-danger">Tidak ada foto</span>
                                @endif   
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <th>Pas Foto</th>
                        <td>
                            @if (!empty($pendaftaran->pas_foto) && Storage::url('images/' . $pendaftaran->pas_foto))
                                <img src="{{ asset('storage/images/sidi/'.$pendaftaran->pas_foto) }}" class="" style="width: 50%">
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