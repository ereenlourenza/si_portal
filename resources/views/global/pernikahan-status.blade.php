@extends('global.layouts.app') {{-- atau layout kamu --}}

@section('content')
<div class="container py-8 max-w-5xl mx-auto">
    <h2 class="text-2xl md:text-3xl font-bold text-amber-700 mb-6 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Status Pendaftaran Pemberkatan Nikah
    </h2>

    {{-- FORM FILTER --}}
    <form method="GET" action="{{ url()->current() }}" class="mb-6 px-3 flex flex-col md:flex-row items-center gap-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Masukkan nama mempelai pria..."
               class="border border-gray-300 rounded px-4 py-2 w-full md:w-1/2">
        <input type="text" name="x" value="{{ request('x') }}" placeholder="Masukkan nama mempelai wanita..."
               class="border border-gray-300 rounded px-4 py-2 w-full md:w-1/2">
        <select name="status" class="border border-gray-300 rounded px-4 py-2">
            <option value="">Semua Status</option>
            <option value="0" @selected(request('status') === '0')>Menunggu</option>
            <option value="1" @selected(request('status') === '1')>Disetujui</option>
            <option value="2" @selected(request('status') === '2')>Ditolak</option>
        </select>
        <button type="submit"
                class="bg-amber-600 text-white px-6 py-2 rounded hover:bg-amber-700 transition">
            Cari
        </button>
    </form>

    {{-- HASIL PENCARIAN --}}
    <div class="px-3">
    @if(request()->filled('q','x') || request()->filled('status'))
        <div class="rounded p-6 ">
            @if($data->count())
                <table class="w-full table-auto border border-gray-200">
                    <thead class="bg-[#614D24] hover:bg-[#4f3f1c] text-white text-left text-sm">
                        <tr>
                            <th class="p-3 border border-[#614D24] text-center">Nama Mempelai Pria</th>
                            <th class="p-3 border border-[#614D24] text-center">Nama Mempelai Wanita</th>
                            <th class="p-3 border border-[#614D24] text-center">Tanggal Pernikahan</th>
                            <th class="p-3 border border-[#614D24] text-center">Dilayani Oleh</th>
                            <th class="p-3 border border-[#614D24] text-center">Status</th>
                            @if($data->contains('status', 2))
                                <th class="p-3 border border-[#614D24] text-center">Keterangan</th>
                            @endif
                        </tr>
                    </thead>                    
                    <tbody class="text-sm">
                        @foreach($data as $item)
                            <tr class="hover:bg-amber-50">
                                <td class="p-3 border-[#614D24] text-center">{{ $item->nama_lengkap_pria }}</td>
                                <td class="p-3 border-[#614D24] text-center">{{ $item->nama_lengkap_wanita }}</td>
                                <td class="p-3 border-[#614D24] text-center">{{ $item->tanggal_pernikahan }}</td>
                                <td class="p-3 border-[#614D24] text-center">{{ $item->dilayani }}</td>
                                <td class="p-3 border-[#614D24] text-center">
                                    @if($item->status == 1)
                                        <span class="text-green-600 font-semibold">Disetujui</span>
                                    @elseif($item->status == 2)
                                        <span class="text-red-600 font-semibold">Ditolak</span>
                                    @else
                                        <span class="text-yellow-400 font-semibold">Menunggu</span>
                                    @endif
                                </td>
                    
                                @if($item->status == 2)
                                    <td class="p-3 border-[#614D24] text-center">{{ $item->alasan_penolakan }}</td>
                                @elseif($data->contains('status', 2))
                                    {{-- tambahkan kolom kosong supaya tabel tetap rata --}}
                                    <td class="p-3 border-[#614D24] text-center">-</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            @else
                <p class="text-gray-500">Data tidak ditemukan sesuai pencarian.</p>
            @endif
        </div>
    @endif
    </div>

    {{-- BUTTON --}}
    <div class="mt-6 flex flex-col md:flex-row justify-end items-center gap-4 text-sm md:text-[15px]">
        <a class="bg-white border border-amber-600 text-amber-700 hover:bg-amber-50 font-medium py-2 px-6 rounded shadow transition" 
           href="{{ url('pelayanan/pelayanan-jemaat/baptisan') }}">
            Kembali
        </a>
    </div>  
</div>
@endsection
