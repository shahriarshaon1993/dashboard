<div class="list-group">
    <a href="{{ route('admin.settings.general') }}" class="list-group-item list-group-item-action {{ Route::is('admin.settings.general') ? 'active' : ''}}">
        Generale
    </a>
    <a href="{{ route('admin.settings.appearance') }}" class="list-group-item list-group-item-action {{ Route::is('admin.settings.appearance') ? 'active' : ''}}">
        Appearance
    </a>
    <a href="{{ route('admin.settings.mail') }}" class="list-group-item list-group-item-action {{ Route::is('admin.settings.mail') ? 'active' : ''}}">
        Setup mail
    </a>
</div>
