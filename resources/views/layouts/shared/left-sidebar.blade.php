<!-- ========== Left Sidebar Start ========== -->
@php
    $config = \App\Models\Config::first();
@endphp
<div class="leftside-menu">

    <!-- Brand Logo Light -->
    <a href="{{ route('any', 'index') }}" class="logo logo-light nav-link">
        <span class="logo-lg">
            @if($config->getFirstMediaUrl('logos'))
                <span style="font-weight: bold;color: #FFFFFF;font-size: 16px">{{ $config->name ?? "SKE APP"}}</span>
                @if($config->getFirstMediaUrl('logos'))
                    <img src="{{ $config->getFirstMediaUrl('logos') }}" alt="logo" style="border-radius: 50%">
                @else
                    <img src="{{ asset('assets/img/logo.png')}}" alt="logo">
                @endif
            @else
                <span style="font-weight: bold;color: #FFFFFF;font-size: 16px">{{ $config->name ?? "SKE APP"}}</span>
                <img src="{{ asset('assets/img/logo.png')}}" alt="logo">
            @endif
        </span>
        <span class="logo-sm">
            @if($config->getFirstMediaUrl('logos'))
                <span style="font-weight: bold;color: #FFFFFF;font-size: 16px">{{ $config->name ?? "SKE APP"}}</span>
                <img src="{{ $config->getFirstMediaUrl('logos') }}" alt="logo">
            @else
                <span style="font-weight: bold;color: #FFFFFF;font-size: 16px">{{ $config->name ?? "SKE APP"}}</span>
                <img src="{{ asset('assets/img/logo.png')}}" alt="logo">
            @endif
        </span>
    </a>

    <!-- Brand Logo Dark -->
    <a href="{{ route('any', 'index') }}" class="logo logo-dark nav-link">
         <span class="logo-lg">
            @if($config->getFirstMediaUrl('logos'))
                 <span style="font-weight: bold;color: #FFFFFF;font-size: 16px">{{ $config->name ?? "SKE APP"}}</span>
                 <img src="{{ $config->getFirstMediaUrl('logos') }}" alt="logo">
             @else
                 <span style="font-weight: bold;color: #FFFFFF;font-size: 16px">{{ $config->name ?? "SKE APP"}}</span>
                 <img src="{{ asset('assets/img/logo.png')}}" alt="logo">
             @endif
        </span>
        <span class="logo-sm">
            @if($config->getFirstMediaUrl('logos'))
                <span style="font-weight: bold;color: #FFFFFF;font-size: 16px">{{ $config->name ?? "SKE APP"}}</span>
                <img src="{{ $config->getFirstMediaUrl('logos') }}" alt="logo">
            @else
                <span style="font-weight: bold;color: #FFFFFF;font-size: 16px">{{ $config->name ?? "SKE APP"}}</span>
                <img src="{{ asset('assets/img/logo.png')}}" alt="logo">
            @endif
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
        <i class="ri-checkbox-blank-circle-line align-middle"></i>
    </div>

    <!-- Full Sidebar Menu Close Button -->
    <div class="button-close-fullsidebar">
        <i class="ri-close-fill align-middle"></i>
    </div>

    <!-- Sidebar -left -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <!-- Leftbar User -->
        <div class="leftbar-user">
            <a href="{{ route('second', ['pages', 'profile']) }}" class="nav-link">
                @if($config->getFirstMediaUrl('logos'))
                    <img src="{{ $config->getFirstMediaUrl('logos') }}" alt="user-image" height="42" class="rounded-circle shadow-sm">
                @else
                    <img src="{{ asset('assets/img/logo.png')}}" alt="user-image" height="42" class="rounded-circle shadow-sm">
                @endif
                <span class="leftbar-user-name mt-2">{{ $config->name ?? "SKE APP"}}</span>
            </a>
        </div>

        <!--- Sidemenu -->
        <ul class="side-nav">

            <li class="side-nav-title">Admin</li>


            <li class="side-nav-item">
                <a href="{{ route('dashboard') }}" class="side-nav-link">
                    <i class="ri-home-4-line"></i>
                    <span> Dashboards </span>
                </a>
            </li>

{{--            <li class="side-nav-title">Apps</li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a href="{{ route('second', ['apps', 'calendar']) }}" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-calendar-event-line"></i>--}}
{{--                    <span> Calendar </span>--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a href="{{ route('second', ['apps', 'chat']) }}" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-message-3-line"></i>--}}
{{--                    <span> Chat </span>--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarEmail" aria-expanded="false" aria-controls="sidebarEmail" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-mail-line"></i>--}}
{{--                    <span> Email </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarEmail">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['email', 'inbox']) }}" class="nav-link">Inbox</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['email', 'read']) }}" class="nav-link">Read Email</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarTasks" aria-expanded="false" aria-controls="sidebarTasks" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-task-line"></i>--}}
{{--                    <span> Tasks </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarTasks">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['task', 'list']) }}" class="nav-link">List</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['task', 'details']) }}" class="nav-link">Details</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a href="{{ route('second', ['apps', 'kanban']) }}" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-list-check-3"></i>--}}
{{--                    <span> Kanban Board </span>--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a href="{{ route('second', ['apps', 'file-manager']) }}" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-folder-2-line"></i>--}}
{{--                    <span> File Manager </span>--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <li class="side-nav-title">Custom</li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarPages" aria-expanded="false" aria-controls="sidebarPages" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-pages-line"></i>--}}
{{--                    <span> Pages </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarPages">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['pages', 'profile']) }}" class="nav-link">Profile</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['pages', 'invoice']) }}" class="nav-link">Invoice</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['pages', 'faq']) }}" class="nav-link">FAQ</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['pages', 'pricing']) }}" class="nav-link">Pricing</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['pages', 'maintenance']) }}" class="nav-link">Maintenance</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['pages', 'starter']) }}" class="nav-link">Starter Page</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['pages', 'preloader']) }}" class="nav-link">With Preloader</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['pages', 'timeline']) }}" class="nav-link">Timeline</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarPagesAuth" aria-expanded="false" aria-controls="sidebarPagesAuth" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-shield-user-line"></i>--}}
{{--                    <span> Auth Pages </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarPagesAuth">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['auth', 'login']) }}" class="nav-link">Login</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['auth', 'login-2']) }}" class="nav-link">Login 2</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['auth', 'register']) }}" class="nav-link">Register</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['auth', 'register-2']) }}" class="nav-link">Register 2</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['auth', 'logout']) }}" class="nav-link">Logout</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['auth', 'logout-2']) }}" class="nav-link">Logout 2</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['auth', 'recoverpw']) }}" class="nav-link">Recover Password</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['auth', 'recoverpw-2']) }}" class="nav-link">Recover Password 2</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['auth', 'lock-screen']) }}" class="nav-link">Lock Screen</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['auth', 'lock-screen-2']) }}" class="nav-link">Lock Screen 2</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['auth', 'confirm-mail']) }}" class="nav-link">Confirm Mail</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['auth', 'confirm-mail-2']) }}" class="nav-link">Confirm Mail 2</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarPagesError" aria-expanded="false" aria-controls="sidebarPagesError" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-error-warning-line"></i>--}}
{{--                    <span> Error Pages </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarPagesError">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['error', '404']) }}" class="nav-link">Error 404</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['error', '404-alt']) }}" class="nav-link">Error 404-alt</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['error', '500']) }}" class="nav-link">Error 500</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarLayouts" aria-expanded="false" aria-controls="sidebarLayouts" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-layout-line"></i>--}}
{{--                    <span> Layouts </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarLayouts">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li class="menu-item">--}}
{{--                            <a class="menu-link nav-link" target="_blank" href="{{ route('second', ['layouts-eg', 'horizontal']) }}"><span class="menu-text">Horizontal</span></a>--}}
{{--                        </li>--}}
{{--                        <li class="menu-item">--}}
{{--                            <a class="menu-link nav-link" target="_blank" href="{{ route('second', ['layouts-eg', 'detached']) }}"><span class="menu-text">Detached</span></a>--}}
{{--                        </li>--}}
{{--                        <li class="menu-item">--}}
{{--                            <a class="menu-link nav-link" target="_blank" href="{{ route('second', ['layouts-eg', 'full-view']) }}"><span class="menu-text">Full View</span></a>--}}
{{--                        </li>--}}
{{--                        <li class="menu-item">--}}
{{--                            <a class="menu-link nav-link" target="_blank" href="{{ route('second', ['layouts-eg', 'fullscreen-view']) }}"><span class="menu-text">Fullscreen View</span></a>--}}
{{--                        </li>--}}
{{--                        <li class="menu-item">--}}
{{--                            <a class="menu-link nav-link" target="_blank" href="{{ route('second', ['layouts-eg', 'hover-menu']) }}"><span class="menu-text">Hover Menu</span></a>--}}
{{--                        </li>--}}
{{--                        <li class="menu-item">--}}
{{--                            <a class="menu-link nav-link" target="_blank" href="{{ route('second', ['layouts-eg', 'compact']) }}"><span class="menu-text">Compact</span></a>--}}
{{--                        </li>--}}
{{--                        <li class="menu-item">--}}
{{--                            <a class="menu-link nav-link" target="_blank" href="{{ route('second', ['layouts-eg', 'icon-view']) }}"><span class="menu-text">Icon View</span></a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarBaseUI" aria-expanded="false" aria-controls="sidebarBaseUI" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-briefcase-line"></i>--}}
{{--                    <span> Base UI </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarBaseUI">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'accordions']) }}" class="nav-link">Accordions</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'alerts']) }}" class="nav-link">Alerts</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'avatars']) }}" class="nav-link">Avatars</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'badges']) }}" class="nav-link">Badges</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'breadcrumb']) }}" class="nav-link">Breadcrumb</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'buttons']) }}" class="nav-link">Buttons</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'cards']) }}" class="nav-link">Cards</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'carousel']) }}" class="nav-link">Carousel</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'collapse']) }}" class="nav-link">Collapse</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'dropdowns']) }}" class="nav-link">Dropdowns</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'embed-video']) }}" class="nav-link">Embed Video</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'grid']) }}" class="nav-link">Grid</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'links']) }}" class="nav-link">Links</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'list-group']) }}" class="nav-link">List Group</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'modals']) }}" class="nav-link">Modals</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'notifications']) }}" class="nav-link">Notifications</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'offcanvas']) }}" class="nav-link">Offcanvas</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'placeholders']) }}" class="nav-link">Placeholders</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'pagination']) }}" class="nav-link">Pagination</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'popovers']) }}" class="nav-link">Popovers</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'progress']) }}" class="nav-link">Progress</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'spinners']) }}" class="nav-link">Spinners</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'tabs']) }}" class="nav-link">Tabs</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'tooltips']) }}" class="nav-link">Tooltips</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'typography']) }}" class="nav-link">Typography</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['ui', 'utilities']) }}" class="nav-link">Utilities</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarExtendedUI" aria-expanded="false" aria-controls="sidebarExtendedUI" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-stack-line"></i>--}}
{{--                    <span> Extended UI </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarExtendedUI">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['extended', 'dragula']) }}" class="nav-link">Dragula</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['extended', 'range-slider']) }}" class="nav-link">Range Slider</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['extended', 'ratings']) }}" class="nav-link">Ratings</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['extended', 'scrollbar']) }}" class="nav-link">Scrollbar</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['extended', 'scrollspy']) }}" class="nav-link">Scrollspy</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a href="{{ route('any', 'widgets') }}" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-pencil-ruler-2-line"></i>--}}
{{--                    <span> Widgets </span>--}}
{{--                </a>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarDiamondRates" aria-expanded="false" aria-controls="sidebarDiamondRates" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-money-dollar-circle-line"></i>--}}
{{--                    <span> Diamond Rates </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarDiamondRates">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('diamond_rates.index') }}" class="nav-link">View All</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('diamond_rates.create') }}" class="nav-link">Create New</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}


            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarUsers" aria-expanded="false" aria-controls="sidebarUsers" class="side-nav-link nav-link">
                    <i class="ri-user-line"></i>
                    <span> Users </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarUsers">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('users.index') }}" class="nav-link">All Users</a>
                        </li>
                        <li>
                            <a href="{{ route('users.create') }}" class="nav-link">Create User</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('orders.index') }}" class="side-nav-link">
                    <i class="ri-shopping-cart-line"></i>
                    <span> Orders </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('purchase-requests.index') }}" class="side-nav-link">
                    <i class="ri-file-list-line"></i>
                    <span> Purchase Requests </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a href="{{ route('user-wallets.index') }}" class="side-nav-link">
                    <i class="ri-wallet-line"></i>
                    <span> User Wallets </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarItems" aria-expanded="false" aria-controls="sidebarItems" class="side-nav-link nav-link">
                    <i class=" ri-app-store-line"></i>
                    <span> Items </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarItems">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('items.index') }}" class="nav-link">All Items</a>
                        </li>
                        <li>
                            <a href="{{ route('items.create') }}" class="nav-link">Create Item</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPosts" aria-expanded="false" aria-controls="sidebarItems" class="side-nav-link nav-link">
                    <i class="ri-file-list-line"></i>
                    <span> Posts </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPosts">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('posts.index') }}" class="nav-link">All Posts</a>
                        </li>
                        <li>
                            <a href="{{ route('posts.create') }}" class="nav-link">Create Post</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPosts" aria-expanded="false" aria-controls="sidebarItems" class="side-nav-link nav-link">
                    <i class="ri-notification-3-fill"></i>
                    <span> Notifications </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPosts">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('notifications.index') }}" class="nav-link">All Notifications</a>
                        </li>
                        <li>
                            <a href="{{ route('notifications.create') }}" class="nav-link">Create Notification</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('permissions.index') }}" class="side-nav-link">
                    <i class="ri-admin-line"></i>
                    <span> Permissions </span>
                </a>
            </li>


{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarIcons" aria-expanded="false" aria-controls="sidebarIcons" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-service-line"></i>--}}
{{--                    <span> Icons </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarIcons">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['icons', 'remixicons']) }}" class="nav-link">Remix Icons</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['icons', 'bootstrapicons']) }}" class="nav-link">Bootstrap Icons</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}


            <li class="side-nav-item">
                <a href="{{ route('fee_groups.index') }}" class="side-nav-link">
                    <i class="ri-percent-line"></i>
                    <span> Fee Groups </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCurrencies" aria-expanded="false" aria-controls="sidebarItems" class="side-nav-link nav-link">
                    <i class="ri-exchange-dollar-line"></i>
                    <span> Currencies </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCurrencies">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('currencies.index') }}" class="nav-link">All Currencies</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarClients" aria-expanded="false" aria-controls="sidebarClients" class="side-nav-link nav-link">
                    <i class="ri-account-circle-line"></i>
                    <span> Clients </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarClients">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('clients.index') }}" class="nav-link">All Clients</a>
                        </li>
                        <li>
                            <a href="{{ route('clients.create') }}" class="nav-link">Create Client</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarAccounts" aria-expanded="false" aria-controls="sidebarAccounts" class="side-nav-link nav-link">
                    <i class=" ri-calculator-line"></i>
                    <span> Accounts </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarAccounts">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('accounts.index') }}" class="nav-link">All ( Calc ) Accounts</a>
                        </li>
                        <li>
                            <a href="{{ route('accounts.create') }}" class="nav-link">Create Transaction</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarAPIS" aria-expanded="false" aria-controls="sidebarAPIS" class="side-nav-link nav-link">
                    <i class="ri-code-s-slash-line"></i>
                    <span> APIS </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarAPIS">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('api-items.edit') }}" class="nav-link">APIs Module</a>
                        </li>
                        <li>
                            <a href="{{ route('clientStores.index') }}" class="nav-link">Business Clients</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-title">Design</li>


            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPaymentMethods" aria-expanded="false" aria-controls="sidebarPaymentMethods" class="side-nav-link nav-link">
                    <i class="ri-money-dollar-box-line"></i>
                    <span> Payment Methods </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPaymentMethods">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('payment-methods.index') }}" class="nav-link">All Payment Methods</a>
                        </li>
                        <li>
                            <a href="{{ route('payment-methods.create') }}" class="nav-link">Create Payment Method</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarTags" aria-expanded="false" aria-controls="sidebarTags" class="side-nav-link nav-link">
                    <i class="ri-price-tag-3-line"></i>
                    <span> Tags </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarTags">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('tags.index') }}" class="nav-link">All Tags</a>
                        </li>
                        <li>
                            <a href="{{ route('tags.create') }}" class="nav-link">Create Tag</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('sliders.index') }}" class="side-nav-link nav-link">
                    <i class="ri-slideshow-line"></i>
                    <span> Sliders </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('news.edit') }}" class="side-nav-link nav-link">
                    <i class="ri-survey-line"></i>
                    <span>M text</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('terms.edit') }}" class="side-nav-link">
                    <i class="ri-question-line"></i>
                    <span> Terms & Conditions </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarCategories" aria-expanded="false" aria-controls="sidebarCategories" class="side-nav-link nav-link">
                    <i class="ri-folder-line"></i>
                    <span> Categories </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarCategories">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('categories.index') }}" class="nav-link">All Categories</a>
                        </li>
                        <li>
                            <a href="{{ route('categories.create') }}" class="nav-link">Create Category</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPosts" aria-expanded="false" aria-controls="sidebarItems" class="side-nav-link nav-link">
                    <i class="ri-add-circle-line"></i>
                    <span> Agents </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPosts">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('partners.index') }}" class="nav-link">All Agents</a>
                        </li>
                        <li>
                            <a href="{{ route('partners.create') }}" class="nav-link">Create Agent</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('configs.edit') }}" class="side-nav-link">
                    <i class="ri-settings-3-line"></i>
                    <span> Configuration </span>
                </a>
            </li>

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarCharts" aria-expanded="false" aria-controls="sidebarCharts" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-bar-chart-line"></i>--}}
{{--                    <span> Charts </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarCharts">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li class="side-nav-item">--}}
{{--                            <a data-bs-toggle="collapse" href="#sidebarApexCharts" aria-expanded="false" aria-controls="sidebarApexCharts" class="nav-link">--}}
{{--                                <span> Apex Charts </span>--}}
{{--                                <span class="menu-arrow"></span>--}}
{{--                            </a>--}}
{{--                            <div class="collapse" id="sidebarApexCharts">--}}
{{--                                <ul class="side-nav-third-level">--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-area']) }}" class="nav-link">Area</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-bar']) }}" class="nav-link">Bar</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-bubble']) }}" class="nav-link">Bubble</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-candlestick']) }}" class="nav-link">Candlestick</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-column']) }}" class="nav-link">Column</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-heatmap']) }}" class="nav-link">Heatmap</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-line']) }}" class="nav-link">Line</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-mixed']) }}" class="nav-link">Mixed</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-timeline']) }}" class="nav-link">Timeline</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-boxplot']) }}" class="nav-link">Boxplot</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-treemap']) }}" class="nav-link">Treemap</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-pie']) }}" class="nav-link">Pie</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-radar']) }}" class="nav-link">Radar</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-radialbar']) }}" class="nav-link">RadialBar</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-scatter']) }}" class="nav-link">Scatter</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-polar-area']) }}" class="nav-link">Polar Area</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'apex-sparklines']) }}" class="nav-link">Sparklines</a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="side-nav-item">--}}
{{--                            <a data-bs-toggle="collapse" href="#sidebarChartJSCharts" aria-expanded="false" aria-controls="sidebarChartJSCharts" class="nav-link">--}}
{{--                                <span> ChartJS </span>--}}
{{--                                <span class="menu-arrow"></span>--}}
{{--                            </a>--}}
{{--                            <div class="collapse" id="sidebarChartJSCharts">--}}
{{--                                <ul class="side-nav-third-level">--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'chartjs-area']) }}" class="nav-link">Area</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'chartjs-bar']) }}" class="nav-link">Bar</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'chartjs-line']) }}" class="nav-link">Line</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="{{ route('second', ['charts', 'chartjs-other']) }}" class="nav-link">Other</a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarForms" aria-expanded="false" aria-controls="sidebarForms" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-survey-line"></i>--}}
{{--                    <span> Forms </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarForms">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['forms', 'elements']) }}" class="nav-link">Basic Elements</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['forms', 'advanced']) }}" class="nav-link">Form Advanced</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['forms', 'validation']) }}" class="nav-link">Validation</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['forms', 'wizard']) }}" class="nav-link">Wizard</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['forms', 'fileuploads']) }}" class="nav-link">File Uploads</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['forms', 'editors']) }}" class="nav-link">Editors</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarTables" aria-expanded="false" aria-controls="sidebarTables" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-table-line"></i>--}}
{{--                    <span> Tables </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarTables">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['tables', 'basic']) }}" class="nav-link">Basic Tables</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['tables', 'datatable']) }}" class="nav-link">Data Tables</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarMaps" aria-expanded="false" aria-controls="sidebarMaps" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-treasure-map-line"></i>--}}
{{--                    <span> Maps </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarMaps">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['maps', 'google']) }}" class="nav-link">Google Maps</a>--}}
{{--                        </li>--}}
{{--                        <li>--}}
{{--                            <a href="{{ route('second', ['maps', 'vector']) }}" class="nav-link">Vector Maps</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}

{{--            <li class="side-nav-item">--}}
{{--                <a data-bs-toggle="collapse" href="#sidebarMultiLevel" aria-expanded="false" aria-controls="sidebarMultiLevel" class="side-nav-link nav-link">--}}
{{--                    <i class="ri-share-line"></i>--}}
{{--                    <span> Multi Level </span>--}}
{{--                    <span class="menu-arrow"></span>--}}
{{--                </a>--}}
{{--                <div class="collapse" id="sidebarMultiLevel">--}}
{{--                    <ul class="side-nav-second-level">--}}
{{--                        <li class="side-nav-item">--}}
{{--                            <a data-bs-toggle="collapse" href="#sidebarSecondLevel" aria-expanded="false" aria-controls="sidebarSecondLevel" class="nav-link">--}}
{{--                                <span> Second Level </span>--}}
{{--                                <span class="menu-arrow"></span>--}}
{{--                            </a>--}}
{{--                            <div class="collapse" id="sidebarSecondLevel">--}}
{{--                                <ul class="side-nav-third-level">--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript: void(0);" class="nav-link">Item 1</a>--}}
{{--                                    </li>--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript: void(0);" class="nav-link">Item 2</a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                        <li class="side-nav-item">--}}
{{--                            <a data-bs-toggle="collapse" href="#sidebarThirdLevel" aria-expanded="false" aria-controls="sidebarThirdLevel" class="nav-link">--}}
{{--                                <span> Third Level </span>--}}
{{--                                <span class="menu-arrow"></span>--}}
{{--                            </a>--}}
{{--                            <div class="collapse" id="sidebarThirdLevel">--}}
{{--                                <ul class="side-nav-third-level">--}}
{{--                                    <li>--}}
{{--                                        <a href="javascript: void(0);" class="nav-link">Item 1</a>--}}
{{--                                    </li>--}}
{{--                                    <li class="side-nav-item">--}}
{{--                                        <a data-bs-toggle="collapse" href="#sidebarFourthLevel" aria-expanded="false" aria-controls="sidebarFourthLevel" class="nav-link">--}}
{{--                                            <span> Item 2 </span>--}}
{{--                                            <span class="menu-arrow"></span>--}}
{{--                                        </a>--}}
{{--                                        <div class="collapse" id="sidebarFourthLevel">--}}
{{--                                            <ul class="side-nav-forth-level">--}}
{{--                                                <li>--}}
{{--                                                    <a href="javascript: void(0);" class="nav-link">Item 2.1</a>--}}
{{--                                                </li>--}}
{{--                                                <li>--}}
{{--                                                    <a href="javascript: void(0);" class="nav-link">Item 2.2</a>--}}
{{--                                                </li>--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}

            <li class="side-nav-title">Business abdel</li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarPlans" aria-expanded="false" aria-controls="sidebarPlans" class="side-nav-link nav-link">
                    <i class="ri-calendar-check-line"></i>
                    <span> Plans </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarPlans">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('plans.index') }}" class="nav-link">All Plans</a>
                        </li>
                        <li>
                            <a href="{{ route('plans.create') }}" class="nav-link">Create Plan</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarSubscriptions" aria-expanded="false" aria-controls="sidebarSubscriptions" class="side-nav-link nav-link">
                    <i class="ri-coin-line"></i>
                    <span> Subscriptions </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarSubscriptions">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('subscriptions.index') }}" class="nav-link">All Subscriptions</a>
                        </li>
                        <li>
                            <a href="{{ route('subscriptions.create') }}" class="nav-link">Create Subscription</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('business-purchase-requests.index') }}" class="side-nav-link">
                    <i class="ri-file-list-line"></i>
                    <span> B.Purchase Requests </span>
                </a>
            </li>

            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarBusinessClientWallets" aria-expanded="false" aria-controls="sidebarBusinessClientWallets" class="side-nav-link nav-link">
                    <i class="ri-wallet-3-line"></i>
                    <span> Business Client Wallets </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarBusinessClientWallets">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('business-client-wallets.index') }}" class="nav-link">View All</a>
                        </li>
                        <li>
                            <a href="{{ route('business-client-wallets.create') }}" class="nav-link">Create New</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>
<!-- ========== Left Sidebar End ========== -->
