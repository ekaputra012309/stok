@php
    $role = App\Models\Privilage::getRoleKodeForAuthenticatedUser();
    $companyProfile = App\Models\CompanyProfile::first();
@endphp

<aside class="main-sidebar sidebar-light-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <img src="{{ asset($companyProfile->image) }}" alt="AdminLTE Logo" style="width: 75px;"> {{ $companyProfile->name }}
    </a>
    <div class="sidebar">
        <br>
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-header">Master</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>Master Data<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('barang.index') }}" class="nav-link {{ request()->routeIs('barang.index') ? 'active' : '' }}">
                                <p>Barang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('satuan.index') }}" class="nav-link {{ request()->routeIs('satuan.index') ? 'active' : '' }}">
                                <p>Satuan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">Transaksi</li>

                <li class="nav-item">
                    <a href="{{ route('purchase_order.index') }}" class="nav-link {{ request()->routeIs('purchase_order.index') ? 'active' : '' }}">                        
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Purchase Order (PO)</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('barang_masuk.index') }}" class="nav-link {{ request()->routeIs('barang_masuk.index') ? 'active' : '' }}">                        
                        <i class="nav-icon fas fa-download"></i>
                        <p>Barang Masuk</p>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">                        
                        <i class="nav-icon fas fa-upload"></i>
                        <p>Barang Keluar</p>
                    </a>
                </li> -->
                <!-- <li class="nav-item">
                    <a href="{{ route('transaksi.index') }}" class="nav-link {{ request()->routeIs('transaksi.index') ? 'active' : '' }}">                        
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Data Transaksi</p>
                    </a>
                </li> -->
                
                @if (in_array($role, ['superadmin', 'admin']))
                <!-- <li class="nav-item">
                    <a href="{{ route('transaksi.laporan') }}" class="nav-link {{ request()->routeIs('transaksi.laporan') ? 'active' : '' }}">                        
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Laporan Transaksi</p>
                    </a>
                </li> -->

                <li class="nav-header">Settings</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Konfigurasi<i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('privilage.index') }}" class="nav-link {{ request()->routeIs('privilage.index') ? 'active' : '' }}">
                                <p>Privilage</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.index') }}" class="nav-link {{ request()->routeIs('user.index') ? 'active' : '' }}">
                                <p>Manage User</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('companyProfile') }}" class="nav-link {{ request()->routeIs('companyProfile') ? 'active' : '' }}">
                                <p>Perusahaan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
