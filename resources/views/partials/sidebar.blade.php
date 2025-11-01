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
            <li class="menu-item {{ request()->routeIs(['yarnquotation.*','yarnreceived.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">note_stack</span>
                    <span class="title">Yarn</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('yarnquotation.index') }}"
                            class="menu-link {{ request()->routeIs('yarnquotation.*') ? 'active' : '' }}">
                            Yarn Quotation
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('yarnreceived.create') }}"
                            class="menu-link {{ request()->routeIs('yarnreceived.create') ? 'active' : '' }}">
                            Yarn Received
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('yarnreceived.index') }}"
                            class="menu-link {{ request()->routeIs('yarnreceived.index') ? 'active' : '' }}">
                            Yarn Stock
                        </a>
                    </li>
                </ul>
            </li>
            <li
                class="menu-item {{ request()->routeIs(['nettingquotation.*','dyeingquotation.*','accessoriesquotation.*','dyedquotation.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">note_stack</span>
                    <span class="title">Quotation</span>
                </a>
                <ul class="menu-sub">

                    <li class="menu-item">
                        <a href="{{ route('dyedquotation.index') }}"
                            class="menu-link {{ request()->routeIs('dyedquotation.*') ? 'active' : '' }}">
                            Dyed Quotation
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
            <li
                class="menu-item {{ request()->routeIs(['yarnreceived.*','nettingreceived.*','dyeingreceived.*','accessoriesreceived.*','yarnreceiveddyed.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">note_stack</span>
                    <span class="title">Goods Receive</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('yarnreceiveddyed.index') }}"
                            class="menu-link {{ request()->routeIs('yarnreceiveddyed.*') ? 'active' : '' }}">
                            Yarn Rec. Dyed Fac.
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('yarnreceived.index') }}"
                            class="menu-link {{ request()->routeIs('yarnreceived.*') ? 'active' : '' }}">
                            Yarn Rec. Netting Fac.
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('nettingreceived.index') }}"
                            class="menu-link {{ request()->routeIs('nettingreceived.*') ? 'active' : '' }}">
                            Netting. Rec. Dyeing Fac.
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('dyeingreceived.index') }}"
                            class="menu-link {{ request()->routeIs('dyeingreceived.*') ? 'active' : '' }}">
                            Dyeing Rec. Garments Fac.
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('accessoriesreceived.index') }}"
                            class="menu-link {{ request()->routeIs('accessoriesreceived.*') ? 'active' : '' }}">
                            Accessories Received
                        </a>
                    </li>
                </ul>
            </li>
            <li
                class="menu-item {{ request()->routeIs(['yarnstorestock.*','nettingstorestock.*', 'accessoriesstock.*', 'dyedyarnstock.*','dyeingknitstorestock.*']) ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <span class="material-symbols-outlined menu-icon">note_stack</span>
                    <span class="title">Goods Stock</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('yarnstorestock.index') }}"
                            class="menu-link {{ request()->routeIs('yarnstorestock.*') ? 'active' : '' }}">
                            Yarn Stock
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('dyedyarnstock.index') }}"
                            class="menu-link {{ request()->routeIs('dyedyarnstock.*') ? 'active' : '' }}">
                            Dyed Yarn Stock
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('nettingstorestock.index') }}"
                            class="menu-link {{ request()->routeIs('nettingstorestock.*') ? 'active' : '' }}">
                            Row Knit Stock
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('dyeingknitstorestock.index') }}"
                            class="menu-link {{ request()->routeIs('dyeingknitstorestock.*') ? 'active' : '' }}">
                            Dyeing Knit
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('accessoriesstock.index') }}"
                            class="menu-link {{ request()->routeIs('accessoriesstock.*') ? 'active' : '' }}">
                            Accessories Stock
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
                            class="menu-link {{ request()->routeIs('settings.style.*') ? 'active' : '' }}">
                            Style
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('settings.yarnfactroy.index') }}"
                            class="menu-link {{ request()->routeIs('settings.yarnfactroy.*') ? 'active' : '' }}">
                            Yarn Factory
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('settings.dyedfactory.index') }}"
                            class="menu-link {{ request()->routeIs('settings.dyedfactory.*') ? 'active' : '' }}">
                            Dyed Factory
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('settings.nettingfactroy.index') }}"
                            class="menu-link {{ request()->routeIs('settings.nettingfactroy.*') ? 'active' : '' }}">
                            Netting Factory
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('settings.dyeingfactroy.index') }}"
                            class="menu-link {{ request()->routeIs('settings.dyeingfactroy.*') ? 'active' : '' }}">
                            Dyeing Factory
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('settings.garmentsfactroy.index') }}"
                            class="menu-link {{ request()->routeIs('settings.garmentsfactroy.*') ? 'active' : '' }}">
                            Garments Factory
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('settings.store.index') }}"
                            class="menu-link {{ request()->routeIs('settings.store.*') ? 'active' : '' }}">
                            Create Store
                        </a>
                    </li>
                </ul>
            </li>


            <li class="menu-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="menu-link logout border-0 w-100">
                        <span class="material-symbols-outlined menu-icon">logout</span>
                        <span class="title">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </aside>
</div>