@extends('global.layouts.app')

@section('content')
<div class="container-fluid py-12 space-y-6">

    <h1 class="max-w-5xl mx-auto text-2xl md:text-3xl font-bold text-amber-700 mb-6 bg-amber-100 px-4 py-4 rounded shadow">
        Persembahan
    </h1>

    <p class="max-w-3xl mx-auto py-4 text-center text-sm md:text-base text-gray-700 leading-relaxed">
        Persembahan secara digital dapat dilakukan melalui transfer bank atau QRIS yang telah disediakan. <br>
        Kiranya persembahan kita menjadi wujud pengucapan syukur yang berkenan di hadapan Tuhan dan menjadi berkat bagi pelayanan-Nya. Tuhan Yesus memberkati.
    </p>

    @if ($pengucapan_syukur)
        <div class="max-w-2xl mx-auto bg-[#231C0D] rounded-xl p-6 shadow-lg text-white flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
            <!-- Ilustrasi HP -->
            <div class="w-full md:w-1/3 flex justify-center">
                <img src="{{ asset('images/global/qris.webp') }}" alt="QRIS Ilustrasi" class="w-32 md:w-40">
            </div>

            <!-- Info QR & Rekening -->
            <div class="w-full md:w-2/3 text-center md:text-left">
                <p class="text-lg font-bold text-center text-white mb-2 uppercase">{{ $pengucapan_syukur->persembahan_nama }}</p>
                <div class="my-2 flex justify-center md:justify-start">
                    <img src="{{ asset('storage/images/barcode/' . $pengucapan_syukur->barcode) }}" alt="QR Pengucapan Syukur" class="mx-auto w-40 h-40 mb-4">
                </div>
                <p class="text-white text-center font-bold text-base md:text-lg">
                    {{ $pengucapan_syukur->nomor_rekening }}<br>
                    <span class="text-sm text-white">a.n {{ $pengucapan_syukur->atas_nama }}</span>
                </p>
            </div>
        </div>
        @endif

    {{-- Teks Penjelasan --}}
    <div class="max-w-3xl mx-auto py-16 text-center text-sm md:text-base text-gray-700 leading-relaxed">
        <p class="mb-6">
            Marilah kita mengungkapkan rasa syukur atas kasih dan berkat Tuhan dalam hidup kita
            <br>
            <em>"Hendaklah masing-masing memberikan menurut kerelaan hatinya, jangan dengan sedih hati atau karena paksaan, sebab Allah mengasihi orang yang memberi dengan sukacita."</em><br>
            <strong>2 Korintus 9:7</strong>
        </p>
    </div>

    {{-- Persembahan Lain --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 mb-8 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
        @foreach ($persembahan_lain as $item)
        <div class="border p-4 rounded-xl text-center shadow hover:shadow-lg transition border-[#614D24] bg-white">
            <p class="text-sm font-bold uppercase text-[#614D24] mb-2">{{ $item->persembahan_nama }}</p>
            <img src="{{ asset('storage/images/barcode/' . $item->barcode) }}" alt="QR {{ $item->persembahan_nama }}" class="mx-auto w-32 h-32 mb-3">
            <p class="text-[#614D24] text-sm">
                {{ $item->nomor_rekening }}<br>
                <span class="text-xs text-[#614D24]">a.n {{ $item->atas_nama }}</span>
            </p>
        </div>
        @endforeach
    </div>

</div>
@endsection
