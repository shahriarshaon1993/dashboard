<ul class="vertical-nav-menu">
    <li class="app-sidebar__heading">Menus</li>
    <li>
        <a href="{{ route('admin.dashboard') }}" class="{{ Route::is('admin.dashboard') ? 'mm-active' : '' }}">
            <i class="metismenu-icon pe-7s-rocket"></i>
            Dashboard
        </a>
    </li>
    <li>
        <a href="{{ route('admin.pages.index') }}" class="{{ Request::is('admin/pages*') ? 'mm-active' : '' }}">
            <i class="metismenu-icon pe-7s-news-paper"></i>
            Pages
        </a>
    </li>
    <li class="app-sidebar__heading">Access Controll</li>
    <li>
        <a href="{{ route('admin.users.index') }}" class="{{ Request::is('admin/users*') ? 'mm-active' : '' }}">
            <i class="metismenu-icon pe-7s-users"></i>
            Users
        </a>
    </li>
    <li>
        <a href="{{ route('admin.roles.index') }}" class="{{ Request::is('admin/roles*') ? 'mm-active' : '' }}">
            <i class="metismenu-icon pe-7s-check"></i>
            Roles
        </a>
    </li>
    <li class="app-sidebar__heading">System</li>
    <li>
        <a href="{{ route('admin.backups.index') }}" class="{{ Request::is('admin/backups*') ? 'mm-active' : '' }}">
            <i class="metismenu-icon pe-7s-cloud"></i>
            Backups
        </a>
    </li>
    <li>
        <a href="{{ route('admin.settings.general') }}" class="{{ Request::is('admin/settings*') ? 'mm-active' : '' }}">
            <i class="metismenu-icon pe-7s-settings"></i>
            Settings
        </a>
    </li>
</ul>
