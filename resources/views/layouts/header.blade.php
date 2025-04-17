<div class="header-container" style="background: linear-gradient(118deg, rgba(39, 41, 61, 0.7), rgba(30, 30, 45, 0.9)); display: flex; justify-content: space-between; align-items: center;">
    <div class="d-flex align-items-center">
        <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
            <i class="bi bi-list" style="font-size: 1.8rem; color: #e0e0e0;"></i>
        </button>

        <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
            <i class="bi bi-list" style="font-size: 1.8rem; color: #e0e0e0;"></i>
        </button>
    </div>

    <ul class="c-header-nav d-flex align-items-center mr-3">
        @can('create_pos_sales')
        <li class="c-header-nav-item mr-3">
            <a class="btn btn-pill {{ request()->routeIs('app.pos.index') ? 'disabled' : '' }}" href="{{ route('app.pos.index') }}" style="background: linear-gradient(118deg, #3699ff, #5a61f4); color: white; border: none; box-shadow: 0 2px 8px rgba(54, 153, 255, 0.5);">
                <i class="bi bi-cart mr-1"></i> POS System
            </a>
        </li>
        @endcan

        <!-- Language Switcher -->
        <li class="c-header-nav-item dropdown d-md-down-none mr-3">
            <a class="c-header-nav-link dropdown-toggle d-flex align-items-center" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                @if(session('locale') == 'en')
                    <span class="flag-icon flag-icon-us mr-1"></span>
                    <span style="color: #e0e0e0;">English</span>
                @else
                    <span class="flag-icon flag-icon-id mr-1"></span>
                    <span style="color: #e0e0e0;">Indonesia (Beta)</span>
                @endif
                <i class="bi bi-chevron-down ml-1" style="font-size: 12px;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right pt-0" style="margin-top: 15px; min-width: 10rem; border-radius: 8px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                <div class="dropdown-header bg-primary text-white py-2">
                    <strong>{{ __('menu.language') }}</strong>
                </div>
                <a class="dropdown-item d-flex align-items-center {{ session('locale') == 'id' ? 'active' : '' }}" href="{{ route('lang.switch', 'id') }}">
                    <span class="flag-icon flag-icon-id mr-2"></span> Indonesia (Beta)
                </a>
                <a class="dropdown-item d-flex align-items-center {{ session('locale') == 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">
                    <span class="flag-icon flag-icon-us mr-2"></span> English
                </a>
            </div>
        </li>

        @can('show_notifications')
        <li class="c-header-nav-item dropdown d-md-down-none mr-3">
            <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="bi bi-bell" style="font-size: 20px; color: #e0e0e0;"></i>
                <span class="badge badge-pill badge-danger" style="position: absolute; top: 5px; right: -3px; font-size: 0.6rem; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                @php
                    $low_quantity_products = \Modules\Product\Entities\Product::select('id', 'product_quantity', 'product_stock_alert', 'product_code')->whereColumn('product_quantity', '<=', 'product_stock_alert')->get();
                    echo $low_quantity_products->count();
                @endphp
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg pt-0" style="margin-top: 15px; border-radius: 8px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                <div class="dropdown-header bg-primary text-white py-2">
                    <strong>{{ $low_quantity_products->count() }} Notifications</strong>
                </div>
                @forelse($low_quantity_products as $product)
                    <a class="dropdown-item" href="{{ route('products.show', $product->id) }}">
                        <i class="bi bi-exclamation-circle-fill mr-1 text-warning"></i> Product: "{{ $product->product_code }}" is low in quantity!
                    </a>
                @empty
                    <a class="dropdown-item" href="#">
                        <i class="bi bi-check-circle mr-2 text-success"></i> No notifications available.
                    </a>
                @endforelse
            </div>
        </li>
        @endcan

        <li class="c-header-nav-item dropdown">
            <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button"
               aria-haspopup="true" aria-expanded="false">
                <div class="c-avatar mr-2">
                    <img class="c-avatar rounded-circle" src="{{ auth()->user()->getFirstMediaUrl('avatars') }}" style="border: 2px solid #3699ff; box-shadow: 0 2px 10px rgba(0,0,0,0.2);" alt="Profile Image">
                </div>
                <div class="d-flex flex-column">
                    <span class="font-weight-bold" style="color: #e0e0e0;">{{ auth()->user()->name }}</span>
                    <span class="font-italic" style="color: #aaa; font-size: 0.8rem;">Online <i class="bi bi-circle-fill text-success" style="font-size: 8px;"></i></span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right pt-0" style="margin-top: 15px; border-radius: 8px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.3);">
                <div class="dropdown-header bg-primary text-white py-2"><strong>Account</strong></div>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="mfe-2 bi bi-person" style="font-size: 1.1rem;"></i> Profile
                </a>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="mfe-2 bi bi-box-arrow-left" style="font-size: 1.1rem;"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</div>

<style>
    .header-container {
        width: 100%;
        padding: 0.5rem 1rem;
        border-bottom: 1px solid #2d2d43;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .c-header-nav-link {
        color: #e0e0e0 !important;
    }
    
    .c-header-nav-link:hover {
        color: #3699ff !important;
    }
    
    .c-header {
        background: transparent !important;
        border: none !important;
    }

    .dropdown-item.active {
        background-color: rgba(54, 153, 255, 0.1);
        color: #3699ff;
    }
</style>
