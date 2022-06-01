<div class="nk-header nk-header-fixed is-light">
    <div class="container-fluid">
        <div class="nk-header-wrap">
            <div class="nk-menu-trigger d-xl-none ml-n1">
                <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
            </div>
            <div class="nk-header-brand d-xl-none">
                <a href="{{ route('home') }}" class="logo-link">
                    <img class="logo-light logo-img" src="{{ asset('assets/images/laveshlogo.png') }}" alt="logo">
                    <img class="logo-dark logo-img" src="{{ asset('assets/images/laveshlogo.png') }}" alt="logo-dark">
                </a>
            </div><!-- .nk-header-brand -->
            <div class="nk-header-news d-none d-xl-block">
                <div class="nk-news-list">
                    <div class="form-control-wrap">
                        <div class="input-group">
                            <input type="text" class="form-control" id="global_search_input" autocomplete="off" style="width: 20vw;">
                            <div class="input-group-btn">
                                <div class="input-group-append">
                                    <span class="input-group-text dropdown-toggle dropdown-indicator " data-toggle="dropdown"><i class="icon ni ni-search" style="font-size: 20px;"></i></span>
                                    <div class="dropdown-menu dropdown-menu-s1">
                                        <ul class="language-list">
                                            <a href="#">
                                                <li class=""> <span class="language-item"> Company </span> </li>
                                            </a>
                                            <a href="#">
                                                <li class=""> <span class="language-item"> Product </span> </li>
                                            </a>
                                            <a href="#">
                                                <li class=""> <span class="language-item"> Product Sub Category </span> </li>
                                            </a>
                                            <a href="#">
                                                <li class=""> <span class="language-item"> Register </span> </li>
                                            </a>
                                            <a href="#">
                                                <li class=""> <span class="language-item"> Sale Bill </span> </li>
                                            </a>
                                            <a href="#">
                                                <li class=""> <span class="language-item"> Payment </span> </li>
                                            </a>
                                            <a href="#">
                                                <li class=""> <span class="language-item"> Commission </span> </li>
                                            </a>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- /btn-group -->
                        </div>
                    </div>
                </div>
            </div><!-- .nk-header-news -->
            <div class="nk-header-tools">
                <ul class="nk-quick-nav">
                    <li class="dropdown language-dropdown d-none d-sm-block mr-n1">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="border-light">
                                <span class="user-name dropdown-indicator">{{ session()->get('user')->financial_year }}</span>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-s1">
                            <ul class="language-list">
                                @foreach ( session()->get('all_fy') as $data)
                                <a href="/financialyear/update-current-financial-year/{{ $data->id }}">
                                    <li class=""> <span class="language-item fy"> {{ $data->name }} {!! $data->id == session()->get('user')->financial_year_id  ? '<em class="icon ni ni-check-thick ml-auto"></em>' : '' !!}</span> </li>
                                </a>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    <li class="dropdown user-dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <div class="user-toggle">
                                <div class="user-avatar sm">
                                    <em class="icon ni ni-user-alt"></em>
                                </div>
                                <div class="user-info d-none d-md-block">
                                    <!-- <div class="user-status">Administrator</div> -->
                                    <div class="user-name dropdown-indicator">{{ $employees['firstname'] }} {{ $employees['lastname'] }}</div>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right dropdown-menu-s1">
                            <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <span>A</span>
                                    </div>
                                    <div class="user-info">
                                        <span class="lead-text">{{ $employees['firstname'] }} {{ $employees['lastname'] }}</span>
                                        <span class="sub-text">{{ $employees['email_id'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="html/user-profile-regular.html"><em class="icon ni ni-user-alt"></em><span>View Profile</span></a></li>
                                    {{-- <li><a href="html/user-profile-setting.html"><em class="icon ni ni-setting-alt"></em><span>Account Setting</span></a></li>
                                    <li><a href="html/user-profile-activity.html"><em class="icon ni ni-activity-alt"></em><span>Login Activity</span></a></li> --}}
                                    <li><a class="dark-switch" href="#"><em class="icon ni ni-moon"></em><span>Dark Mode</span></a></li>
                                </ul>
                            </div>
                            <div class="dropdown-inner">
                                <ul class="link-list">
                                    <li><a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();"><em class="icon ni ni-signout"></em><span>Sign out</span></a></li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </li><!-- .dropdown -->
                    <li class="dropdown notification-dropdown mr-n1">
                        <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                            <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right dropdown-menu-s1">
                            <div class="dropdown-head">
                                <span class="sub-title nk-dropdown-title">Notifications</span>
                                <a href="#">Mark All as Read</a>
                            </div>
                            <div class="dropdown-body">
                                <div class="nk-notification">
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">You have requested to <span>Widthdrawl</span></div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                    <div class="nk-notification-item dropdown-inner">
                                        <div class="nk-notification-icon">
                                            <em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em>
                                        </div>
                                        <div class="nk-notification-content">
                                            <div class="nk-notification-text">Your <span>Deposit Order</span> is placed</div>
                                            <div class="nk-notification-time">2 hrs ago</div>
                                        </div>
                                    </div>
                                </div><!-- .nk-notification -->
                            </div><!-- .nk-dropdown-body -->
                            <div class="dropdown-foot center">
                                <a href="#">View All</a>
                            </div>
                        </div>
                    </li><!-- .dropdown -->
                </ul><!-- .nk-quick-nav -->
            </div><!-- .nk-header-tools -->
        </div><!-- .nk-header-wrap -->
    </div><!-- .container-fliud -->
</div>
