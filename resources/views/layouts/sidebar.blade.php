<div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show {{ request()->routeIs('app.pos.*') ? 'c-sidebar-minimized' : '' }}" id="sidebar" style="background: linear-gradient(180deg, #1e1e2d 0%, #181824 100%); box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);">
    <div class="c-sidebar-brand d-md-down-none" style="background: rgba(0, 0, 0, 0.2); border-bottom: 1px solid rgba(255, 255, 255, 0.05);">
        <a href="{{ route('home') }}">
            <span class="c-sidebar-brand-full" style="font-size: 1.5rem; font-weight: bold; color: white; text-shadow: 0 0 10px rgba(54, 153, 255, 0.5);">Gudangku</span>
            <span class="c-sidebar-brand-minimized" style="font-size: 1.2rem; font-weight: bold; color: white; text-shadow: 0 0 10px rgba(54, 153, 255, 0.5);">G</span>
        </a>
    </div>
    <ul class="c-sidebar-nav" style="margin-top: 10px;">
        @include('layouts.menu')
    </ul>
    <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
</div>

<style>
    .c-sidebar-nav-link, .c-sidebar-nav-dropdown-toggle {
        position: relative;
        padding-left: 15px !important;
        margin: 0 10px;
        border-radius: 5px !important;
        transition: all 0.2s ease;
    }
    
    .c-sidebar-nav-link:hover, .c-sidebar-nav-dropdown-toggle:hover {
        background: rgba(54, 153, 255, 0.15) !important;
        color: white !important;
    }
    
    .c-sidebar-nav-link.c-active, .c-active.c-sidebar-nav-dropdown-toggle {
        background: linear-gradient(118deg, #3699ff, #5a61f4) !important;
        box-shadow: 0 0 10px 1px rgba(54, 153, 255, 0.7);
    }
    
    .c-sidebar-minimized .c-sidebar-nav-link, .c-sidebar-minimized .c-sidebar-nav-dropdown-toggle {
        margin: 0;
        border-radius: 0 !important;
    }

    /* Perbaikan untuk submenu dropdown */
    .c-sidebar-nav-dropdown-items {
        padding-left: 0 !important;
        margin-left: 0 !important;
    }
    
    .c-sidebar-nav-dropdown-items .c-sidebar-nav-item {
        padding-left: 0 !important;
    }
    
    .c-sidebar-nav-dropdown-items .c-sidebar-nav-link {
        padding-left: 30px !important;
        margin-left: 5px;
        margin-right: 5px;
        font-size: 0.9rem;
    }
    
    /* Menambahkan background untuk submenu */
    .c-sidebar-nav-dropdown.c-show {
        background: rgba(54, 153, 255, 0.05);
        border-radius: 5px;
        margin: 0 5px;
    }
    
    /* Perbaikan saat sidebar minimized */
    .c-sidebar-minimized .c-sidebar-nav-dropdown-items {
        margin-left: 0 !important;
        padding-left: 0 !important;
    }
    
    .c-sidebar-minimized .c-sidebar-nav-dropdown-items .c-sidebar-nav-link {
        padding-left: 0 !important;
        margin-left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    /* Menambahkan indikator navigasi dengan tanda > yang berubah menjadi v */
    .c-sidebar-nav-dropdown-toggle::after {
        content: '>';
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-family: monospace;
        font-size: 16px;
        font-weight: bold;
        transition: transform 0.3s;
    }
    
    .c-sidebar-nav-dropdown.c-show > .c-sidebar-nav-dropdown-toggle::after {
        transform: translateY(-50%) rotate(90deg);
    }
    
    /* Menambahkan garis pemisah tipis antara submenu */
    .c-sidebar-nav-dropdown-items .c-sidebar-nav-item:not(:last-child) {
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }
    
    /* Perbaikan indentasi submenu */
    .c-sidebar-nav-dropdown-items {
        margin-top: 5px;
        margin-bottom: 5px;
    }
    
    /* Memperbaiki spasi submenu */
    .c-sidebar-nav-dropdown-items .c-sidebar-nav-link {
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        position: relative;
    }
    
    /* Memperbaiki padding parent menu */
    .c-sidebar-nav-dropdown-toggle {
        padding-top: 9px !important;
        padding-bottom: 9px !important;
    }
</style>
