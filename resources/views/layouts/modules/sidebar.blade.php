<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            {{-- <a href="index.html">Stisla</a> --}}
            <a href="">
                {{-- <img src="{{ asset('assets/img/logo_app.png') }}" alt="logo" width="100" class=""> --}}
                <a href="">ONLINE SHOP ADAM</a>
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="">OSA</a>
            <a href="">
                {{-- <img src="{{ asset('assets/img/logo_app.png') }}" alt="logo" width="35" class=""> --}}
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fire"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="{{ request()->is('category') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('category.index') }}">
                    <i class="fas fa-list-ol"></i> <span>Category</span>
                </a>
            </li>
        </ul>
    </aside>
</div>