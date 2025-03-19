@extends('layouts.template')

@section('content')
    <div class="row">
        <div class="col-3">
            <div class="card p-4">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/users/avatar-2.png') }}" class="rounded-circle mr-3" width="80" alt="User">
                    <div class="ml-3">
                        <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                        <p class="text-muted">Username : {{ Auth::user()->username }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-6">
          <!-- small box -->
          <div class="small-box bg-info">
            <div class="inner">
              <h3></h3>

              <p>Pengelolaan Pengguna</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="{{ url('/pengelolaan-pengguna') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
    </div>

@endsection