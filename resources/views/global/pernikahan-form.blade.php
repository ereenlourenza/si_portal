@extends('global.layouts.app')

@section('content')
<div class="container-fluid py-12 px-4 max-w-4xl mx-auto">

    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-6 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Formulir Pendaftaran Pemberkatan Nikah
    </h1>

    <div class="p-6">
        <form action="{{ route('pemberkatan-nikah.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow px-6 py-6 space-y-6 border border-amber-200">
            @csrf

            {{-- CALON MEMPELAI PRIA --}}
            <div class="mb-4">
                <h4 class="text-md font-bold text-amber-700">CALON MEMPELAI PRIA</h4>
            </div>

            {{-- Nama Lengkap --}}
            <div class="mb-4 ">
                <label for="nama_lengkap_pria" class="block font-semibold mb-1">Nama Lengkap<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nama_lengkap_pria" id="nama_lengkap_pria" value="{{ old('nama_lengkap_pria') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('nama_lengkap_pria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tempat Lahir --}}
            <div class="mb-4 ">
                <label for="tempat_lahir_pria" class="block font-semibold mb-1">Tempat Lahir<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="tempat_lahir_pria" id="tempat_lahir_pria" value="{{ old('tempat_lahir_pria') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('tempat_lahir_pria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tanggal Lahir --}}
            <div class="mb-4 ">
                <label for="tanggal_lahir_pria" class="block font-semibold mb-1">Tanggal Lahir<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="date" name="tanggal_lahir_pria" id="tanggal_lahir_pria" value="{{ old('tanggal_lahir_pria') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('tanggal_lahir_pria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tempat Sidi --}}
            <div class="mb-4 ">
                <label for="tempat_sidi_pria" class="block font-semibold mb-1">Tempat Sidi<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="tempat_sidi_pria" id="tempat_sidi_pria" value="{{ old('tempat_sidi_pria') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('tempat_sidi_pria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tanggal Sidi --}}
            <div class="mb-4 ">
                <label for="tanggal_sidi_pria" class="block font-semibold mb-1">Tanggal Sidi<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="date" name="tanggal_sidi_pria" id="tanggal_sidi_pria" value="{{ old('tanggal_sidi_pria') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('tanggal_sidi_pria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Pekerjaan --}}
            <div class="mb-4 ">
                <label for="pekerjaan_pria" class="block font-semibold mb-1">Pekerjaan<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="pekerjaan_pria" id="pekerjaan_pria" value="{{ old('pekerjaan_pria') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('pekerjaan_pria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Alamat --}}
            <div class="mb-4 ">
                <label for="alamat_pria" class="block font-semibold mb-1">Alamat<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="alamat_pria" id="alamat_pria" value="{{ old('alamat_pria') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('alamat_pria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nomor Telepon --}}
            <div class="mb-4 ">
                <label for="nomor_telepon_pria" class="block font-semibold mb-1">Nomor Telepon<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nomor_telepon_pria" id="nomor_telepon_pria" value="{{ old('nomor_telepon_pria') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('nomor_telepon_pria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nama Ayah --}}
            <div class="mb-4 ">
                <label for="nama_ayah_pria" class="block font-semibold mb-1">Nama Ayah<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nama_ayah_pria" id="nama_ayah_pria" value="{{ old('nama_ayah_pria') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('nama_ayah_pria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nama Ibu --}}
            <div class="mb-4 ">
                <label for="nama_ibu_pria" class="block font-semibold mb-1">Nama Ibu<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nama_ibu_pria" id="nama_ibu_pria" value="{{ old('nama_ibu_pria') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('nama_ibu_pria')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- CALON MEMPELAI WANITA --}}
            <div class="mb-4">
                <h4 class="text-md font-bold text-amber-700">CALON MEMPELAI WANITA</h4>
            </div>

            {{-- Nama Lengkap --}}
            <div class="mb-4 ">
                <label for="nama_lengkap_wanita" class="block font-semibold mb-1">Nama Lengkap<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nama_lengkap_wanita" id="nama_lengkap_wanita" value="{{ old('nama_lengkap_wanita') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('nama_lengkap_wanita')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tempat Lahir --}}
            <div class="mb-4 ">
                <label for="tempat_lahir_wanita" class="block font-semibold mb-1">Tempat Lahir<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="tempat_lahir_wanita" id="tempat_lahir_wanita" value="{{ old('tempat_lahir_wanita') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('tempat_lahir_wanita')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tanggal Lahir --}}
            <div class="mb-4 ">
                <label for="tanggal_lahir_wanita" class="block font-semibold mb-1">Tanggal Lahir<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="date" name="tanggal_lahir_wanita" id="tanggal_lahir_wanita" value="{{ old('tanggal_lahir_wanita') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('tanggal_lahir_wanita')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tempat Sidi --}}
            <div class="mb-4 ">
                <label for="tempat_sidi_wanita" class="block font-semibold mb-1">Tempat Sidi<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="tempat_sidi_wanita" id="tempat_sidi_wanita" value="{{ old('tempat_sidi_wanita') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('tempat_sidi_wanita')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tanggal Sidi --}}
            <div class="mb-4 ">
                <label for="tanggal_sidi_wanita" class="block font-semibold mb-1">Tanggal Sidi<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="date" name="tanggal_sidi_wanita" id="tanggal_sidi_wanita" value="{{ old('tanggal_sidi_wanita') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('tanggal_sidi_wanita')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Pekerjaan --}}
            <div class="mb-4 ">
                <label for="pekerjaan_wanita" class="block font-semibold mb-1">Pekerjaan<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="pekerjaan_wanita" id="pekerjaan_wanita" value="{{ old('pekerjaan_wanita') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('pekerjaan_wanita')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Alamat --}}
            <div class="mb-4 ">
                <label for="alamat_wanita" class="block font-semibold mb-1">Alamat<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="alamat_wanita" id="alamat_wanita" value="{{ old('alamat_wanita') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('alamat_wanita')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nomor Telepon --}}
            <div class="mb-4 ">
                <label for="nomor_telepon_wanita" class="block font-semibold mb-1">Nomor Telepon<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nomor_telepon_wanita" id="nomor_telepon_wanita" value="{{ old('nomor_telepon_wanita') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('nomor_telepon_wanita')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nama Ayah --}}
            <div class="mb-4 ">
                <label for="nama_ayah_wanita" class="block font-semibold mb-1">Nama Ayah<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nama_ayah_wanita" id="nama_ayah_wanita" value="{{ old('nama_ayah_wanita') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('nama_ayah_wanita')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nama Ibu --}}
            <div class="mb-4 ">
                <label for="nama_ibu_wanita" class="block font-semibold mb-1">Nama Ibu<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nama_ibu_wanita" id="nama_ibu_wanita" value="{{ old('nama_ibu_wanita') }}" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400" required>
                    @error('nama_ibu_wanita')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- PELAYANAN PEMBERKATAN NIKAH --}}
            <div class="mb-4 mt-12">
                <h4 class="text-md font-bold text-amber-700">PELAYANAN PEMBERKATAN NIKAH</h4>
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

            {{-- Waktu Pernikahan --}}
            <div class="mb-4 ">
                <label for="waktu_pernikahan" class="block font-semibold mb-1">Waktu Pernikahan<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="time" name="waktu_pernikahan" id="waktu_pernikahan" value="{{ old('waktu_pernikahan') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('waktu_pernikahan')
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

            {{-- Kartu Tanda Penduduk --}}
            <div class="mb-4 ">
                <label for="ktp" class="block font-semibold mb-1">Kartu Tanda Penduduk<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="ktp" id="ktp" value="{{ old('ktp') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('ktp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Kartu Keluarga --}}
            <div class="mb-4 ">
                <label for="kk" class="block font-semibold mb-1">Kartu Keluarga<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="kk" id="kk" value="{{ old('kk') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('kk')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Surat Sidi --}}
            <div class="mb-4 ">
                <label for="surat_sidi" class="block font-semibold mb-1">Surat Sidi<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="surat_sidi" id="surat_sidi" value="{{ old('surat_sidi') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('surat_sidi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Akta Kelahiran --}}
            <div class="mb-4 ">
                <label for="akta_kelahiran" class="block font-semibold mb-1">Akta Kelahiran<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="akta_kelahiran" id="akta_kelahiran" value="{{ old('akta_kelahiran') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('akta_kelahiran')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Surat Keterangan Nikah (N1) --}}
            <div class="mb-4 ">
                <label for="sk_nikah" class="block font-semibold mb-1">Surat Keterangan Nikah (N1)<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="sk_nikah" id="sk_nikah" value="{{ old('sk_nikah') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('sk_nikah')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Surat Keterangan Asal Usul (N2) --}}
            <div class="mb-4 ">
                <label for="sk_asalusul" class="block font-semibold mb-1">Surat Keterangan Asal Usul (N2)<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="sk_asalusul" id="sk_asalusul" value="{{ old('sk_asalusul') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('sk_asalusul')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Surat Persetujuan Mempelai (N3) --}}
            <div class="mb-4 ">
                <label for="sp_mempelai" class="block font-semibold mb-1">Surat Persetujuan Mempelai (N3)<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="sp_mempelai" id="sp_mempelai" value="{{ old('sp_mempelai') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('sp_mempelai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Surat Keterangan Orang Tua (N4) --}}
            <div class="mb-4 ">
                <label for="sk_ortu" class="block font-semibold mb-1">Surat Keterangan Orang Tua (N4)<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="sk_ortu" id="sk_ortu" value="{{ old('sk_ortu') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('sk_ortu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Akta Perceraian/Kematian --}}
            <div class="mb-4 ">
                <label for="akta_perceraian_kematian" class="block font-semibold mb-1">Akta Perceraian/Kematian</label> 
                <div class="w-full rounded-md">
                    <input type="file" name="akta_perceraian_kematian" id="akta_perceraian_kematian" value="{{ old('akta_perceraian_kematian') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " >
                    @error('akta_perceraian_kematian')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Surat Ijin Kawin Komandan/Kepala (TNI/Polri) --}}
            <div class="mb-4 ">
                <label for="si_kawin_komandan" class="block font-semibold mb-1">Surat Ijin Kawin Komandan/Kepala (TNI/Polri)</label> 
                <div class="w-full rounded-md">
                    <input type="file" name="si_kawin_komandan" id="si_kawin_komandan" value="{{ old('si_kawin_komandan') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " >
                    @error('si_kawin_komandan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Surat Pelimpahan Dari Gereja Asal --}}
            <div class="mb-4 ">
                <label for="sp_gereja_asal" class="block font-semibold mb-1">Surat Pelimpahan Dari Gereja Asal</label> 
                <div class="w-full rounded-md">
                    <input type="file" name="sp_gereja_asal" id="sp_gereja_asal" value="{{ old('sp_gereja_asal') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " >
                    @error('sp_gereja_asal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Foto Berwarna Berdampingan 4x6 --}}
            <div class="mb-4 ">
                <label for="foto" class="block font-semibold mb-1">Foto Berwarna Berdampingan 4x6<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="foto" id="foto" value="{{ old('foto') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('foto')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Bukti Pembayaran Biaya Administrasi --}}
            <div class="mb-4 ">
                <label for="biaya" class="block font-semibold mb-1">Bukti Pembayaran Biaya Administrasi<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="biaya" id="biaya" value="{{ old('biaya') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('biaya')
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
                   href="{{ url('pelayanan/pelayanan-jemaat/pemberkatan-nikah') }}">
                    Kembali
                </a>
            </div>            
            
        </form>
    </div>

</div>
@endsection
