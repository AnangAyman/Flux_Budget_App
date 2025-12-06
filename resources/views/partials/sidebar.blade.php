<aside class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-wallet"></i> Flux
    </div>

    <ul class="sidebar-menu">
        <!-- Dashboard Link -->
        <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">
                <i class="fas fa-home"></i>
                <span>{{ __('menu_dashboard') }}</span>
            </a>
        </li>
        
        <!-- Transactions Link -->
        <li class="menu-item {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
            <a href="{{ route('transactions.index') }}">
                <i class="fas fa-exchange-alt"></i>
                <span>{{ __('menu_transactions') }}</span>
            </a>
        </li>
        
        <!-- Settings Link -->
        <li class="menu-item {{ request()->routeIs('settings') ? 'active' : '' }}">
            <a href="{{ route('settings') }}">
                <i class="fas fa-cog"></i>
                <span>{{ __('menu_settings') }}</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <!-- Logout Form -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout-full">
                <i class="fas fa-sign-out-alt"></i> <span>{{ __('btn_logout') }}</span>
            </button>
        </form>
    </div>
</aside>