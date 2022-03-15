<div class="nk-sidebar nk-sidebar-fixed is-dark " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-menu-trigger">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
        </div>
        <div class="nk-sidebar-brand">
            <a href="{{ route('home') }}" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" src="{{ asset('assets/images/laveshlogo.png') }}" alt="logo">
                <img class="logo-dark logo-img" src="{{ asset('assets/images/laveshlogo.png') }}" alt="logo-dark">
            </a>
        </div>
    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element nk-sidebar-body">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Navigation</h6>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="{{ route('home') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-layers"></em></span>
                            <span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="{{ route('logs') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                            <span class="nk-menu-text">Employee Log</span>
                            <!-- <span class="nk-menu-badge">HOT</span> -->
                        </a>
                    </li><!-- .nk-menu-item -->                    
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Management</h6>
                    </li><!-- .nk-menu-heading -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-tile-thumb"></em></span>
                            <span class="nk-menu-text">Databank</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{ route('users-group') }}" class="nk-menu-link"><span class="nk-menu-text">User Group</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('employee') }}" class="nk-menu-link"><span class="nk-menu-text">Employee List</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('product-category') }}" class="nk-menu-link"><span class="nk-menu-text">Product Category</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('productsub-category') }}" class="nk-menu-link"><span class="nk-menu-text">Product Sub Category</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('catalog') }}" class="nk-menu-link"><span class="nk-menu-text">Catalog List</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('companyCategory') }}" class="nk-menu-link"><span class="nk-menu-text">Company Category</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('companies') }}" class="nk-menu-link"><span class="nk-menu-text">Company List</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('link-company') }}" class="nk-menu-link"><span class="nk-menu-text">Link Companies</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="html/project-list.html" class="nk-menu-link"><span class="nk-menu-text">Attendence</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="{{ route('reference') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-view-col"></em></span>
                            <span class="nk-menu-text">Reference Id</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="{{ route('register') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-view-col"></em></span>
                            <span class="nk-menu-text">Register</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                            <span class="nk-menu-text">Account</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="html/user-list-regular.html" class="nk-menu-link"><span class="nk-menu-text">Sale Bill</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="html/user-list-compact.html" class="nk-menu-link"><span class="nk-menu-text">Payment</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="html/user-details-regular.html" class="nk-menu-link"><span class="nk-menu-text">Ledger</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="html/user-profile-regular.html" class="nk-menu-link"><span class="nk-menu-text">Commision</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                            <span class="nk-menu-text">Settings</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="{{ route('bank-details') }}" class="nk-menu-link"><span class="nk-menu-text">Bank Details</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('countries') }}" class="nk-menu-link"><span class="nk-menu-text">Countries List</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('states') }}" class="nk-menu-link"><span class="nk-menu-text">States List</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('cities') }}" class="nk-menu-link"><span class="nk-menu-text">Cities List</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('transport-details') }}" class="nk-menu-link"><span class="nk-menu-text">Transport Details</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('type-of-address') }}" class="nk-menu-link"><span class="nk-menu-text">Types of Address List</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('default-settings') }}" class="nk-menu-link"><span class="nk-menu-text">Default Settings</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('designation') }}" class="nk-menu-link"><span class="nk-menu-text">Designation</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('sms-settings') }}" class="nk-menu-link"><span class="nk-menu-text">SMS Settings</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('agent') }}" class="nk-menu-link"><span class="nk-menu-text">Agent</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('sale-bill-agent') }}" class="nk-menu-link"><span class="nk-menu-text">Sale bill Agent</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('fabricGroup') }}" class="nk-menu-link"><span class="nk-menu-text">Fabric Group</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('companyType') }}" class="nk-menu-link"><span class="nk-menu-text">Company Types</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="{{ route('permission') }}" class="nk-menu-link"><span class="nk-menu-text">Permissions</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="html/pricing-table.html" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-view-col"></em></span>
                            <span class="nk-menu-text">Calendar</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="html/pricing-table.html" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-view-col"></em></span>
                            <span class="nk-menu-text">Reports</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="html/pricing-table.html" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-view-col"></em></span>
                            <span class="nk-menu-text">Enjoy Call Records</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="html/pricing-table.html" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-view-col"></em></span>
                            <span class="nk-menu-text">Recorded Files</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item has-sub">
                        <a href="#" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-tranx"></em></span>
                            <span class="nk-menu-text">Message</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="html/transaction-basic.html" class="nk-menu-link"><span class="nk-menu-text">Message</span></a>
                            </li>
                            <li class="nk-menu-item">
                                <a href="html/transaction-crypto.html" class="nk-menu-link"><span class="nk-menu-text">Send</span></a>
                            </li>
                        </ul><!-- .nk-menu-sub -->
                    </li><!-- .nk-menu-item -->
                    <li class="nk-menu-item">
                        <a href="html/pricing-table.html" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-view-col"></em></span>
                            <span class="nk-menu-text">Auth Config</span>
                        </a>
                    </li><!-- .nk-menu-item -->
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>