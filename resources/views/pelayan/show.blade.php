@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools"></div>
        </div>
        <div class="card-body">
            @empty($pelayan)
                <div class="alert alert-danger alert-dismissible">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang Anda cari tidak ditemukan.
                </div>
            @else
                <table class="table table-bordered table-striped table-hover table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $pelayan->pelayan_id }}</td>
                    </tr>
                    <tr>
                        <th>Kategori Pelayan</th>
                        <td>{{ $pelayan->kategoripelayan->kategoripelayan_nama }}</td>
                    </tr>
                    <tr>
                        <th>Nama</th>
                        <td>{{ $pelayan->nama }}</td>
                    </tr>
                    <tr>
                        <th>Masa Jabatan Mulai</th>
                        <td>{{ $pelayan->masa_jabatan_mulai }}</td>
                    </tr>
                    <tr>
                        <th>Masa Jabatan Selesai</th>
                        <td>{{ $pelayan->masa_jabatan_selesai }}</td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td>{{ $pelayan->keterangan ? $pelayan->keterangan : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Foto</th>
                        <th>
                            @if (!empty($pelayan->foto) && Storage::url('dokumen/' . $pelayan->foto))
                                <img src="{{ asset('storage/images/pelayan/'.$pelayan->foto) }}" class="" style="width: 20%">
                            @else
                                <span class="text-danger">Tidak ada foto</span>
                            @endif   
                        </th>
                    </tr>
                    <!-- Tambahan: Informasi PHMJ -->
                    @if ($pelayan->phmj)
                        <tr>
                            <th>Jabatan di PHMJ</th>
                            <td>
                                {{ $pelayan->phmj ? $pelayan->phmj->jabatan : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Periode PHMJ</th>
                            <td>
                                @if ($pelayan->phmj)
                                    {{ $pelayan->phmj->periode_mulai }} - {{ $pelayan->phmj->periode_selesai }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        
                        
                    @endif
                </table>
            @endempty
            
            <a href="{{ url('pengelolaan-informasi/pelayan') }}" class="btn btn-sm btn-default mt-2">Kembali</a>
        </div>
    </div>
@endsection

@push('css')
@endpush

@push('js')
@endpush    