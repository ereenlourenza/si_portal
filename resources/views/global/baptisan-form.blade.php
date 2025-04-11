@extends('global.layouts.app')

@section('content')
<div class="container-fluid py-12 px-4 max-w-4xl mx-auto">

    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-6 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Formulir Pendaftaran Baptisan
    </h1>

    <div class="p-6">
        <form action="{{ route('baptis.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow px-6 py-6 space-y-6 border border-amber-200">
            @csrf

            {{-- DATA ANAK --}}
            <div class="mb-4">
                <h4 class="text-md font-bold text-amber-700">DATA ANAK</h4>
            </div>

            {{-- Nama Lengkap --}}
            <div class="mb-4 ">
                <label for="nama_lengkap" class="block font-semibold mb-1">Nama Lengkap<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('nama_lengkap')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tempat Lahir --}}
            <div class="mb-4 ">
                <label for="tempat_lahir" class="block font-semibold mb-1">Tempat Lahir<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('tempat_lahir')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tanggal Lahir --}}
            <div class="mb-4 ">
                <label for="tanggal_lahir" class="block font-semibold mb-1">Tanggal Lahir<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('tanggal_lahir')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Jenis Kelamin --}}
            <div class="mb-4 ">
                <label for="jenis_kelamin" class="block font-semibold mb-1">Jenis Kelamin<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    {{-- <input type="date" name="jenis_kelamin" id="jenis_kelamin" value="{{ old('jenis_kelamin') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required> --}}
                    <select id="jenis_kelamin" name="jenis_kelamin" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                        <option value="" selected disabled>Pilih</option>
                        <option value="L" >Laki-Laki</option>
                        <option value="P" >Perempuan</option>
                        
                    </select>
                    @error('jenis_kelamin')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>



            {{-- DATA ORANG TUA --}}
            <div class="mb-4 mt-12">
                <h4 class="text-md font-bold text-amber-700">DATA ORANG TUA</h4>
            </div>

            {{-- Nama Ayah --}}
            <div class="mb-4 ">
                <label for="nama_ayah" class="block font-semibold mb-1">Nama Ayah<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nama_ayah" id="nama_ayah" value="{{ old('nama_ayah') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('nama_ayah')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nama Ibu --}}
            <div class="mb-4 ">
                <label for="nama_ibu" class="block font-semibold mb-1">Nama Ibu<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('nama_ibu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tempat Pernikahan --}}
            <div class="mb-4 ">
                <label for="tempat_pernikahan" class="block font-semibold mb-1">Tempat Pernikahan<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="tempat_pernikahan" id="tempat_pernikahan" value="{{ old('tempat_pernikahan') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('tempat_pernikahan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tanggal Pernikahan --}}
            <div class="mb-4 ">
                <label for="tanggal_pernikahan" class="block font-semibold mb-1">Tanggal Pernikahan<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="date" name="tanggal_pernikahan" id="tanggal_pernikahan" value="{{ old('tanggal_pernikahan') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('tanggal_pernikahan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tempat Sidi Ayah --}}
            <div class="mb-4 ">
                <label for="tempat_sidi_ayah" class="block font-semibold mb-1">Tempat Sidi Ayah<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="tempat_sidi_ayah" id="tempat_sidi_ayah" value="{{ old('tempat_sidi_ayah') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('tempat_sidi_ayah')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tanggal Sidi Ayah --}}
            <div class="mb-4 ">
                <label for="tanggal_sidi_ayah" class="block font-semibold mb-1">Tanggal Sidi Ayah<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="date" name="tanggal_sidi_ayah" id="tanggal_sidi_ayah" value="{{ old('tanggal_sidi_ayah') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('tanggal_sidi_ayah')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tempat Sidi Ibu --}}
            <div class="mb-4 ">
                <label for="tempat_sidi_ibu" class="block font-semibold mb-1">Tempat Sidi Ibu<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="tempat_sidi_ibu" id="tempat_sidi_ibu" value="{{ old('tempat_sidi_ibu') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('tempat_sidi_ibu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tanggal Sidi Ibu --}}
            <div class="mb-4 ">
                <label for="tanggal_sidi_ibu" class="block font-semibold mb-1">Tanggal Sidi Ibu<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="date" name="tanggal_sidi_ibu" id="tanggal_sidi_ibu" value="{{ old('tanggal_sidi_ibu') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('tanggal_sidi_ibu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Alamat --}}
            <div class="mb-4">
                <label for="alamat" class="block font-semibold mb-1">Alamat<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('alamat')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nomor Telepon --}}
            <div class="mb-4 ">
                <label for="nomor_telepon" class="block font-semibold mb-1">Nomor Telepon<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nomor_telepon" id="nomor_telepon" value="{{ old('nomor_telepon') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('nomor_telepon')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>



            {{-- PELAYANAN BAPTISAN --}}
            <div class="mb-4 mt-12">
                <h4 class="text-md font-bold text-amber-700">PELAYANAN BAPTISAN</h4>
            </div>

            {{-- Tanggal Baptis --}}
            <div class="mb-4 ">
                <label for="tanggal_baptis" class="block font-semibold mb-1">Tanggal Baptis<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="date" name="tanggal_baptis" id="tanggal_baptis" value="{{ old('tanggal_baptis') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('tanggal_baptis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Dilayani Oleh --}}
            <div class="mb-4 ">
                <label for="dilayani" class="block font-semibold mb-1">Dilayani Oleh<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="dilayani" id="dilayani" value="{{ old('dilayani') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('dilayani')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>



            {{-- LAMPIRAN --}}
            <div class="mb-4 mt-12">
                <h4 class="text-md font-bold text-amber-700">LAMPIRAN</h4>
            </div>

            {{-- Surat Nikah Ortu --}}
            <div class="mb-4 ">
                <label for="surat_nikah_ortu" class="block font-semibold mb-1">Surat Nikah Ortu<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="surat_nikah_ortu" id="surat_nikah_ortu" value="{{ old('surat_nikah_ortu') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('surat_nikah_ortu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Akta Kelahiran Anak --}}
            <div class="mb-4 ">
                <label for="akta_kelahiran_anak" class="block font-semibold mb-1">Akta Kelahiran Anak<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="akta_kelahiran_anak" id="akta_kelahiran_anak" value="{{ old('akta_kelahiran_anak') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('akta_kelahiran_anak')
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
                   href="{{ url('pelayanan/pelayanan-jemaat/baptisan') }}">
                    Kembali
                </a>
            </div>            
            
        </form>
    </div>

</div>
@endsection
