<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">tavivu.net</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="javascript:;">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('admin.dashboard') }}</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        {{ __('admin.managements') }}
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTours" aria-expanded="true"
            aria-controls="collapseTours">
            <i class="fas fa-fw fa-cog"></i>
            <span>{{ __('admin.label.tours.tours') }}</span>
        </a>
        <div id="collapseTours" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                {{-- <h6 class="collapse-header">Custom Components:</h6> --}}
                <a class="collapse-item"
                    href="{{route('admin.tours.index')}}">{{ __('admin.label.tours.tours_list') }}</a>
                <a class="collapse-item"
                    href="{{route('admin.tours.create')}}">{{ __('admin.label.tours.create_tour') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true"
            aria-controls="collapsePages">
            <i class="fas fa-fw fa-cog"></i>
            <span>{{ __('admin.label.pages.pages') }}</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item"
                    href="{{route('admin.pages.index')}}">{{ __('admin.label.pages.pages_list') }}</a>
                <a class="collapse-item"
                    href="{{route('admin.pages.create')}}">{{ __('admin.label.pages.create_page') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePlaces" aria-expanded="true"
            aria-controls="collapsePlaces">
            <i class="fas fa-fw fa-cog"></i>
            <span>{{ __('admin.label.places.places') }}</span>
        </a>
        <div id="collapsePlaces" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item"
                    href="{{route('admin.places.index')}}">{{ __('admin.label.places.places_list') }}</a>
                <a class="collapse-item"
                    href="{{route('admin.places.create')}}">{{ __('admin.label.places.create_page') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseContacts" aria-expanded="true"
            aria-controls="collapseContacts">
            <i class="fas fa-fw fa-cog"></i>
            <span>{{ __('admin.label.contacts.contacts') }}</span>
        </a>
        <div id="collapseContacts" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item"
                    href="{{route('admin.contacts.index')}}">{{ __('admin.label.contacts.contacts_list') }}</a>
                <a class="collapse-item"
                    href="{{route('admin.contacts.create')}}">{{ __('admin.label.contacts.create_contact') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseServices" aria-expanded="true"
            aria-controls="collapseServices">
            <i class="fas fa-fw fa-cog"></i>
            <span>{{ __('admin.label.services.services') }}</span>
        </a>
        <div id="collapseServices" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item"
                    href="{{route('admin.services.index')}}">{{ __('admin.label.services.services_list') }}</a>
                <a class="collapse-item"
                    href="{{route('admin.services.create')}}">{{ __('admin.label.services.create_service') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTourOrders" aria-expanded="true"
            aria-controls="collapseServices">
            <i class="fas fa-fw fa-cog"></i>
            <span>{{ __('admin.label.tour_orders.tour_orders') }}</span>
        </a>
        <div id="collapseTourOrders" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item"
                    href="{{route('admin.tour_orders.index')}}">{{ __('admin.label.tour_orders.tour_orders_list') }}</a>
                <a class="collapse-item"
                    href="{{route('admin.tour_orders.create')}}">{{ __('admin.label.tour_orders.create_tour_order') }}</a>
            </div>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseImages" aria-expanded="true"
            aria-controls="collapseServices">
            <i class="fas fa-fw fa-cog"></i>
            <span>{{ __('admin.label.images.images') }}</span>
        </a>
        <div id="collapseImages" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item"
                    href="{{route('admin.images.index')}}">{{ __('admin.label.images.images_list') }}</a>
                <a class="collapse-item"
                    href="{{route('admin.images.create')}}">{{ __('admin.label.images.create_image') }}</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        {{ __('admin.general_settings') }}
    </div>

    <!-- Nav Item - Settings -->
    <li class="nav-item">
        <a class="nav-link" href="javascript:;">
            <i class="fas fa-fw fa-cogs"></i>
            <span>{{ __('admin.settings') }}</span></a>
    </li>

    <li class="nav-item">
    <a class="nav-link" href="{{route('admin.clear-cache')}}">
            <i class="fas fa-fw fa-cogs"></i>
            <span>{{ __('admin.clear_cache') }}</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
