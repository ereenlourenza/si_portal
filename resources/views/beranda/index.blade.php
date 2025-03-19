@extends('layouts.template')

@section('content')

    <div class="card p-4">
        <div class="d-flex align-items-center">
            {{-- <img src="{{ asset('images/users/avatar-2.png') }}" class="rounded-circle mr-3" width="80" alt="User"> --}}
            <img src="
                @if(auth()->user()->level->level_kode == 'SAD')
                    {{ asset('images/users/avatar-2.png') }}
                @elseif(auth()->user()->level->level_kode == 'ADM')
                    {{ asset('images/users/avatar-4.png') }}
                @elseif(auth()->user()->level->level_kode == 'MLJ')
                    {{ asset('images/users/avatar-1.png') }}
                @elseif(auth()->user()->level->level_kode == 'PHM')
                    {{ asset('images/users/avatar-3.png') }}
                @else
                    {{ asset('images/users/avatar.png') }}
                @endif
            " class="rounded-circle mr-3" width="80" alt="User">

            <div class="ml-3">
                <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                <p class="text-muted">Username : {{ Auth::user()->username }}</p>
            </div>
        </div>
    </div>

@endsection