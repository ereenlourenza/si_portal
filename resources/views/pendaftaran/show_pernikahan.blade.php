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
                        <td>{{ $pendaftaran->pernikahan_id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $pendaftaran->nama_lengkap_pria }}</td>
                    </tr>
                    <tr>
                        <th>Tempat Lahir</th>
                        <td>{{ $pendaftaran->tempat_lahir_pria }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>{{ $pendaftaran->tanggal_lahir_pria }}</td>
                    </tr>
                    <tr>
                        <th>Tempat Sidi</th>
                        <td>{{ $pendaftaran->tempat_sidi_pria }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Sidi</th>
                        <td>{{ $pendaftaran->tanggal_sidi_pria }}</td>
                    </tr>
                    <tr>
                        <th>Pekerjaan</th>
                        <td>{{ $pendaftaran->pekerjaan_pria }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $pendaftaran->alamat_pria }}</td>
                    </tr>
                    <tr>
                        <th>No Telp</th>
                        <td>{{ $pendaftaran->nomor_telepon_pria }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ayah</th>
                        <td>{{ $pendaftaran->nama_ayah_pria }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ibu</th>
                        <td>{{ $pendaftaran->nama_ibu_pria }}</td>
                    </tr>
                    <tr>
                        <th>Nama Lengkap</th>
                        <td>{{ $pendaftaran->nama_lengkap_wanita }}</td>
                    </tr>
                    <tr>
                        <th>Tempat Lahir</th>
                        <td>{{ $pendaftaran->tempat_lahir_wanita }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>{{ $pendaftaran->tanggal_lahir_wanita }}</td>
                    </tr>
                    <tr>
                        <th>Tempat Sidi</th>
                        <td>{{ $pendaftaran->tempat_sidi_wanita }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Sidi</th>
                        <td>{{ $pendaftaran->tanggal_sidi_wanita }}</td>
                    </tr>
                    <tr>
                        <th>Pekerjaan</th>
                        <td>{{ $pendaftaran->pekerjaan_wanita }}</td>
                    </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>{{ $pendaftaran->alamat_wanita }}</td>
                    </tr>
                    <tr>
                        <th>No Telp</th>
                        <td>{{ $pendaftaran->nomor_telepon_wanita }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ayah</th>
                        <td>{{ $pendaftaran->nama_ayah_wanita }}</td>
                    </tr>
                    <tr>
                        <th>Nama Ibu</th>
                        <td>{{ $pendaftaran->nama_ibu_wanita }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pernikahan</th>
                        <td>{{ $pendaftaran->tanggal_pernikahan }}</td>
                    </tr>
                    <tr>
                        <th>Waktu Pernikahan</th>
                        <td>{{ $pendaftaran->waktu_pernikahan }}</td>
                    </tr>
                    <tr>
                        <th>Dilayani Oleh</th>
                        <td>{{ $pendaftaran->dilayani }}</td>
                    </tr>
                    <tr>
                        <th>Kartu Tanda Penduduk</th>
                        <td>
                            @if (!empty($pendaftaran->ktp) && Storage::url('images/' . $pendaftaran->ktp))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->ktp) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada gambar</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Kartu Keluarga</th>
                        <td>
                            @if (!empty($pendaftaran->kk) && Storage::url('images/' . $pendaftaran->kk))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->kk) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Surat Sidi</th>
                        <td>
                            @if (!empty($pendaftaran->surat_sidi) && Storage::url('images/' . $pendaftaran->surat_sidi))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->surat_sidi) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Akta Kelahiran</th>
                        <td>
                            @if (!empty($pendaftaran->akta_kelahiran) && Storage::url('images/' . $pendaftaran->akta_kelahiran))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->akta_kelahiran) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Surat Keterangan Nikah (N1)</th>
                        <td>
                            @if (!empty($pendaftaran->sk_nikah) && Storage::url('images/' . $pendaftaran->sk_nikah))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->sk_nikah) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Surat Keterangan Asal Usul (N2)</th>
                        <td>
                            @if (!empty($pendaftaran->sk_asalusul) && Storage::url('images/' . $pendaftaran->sk_asalusul))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->sk_asalusul) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Surat Persetujuan Mempelai (N3)</th>
                        <td>
                            @if (!empty($pendaftaran->sp_mempelai) && Storage::url('images/' . $pendaftaran->sp_mempelai))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->sp_mempelai) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Surat Keterangan Orang Tua (N4)</th>
                        <td>
                            @if (!empty($pendaftaran->sk_ortu) && Storage::url('images/' . $pendaftaran->sk_ortu))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->sk_ortu) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Akta Perceraian/Kematian</th>
                        <td>
                            @if (!empty($pendaftaran->akta_perceraian_kematian) && Storage::url('images/' . $pendaftaran->akta_perceraian_kematian))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->akta_perceraian_kematian) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Surat Ijin Kawin Komandan/Kepala (TNI-Polri)</th>
                        <td>
                            @if (!empty($pendaftaran->si_kawin_komandan) && Storage::url('images/' . $pendaftaran->si_kawin_komandan))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->si_kawin_komandan) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Surat Pelimpahan Dari Gereja Asal</th>
                        <td>
                            @if (!empty($pendaftaran->sp_gereja_asal) && Storage::url('images/' . $pendaftaran->sp_gereja_asal))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->sp_gereja_asal) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Foto Berwarna Berdampingan 4x6</th>
                        <td>
                            @if (!empty($pendaftaran->foto) && Storage::url('images/' . $pendaftaran->foto))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->foto) }}" class="" style="width: 50%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </td>
                    </tr>
                    <tr>
                        <th>Bukti Pembayaran Biaya Administrasi</th>
                        <td>
                            @if (!empty($pendaftaran->biaya) && Storage::url('images/' . $pendaftaran->biaya))
                                <img src="{{ asset('storage/images/pernikahan/'.$pendaftaran->biaya) }}" class="" style="width: 50%">
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