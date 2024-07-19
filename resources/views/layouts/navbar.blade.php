 <div class="navbar-bg"></div>
 <nav class="navbar navbar-expand-lg main-navbar">
     <form class="form-inline mr-auto">
         <ul class="navbar-nav mr-3">
             <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>

         </ul>
     </form>
     <ul class="navbar-nav navbar-right">
         <li class="dropdown"><a href="#" data-toggle="dropdown"
                 class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                 <img alt="image" src="assets/img/avatar/avatar-2.png" class="rounded-circle mr-1">
                 <div class="d-sm-none d-lg-inline-block">{{ auth()->user()->name }}</div>
             </a>
             <div class="dropdown-menu dropdown-menu-right">
                 <div class="dropdown-title">Logged in 5 min ago</div>
                 <a href="features-profile.html" class="dropdown-item has-icon">
                     <i class="far fa-user"></i> Profile
                 </a>
                 <a href="features-activities.html" class="dropdown-item has-icon">
                     <i class="fas fa-bolt"></i> Activities
                 </a>
                 <a href="features-settings.html" class="dropdown-item has-icon">
                     <i class="fas fa-cog"></i> Settings
                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item has-icon text-danger"
                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                     <i class="fas fa-sign-out-alt"></i> Logout
                 </a>
             </div>
         </li>
     </ul>
 </nav>
 <div class="main-sidebar sidebar-style-2">
     <aside id="sidebar-wrapper">
         <div class="sidebar-brand">
             <a href="index.html">Muza Mebel</a>
         </div>
         <div class="sidebar-brand sidebar-brand-sm">
             <a href="index.html">MZ</a>
         </div>
         <ul class="sidebar-menu">
             <li class="menu-header">Dashboard</li>
             <li class="{{ Request::is('dashboard') ? 'active' : '' }}"><a class="nav-link"
                     href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a>
             </li>
             <li class="menu-header">Data Master </li>
             <li class="{{ Request::is('produk') ? 'active' : '' }}"><a class="nav-link"
                     href="{{ route('produk.index') }}"><i class="fas fa-cubes"></i><span>Produk</span></a></li>
             <li class="{{ Request::is('kategori') ? 'active' : '' }}"><a class="nav-link"
                     href="{{ route('kategori.index') }}"><i class="fas fa-list"></i><span>Kategori</span></a></li>
             <li class="{{ Request::is('pelanggan') ? 'active' : '' }}"><a class="nav-link"
                     href="{{ route('pelanggan.index') }}"><i class="fas fa-users"></i><span>Pelanggan</span></a></li>
             <li class="menu-header">Transaki </li>
             <li class="{{ Request::is('permintaan') ? 'active' : '' }}"><a class="nav-link"
                     href="{{ route('permintaan.index') }}"><i class="fas fa-download"></i><span>Permintaan</span></a>
             </li>
             <li class="{{ Request::is('penjualan') ? 'active' : '' }}"><a class="nav-link"
                     href="{{ route('penjualan.index') }}"><i class="fas fa-wallet"></i><span>Penjualan</span></a></li>
             <li class="{{ Request::is('transaksi') ? 'active' : '' }}"><a class="nav-link"
                     href="{{ route('transaksi.baru') }}"><i class="fas fa-cart-plus"></i><span>Transkai</span></a>
             </li>
             <li class="menu-header">Report </li>
             <li class="{{ Request::is('laporan') ? 'active' : '' }}"><a class="nav-link"
                     href="{{ route('laporan.index') }}"><i class="fas fa-file-pdf"></i><span>Laporan</span></a></li>
             {{-- <li class="menu-header">Data HRD </li>
             <li class="{{ Request::is('api_native_hrd') ? 'active' : '' }}"><a class="nav-link"
                     href="{{ route('api_native_hrd.index') }}"><i class="fas fa-file-pdf"></i><span>Data Karyawan</span></a></li> --}}
         </ul>
     </aside>
 </div>
 <form action="{{ route('logout') }}" method="post" id="logout-form" style="display: none;">
     @csrf
 </form>
