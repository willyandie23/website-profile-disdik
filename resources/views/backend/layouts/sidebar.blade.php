<nav class="pc-sidebar">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ url('/dashboard') }}" class="b-brand text-primary">
                <img src="" id="site_logo" alt="Logo" style="height: 75px;">
            </a>
        </div>
        <div class="navbar-content">
            <ul class="pc-navbar">
                <li class="pc-item">
                    <a href="{{ url('/dashboard') }}" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-dashboard"></i></span>
                        <span class="pc-mtext">Beranda</span>
                    </a>
                </li>

                <li class="pc-item pc-caption">
                    <label>Master Data</label>
                    <i class="ti ti-dashboard"></i>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-world"></i></span>
                        <span class="pc-mtext">Manajemen Website</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a class="pc-link" href="{{ route('identity.index') }}">
                                Identitas Website
                            </a>
                        </li>
                        <li class="pc-item">
                            <a class="pc-link" href="{{ route('banner.index') }} ">
                                Banner
                            </a>
                        </li>
                        <li class="pc-item">
                            <a class="pc-link" href="{{ route('link.index') }} ">
                                Link
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-files"></i></span>
                        <span class="pc-mtext">Manajemen Data</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a class="pc-link" href="{{ route('news.index') }}">
                                Berita
                            </a>
                        </li>
                        <li class="pc-item">
                            <a class="pc-link" href="{{ route('gallery.index') }}">
                                Galeri
                            </a>
                        </li>
                        <li class="pc-item">
                            <a class="pc-link" href="{{ route('download.index') }}">
                                Unduhan
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="pc-item pc-hasmenu">
                    <a href="#!" class="pc-link">
                        <span class="pc-micon"><i class="ti ti-user"></i></span>
                        <span class="pc-mtext">Struktur Organisasi</span>
                        <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                    </a>
                    <ul class="pc-submenu">
                        <li class="pc-item">
                            <a class="pc-link" href="{{ route('organizations.index') }}">
                                Daftar Anggota
                            </a>
                        </li>
                        <li class="pc-item">
                            <a class="pc-link" href="{{ route('field.index') }}">
                                Bidang Kantor
                            </a>
                        </li>
                    </ul>
                    <li class="pc-item">
                        <a href="{{ route('contact.index') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-mailbox"></i></span>
                            <span class="pc-mtext">Kotak Saran</span>
                        </a>
                    </li
                </li>
                @hasrole('superadmin')
                    <li class="pc-item pc-caption">
                        <label>Manajemen Aplikasi</label>
                        <i class="ti ti-dashboard"></i>
                    </li>
                    <li class="pc-item {{ request()->is('app-logs*') ? 'active' : '' }}">
                        <a href="{{ url('app-logs') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-receipt"></i></span>
                            <span class="pc-mtext">Log Aktivitas</span>
                        </a>
                    </li>
                @endhasrole
            </ul>
        </div>
    </div>
</nav>