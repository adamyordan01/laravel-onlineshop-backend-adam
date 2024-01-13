<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            {{-- <a href="index.html">Stisla</a> --}}
            <a href="">
                {{-- <img src="{{ asset('assets/img/logo_app.png') }}" alt="logo" width="100" class=""> --}}
                <a href="index.html">RSUD LANGSA</a>
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            {{-- <a href="index.html">St</a> --}}
            <a href="">
                <img src="{{ asset('assets/img/logo_app.png') }}" alt="logo" width="35" class="">
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="menu-header">Resource</li>
            {{-- make active menu --}}
            <li class="dropdown {{ request()->is('resources*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i> <span>Resource</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->is('resources/location') ? 'active' : '' }}">
                        <a href="{{ route('location') }}">Location</a>
                    </li>

                    <li class="{{ request()->is('resources/organization') ? 'active' : '' }}">
                        <a href="{{ route('organization') }}">Organization</a>
                    </li>

                    <li class="{{ request()->is('resources/doctor') ? 'active' : '' }}">
                        <a href="{{ route('doctor') }}">Dokter</a>
                    </li>

                    <li class="{{ request()->is('resources/midwife-nurse') ? 'active' : '' }}">
                        <a href="{{ route('midwife-nurse') }}">Perawat-Bidan</a>
                    </li>

                    <li><a href="auth-register.html">Register</a></li>
                    <li><a href="auth-reset-password.html">Reset Password</a></li>
                </ul>
            </li>
        </ul>
    </aside>
</div>