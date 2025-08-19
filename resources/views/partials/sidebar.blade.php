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
                            class="menu-link {{ request()->routeIs('order.create') ? 'active' : '' }}">
                            Create Order
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('order.index') }}"
                            class="menu-link {{ request()->routeIs(['order.index','order.show']) ? 'active' : '' }}">
                            All Orders
                        </a>
                    </li>
                </ul>
            </li>
            <li
                class="menu-item {{ request()->routeIs(['yarnquotation.*', 'nettingquotation.*','dyeingquotation.*','accessoriesquotation.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">note_stack</span>
                    <span class="title">Quotation</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('yarnquotation.index') }}"
                            class="menu-link {{ request()->routeIs('yarnquotation.*') ? 'active' : '' }}">
                            Yarn Quotation
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('nettingquotation.index') }}"
                            class="menu-link {{ request()->routeIs('nettingquotation.*') ? 'active' : '' }}">
                            Netting Quotation
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('dyeingquotation.index') }}"
                            class="menu-link {{ request()->routeIs('dyeingquotation.*') ? 'active' : '' }}">
                            Dyeing Quotation
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('accessoriesquotation.index') }}"
                            class="menu-link {{ request()->routeIs('accessoriesquotation.*') ? 'active' : '' }}">
                            Accessories Quotation
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item {{ request()->routeIs(['yarnreceived.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">note_stack</span>
                    <span class="title">Goods Receive</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('yarnreceived.index') }}"
                            class="menu-link {{ request()->routeIs('yarnreceived.*') ? 'active' : '' }}">
                            Yarn Received
                        </a>
                    </li>
                </ul>
            </li>

            <li class="menu-item {{ request()->routeIs('settings.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">note_stack</span>
                    <span class="title">App Settings</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('settings.style.index') }}"
                            class="menu-link {{ request()->routeIs('settings.style.index') ? 'active' : '' }}">
                            Style
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('settings.yarnfactroy.index') }}"
                            class="menu-link {{ request()->routeIs('settings.yarnfactroy.index') ? 'active' : '' }}">
                            Yarn Factory
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('settings.nettingfactroy.index') }}"
                            class="menu-link {{ request()->routeIs('settings.nettingfactroy.index') ? 'active' : '' }}">
                            Netting Factory
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('settings.dyeingfactroy.index') }}"
                            class="menu-link {{ request()->routeIs('settings.dyeingfactroy.index') ? 'active' : '' }}">
                            Dyeing Factory
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('settings.garmentsfactroy.index') }}"
                            class="menu-link {{ request()->routeIs('settings.garmentsfactroy.index') ? 'active' : '' }}">
                            Garments Factory
                        </a>
                    </li>
                </ul>
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