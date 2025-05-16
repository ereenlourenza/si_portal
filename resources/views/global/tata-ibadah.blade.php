@extends('global.layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-4 py-12 leading-relaxed">
    <h1 class="text-2xl md:text-3xl font-bold text-amber-700 mb-4 bg-amber-100 px-4 py-4 rounded shadow text-center md:text-left">
        Tata Ibadah
    </h1>

    <!-- Filter Form -->
    <div class="max-w-6xl mx-auto px-4 mt-16">
        <form method="GET" class="mb-6">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <label for="tanggal" class="text-sm font-medium text-gray-700">Filter berdasarkan tanggal:</label>
                <input 
                    type="date" 
                    name="tanggal" 
                    id="tanggal"
                    value="{{ request('tanggal') }}"
                    class="border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-amber-300 focus:outline-none"
                >
                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded shadow">
                    Tampilkan
                </button>
                <a href="{{ route('tata-ibadah') }}" class="border-amber-600 text-amber-700 hover:bg-amber-50 px-4 py-2 border rounded shadow transition">Reset</a>
            </div>
        </form>
    </div>

    <!-- Grid Card -->
    <div class="max-w-6xl mx-auto px-4 py-8 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

        @forelse ($tataIbadahList as $item)
            <a href="{{ asset('storage/dokumen/tataibadah/'.$item->file) }}" target="_blank" class="transform transition-transform duration-500 hover:scale-105">
                <div class="bg-white rounded overflow-hidden shadow-lg">
                    <img loading="lazy" src="{{ asset('images/global/tata-ibadah.webp') }}" alt="Tata Ibadah" class="w-full h-auto object-cover">
                    <div class="bg-[#231C0D] text-white px-4 py-3 text-center">
                        <h3 class="text-md font-bold uppercase">Tata Ibadah</h3>
                        <p class="text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</p>
                        <p class="text-sm">{{ $item->deskripsi }}</p>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center text-gray-600 py-8">
                <p class="text-lg">Belum ada data Tata Ibadah untuk tanggal ini.</p>
            </div>
        @endforelse

        {{-- @forelse ($tataIbadahList as $item)
            <div class="relative group transform transition-transform duration-500 hover:scale-105">
                <div class="bg-white rounded overflow-hidden shadow-lg">
                    <img src="{{ asset('images/global/tata-ibadah.webp') }}" alt="Tata Ibadah" class="w-full h-auto object-cover">
                    <div class="bg-[#231C0D] text-white px-4 py-3 text-center">
                        <h3 class="text-md font-bold uppercase">Tata Ibadah</h3>
                        <p class="text-sm">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}</p>
                        <p class="text-sm">{{ $item->deskripsi }}</p>
                    </div>
                </div>

                <!-- Tombol mengambang -->
                <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition duration-300">
                    <!-- Button Preview -->
                    <button onclick="openPdfModal('{{ asset('storage/dokumen/tataibadah/'.$item->file) }}')" 
                            class="bg-white hover:bg-amber-500 hover:text-white text-gray-700 border border-gray-300 rounded-full p-2 shadow-md transition duration-200"
                            title="Lihat Dokumen">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m0 0h6m-6 0V6m0 6v6" />
                        </svg>
                    </button>

                    <!-- Button Download -->
                    <a href="{{ asset('storage/dokumen/tataibadah/'.$item->file) }}" 
                    download 
                    class="bg-white hover:bg-amber-500 hover:text-white text-gray-700 border border-gray-300 rounded-full p-2 shadow-md transition duration-200"
                    title="Download Dokumen">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 12v6m0 0l3-3m-3 3l-3-3m6-9h2a2 2 0 012 2v4m-2-6H6a2 2 0 00-2 2v4" />
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-600 py-8">
                <p class="text-lg">Belum ada data Tata Ibadah untuk tanggal ini.</p>
            </div>
        @endforelse

        <!-- PDF Viewer Modal -->
        <div id="pdfModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg w-full max-w-4xl h-[80vh] relative shadow-lg overflow-hidden">
                <button onclick="closePdfModal()" class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl">&times;</button>
                <iframe id="pdfViewer" src="" class="w-full h-full" frameborder="0"></iframe>
            </div>
        </div> --}}



    </div>

    
</div>

@endsection

@push('css')
    
@endpush

@push('js')
<script>
    function openPdfModal(pdfUrl) {
        const modal = document.getElementById('pdfModal');
        const viewer = document.getElementById('pdfViewer');
        viewer.src = pdfUrl;
        modal.classList.remove('hidden');
    }

    function closePdfModal() {
        const modal = document.getElementById('pdfModal');
        const viewer = document.getElementById('pdfViewer');
        viewer.src = '';
        modal.classList.add('hidden');
    }
</script>

@endpush
