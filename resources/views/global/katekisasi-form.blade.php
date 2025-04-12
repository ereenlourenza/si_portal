@extends('global.layouts.app')

@section('content')
<div class="container-fluid py-12 px-4 max-w-4xl mx-auto">

    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-6 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Formulir Pendaftaran Katekisasi
    </h1>

    <div class="p-6">
        <form action="{{ route('katekisasi.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow px-6 py-6 space-y-6 border border-amber-200">
            @csrf

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

            {{-- Alamat --}}
            <div class="mb-4">
                <label for="alamat_katekumen" class="block font-semibold mb-1">Alamat<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="alamat_katekumen" id="alamat_katekumen" value="{{ old('alamat_katekumen') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('alamat_katekumen')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nomor Telepon --}}
            <div class="mb-4 ">
                <label for="nomor_telepon_katekumen" class="block font-semibold mb-1">Nomor Telepon<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nomor_telepon_katekumen" id="nomor_telepon_katekumen" value="{{ old('nomor_telepon_katekumen') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('nomor_telepon_katekumen')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Pendidikan Terakhir --}}
            <div class="mb-4 ">
                <label for="nomor_telepon" class="block font-semibold mb-1">Pendidikan Terakhir<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <select id="pendidikan_terakhir" name="pendidikan_terakhir" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                        <option value="" selected disabled>Pilih</option>
                        <option value="SD" >Sekolah Dasar (SD/Paket A)</option>
                        <option value="SMP" >Sekolah Menengah Pertama (SMP/SLTP/Paket B)</option>
                        <option value="SMA" >Sekolah Menengah Atas/Kejuruan (SMA/SMK/SLTA/Paket C)</option>
                        <option value="D1" >Diploma 1 (D1)</option>
                        <option value="D2" >Diploma 2 (D2)</option>
                        <option value="D3" >Diploma 3 (D3)</option>
                        <option value="D4" >Diploma 4 (D4)</option>
                        <option value="S1" >Sarjana (S1)</option>
                        <option value="S2" >Magister (S2)</option>
                        <option value="S3" >Doktor (S3)</option>
                    </select>

                    @error('pendidikan_terakhir')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Pekerjaan --}}
            <div class="mb-4 ">
                <label for="pekerjaan" class="block font-semibold mb-1">Pekerjaan<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="pekerjaan" id="pekerjaan" value="{{ old('pekerjaan') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('pekerjaan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Is Baptis --}}
            <div class="mb-4 ">
                <label for="is_baptis" class="block font-semibold mb-1">Status Baptis<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <select id="is_baptis" name="is_baptis" class="text-sm md:text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                        <option value="" selected disabled>Pilih</option>
                        <option value="Sudah" selected>Sudah</option>
                        <option value="Belum" >Belum</option>
                        
                    </select>

                    @error('is_baptis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tempat Baptis --}}
            <div class="mb-4 group-sudah">
                <label for="tempat_baptis" class="block font-semibold mb-1">Gereja Tempat Baptis<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="tempat_baptis" id="tempat_baptis" value="{{ old('tempat_baptis') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " >
                    @error('tempat_baptis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nomor Surat Baptis --}}
            <div class="mb-4 group-sudah">
                <label for="no_surat_baptis" class="block font-semibold mb-1">Nomor Surat Baptis<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="no_surat_baptis" id="no_surat_baptis" value="{{ old('no_surat_baptis') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " >
                    @error('no_surat_baptis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tanggal Surat Baptis --}}
            <div class="mb-4 group-sudah">
                <label for="tanggal_surat_baptis" class="block font-semibold mb-1">Tanggal Surat Baptis<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="date" name="tanggal_surat_baptis" id="tanggal_surat_baptis" value="{{ old('tanggal_surat_baptis') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " >
                    @error('tanggal_surat_baptis')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Dilayani Oleh --}}
            <div class="mb-4 group-sudah">
                <label for="dilayani" class="block font-semibold mb-1">Dilayani Oleh<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="dilayani" id="dilayani" value="{{ old('dilayani') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " >
                    @error('dilayani')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nama Ayah --}}
            <div class="mb-4 group-belum">
                <label for="nama_ayah" class="block font-semibold mb-1">Nama Ayah<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nama_ayah" id="nama_ayah" value="{{ old('nama_ayah') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " >
                    @error('nama_ayah')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nama Ibu --}}
            <div class="mb-4 group-belum">
                <label for="nama_ibu" class="block font-semibold mb-1">Nama Ibu<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " >
                    @error('nama_ibu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Alamat Orang Tua --}}
            <div class="mb-4 group-belum">
                <label for="alamat_ortu" class="block font-semibold mb-1">Alamat Orang Tua<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="alamat_ortu" id="alamat_ortu" value="{{ old('alamat_ortu') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " >
                    @error('alamat_ortu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Nomor Telepon Ortu --}}
            <div class="mb-4 group-belum">
                <label for="nomor_telepon_ortu" class="block font-semibold mb-1">Nomor Telepon Ortu<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="text" name="nomor_telepon_ortu" id="nomor_telepon_ortu" value="{{ old('nomor_telepon_ortu') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " >
                    @error('nomor_telepon_ortu')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- LAMPIRAN --}}
            <div class="mb-4 mt-12">
                <h4 class="text-md font-bold text-amber-700">LAMPIRAN</h4>
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

            {{-- Surat Baptis --}}
            <div class="mb-4 group-sudah">
                <label for="surat_baptis" class="block font-semibold mb-1">Surat Baptis<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="surat_baptis" id="surat_baptis" value="{{ old('surat_baptis') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 ">
                    @error('surat_baptis')
                        <p class="text-red-500 text-sm mt-5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Pas Foto --}}
            <div class="mb-4 ">
                <label for="pas_foto" class="block font-semibold mb-1">Pas Foto<span class="text-red-500">*</span></label> 
                <div class="w-full rounded-md">
                    <input type="file" name="pas_foto" id="pas_foto" value="{{ old('pas_foto') }}" class="text-sm text-[15px] py-1 px-3 w-full border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-400 " required>
                    @error('pas_foto')
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
                   href="{{ url('pelayanan/pelayanan-jemaat/katekisasi') }}">
                    Kembali
                </a>
            </div>            
            
        </form>
    </div>

</div>
@endsection

@push('css')
    <style>
        .group-sudah,
        .group-belum {
            display: none;
        }
    </style>
@endpush

@push('js')
    <script>
        function toggleGroup() {
            var status = document.getElementById('is_baptis').value;
            if (status === 'Sudah') {
                document.querySelectorAll('.group-sudah').forEach(el => el.style.display = 'flex');
                document.querySelectorAll('.group-belum').forEach(el => el.style.display = 'none');
            } else if (status === 'Belum') {
                document.querySelectorAll('.group-sudah').forEach(el => el.style.display = 'none');
                document.querySelectorAll('.group-belum').forEach(el => el.style.display = 'flex');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleGroup(); // inisialisasi saat pertama load
            document.getElementById('is_baptis').addEventListener('change', toggleGroup);
        });
    </script>
@endpush
