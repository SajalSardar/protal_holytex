<div class="sidebar-area" id="sidebar-area">
    <div class="logo position-relative">
        <a href="index" class="d-block text-decoration-none position-relative">
            <img src="/assets/images/logo-icon.png" alt="logo-icon">
            <span class="logo-text fw-bold text-dark">Trezo</span>
        </a>
        <button
            class="sidebar-burger-menu bg-transparent p-0 border-0 opacity-0 z-n1 position-absolute top-50 end-0 translate-middle-y"
            id="sidebar-burger-menu">
            <i data-feather="x"></i>
        </button>
    </div>

    <aside id="layout-menu" class="layout-menu menu-vertical menu active" data-simplebar>
        <ul class="menu-inner">
            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">MAIN</span>
            </li>
            <li class="menu-item open">
                <a href="{{ route('dashboard') }}" class="menu-link {{ Request::is('dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined menu-icon">dashboard</span>
                    <span class="title">Dashboard</span>
                </a>
            </li>

            <li class="menu-item {{ request()->routeIs('order.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">note_stack</span>
                    <span class="title">Orders</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('order.create') }}"
                            class="menu-link {{ Request::is('order/create') ? 'active' : '' }}">
                            Create Order
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/landing-page" class="menu-link {{ Request::is('landing-page') ? 'active' : '' }}">
                            All Orders
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">note_stack</span>
                    <span class="title">Front Pages</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="/landing-page" class="menu-link {{ Request::is('landing-page') ? 'active' : '' }}">
                            Landing Page
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">APPS</span>
            </li>

            <li class="menu-item">
                <a href="/calender" class="menu-link {{ Request::is('calender') ? 'active' : '' }}">
                    <span class="material-symbols-outlined menu-icon">date_range</span>
                    <span class="title">Calender</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="/logout" class="menu-link logout">
                    <span class="material-symbols-outlined menu-icon">logout</span>
                    <span class="title">Logout</span>
                </a>
            </li>
        </ul>
    </aside>
</div>