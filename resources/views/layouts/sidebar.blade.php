<div class="sidebar">
    <!-- Sidebar user (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        @if(auth()->user()->level->level_kode == 'SAD')
        <div class="info">
            <a href="#" class="d-block">Pengelolaan Pengguna</a>
        </div>
        @elseif(auth()->user()->level->level_kode == 'ADM')
        <div class="info">
            <a href="#" class="d-block">Pengelolaan Informasi</a>
        </div>
        @elseif(auth()->user()->level->level_kode == 'MLJ')
        <div class="info">
            <a href="#" class="d-block">Majelis Jemaat</a>
        </div>
        @elseif(auth()->user()->level->level_kode == 'PHM')
        <div class="info">
            <a href="#" class="d-block">PHMJ</a>
        </div>
        @endif
    </div>

     <!-- Sidebar Menu --> 
    <nav class="mt-2"> 
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"> 
            {{-- <li class="nav-item has-treeview {{ ($activeMenu == 'pengguna')? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Pengelolaan Pengguna
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('level.index') }}" class="nav-link {{ ($activeMenu == 'level')? 'active' : '' }}">
                            <i class="far fa-arrow nav-icon"></i>
                            <p>Level Pengguna</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}" class="nav-link {{ ($activeMenu == 'user')? 'active' : '' }}">
                            <i class="far fa-arrow nav-icon"></i>
                            <p>Data Pengguna</p>
                        </a>
                    </li>
                </ul>
            </li> --}}
            
            <li class="nav-item"> 
                <a href="{{ route('beranda.index') }}" class="nav-link  {{ ($activeMenu == 'dashboard')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-tachometer-alt"></i> 
                    <p>Dashboard</p> 
                </a> 
            </li>

            @if(auth()->user()->level->level_kode == 'SAD')
            <li class="nav-header">Data Pengguna</li> 
            <li class="nav-item"> 
                <a href="{{ route('level.index') }}" class="nav-link {{ ($activeMenu == 'level')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-layer-group"></i> 
                    <p>Level Pengguna</p> 
                </a>
            </li> 
            <li class="nav-item"> 
                <a href="{{ route('user.index') }}" class="nav-link {{ ($activeMenu == 'user')? 'active' : '' }}"> 
                    <i class="nav-icon far fa-user"></i> 
                    <p>Data Pengguna</p> 
                </a> 
            </li> 
            <li class="nav-header">Aktivitas Pengguna</li> 
            <li class="nav-item">
                <a href="{{ url('/aktivitas') }}" class="nav-link {{ ($activeMenu == 'aktivitas')? 'active' : '' }} "> 
                    <i class="nav-icon far fa-bookmark"></i> 
                    <p>Log Aktivitas</p>
                </a> 
            </li>
            @endif
            
            @if(auth()->user()->level->level_kode == 'ADM')
            <li class="nav-header">Upload</li> 
            <li class="nav-item">
                <a href="{{ route('dokumen.index') }}" class="nav-link {{ ($activeMenu == 'dokumen')? 'active' : '' }} "> 
                    <i class="nav-icon far fa-file"></i> 
                    <p>Dokumen</p>
                </a> 
            </li>
            {{-- <li class="nav-item">
                <a href="{{ route('tataibadah.index') }}" class="nav-link {{ ($activeMenu == 'tataibadah')? 'active' : '' }} "> 
                    <i class="nav-icon far fa-file"></i> 
                    <p>Tata Ibadah</p>
                </a> 
            </li>
            <li class="nav-item">
                <a href="{{ route('tataibadah.index') }}" class="nav-link {{ ($activeMenu == 'wartajemaat')? 'active' : '' }} "> 
                    <i class="nav-icon far fa-book"></i> 
                    <p>Warta Jemaat</p>
                </a> 
            </li> --}}
            <li class="nav-header">Informasi Ibadah</li> 
            <li class="nav-item">
                <a href="{{ route('kategoriibadah.index') }}" class="nav-link {{ ($activeMenu == 'kategoriibadah')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-calendar-plus"></i> 
                    <p>Kategori Ibadah</p>
                </a> 
            </li>
            <li class="nav-item">
                <a href="{{ route('ibadah.index') }}" class="nav-link {{ ($activeMenu == 'ibadah')? 'active' : '' }} "> 
                    <i class="nav-icon far fa-calendar"></i> 
                    <p>Jadwal Ibadah</p>
                </a> 
            </li>
            <li class="nav-header">Informasi Pelayan</li> 
            <li class="nav-item">
                <a href="{{ route('kategoripelayan.index') }}" class="nav-link {{ ($activeMenu == 'kategoripelayan')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-user-plus"></i> 
                    <p>Kategori Pelayan</p>
                </a> 
            </li>
            <li class="nav-item">
                <a href="{{ route('pelayan.index') }}" class="nav-link {{ ($activeMenu == 'pelayan')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-people-arrows"></i> 
                    <p>Data Pelayan</p>
                </a> 
            </li>
            <li class="nav-header">Galeri</li> 
            <li class="nav-item">
                <a href="{{ route('kategorigaleri.index') }}" class="nav-link {{ ($activeMenu == 'kategorigaleri')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-camera"></i> 
                    <p>Kategori Galeri</p>
                </a> 
            </li>
            <li class="nav-item">
                <a href="{{ route('galeri.index') }}" class="nav-link {{ ($activeMenu == 'galeri')? 'active' : '' }} "> 
                    <i class="nav-icon far fa-image"></i> 
                    <p>Data Galeri</p>
                </a> 
            </li>
            <li class="nav-header">Publik</li> 
            <li class="nav-item">
                <a href="{{ route('sektor.index') }}" class="nav-link {{ ($activeMenu == 'sektor')? 'active' : '' }} "> 
                    <i class="nav-icon far fa-map"></i> 
                    <p>Sektor</p>
                </a> 
            </li>
            <li class="nav-item">
                <a href="{{ route('sejarah.index') }}" class="nav-link {{ ($activeMenu == 'sejarah')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-film"></i> 
                    <p>Sejarah</p>
                </a> 
            </li>
            <li class="nav-item">
                <a href="{{ route('pelkat.index') }}" class="nav-link {{ ($activeMenu == 'pelkat')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-object-group"></i> 
                    <p>Pelkat</p>
                </a> 
            </li>
            <li class="nav-item">
                <a href="{{ route('komisi.index') }}" class="nav-link {{ ($activeMenu == 'komisi')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-object-group"></i> 
                    <p>Komisi</p>
                </a> 
            </li>
            <li class="nav-item">
                <a href="{{ route('persembahan.index') }}" class="nav-link {{ ($activeMenu == 'persembahan')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-qrcode"></i> 
                    <p>QR Persembahan</p>
                </a> 
            </li>
            <li class="nav-header">Ruangan</li> 
            <li class="nav-item">
                <a href="{{ route('ruangan.index') }}" class="nav-link {{ ($activeMenu == 'ruangan')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-building"></i> 
                    <p>Data Ruangan</p>
                </a> 
            </li>
            <li class="nav-item">
                <a href="{{ route('peminjamanruangan.index') }}" class="nav-link {{ ($activeMenu == 'peminjamanruangan')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-place-of-worship"></i> 
                    <p>Peminjaman Ruangan</p>
                </a> 
            </li>
            <li class="nav-header">Sakramen</li> 
            <li class="nav-item">
                <a href="{{ route('pendaftaran.index') }}" class="nav-link {{ ($activeMenu == 'pendaftaran')? 'active' : '' }} "> 
                    <i class="nav-icon fas fa-receipt"></i> 
                    <p>Pendaftaran Sakramen</p>
                </a> 
            </li>

            @endif
            {{-- <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link">
                  <i class="nav-icon far fa-sign-out-alt nav-icon"></i>
                  <p>Logout</p>
                </a>
            </li> --}}
        </ul> 
    </nav>
</div>