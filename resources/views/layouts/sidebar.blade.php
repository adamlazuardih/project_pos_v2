<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url(auth()->user()->foto) }}" class="img-circle img-profil" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ auth()->user()->name }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">

            <li class="{{ Request::path() == 'dashboard' ? 'active' : '' }}">
                <a href="{{ url('/dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="header">MASTER</li>
            <li class="{{ Request::path() == 'kategori' ? 'active' : '' }}">
                <a href="{{ route('kategori.index') }}">
                    <i class="fa fa-cube"></i> <span>Kategori</span>
                </a>
            </li>
            <li class="{{ Request::path() == 'produk' ? 'active' : '' }}">
                <a href="{{ route('produk.index') }}">
                    <i class="fa fa-cubes"></i> <span>Produk</span>
                </a>
            </li>
            <li class="{{ Request::path() == 'member' ? 'active' : '' }}">
                <a href="{{ route('member.index') }}">
                    <i class="fa fa-id-card"></i> <span>Member</span>
                </a>
            </li>
            <li class="{{ Request::path() == 'supplier' ? 'active' : '' }}">
                <a href="{{ route('supplier.index') }}">
                    <i class="fa fa-truck"></i> <span>Supplier</span>
                </a>
            </li>
            <li class="header">TRANSAKSI</li>
            <li class="{{ Request::path() == 'pengeluaran' ? 'active' : '' }}">
                <a href="{{ route('pengeluaran.index') }}">
                    <i class="fa fa-money"></i> <span>Pengeluaran</span>
                </a>
            </li>
            <li class="{{ Request::path() ==  'pembelian' ? 'active' : ''  }}">
                <a href="{{ route('pembelian.index') }}">
                    <i class="fa fa-archive"></i> <span>Pembelian</span>
                </a>
            </li>
            <li class="{{ Request::path() ==  'penjualan' ? 'active' : ''  }}">
                <a href="{{ route('penjualan.index') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Penjualan</span>
                </a>
            </li>
            {{-- <li>
                <a href="{{ route('transaksi.index') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Lama</span>
                </a>
            </li> --}}
            {{-- <li class="{{ Request::path() == 'transaksi/baru' ? 'active' : '' }}">
                <a href="{{ route('transaksi.baru') }}">
                    <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Baru</span>
                </a>
            </li> --}}
            <li class="header">REPORT</li>
            <li class="{{ Request::path() ==  'laporan' ? 'active' : ''  }}">
                <a href="{{ route('laporan.index') }}">
                    <i class="fa fa-file-pdf-o"></i> <span>Laporan</span>
                </a>
            </li>
            <li class="header">SYSTEM</li>
            <li class="{{ Request::path() ==  'user' ? 'active' : ''  }}">
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-users"></i> <span>User</span>
                </a>
            </li>
            <li class="{{ Request::path() ==  'setting' ? 'active' : ''  }}">
                <a href="{{ route('setting.index') }}">
                    <i class="fa fa-cogs"></i> <span>Pengaturan</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
