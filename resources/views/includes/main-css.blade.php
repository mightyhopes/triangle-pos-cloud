<!-- Dropezone CSS -->
<link rel="stylesheet" href="{{ asset('css/dropzone.css') }}">
<!-- CoreUI CSS -->
@vite('resources/sass/app.scss')
<link href="https://cdn.datatables.net/v/bs4/jszip-3.10.1/dt-1.13.5/b-2.4.1/b-html5-2.4.1/b-print-2.4.1/sl-1.7.0/datatables.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
<!-- Flag Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.6.6/css/flag-icons.min.css">

@yield('third_party_stylesheets')

@stack('page_css')

@livewireStyles

<style>
    div.dataTables_wrapper div.dataTables_length select {
        width: 65px;
        display: inline-block;
    }
    .select2-container--default .select2-selection--single {
        background-color: #1a1a27;
        border: 1px solid #2d2d43;
        border-radius: 4px;
        color: #e0e0e0;
    }
    .select2-container--default .select2-selection--multiple {
        background-color: #1a1a27;
        border: 1px solid #2d2d43;
        border-radius: 4px;
        color: #e0e0e0;
    }
    .select2-container .select2-selection--multiple {
        height: 35px;
    }
    .select2-container .select2-selection--single {
        height: 35px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 33px;
        color: #e0e0e0;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        margin-top: 2px;
    }
    
    /* Additional dark theme styles */
    .select2-dropdown {
        background-color: #1a1a27;
        border: 1px solid #2d2d43;
        color: #e0e0e0;
    }
    
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #3699ff;
        color: white;
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #3699ff;
        color: white;
    }
    
    .select2-search--dropdown .select2-search__field {
        background-color: #1a1a27;
        border: 1px solid #2d2d43;
        color: #e0e0e0;
    }
    
    .modal-content {
        background-color: #27293d;
        border: 1px solid #2d2d43;
        color: #e0e0e0;
    }
    
    .modal-header, .modal-footer {
        border-color: #2d2d43;
    }
    
    .close {
        color: #e0e0e0;
    }
    
    .breadcrumb {
        background-color: #27293d;
        border: 1px solid #2d2d43;
    }
    
    .breadcrumb-item.active {
        color: #3699ff;
    }
    
    .breadcrumb-item a {
        color: #e0e0e0;
    }
    
    .nav-tabs .nav-link.active {
        background-color: #27293d;
        border-color: #2d2d43;
        color: #3699ff;
    }
    
    .nav-tabs {
        border-color: #2d2d43;
    }
    
    .nav-tabs .nav-link:hover {
        border-color: #2d2d43;
    }
    
    /* Add a subtle transition effect for a more modern feel */
    a, .btn, .nav-link, .form-control, select, .modal, .dropdown-item {
        transition: all 0.2s ease-in-out;
    }
    
    /* Add nice shadows to cards for a more modern look */
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
    }
    
    /* Make buttons more modern with rounded corners */
    .btn {
        border-radius: 5px;
    }
    
    /* Custom scrollbar for dark theme */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #1a1a27;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #3699ff;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #0066cc;
    }
</style>
