<?php
// INTIMATION
$manage_intimation =    Route::currentRouteName() == 'intimations.index' ||
                        Route::currentRouteName() == 'intimations.show' ||
                        Route::currentRouteName() == 'intimations.payment' ||
                        Route::currentRouteName() == 'intimations.create-step-1' ||
                        Route::currentRouteName() == 'intimations.create-step-2' ||
                        Route::currentRouteName() == 'intimations.create-step-3' ||
                        Route::currentRouteName() == 'intimations.create-step-4' ||
                        Route::currentRouteName() == 'intimations.create-step-5' ||
                        Route::currentRouteName() == 'intimations.create-step-6' ||
                        Route::currentRouteName() == 'intimations.create-step-7';

$manage_final_intimation =  Route::currentRouteName() == 'intimations.index' && Route::current()->parameters() ['slug'] == 'final';
$manage_partial_intimation =  Route::currentRouteName() == 'intimations.index' && Route::current()->parameters() ['slug'] == 'partial';
$manage_list_intimation =  Route::currentRouteName() == 'intimations.index' && Route::current()->parameters() ['slug'] == 'list';

// LOWER COURT
$manage_lower_court =   Route::currentRouteName() == 'lower-court.index' ||
                        Route::currentRouteName() == 'lower-court.show' ||
                        Route::currentRouteName() == 'lower-court.create-step-1' ||
                        Route::currentRouteName() == 'lower-court.create-step-2' ||
                        Route::currentRouteName() == 'lower-court.create-step-3' ||
                        Route::currentRouteName() == 'lower-court.create-step-4' ||
                        Route::currentRouteName() == 'lower-court.create-step-5' ||
                        Route::currentRouteName() == 'lower-court.create-step-6' ||
                        Route::currentRouteName() == 'lower-court.create-step-7' ||
                        Route::currentRouteName() == 'gc-lower-court.index'||
                        Route::currentRouteName() == 'gc-lower-court.edit'||
                        Route::currentRouteName() == 'gc-lower-court.show' ||
                        Route::currentRouteName() == 'lower-court.indexv2';

$manage_lower_court_final = Route::currentRouteName() == 'lower-court.index' && Route::current()->parameters() ['slug'] == 'final';
$manage_lower_court_partial = Route::currentRouteName() == 'lower-court.index' && Route::current()->parameters() ['slug'] == 'partial';
$manage_lower_court_move_from_intimation = Route::currentRouteName() == 'lower-court.index' && Route::current()->parameters() ['slug'] == 'move-from-intimation';
$manage_lower_court_direct_entry = Route::currentRouteName() == 'lower-court.index' && Route::current()->parameters() ['slug'] == 'direct-entry';
$manage_lower_court_excel_import = Route::currentRouteName() == 'lower-court.index' && Route::current()->parameters() ['slug'] == 'excel-import';
$manage_lower_court_list = Route::currentRouteName() == 'lower-court.index' && Route::current()->parameters() ['slug'] == 'list';
$manage_lower_court_gc = Route::currentRouteName() == 'gc-lower-court.index' ||
                        Route::currentRouteName() == 'gc-lower-court.edit' ||
                        Route::currentRouteName() == 'gc-lower-court.show';

// HIGH COURT
$manage_high_court =  in_array(Route::currentRouteName(),['high-court.index','high-court.show']);
$manage_high_court_submit = Route::currentRouteName() == 'high-court.index' && Route::current()->parameters() ['slug'] == 'submit';
$manage_high_court_partial = Route::currentRouteName() == 'high-court.index' && Route::current()->parameters() ['slug'] == 'partial';
$manage_high_court_all = Route::currentRouteName() == 'high-court.index' && Route::current()->parameters() ['slug'] == 'all';

$manage_reports = 
                Route::currentRouteName() == 'reports.index' || 
                Route::currentRouteName() == 'reports.vpp' || 
                Route::currentRouteName() == 'reports.lower-court.rcpt' || 
                Route::currentRouteName() == 'reports.intimation' ||
                Route::currentRouteName() == 'reports.lower-court.license.index' ||
                Route::currentRouteName() == 'reports.high-court.license.index' || 
                Route::currentRouteName() == 'reports.voter-member.index' ||
                Route::currentRouteName() == 'reports.lawyer-summary-report.index' ||
                Route::currentRouteName() == 'reports.general-search-report'
            ;

$manage_posts = Route::currentRouteName() == 'posts.index';
?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="https://pbbarcouncil.com/" class="brand-link" target="_blank">
        <img src="{{asset('public/admin/images/logo-white.png')}}" style="    width: 70px;
        height: 70px;
        margin-left: 78px;">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="{{route('admin.dashboard')}}"
                        class="nav-link {{(Route::currentRouteName() == 'admin.dashboard') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                @if (permission('manage-operators') || permission('manage-users'))
                <li class="nav-header"><b>USERS</b></li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-operators'))
                <li
                    class="nav-item has-treeview {{(Route::currentRouteName() == 'admins.create' || Route::currentRouteName() == 'admins.index'|| Route::currentRouteName() == 'admins.edit' ) ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{(Route::currentRouteName() == 'admins.create' || Route::currentRouteName() == 'admins.index' || Route::currentRouteName() == 'admins.edit' ) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Manage Operators
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::guard('admin')->user()->hasPermission('add-operators'))
                        <li class="nav-item">
                            <a href="{{route('admins.create')}}"
                                class="nav-link {{(Route::currentRouteName() == 'admins.create') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Add Operator</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{route('admins.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'admins.index' || Route::currentRouteName() == 'admins.edit' ) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>List Operators</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-users'))
                <li
                    class="nav-item has-treeview {{in_array(Route::currentRouteName(),['users.index','users.edit','gc-users.show'])? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{in_array(Route::currentRouteName(),['users.index','users.edit','gc-users.show']) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Manage Users
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('users.index','all')}}"
                                class="nav-link {{(Route::currentRouteName() == 'users.index' && Route::current()->parameters() ['slug'] == 'all' || Route::currentRouteName() == 'users.edit' ) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>List Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('users.index','gc')}}"
                                class="nav-link {{(Route::currentRouteName() == 'users.index' && Route::current()->parameters() ['slug'] == 'gc' || Route::currentRouteName() == 'gc-users.show' ) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>GC Users</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif


                @if (permission('manage-intimations') || permission('manage-lower-court') ||
                permission('manage-high-court') || permission('manage-applications'))
                <li class="nav-header"><b>LAWYERS</b></li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-intimations'))
                <li class="nav-item has-treeview {{($manage_intimation) ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{($manage_intimation) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Intimation
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('intimations.index',['slug'=> 'final'])}}"
                                class="nav-link {{($manage_final_intimation) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Submitted Applications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('intimations.index', ['slug'=> 'partial'])}}"
                                class="nav-link {{($manage_partial_intimation) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Partial Applications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('intimations.index', ['slug'=> 'list'])}}"
                                class="nav-link {{($manage_list_intimation) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>List Applications</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-lower-court'))
                <li class="nav-item has-treeview {{($manage_lower_court) ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{($manage_lower_court) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Lower Court
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('lower-court.index',['slug'=> 'final'])}}"
                                class="nav-link {{($manage_lower_court_final) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Submitted Applications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('lower-court.index', ['slug'=> 'partial'])}}"
                                class="nav-link {{($manage_lower_court_partial) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Partial Applications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('lower-court.index', ['slug'=> 'move-from-intimation'])}}"
                                class="nav-link {{($manage_lower_court_move_from_intimation) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Moved from Intimation</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('lower-court.index', ['slug'=> 'direct-entry'])}}"
                                class="nav-link {{($manage_lower_court_direct_entry) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Direct Entries</p>
                            </a>
                        </li>

                        @if (permission('gc_lower_court'))
                        <li class="nav-item">
                            <a href="{{route('gc-lower-court.index')}}"
                                class="nav-link {{($manage_lower_court_gc) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>GC Lower Court</p>
                            </a>
                        </li>
                        @endif

                        <hr>
                        <li class="nav-item">
                            <a href="{{route('lower-court.indexv2', ['slug'=> 'submit'])}}"
                                class="nav-link {{(Route::currentRouteName() == 'lower-court.indexv2') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Submitted List - v2</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('lower-court.indexv2', ['slug'=> 'all'])}}"
                                class="nav-link {{(Route::currentRouteName() == 'lower-court.indexv2') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>All List - v2</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-high-court'))
                <li class="nav-item has-treeview {{($manage_high_court) ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{($manage_high_court) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            High Court
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('high-court.index','submit')}}"
                                class="nav-link {{($manage_high_court_submit) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Submitted List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('high-court.index','partial')}}"
                                class="nav-link {{($manage_high_court_partial) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Partial List</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('high-court.index','all')}}"
                                class="nav-link {{($manage_high_court_all) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>All List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                <li class="nav-header"><b>APPLICATIONS</b></li>

                @if (Auth::guard('admin')->user()->hasPermission('manage-applications'))
                <li class="nav-item has-treeview {{(
                                                    Route::currentRouteName() == 'applications.create' ||
                                                    Route::currentRouteName() == 'applications.index'||
                                                    Route::currentRouteName() == 'applications.edit' ||
                                                    Route::currentRouteName() == 'applications.show' ||
                                                    Route::currentRouteName() == 'secure-card.lower-court' ||
                                                    Route::currentRouteName() == 'secure-card.renewal-lower-court' ||
                                                    Route::currentRouteName() == 'secure-card.higher-court' ||
                                                    Route::currentRouteName() == 'secure-card.renewal-higher-court' ||
                                                    Route::currentRouteName() == 'applications.unapproved'
                                                    ) ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{(
                                                            Route::currentRouteName() == 'applications.create' ||
                                                            Route::currentRouteName() == 'applications.index' ||
                                                            Route::currentRouteName() == 'applications.edit' ||
                                                            Route::currentRouteName() == 'applications.show' ||
                                                            Route::currentRouteName() == 'secure-card.lower-court' ||
                                                            Route::currentRouteName() == 'secure-card.renewal-lower-court' ||
                                                            Route::currentRouteName() == 'secure-card.higher-court' ||
                                                            Route::currentRouteName() == 'secure-card.renewal-higher-court' ||
                                                            Route::currentRouteName() == 'applications.unapproved'
                                                            ) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Secure Card
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('secure-card.renewal-higher-court')}}"
                                class="nav-link {{(Route::currentRouteName() == 'secure-card.renewal-higher-court') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>List Applications</p>
                            </a>
                        </li>

                    </ul>
                </li>
                @endif

                @if (permission('manage-lawyer-requests'))
                <li
                    class="nav-item has-treeview {{(in_array(Route::currentRouteName(),['lawyer-requests.index','lawyer-requests.sub-categories'])) ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{(in_array(Route::currentRouteName(),['lawyer-requests.index','lawyer-requests.sub-categories'])) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Lawyer Requests
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('lawyer-requests.sub-categories')}}"
                                class="nav-link {{(Route::currentRouteName() == 'lawyer-requests.sub-categories') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('lawyer-requests.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'lawyer-requests.index') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Lawyer Requests</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li
                    class="nav-item has-treeview {{(in_array(Route::currentRouteName(),['complaint.index'])) ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{(in_array(Route::currentRouteName(),['complaint.index'])) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Lawyer Complaints
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('complaint.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'complaint.index') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Complaint List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage_posts'))
                <li class="nav-item has-treeview {{($manage_posts) ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{($manage_posts) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>Manage Posts <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('posts.index')}}" class="nav-link {{($manage_posts) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Submitted List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- @if (Auth::guard('admin')->user()->hasPermission('manage-complaints'))
                <li class="nav-item">
                    <a href="{{route('complaints.index')}}"
                        class="nav-link {{(Route::currentRouteName() == 'complaints.index') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p> Manage Inquiries - OLD VERSION </p>
                    </a>
                </li>
                @endif --}}

                @if (Auth::guard('admin')->user()->hasPermission('manage-reports'))
                <li class="nav-header"><b>REPORTS</b></li>
                <li class="nav-item has-treeview {{($manage_reports) ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{($manage_reports) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Manage Reports
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- <li class="nav-item">
                            <a href="{{route('reports.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'reports.index') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>General Report</p>
                            </a>
                        </li> --}}
                       
                        <li class="nav-item">
                            <a href="{{route('reports.intimation')}}"
                                class="nav-link {{(Route::currentRouteName() == 'reports.intimation') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Intimation Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('reports.lower-court.rcpt')}}"
                                class="nav-link {{(Route::currentRouteName() == 'reports.lower-court.rcpt') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Lower Court RCPT</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('reports.lower-court.license.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'reports.lower-court.license.index') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Lower Court License</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('reports.high-court.license.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'reports.high-court.license.index') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>High Court License</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('reports.vpp')}}"
                                class="nav-link {{(Route::currentRouteName() == 'reports.vpp') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>VPP Report</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('reports.voter-member.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'reports.voter-member.index') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Voter Member</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('reports.lawyer-summary-report.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'reports.lawyer-summary-report.index') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Lawyer Summary Report</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('reports.general-search-report')}}"
                                class="nav-link {{(Route::currentRouteName() == 'reports.general-search-report') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>General Search Report</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-payments'))
                <li class="nav-header"><b>PAYMENTS</b></li>
                <li
                    class="nav-item has-treeview {{(Route::currentRouteName() == 'payments.index' )? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{(Route::currentRouteName() == 'payments.index' ) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Manage Payments
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('payments.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'payments.index' ) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>List Payments</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-policies'))
                <li
                    class="nav-item has-treeview {{(Route::currentRouteName() == 'policies.create' || Route::currentRouteName() == 'policies.index'|| Route::currentRouteName() == 'policies.edit' ) ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{(Route::currentRouteName() == 'policies.create' || Route::currentRouteName() == 'policies.index' || Route::currentRouteName() == 'policies.edit' ) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Manage Policies
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::guard('admin')->user()->hasPermission('add-policies'))
                        <li class="nav-item">
                            <a href="{{route('policies.create')}}"
                                class="nav-link {{(Route::currentRouteName() == 'policies.create') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Add Policy</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{route('policies.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'policies.index' || Route::currentRouteName() == 'policies.edit' ) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>List Policies</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                {{-- @if (Auth::guard('admin')->user()->hasPermission('manage-vouchers'))
                <li class="nav-item">
                    <a href="{{route('vouchers.index')}}"
                        class="nav-link {{(Route::currentRouteName() == 'vouchers.index') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p> Manage Vouchers </p>
                    </a>
                </li>
                @endif --}}


                @if (permission('manage-universities') || permission('manage-bars') ||
                permission('manage-districts') || permission('manage-members'))
                <li class="nav-header"><b>SYSTEM DATA</b></li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-universities'))
                <li
                    class="nav-item has-treeview {{(Route::currentRouteName() == 'universities.create' || Route::currentRouteName() == 'universities.index'|| Route::currentRouteName() == 'universities.edit' || Route::currentRouteName() == 'universities.unapproved' ) ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{(Route::currentRouteName() == 'universities.create' || Route::currentRouteName() == 'universities.index' || Route::currentRouteName() == 'universities.edit' || Route::currentRouteName() == 'universities.unapproved') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Manage Universities
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::guard('admin')->user()->hasPermission('add-universities'))
                        <li class="nav-item">
                            <a href="{{route('universities.create')}}"
                                class="nav-link {{(Route::currentRouteName() == 'universities.create') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Add University</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{route('universities.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'universities.index' || Route::currentRouteName() == 'universities.edit' ) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>List Universities</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('universities.unapproved')}}"
                                class="nav-link {{(Route::currentRouteName() == 'universities.unapproved') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Unapproved List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-bars'))
                <li
                    class="nav-item has-treeview {{(Route::currentRouteName() == 'bars.create' || Route::currentRouteName() == 'bars.index'|| Route::currentRouteName() == 'bars.edit' ) ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{(Route::currentRouteName() == 'bars.create' || Route::currentRouteName() == 'bars.index' || Route::currentRouteName() == 'bars.edit' ) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Manage Bars Data
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('bars.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'bars.index' || Route::currentRouteName() == 'bars.edit' ) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>List Bars</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-districts'))
                <li
                    class="nav-item has-treeview {{(Route::currentRouteName() == 'districts.create' || Route::currentRouteName() == 'districts.index'|| Route::currentRouteName() == 'districts.edit' || Route::currentRouteName() == 'divisions.index' ) ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{(Route::currentRouteName() == 'districts.create' || Route::currentRouteName() == 'districts.index' || Route::currentRouteName() == 'districts.edit' || Route::currentRouteName() == 'divisions.index' ) ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Districts & Tehsils
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('divisions.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'divisions.index' || Route::currentRouteName() == 'divisions.edit' ) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>List Divisions</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('districts.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'districts.index' || Route::currentRouteName() == 'districts.edit' ) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>List Districts</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-members'))
                <li class="nav-item has-treeview {{(Route::currentRouteName() == 'members.index' ||
                                                    Route::currentRouteName() == 'members.create' ||
                                                    Route::currentRouteName() == 'members.show' ||
                                                    Route::currentRouteName() == 'members.edit') ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{(Route::currentRouteName() == 'members.index' ||
                                                        Route::currentRouteName() == 'members.create' ||
                                                        Route::currentRouteName() == 'members.show' ||
                                                        Route::currentRouteName() == 'members.edit') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Manage Members
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('members.create')}}"
                                class="nav-link {{(Route::currentRouteName() == 'members.create') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Add Member</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('members.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'members.index') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Members List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (permission('manage-complaints'))
                <li class="nav-header"><b>SETTINGS</b></li>
                @endif

                {{-- @if (Auth::guard('admin')->user()->id == 1)
                <li class="nav-item">
                    <a href="{{route('cases.create')}}"
                        class="nav-link {{(Route::currentRouteName() == 'cases.create') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p> RF ID Card Scan </p>
                    </a>
                </li>
                @endif --}}

                <li class="nav-item">
                    <a href="{{route('admin.settings')}}"
                        class="nav-link {{(Route::currentRouteName() == 'admin.settings') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>
                            Account Password
                        </p>
                    </a>
                </li>

                <div style="margin-bottom:70px"></div>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
