<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    @endpush

    @section('title', 'backup your website')

    <x-slot name="header">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-cloud icon-gradient bg-mean-fruit"></i>
            </div>
            <div>
                {{ __('Backups') }}
                <div class="page-title-subheading">
                    {{ __('This is a backup management section, you can backup your web site.') }}
                </div>
            </div>
        </div>
        @permission('admin.backups.create')
        <div class="page-title-actions">
            @permission('admin.backups.destroy')
                <button type="button" class="btn-shadow mr-3 btn btn-danger" onclick="event.preventDefault();
                    document.getElementById('clean-backup-form').submit();">
                        <i class="fas fa-trash"></i>
                        Clean old backup
                </button>

                <form method="POST" action="{{ route('admin.backups.clean') }}" class="d-none" id="clean-backup-form">
                    @csrf
                    @method('DELETE')
                </form>
            @endpermission

            @permission('admin.backups.create')
                <button type="button" class="btn-shadow mr-3 btn btn-primary" onclick="event.preventDefault();
                    document.getElementById('new-backup-form').submit();">
                        <i class="fas fa-plus-circle"></i>
                        Create new backup
                </button>

                <form method="POST" action="{{ route('admin.backups.store') }}" class="d-none" id="new-backup-form">
                        @csrf
                </form>
            @endpermission
        </div>
        @endpermission
    </x-slot>

    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card">
                <div class="table-responsive p-3">
                    <table id="datatable" class="align-middle mb-0 table table-borderless table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">File Name</th>
                            <th class="text-center">Size</th>
                            <th class="text-center">Created at</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($backups as $key=> $backup)
                                <tr>
                                    <td class="text-center text-muted">{{ $key + 1 }}</td>
                                    <td class="text-center">{{ $backup['file_name'] }}</td>
                                    <td class="text-center">{{ $backup['file_size'] }}</td>
                                    <td class="text-center">{{ $backup['created_at'] }}</td>
                                    <td class="text-center">
                                        @permission('admin.backups.download')
                                            <a href="{{ route('admin.backups.download', $backup['file_name']) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endpermission()

                                        @permission('admin.backups.destroy')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteData({{ $key }})">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>

                                            <form id="delete-form-{{ $key }}" method="POST" action="{{ route('admin.backups.destroy', $backup['file_name']) }}" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endpermission
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
