<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    @endpush

    @section('title', 'user management')

    <x-slot name="header">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="{{ ($isTrashed) ? 'pe-7s-trash' : 'pe-7s-users' }} icon-gradient bg-mean-fruit"></i>
            </div>
            <div>
                {{ ($isTrashed) ? 'Trash' : 'Users' }}
                <div class="page-title-subheading">
                    {{ ($isTrashed) ? 'Restore your trashed data or delete it permanently' : 'This is a user management section, you can create, edit and delete users.' }}
                </div>
            </div>
        </div>
        @if ($isTrashed)
            <div class="page-title-actions">
                <div class="d-inline-block dropdown">
                    <a href="{{ route('admin.users.index') }}" class="btn-shadow btn btn-dark">
                        <span class="btn-icon-wrapper pr-2 opacity-7">
                            <i class="fa fa-arrow-left"></i>
                        </span>
                        Back to list
                    </a>
                </div>
            </div>
        @else
            @permission('admin.users.create')
                <div class="page-title-actions">
                    <div class="d-inline-block dropdown">
                        <a href="{{ url('/admin/users?archive') }}" class="btn-shadow mr-3 btn btn-dark">
                            <i class="fa fa-trash"></i>
                        </a>
                        <a href="{{ route('admin.users.create') }}" class="btn-shadow btn btn-primary">
                            <span class="btn-icon-wrapper pr-2 opacity-7">
                                <i class="fas fa-plus"></i>
                            </span>
                            Create
                        </a>
                    </div>
                </div>
            @endpermission
        @endif
    </x-slot>

    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="table-responsive p-3">
                    <table id="datatable" class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Joined at</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key=> $user)
                                <tr>
                                    <td class="text-center text-muted">{{ $key + 1 }}</td>
                                    <td>
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">
                                                <div class="widget-content-left mr-3">
                                                    <div class="widget-content-left">
                                                        <img width="40" class="rounded-circle"
                                                            src="{{ $user->getFirstMediaUrl('avatar') != null ? $user->getFirstMediaUrl('avatar','thumb') : config('app.placeholder').'160' }}"  alt="User Avatar">
                                                    </div>
                                                </div>
                                                <div class="widget-content-left flex2">
                                                    <div class="widget-heading">{{ $user->name }}</div>
                                                    <div class="widget-subheading opacity-7">
                                                        @if ($user->role)
                                                            <span class="badge badge-info">{{ $user->role->name }}</span>
                                                        @else
                                                            <span class="badge badge-danger">No role found :(</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $user->email }}</td>
                                    <td class="text-center">
                                        @if ($user->status == true)
                                            <span class="badge badge-info">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $user->created_at->diffForHumans() }}</td>
                                    <td class="text-center">
                                        @if ($user->trashed())
                                            <button type="button" class="btn btn-info btn-sm" onclick="restoreData({{ $user->id }})">
                                                <i class="fas fa-undo" title="Restore"></i>
                                            </button>

                                            <form id="restore-form-{{ $user->id }}" method="POST" action="{{ route('admin.user.restore', $user->id) }}" class="d-none">
                                                @csrf
                                            </form>

                                            <button type="button" class="btn btn-danger btn-sm" onclick="permanentData({{ $user->id }})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>

                                            <form id="permanent-form-{{ $user->id }}" method="POST" action="{{ route('admin.user.forcedelete', $user->id) }}" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @else
                                            <a href="{{ route('admin.users.show', $user->slug) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @permission('admin.users.edit')
                                                <a href="{{ route('admin.users.edit', $user->slug) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endpermission

                                            @if ($user->deletable == true)
                                                @permission('admin.users.destroy')
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteData({{ $user->id }})">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>

                                                    <form id="delete-form-{{ $user->id }}" method="POST" action="{{ route('admin.users.destroy', $user->slug) }}" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                @endpermission
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#datatable').DataTable();
            } );
        </script>
    @endpush

</x-app-layout>

