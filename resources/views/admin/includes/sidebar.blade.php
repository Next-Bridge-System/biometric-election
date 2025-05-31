<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link" target="_blank">
        {{-- <img src="{{asset('public/admin/images/logo.png')}}" class="w-100" alt="Admin Logo"> --}}
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
                            Manage Admins
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if (Auth::guard('admin')->user()->hasPermission('add-operators'))
                        <li class="nav-item">
                            <a href="{{route('admins.create')}}"
                                class="nav-link {{(Route::currentRouteName() == 'admins.create') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Add Admin</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{route('admins.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'admins.index' || Route::currentRouteName() == 'admins.edit' ) ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>List Admins</p>
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

                {{-- @if (Auth::guard('admin')->user()->hasPermission('manage-districts'))
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
                @endif --}}

                @if (Auth::guard('admin')->user()->hasPermission('manage-elections'))
                <li class="nav-item has-treeview {{(Route::currentRouteName() == 'elections.index' ||
                                                    Route::currentRouteName() == 'elections.create' ||
                                                    Route::currentRouteName() == 'elections.show' ||
                                                    Route::currentRouteName() == 'elections.edit') ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{(Route::currentRouteName() == 'elections.index' ||
                                                        Route::currentRouteName() == 'elections.create' ||
                                                        Route::currentRouteName() == 'elections.show' ||
                                                        Route::currentRouteName() == 'elections.edit') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Manage Elections
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('elections.create')}}"
                                class="nav-link {{(Route::currentRouteName() == 'elections.create') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Add Election</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('elections.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'elections.index') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Elections List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-seats'))
                <li class="nav-item has-treeview {{(Route::currentRouteName() == 'seats.index' ||
                                                    Route::currentRouteName() == 'seats.create' ||
                                                    Route::currentRouteName() == 'seats.show' ||
                                                    Route::currentRouteName() == 'seats.edit') ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{(Route::currentRouteName() == 'seats.index' ||
                                                        Route::currentRouteName() == 'seats.create' ||
                                                        Route::currentRouteName() == 'seats.show' ||
                                                        Route::currentRouteName() == 'seats.edit') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Manage Seats
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('seats.create')}}"
                                class="nav-link {{(Route::currentRouteName() == 'seats.create') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Add Seat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('seats.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'seats.index') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Seats List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (Auth::guard('admin')->user()->hasPermission('manage-candidates'))
                <li class="nav-item has-treeview {{(Route::currentRouteName() == 'candidates.index' ||
                                                    Route::currentRouteName() == 'candidates.create' ||
                                                    Route::currentRouteName() == 'candidates.show' ||
                                                    Route::currentRouteName() == 'candidates.edit') ? 'menu-open' : ''}}">
                    <a href="#"
                        class="nav-link {{(Route::currentRouteName() == 'candidates.index' ||
                                                        Route::currentRouteName() == 'candidates.create' ||
                                                        Route::currentRouteName() == 'candidates.show' ||
                                                        Route::currentRouteName() == 'candidates.edit') ? 'active' : ''}}">
                        <i class="nav-icon fas fa-paper-plane"></i>
                        <p>
                            Manage Candidates
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('candidates.create')}}"
                                class="nav-link {{(Route::currentRouteName() == 'candidates.create') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Add Candidate</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('candidates.index')}}"
                                class="nav-link {{(Route::currentRouteName() == 'candidates.index') ? 'active' : ''}}">
                                <i class="fas fa-caret-right nav-icon"></i>
                                <p>Candidates List</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif

                @if (permission('manage-complaints'))
                <li class="nav-header"><b>SETTINGS</b></li>
                @endif

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