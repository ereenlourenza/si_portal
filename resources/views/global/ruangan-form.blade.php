@extends('global.layouts.app')

@section('content')
<div class="container-fluid py-12 px-4 max-w-4xl mx-auto">

    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-6 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Formulir Pengajuan Peminjaman Ruangan
    </h1>

    <div class="p-6">
        <form action="{{ route('peminjaman-ruangan.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow px-6 py-6 space-y-6 border border-amber-200">
            @csrf

            {{-- Peminjam Nama --}}
            <div class="mb-4 ">
                <label for="peminjam_nama" class="block font-semibold mb-1">Peminjam Nama<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="peminjam_nama" id="peminjam_nama" value="{{ old('peminjam_nama') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('peminjam_nama')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Peminjam Telepon --}}
            <div class="mb-4 ">
                <label for="peminjam_telepon" class="block font-semibold mb-1">Peminjam Telepon<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="peminjam_telepon" id="peminjam_telepon" value="{{ old('peminjam_telepon') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('peminjam_telepon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tanggal --}}
            <div class="mb-4 ">
                <label for="tanggal" class="block font-semibold mb-1">Tanggal<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('tanggal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Waktu Mulai --}}
            <div class="mb-4 ">
                <label for="waktu_mulai" class="block font-semibold mb-1">Waktu Mulai<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="time" name="waktu_mulai" id="waktu_mulai" value="{{ old('waktu_mulai') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('waktu_mulai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Waktu Selesai --}}
            <div class="mb-4 ">
                <label for="waktu_selesai" class="block font-semibold mb-1">Waktu Selesai<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="time" name="waktu_selesai" id="waktu_selesai" value="{{ old('waktu_selesai') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('waktu_selesai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Ruangan --}}
            <div class="mb-4 ">
                <label for="ruangan_id" class="block font-semibold mb-1">Ruangan<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <select id="ruangan_id" name="ruangan_id" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                        <option value="" selected disabled>Pilih</option>
                            @foreach($ruangan as $item)
                                <option value="{{ $item->ruangan_id }}">{{ $item->ruangan_nama }}</option>
                            @endforeach
                    </select>

                    @error('ruangan_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Keperluan --}}
            <div class="mb-4 ">
                <label for="keperluan" class="block font-semibold mb-1">Keperluan<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="keperluan" id="keperluan" value="{{ old('keperluan') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('keperluan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- BUTTON --}}
            <div class="mt-12 flex flex-col md:flex-row justify-end items-center gap-4 text-sm md:text-[15px]">
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-6 rounded shadow">
                    Kirim Pendaftaran
                </button>
                <a class="bg-white border border-amber-600 text-amber-700 hover:bg-amber-50 font-medium py-2 px-6 rounded shadow transition" 
                   href="{{ url('pelayanan/pelayanan-jemaat/peminjaman-ruangan') }}">
                    Kembali
                </a>
            </div>            
            
        </form>
    </div>

</div>
@endsection
