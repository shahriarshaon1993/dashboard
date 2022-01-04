<x-app-layout>

    @section('title', 'create role')

    <x-slot name="header">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-check icon-gradient bg-mean-fruit"></i>
            </div>
            <div>
                {{ __('Create role') }}
                <div class="page-title-subheading">
                    {{ __('You can create role and set permissions') }}
                </div>
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block dropdown">
                <a href="{{ route('admin.roles.index') }}" class="btn-shadow btn btn-dark">
                    <span class="btn-icon-wrapper pr-2 opacity-7">
                        <i class="fa fa-arrow-left"></i>
                    </span>
                    Back to list
                </a>
            </div>
        </div>
    </x-slot>

    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <form method="POST" action="{{ isset($role) ? route('admin.roles.update', $role->slug) : route('admin.roles.store') }}"">
                    @csrf
                    @isset($role)
                        @method('PUT')
                    @endisset
                    <div class="card-body">
                        <h5 class="card-title">Manage roles</h5>

                        <div class="form-group">
                            <label for="name">Role name</label>
                            <input id="name" type="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $role->name ?? old('name') }}" placeholder="Enter role name" required autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="text-center">
                            <strong>Manage permissions for role</strong>
                            @error('permissions')
                                <p class="p-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                </p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="form-check-input" name="" id="select-all">
                                <label for="select-all" class="form-check-label">Select all</label>
                            </div>
                        </div>

                        @forelse ($modules->chunk(2) as $key=>$chunks)
                            <div class="form-row">
                                @foreach ($chunks as $key=>$module)
                                    <div class="col">
                                        <h5>Module: {{ $module->name }}</h5>
                                        @foreach ($module->permissions as $key=>$permission)
                                            <div class="mb-3 ml-4">
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="permissions-{{ $permission->id }}"
                                                        name="permissions[]"
                                                        value="{{ $permission->id }}"
                                                        @isset($role)
                                                            @foreach ($role->permissions as $rPermission)
                                                                {{ $permission->id == $rPermission->id ? 'checked' : '' }}
                                                            @endforeach
                                                        @endisset
                                                    >
                                                    <label for="permissions-{{ $permission->id }}" class="form-check-label">{{ $permission->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @empty
                            <div class="row">
                                <div class="col text-center">
                                    <strong>No module found.</strong>
                                </div>
                            </div>
                        @endforelse
                        <button type="submit" class="btn btn-primary">
                            @isset($role)
                                <i class="fas fa-arrow-circle-up"></i>
                                Update
                            @else
                                <i class="fas fa-plus-circle"></i>
                                Create
                            @endisset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            // Listen for click on toggle checkbox
            $('#select-all').click(function (event) {
                if(this.checked) {
                    // Iterate each checkbox
                    $(':checkbox').each(function () {
                        this.checked = true;
                    });
                }else {
                    $(':checkbox').each(function () {
                        this.checked = false;
                    });
                }
            });
        </script>
    @endpush

</x-app-layout>
