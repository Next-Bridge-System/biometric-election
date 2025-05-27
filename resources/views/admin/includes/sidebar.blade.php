<?php?>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link" target="_blank">
        <img src="{{asset('public/admin/images/logo.png')}}" class="w-100" alt="Admin Logo">
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